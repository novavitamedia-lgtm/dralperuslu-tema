# Tasarım Sistemi — Op. Dr. Alper Burak Uslu Teması

Kaynak görsel dil: embraceyoursmile.com (yalnızca **karakter** — renk ritmi, bölüm yapısı,
tipografik ölçek, boşluk, buton/kart stili). Hiçbir görsel/metin/isim alınmadı.
Renk yönü onaylandı: **teal/turkuaz + beyaz, sıcak-samimi, temiz sans** (TR karakter destekli).

---

## 1. Renk paleti (CSS değişkenleri)

Teal karakteri korunur, kliniğe premium-sıcak uyarlanır. Cream (sıcak beyaz) zeminler
embraceyoursmile'ın "bölüm ritmi"ni verir; altın aksan sertifika/ödül rozetleri için.

| Token | Hex | Kullanım |
|---|---|---|
| `--brand-700` | `#0F6B65` | Koyu teal — hover, koyu CTA zemin |
| `--brand-600` | `#12857D` | **Ana marka** — butonlar, linkler, ikonlar |
| `--brand-500` | `#1AA79C` | Parlak teal — vurgu, sayaç, çizgiler |
| `--brand-300` | `#7FCFC7` | Açık teal — çerçeve, hafif vurgu |
| `--brand-100` | `#E6F4F2` | Teal tint — kart/bölüm zemini |
| `--ink-900` | `#12201F` | Başlık metni (sıcak siyah) |
| `--ink-700` | `#324241` | Gövde metni |
| `--ink-500` | `#5C6E6C` | İkincil/muted metin |
| `--cream-50` | `#F8F6F1` | Sıcak off-white — dönüşümlü bölüm zemini |
| `--cream-100`| `#F1ECE3` | İkinci cream tonu |
| `--gold-500` | `#C39B4A` | Altın aksan — ödül/sertifika rozetleri, yıldızlar |
| `--white` | `#FFFFFF` | Ana zemin |
| `--line` | `#E3E8E7` | Kenarlık/ayraç |

Koyu bölüm (hero overlay / "doktor kartları" / final CTA): `--brand-700` üstüne beyaz metin.

## 2. Tipografi

- **Başlık / display:** `Poppins` (600–700) — geometrik, sıcak, Türkçe (Latin-Extended) tam destekli. Self-host.
- **Gövde:** `Inter` (400–500) — yüksek okunabilirlik, ı/ğ/ş/ç/ö/ü tam. Self-host.
- Türkçe glyph testi build QA'ında zorunlu (`Iı Şş Ğğ Çç Öö Üü`).

Ölçek (majör üçlü ~1.20, `clamp()` ile akıcı):

| Rol | Boyut (clamp) | line-height | ağırlık |
|---|---|---|---|
| Hero H1 | `clamp(2.5rem, 5vw, 4.25rem)` | 1.05 | 700 |
| Section H2 | `clamp(1.9rem, 3.2vw, 2.75rem)` | 1.12 | 700 |
| H3 kart başlık | `1.35rem` | 1.2 | 600 |
| Büyük gövde/intro | `1.15rem` | 1.7 | 400 |
| Gövde | `1rem` | 1.75 | 400 |
| Küçük/etiket | `0.8125rem` | 1.4 | 500, +0.06em tracking, UPPERCASE |

## 3. Boşluk, radius, gölge, hareket

- **Ritim:** 8px tabanlı (`4,8,12,16,24,32,48,64,96,128`). Bölüm dikey padding: mobil `4rem`, masaüstü `7rem`.
- **Konteyner:** max `1200px`, yan padding `1.25rem` (mobil) → `2rem` (masaüstü).
- **Radius:** buton `9999px` (pill), kart `1.25rem` (2xl), görsel `1rem`, input `0.75rem`.
- **Gölge:** kart `0 10px 30px -12px rgba(18,133,125,.18)`; hover `0 18px 40px -12px rgba(18,133,125,.28)`.
- **Breakpoint (Tailwind default):** `sm 640 / md 768 / lg 1024 / xl 1280 / 2xl 1536`. Test: 390/768/1024/1440.
- **Hareket:** geçiş `200–300ms ease-out`; scroll reveal `IntersectionObserver`, `600ms` fade+translateY(16px); sayaçlar görünürde başlar. Tümü `prefers-reduced-motion: reduce` altında kapanır.

---

## 4. Bölüm → İçerik → ACF eşleme tablosu (ANA SAYFA)

embraceyoursmile'ın her deseni, A kaynağındaki **gerçek** içerikle doldurulur.
Her bölüm bir `template-parts/sections/*.php` + ACF Flexible Content layout'u.

