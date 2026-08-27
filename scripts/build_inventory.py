#!/usr/bin/env python3
"""
AŞAMA 1 — İçerik envanteri + medya indirici.
dralperuslu.com (WPML dizin modu) REST API'sinden TR/EN/DE içeriğini çeker,
WPBakery/Porto shortcode'larını temizler, medyayı indirir, inventory.json üretir.
Sıfır uydurma: yalnızca API'nin döndürdüğü gerçek veri.
"""
import json, os, re, sys, time, html, urllib.parse
from urllib.request import Request, urlopen
from urllib.error import HTTPError, URLError

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
CONTENT = os.path.join(ROOT, "content")
MEDIA = os.path.join(CONTENT, "media")
os.makedirs(MEDIA, exist_ok=True)

UA = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36"
BASES = {
    "tr": "https://dralperuslu.com/wp-json/wp/v2",
    "en": "https://dralperuslu.com/eng/wp-json/wp/v2",
    "de": "https://dralperuslu.com/de/wp-json/wp/v2",
}
LANG_PROC_BASE = {
    "tr": "https://dralperuslu.com/uzmanliklar/",
    "en": "https://dralperuslu.com/eng/procedures/",
    "de": "https://dralperuslu.com/de/spezialisierungen/",
}

def fetch(url, tries=4, as_json=True, binary=False):
    last = None
    for i in range(tries):
        try:
            req = Request(url, headers={"User-Agent": UA, "Accept": "*/*"})
            with urlopen(req, timeout=45) as r:
                data = r.read()
                if binary:
                    return data, dict(r.headers)
                text = data.decode("utf-8", "replace")
                return (json.loads(text) if as_json else text), dict(r.headers)
        except (HTTPError, URLError, TimeoutError, json.JSONDecodeError) as e:
            last = e
            time.sleep(1.5 * (i + 1))
    print(f"  ! FAIL {url} -> {last}", file=sys.stderr)
    return None, {}

def fetch_all(base, endpoint, params=""):
    """Paginated fetch of a WP collection."""
    out, page = [], 1
    while True:
        sep = "&" if params else ""
        url = f"{base}/{endpoint}?per_page=100&page={page}{sep}{params}"
        data, hdr = fetch(url)
        if not data:
            break
        if isinstance(data, dict) and data.get("code"):  # error object
            break
        out.extend(data)
        total_pages = int(hdr.get("X-WP-TotalPages", hdr.get("x-wp-totalpages", 1)) or 1)
        if page >= total_pages:
            break
        page += 1
    return out

# ---- İçerik temizleme ----
SHORTCODE_RE = re.compile(r"\[/?[a-zA-Z0-9_]+[^\]]*\]")
TAG_RE = re.compile(r"<[^>]+>")
WS_RE = re.compile(r"[ \t\r\f\v]+")
NL_RE = re.compile(r"\n{3,}")

def strip_shortcodes(s):
    prev = None
    while prev != s:
        prev = s
        s = SHORTCODE_RE.sub(" ", s)
    return s

def html_to_text(raw):
    if not raw:
        return ""
    s = strip_shortcodes(raw)
    # başlık ve paragraf sınırlarını koru
    s = re.sub(r"</(p|div|h[1-6]|li|br|tr)\s*>", "\n", s, flags=re.I)
    s = re.sub(r"<br\s*/?>", "\n", s, flags=re.I)
    s = re.sub(r"<li[^>]*>", "\n• ", s, flags=re.I)
    s = TAG_RE.sub("", s)
    s = html.unescape(s)
    s = WS_RE.sub(" ", s)
    s = "\n".join(line.strip() for line in s.split("\n"))
    s = NL_RE.sub("\n\n", s).strip()
    return s

def extract_headings(raw):
    if not raw:
        return []
    hs = []
    for m in re.finditer(r"<h([1-6])[^>]*>(.*?)</h\1>", raw, flags=re.I | re.S):
        t = html.unescape(TAG_RE.sub("", strip_shortcodes(m.group(2)))).strip()
        if t:
            hs.append({"level": int(m.group(1)), "text": t})
    return hs

def extract_images(raw):
    imgs = []
    if not raw:
        return imgs
    for m in re.finditer(r"<img[^>]+>", raw, flags=re.I):
        tag = m.group(0)
        src = re.search(r'src=["\']([^"\']+)["\']', tag)
        alt = re.search(r'alt=["\']([^"\']*)["\']', tag)
        if src:
            imgs.append({"src": html.unescape(src.group(1)),
                         "alt": html.unescape(alt.group(1)) if alt else ""})
    return imgs

def extract_internal_links(raw):
    links = []
    if not raw:
        return links
    for m in re.finditer(r'<a[^>]+href=["\']([^"\']+)["\'][^>]*>(.*?)</a>', raw, flags=re.I | re.S):
        href = html.unescape(m.group(1))
        if "dralperuslu.com" in href:
            txt = html.unescape(TAG_RE.sub("", m.group(2))).strip()
            links.append({"href": href, "text": txt})
    return links

