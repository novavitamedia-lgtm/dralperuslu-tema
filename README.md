# Op. Dr. Alper Burak Uslu — Özel WordPress Teması + Statik Önizleme

Op. Dr. Alper Burak Uslu (Plastik, Rekonstrüktif ve Estetik Cerrahi) için sıfırdan yazılmış **özel (custom) klasik WordPress teması** ve onun **statik önizlemesi**. Görsel dil embraceyoursmile.com'dan (yalnızca desen/ritim) esinlenmiş; **tüm içerik, görsel ve bilgi** doktorun kendi sitesinden ([dralperuslu.com](https://dralperuslu.com)) REST API ile alınmıştır. Sıfır uydurma bilgi, sıfır stok görsel, sıfır placeholder.

> **Bu depo iki şey içerir:**
> - **`/theme`** → gerçek siteye kurulacak WordPress teması (canlı sürüm).
> - **`/preview`** → tasarımın statik önizlemesi (GitHub Pages'te yayınlanır, onay için).

## 🔗 Canlı önizleme
GitHub Pages: **https://novavitamedia-lgtm.github.io/dralperuslu-tema/** · Kök `/` → Türkçe ana sayfaya yönlenir. Diller: `/tr/`, `/en/`, `/de/`.

---

## 📁 Klasör yapısı

```
.
├── theme/                  # WordPress teması (kurulacak olan)
│   ├── style.css           # Tema başlığı (görünür stiller dist/main.css'te)
│   ├── functions.php       # Önyükleme
│   ├── front-page.php, header.php, footer.php, single-uzmanlik.php,
│   │   archive-uzmanlik.php, taxonomy-uzmanlik-kategori.php, page.php,
│   │   single.php, index.php, search.php, 404.php
│   ├── inc/                # cpt, acf, enqueue, schema, security, helpers
│   ├── template-parts/sections/   # her bölüm ayrı parça (ACF Flexible Content)
│   ├── acf-json/           # ACF field group'ları (JSON, sürüm kontrollü)
│   ├── assets/
│   │   ├── src/            # tailwind.css (kaynak), main.js
│   │   ├── dist/           # main.css (Tailwind CLI ile derlenmiş, minified)
│   │   ├── fonts/          # self-host woff2 (Fraunces + Plus Jakarta Sans, TR latin-ext)
│   │   └── vendor/         # alpine.min.js, swiper-bundle.min.(js|css)
│   ├── languages/          # dr-alper-uslu.pot
│   └── screenshot.png
├── preview/                # Statik dışa aktarım (GitHub Pages)
├── content/                # Envanter ve medya (kaynak veri)
│   ├── inventory.json      # 3 dil × sayfa/uzmanlik: tam metin + SSS + görsel + SEO
│   ├── media/              # indirilen tüm görseller (gerçek)
│   ├── design-system.md    # tasarım token'ları + bölüm→ACF eşleme tablosu
│   ├── seo-url-map.md/.csv # eski→yeni URL (birebir korunuyor)
│   └── menu_groups_*.json  # kategori eşlemesi
├── scripts/
│   ├── build_inventory.py  # REST API'den içerik + medya çekici (Aşama 1)
│   ├── build_preview.py    # statik önizleme üreticisi (Aşama 5)
│   └── wp-import.php        # WP-CLI içerik aktarımı
└── docs/                   # ek dokümantasyon
```

---

## 🎨 Teknoloji

- **WordPress 6.x, PHP 8.1+**, klasik tema (template hierarchy). Blok tema değil, child tema değil, sayfa oluşturucu bağımsız.
- **Tailwind CSS** — Tailwind CLI ile tek minified `assets/dist/main.css`'e derlenir (canlıda node_modules/runtime build yok).
- **Alpine.js** (menü, akordeon, mobil), **Swiper.js** (galeri/slider), **IntersectionObserver** (scroll reveal + sayaç). jQuery yok.
- **ACF Pro** (Flexible Content + Options Page) — field group'lar `acf-json/`'da. ACF yoksa tema güvenli varsayılanlarla çalışır.
- **CPT** `uzmanlik` (taban `/uzmanliklar/`) + taksonomi `uzmanlik-kategori` (Yüz / Vücut / Göğüs / Ameliyatsız). Blog = native posts.
- Fontlar self-host (Türkçe ı/ğ/ş/ç/ö/ü tam). i18n `.pot` (TR/EN/DE), şablonlarda hard-coded metin yok.

---

## 🚀 Yerel geliştirme

### CSS derleme (Tailwind)
```bash
cd theme
npm install
npm run build      # tek seferlik minified derleme → assets/dist/main.css
npm run dev        # geliştirme sırasında izleme (watch)
```

### Statik önizlemeyi yeniden üretme
```bash
# 1) içeriği çek (opsiyonel — content/ zaten dolu)
python3 scripts/build_inventory.py
# 2) CSS'i derle (theme/)
cd theme && npm run build && cd ..
# 3) statik siteyi üret
python3 scripts/build_preview.py
# 4) yerelde önizle
cd preview && python3 -m http.server 8000    # http://localhost:8000
```

### WordPress'te yerel çalıştırma (wp-env)
```bash
# theme/ dizininde .wp-env.json ile
npx @wordpress/env start
# tema aktivasyonu + içerik aktarımı
wp theme activate dr-alper-uslu
wp eval-file scripts/wp-import.php tr     # sonra en / de
```

---

## 🔧 Canlı siteye kurulum (özet — detay `docs/kurulum.md`)

1. **Tam yedek** al (dosya + veritabanı).
2. `theme/` klasörünü `dr-alper-uslu` adıyla `wp-content/themes/`'e yükle.
3. `cd theme && npm install && npm run build` (veya derlenmiş `assets/dist/main.css`'i olduğu gibi bırak).
4. Temayı etkinleştir → CPT/taksonomi ve 4 kategori otomatik oluşur, kalıcı bağlantılar temizlenir.
5. **ACF Pro**'yu kur → field group'lar `acf-json/`'dan otomatik yüklenir. **Tema Ayarları**'ndan telefon/sosyal/adres gir.
6. Menüleri (Görünüm → Menüler) `primary` konumuna ata.
7. İçerik zaten sitedeyse yerinde kalır; temiz kurulumsa `wp eval-file scripts/wp-import.php`.
8. Ana sayfayı statik yap ve bölümleri ACF'den kur.

> **Not:** Bölümler ACF Flexible Content ile kod yazmadan eklenir/sıralanır. Her bölüm `template-parts/sections/*.php`.

---

## 🔒 Güvenlik & SEO
- `inc/security.php`: sürüm gizleme, XML-RPC kapalı, REST kullanıcı listeleme engeli, güvenlik başlıkları (OWASP farkındalıklı).
- `inc/schema.php`: JSON-LD (Physician, MedicalProcedure, FAQPage, BreadcrumbList) — SEO eklentisinden bağımsız, `dau_output_schema` filtresiyle kapatılabilir.
- Çıktı kaçışı (`esc_html/esc_url/esc_attr/wp_kses_post`), srcset+lazy görseller, LCP'ye `fetchpriority`, `prefers-reduced-motion` desteği.
- SEO URL'leri birebir korunur (`content/seo-url-map.md`, 92 URL, 0 yönlendirme, 0 kayıp).

---

## ⚠️ İçerik notları (dürüstlük)
- İşlem sayfalarının çoğunun kendine ait fotoğrafı kaynakta **yok**; kategori temsili görsel/tipografik kapak kullanıldı (uydurma stok yok).
- Blog kaynağı **boş**; blog bölümü şimdilik kapalı (native `posts` eklenince otomatik açılır).
- Meta description çoğu sayfada kaynakta yok; mevcut metinden özgün özet üretildi.
- EN/DE, kaynağın çevrilmiş **gerçek** içeriğini gösterir (EN 21, DE 24 uzmanlık — kaynakta o kadar çevrili).

---

**Statik önizleme, tasarımın onaya sunulan sürümüdür. Canlı sürüm `/theme` klasöründeki WordPress temasıdır.**

---

## 🔗 Teslim linkleri
- **Repo:** https://github.com/novavitamedia-lgtm/dralperuslu-tema
- **Canlı önizleme:** https://novavitamedia-lgtm.github.io/dralperuslu-tema/ (TR/EN/DE)
- **QA raporu:** [docs/qa-raporu.md](docs/qa-raporu.md) — Lighthouse: Perf 100 / A11y 100 / BP 96 / SEO 50 (önizleme noindex)