| # | Bölüm (desen) | Doktorun içeriği (A kaynağı) | ACF layout / alanları |
|---|---|---|---|
| 1 | **Full-bleed hero** (arka plan görsel + ken-burns, 2 satır büyük başlık + CTA) | Klinik/doktor görseli (ana sayfa medyası); başlık "Op. Dr. Alper Burak Uslu · Plastik, Rekonstrüktif ve Estetik Cerrahi"; alt cümle Hakkımda intro'dan; CTA "Randevu Al"→İletişim, "WhatsApp" | `hero`: baslik, alt_baslik, arka_gorsel(img), cta_birincil(link), cta_ikincil(link), rozet_metni |
| 2 | **Misyon + 4 sayaç** | Hakkımda misyon paragrafı + rakamlar: 12 yıl, 2000+ estetik işlem, 4000+ ameliyat, 35+ atıf | `sayaclar`: intro_metin, repeater[sayi, son_ek, etiket] |
| 3 | **Metin/görsel split + buton** | "Hakkımda" özeti + doktor portresi (`alper-burak-uslu_.jpg`) + "Devamı"→Hakkımda | `metin_gorsel`: baslik, metin(wysiwyg), gorsel, buton, gorsel_konum(sol/sağ) |
| 4 | **Hizmet kartları grid + Tümünü Gör** | 4 kategori (Yüz/Vücut/Göğüs/Ameliyatsız) kartı → kategori arşivine; altında öne çıkan işlemler | `hizmet_grid`: baslik, kaynak(kategori/seçili işlemler), buton |
| 5 | **What To Expect (01/02/03 adım)** | Süreç: 01 Muayene/Konsültasyon · 02 Planlama · 03 Operasyon & takip (Hakkımda/İletişim metninden türetilir; uydurma yok, jenerik klinik süreci) | `adimlar`: baslik, repeater[numara, baslik, aciklama] |
| 6 | **Renkli zemin doktor kartı** (koyu teal) | Doktor kartı: foto, ünvan (M.D, FEBOPRAS), kısa bio, sertifikalar linki | `doktor_karti`: gorsel, ad, unvan, kisa_bio, buton |
| 7 | **What Sets Us Apart + rozetler** | Üyelik/sertifikalar: ISAPS, ASPS, EBOPRAS, TPRECD, UEMS (logo bulunursa logo, yoksa metin rozet) | `rozetler`: baslik, aciklama, repeater[logo(img ops), isim] |
| 8 | **Yorum slider (sonsuz, 5★, Google ikonu, isim)** | A kaynağındaki gerçek yorumlar (varsa); yoksa bölüm gizli + sana bildirilir | `yorumlar`: baslik, repeater[isim, puan, metin, kaynak] |
| 9 | ~~Blog carousel~~ | **Blog boş → bölüm KAPALI** (native posts eklenince otomatik açılır) | `blog` (koşullu: yayın varsa) |
| 10 | **Vaka galerisi (öncesi/sonrası)** | Yalnızca A kaynağında gerçek görsel doğrulanırsa; yönetmeliğe uygun (vaatsiz, "sonuçlar kişiye göre değişir" notu) | `galeri`: baslik, uyari_metni, repeater[gorsel, aciklama] |
| 11 | **CTA şeridi** | "Ücretsiz ön görüşme" → İletişim/WhatsApp | `cta_serit`: baslik, metin, butonlar |
| — | **Footer** | Logo, hızlı form (CF7), sosyal (FB/IG/YouTube gerçek linkler), adres (Fenerbahçe Mah. Bağdat Cad. 134/11 Kadıköy), çalışma saatleri, kısa yasal uyarı + link | `theme_options` (ACF Options): logo, telefon, whatsapp, sosyal[], adres, calisma_saatleri, uyari_metni |
| — | **Mobil sabit çubuk** | "Hemen Ara" (tel:) + WhatsApp | Options'tan telefon/whatsapp |

## 5. Diğer şablonlar

- **single-uzmanlik.php:** hero (başlık + kategori etiketi + kategori temalı görsel) → intro → içerik (H2/H3) → **SSS akordeonu** (envanterdeki 244 sorudan) → "İlgili işlemler" (aynı kategori) → CTA. ACF: ek_gorseller, one_cikan_sss, cta.
- **archive-uzmanlik / taxonomy-uzmanlik-kategori:** kategori hero + filtrelenebilir işlem kartları grid.
- **page.php:** esnek ACF Flexible Content (yukarıdaki tüm layout'lar her sayfada kullanılabilir).
- **Hakkımda:** metin/görsel split + sayaçlar + rozetler + zaman çizelgesi (bio'dan).
- **İletişim:** harita + adres + form + çalışma saatleri + WhatsApp.
- **Başarılar:** 9 görsellik Swiper galeri (envanterden).
- **Yasal Uyarı:** düz içerik (envanterden birebir).

## 6. `uzmanlik-kategori` taksonomisi (menüden türetildi)

- **Yüz Estetiği** (12): burun-estetigi, yuz-germe, boyun-germe, goz-cevresi, goz-kapagi, kepce-kulak, badem-goz, sakak-germe, lip-lift, koruyucu-rinoplasti, endoskopik-yuz-germe, piezo-rinoplasti
- **Vücut Estetiği** (9): liposuction, kalca, karin-germe, bbl, gidi, genital, kol-germe, vaser-liposuction, popo
- **Göğüs Estetiği** (3): meme-buyutme, meme-kucultme, meme-diklestirme
- **Ameliyatsız Estetik** (6): botoks, dolgu, dudak, yag-dolgusu, genclik-asisi, prp