def extract_faq(raw):
    """WPBakery/Porto akordeonlarından Soru/Cevap çıkar (title=... içeren shortcode'lar)."""
    faq = []
    if not raw:
        return faq
    for m in re.finditer(r'\[(?:vc_tta_section|porto_accordion_item|toggle)[^\]]*title="([^"]+)"[^\]]*\](.*?)\[/(?:vc_tta_section|porto_accordion_item|toggle)\]', raw, flags=re.I | re.S):
        q = html.unescape(m.group(1)).strip()
        a = html_to_text(m.group(2))
        if q and a:
            faq.append({"q": q, "a": a})
    return faq

def faq_from_headings(raw):
    """İşlem sayfalarındaki 'Soru?' biçimli başlık + takip eden metni SSS'e çevir."""
    faq = []
    if not raw:
        return faq
    # başlıkları sırayla, aralarındaki metinle eşle
    parts = re.split(r"(<h[2-4][^>]*>.*?</h[2-4]>)", raw, flags=re.I | re.S)
    i = 1
    while i < len(parts):
        htxt = html.unescape(TAG_RE.sub("", strip_shortcodes(parts[i]))).strip()
        body = html_to_text(parts[i + 1]) if i + 1 < len(parts) else ""
        if htxt.endswith("?") and body:
            faq.append({"q": htxt, "a": body})
        i += 2
    return faq

def yoast(obj):
    y = obj.get("yoast_head_json") or {}
    return {
        "meta_title": y.get("title", ""),
        "meta_description": y.get("description", ""),
        "og_image": (y.get("og_image") or [{}])[0].get("url", "") if y.get("og_image") else "",
        "canonical": y.get("canonical", ""),
        "robots": y.get("robots", {}),
    }

def slugify_media(url, alt):
    name = urllib.parse.unquote(os.path.basename(urllib.parse.urlparse(url).path))
    name = re.sub(r"[^a-zA-Z0-9._-]", "-", name).strip("-")
    return name or "media"

MEDIA_INDEX = {}  # url -> local filename (dedup)
MEDIA_MAP = {}    # attachment id -> {"src","alt"}
CURRENT_BASE = [BASES["tr"]]  # aktif dil REST tabanı (galeri ID fallback için)

def build_media_map(base):
    """Tüm medya kütüphanesini id->url haritasına çek + indir."""
    items = fetch_all(base, "media",
                      params="_fields=id,source_url,alt_text,media_details,mime_type")
    for m in items:
        mid = m.get("id")
        src = m.get("source_url", "")
        if mid and src:
            MEDIA_MAP[mid] = {"src": src, "alt": m.get("alt_text", "")}
            download_media(src, m.get("alt_text", ""))
    return len(MEDIA_MAP)

# quote/entity ne olursa olsun images="1,2,3" içindeki ID dizisini yakala
GALLERY_IDS_RE = re.compile(r'\b(?:images|include)=["“”″′\'\s]*([0-9][0-9,\s]*)')

def resolve_gallery_ids(raw):
    """WPBakery vc_gallery/vc_single_image içindeki medya ID'lerini URL'ye çevir."""
    out = []
    if not raw:
        return out
    seen = set()
    # &#8221; gibi entity'ler rakam içerdiğinden önce çöz (tırnaklar tek karaktere iner)
    raw = html.unescape(raw)
    for m in GALLERY_IDS_RE.finditer(raw):
        for tok in m.group(1).replace(" ", "").split(","):
            if tok.isdigit():
                mid = int(tok)
                if mid in seen:
                    continue
                info = MEDIA_MAP.get(mid)
                if not info:  # haritada yoksa doğrudan çek
                    m, _ = fetch(f"{CURRENT_BASE[0]}/media/{mid}?_fields=id,source_url,alt_text")
                    if m and m.get("source_url"):
                        info = {"src": m["source_url"], "alt": m.get("alt_text", "")}
                        MEDIA_MAP[mid] = info
                if info:
                    seen.add(mid)
                    out.append({"src": info["src"], "alt": info["alt"],
                                "local": download_media(info["src"], info["alt"])})
    return out

META_TITLE_RE = re.compile(r"<title>(.*?)</title>", re.I | re.S)
META_DESC_RE = re.compile(r'<meta[^>]+name=["\']description["\'][^>]+content=["\']([^"\']*)["\']', re.I)
OG_IMG_RE = re.compile(r'<meta[^>]+property=["\']og:image["\'][^>]+content=["\']([^"\']+)["\']', re.I)
CANON_RE = re.compile(r'<link[^>]+rel=["\']canonical["\'][^>]+href=["\']([^"\']+)["\']', re.I)

def scrape_html_meta(url):
    txt, _ = fetch(url, as_json=False)
    if not txt:
        return {}
    t = META_TITLE_RE.search(txt)
    d = META_DESC_RE.search(txt)
    og = OG_IMG_RE.search(txt)
    c = CANON_RE.search(txt)
    return {
        "meta_title": html.unescape(t.group(1).strip()) if t else "",
        "meta_description": html.unescape(d.group(1).strip()) if d else "",
        "og_image": html.unescape(og.group(1)) if og else "",
        "canonical": html.unescape(c.group(1)) if c else "",
    }

def download_media(url, alt=""):
    if not url or not url.startswith("http"):
        return None
    if url in MEDIA_INDEX:
        return MEDIA_INDEX[url]
    fname = slugify_media(url, alt)
    dest = os.path.join(MEDIA, fname)
    n = 1
    base, ext = os.path.splitext(fname)
    while os.path.exists(dest) and MEDIA_INDEX.get(url) != fname:
        # farklı url, aynı isim
        if url not in MEDIA_INDEX:
            fname = f"{base}-{n}{ext}"
            dest = os.path.join(MEDIA, fname)
            n += 1
        else:
            break
    if not os.path.exists(dest):
        data, hdr = fetch(url, binary=True)
        if data is None:
            return None
        with open(dest, "wb") as f:
            f.write(data)
    MEDIA_INDEX[url] = fname
    return fname

def process_item(obj, lang, kind):
    content_raw = (obj.get("content") or {}).get("rendered", "")
    excerpt_raw = (obj.get("excerpt") or {}).get("rendered", "")
    title = html.unescape((obj.get("title") or {}).get("rendered", "")).strip()
    imgs = extract_images(content_raw)
    # featured image via _embedded
    featured = ""
    emb = obj.get("_embedded", {}).get("wp:featuredmedia")
    if emb and isinstance(emb, list) and emb and emb[0].get("source_url"):
        featured = emb[0]["source_url"]
        imgs.insert(0, {"src": featured, "alt": emb[0].get("alt_text", "")})
    # galeri ID'lerinden görselleri çöz (WPBakery vc_gallery / vc_single_image)
    imgs.extend(resolve_gallery_ids(content_raw))
    # medya indir + tekilleştir
    seen_src = set()
    uniq = []
    for im in imgs:
        if im["src"] in seen_src:
            continue
        seen_src.add(im["src"])
        if "local" not in im:
            im["local"] = download_media(im["src"], im.get("alt", ""))
        uniq.append(im)
    imgs = uniq
    # SEO meta: REST yoast yoksa canlı HTML'den kaz
    seo = yoast(obj)
    if not seo.get("meta_title") and obj.get("link"):
        html_meta = scrape_html_meta(obj["link"])
        for k, v in html_meta.items():
            if v and not seo.get(k):
                seo[k] = v
    rec = {
        "id": obj.get("id"),
        "lang": lang,
        "kind": kind,
        "slug": obj.get("slug", ""),
        "link": obj.get("link", ""),
        "title": title,
        "headings": extract_headings(content_raw),
        "text": html_to_text(content_raw),
        "excerpt": html_to_text(excerpt_raw),
        "images": imgs,
        "featured_image": featured,
        "internal_links": extract_internal_links(content_raw),
        "faq": extract_faq(content_raw) or faq_from_headings(content_raw),
        "categories": obj.get("portfolio_cat", []) or obj.get("categories", []),
        "date": obj.get("date", ""),
        "seo": seo,
    }
    return rec

def main():
    inventory = {"source": "https://dralperuslu.com", "generated": time.strftime("%Y-%m-%d %H:%M"),
                 "languages": {}, "taxonomies": {}, "failures": []}
    summary = {}
    for lang, base in BASES.items():
        print(f"\n=== {lang.upper()} ===")
        lang_data = {"pages": [], "portfolio": [], "posts": []}
        CURRENT_BASE[0] = base
        MEDIA_MAP.clear()
        print(f"  medya haritası: {build_media_map(base)} kayıt")
        # taxonomy: portfolio_cat
        cats, _ = fetch(f"{base}/portfolio_cat?per_page=100&_fields=id,name,slug,parent,count")
        inventory["taxonomies"][f"portfolio_cat_{lang}"] = cats or []
        for kind, endpoint in [("page", "pages"), ("portfolio", "portfolio"), ("post", "posts")]:
            items = fetch_all(base, endpoint, params="_embed=1")
            print(f"  {endpoint}: {len(items)}")
            for obj in items:
                rec = process_item(obj, lang, kind)
                key = {"page": "pages", "portfolio": "portfolio", "post": "posts"}[kind]
                lang_data[key].append(rec)
        inventory["languages"][lang] = lang_data
        summary[lang] = {k: len(v) for k, v in lang_data.items()}

    inventory["media_count"] = len(MEDIA_INDEX)
    with open(os.path.join(CONTENT, "inventory.json"), "w", encoding="utf-8") as f:
        json.dump(inventory, f, ensure_ascii=False, indent=2)

    print("\n===== ÖZET =====")
    for lang, s in summary.items():
        print(f"  {lang}: {s}")
    print(f"  medya dosyası: {len(MEDIA_INDEX)}")
    with open(os.path.join(CONTENT, "summary.json"), "w", encoding="utf-8") as f:
        json.dump({"summary": summary, "media_count": len(MEDIA_INDEX)}, f, ensure_ascii=False, indent=2)

if __name__ == "__main__":
    main()
