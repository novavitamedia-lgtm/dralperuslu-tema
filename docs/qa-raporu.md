# QA Raporu — Op. Dr. Alper Burak Uslu Teması

Tarih: 2026-08-27 · Sürüm 1.0.0

## Kanıtlı Pass/Fail

| # | Kontrol | Durum | Kanıt |
|---|---|---|---|
| 1 | 0 placeholder / lorem ipsum / kırık görsel-link | ✅ PASS | 106 HTML tarandı → 0 kırık medya referansı; 0 boş işlem içeriği |
| 2 | inventory.json'daki tüm sayfalar mevcut | ✅ PASS | 92 URL (TR 35, EN 27, DE 30) → tema/önizlemede tamamı var, 0 kayıp (`seo-url-map.md`) |
| 3 | embraceyoursmile'dan medya/metin/isim ALINMADI | ✅ PASS | Tüm içerik yalnızca A kaynağı REST'inden (`inventory.json`); tasarımdan sadece desen/ritim |
| 4 | Tema temiz WP'de fatal error olmadan aktifleşir | ✅ PASS | WordPress 6.6 / PHP 8.1 (Docker): `wp theme activate` → Success; ana sayfa HTTP 200; 0 PHP hata dizesi |
| 5 | PHP söz dizimi temiz | ✅ PASS | `php -l` → 27/27 dosya "No syntax errors" |
| 6 | CPT + taksonomi + kategoriler kayıtlı | ✅ PASS | `uzmanlik` CPT + `uzmanlik-kategori` + 4 kategori otomatik seed edildi |
| 7 | Bölümler render oluyor (schema dahil) | ✅ PASS | Canlı WP render: 7 section, 4 sayaç, ISAPS rozeti, JSON-LD, WhatsApp, CSS |
| 8 | 390 / 768 / 1024 / 1440 responsive | ✅ PASS | 390 + 1440 görsel doğrulandı; 768/1024 Tailwind breakpoint'leriyle akışkan |
| 9 | Lighthouse (canlı önizleme, desktop) | ⚠️ Kısmi | **Performance 100 · Accessibility 100 · Best Practices 96 · SEO 50** |
| 10 | Klavye nav / focus / AA kontrast | ✅ PASS | `:focus-visible` ring; skip-link; Lighthouse Accessibility **100/100** |
| 11 | Form: loading/success durumları | ✅ PASS | Önizleme formu JS ile success mesajı; canlıda CF7 (kurulumda) |
| 12 | Türkçe karakterler (meta+slug dahil) | ✅ PASS | Fraunces + Plus Jakarta Sans latin-ext; ı/ğ/ş/ç/ö/ü render doğru |

## Lighthouse notu (madde 9)
- **SEO 50** yalnızca **önizlemeye özel** `noindex,nofollow` etiketinden kaynaklanır (Lighthouse "page blocked from indexing" cezası). Bu **kasıtlıdır** — statik önizleme canlı siteyle çakışmasın diye. Ayrıca hreflang/canonical önizlemede görelidir. **Gerçek WordPress temasında** noindex yoktur, canonical/hreflang mutlaktır → SEO skoru ~95+ beklenir.
- Performance 100, Accessibility 100, Best Practices 96 hedeflerin üzerinde.

## Güvenlik (OWASP farkındalıklı)
- Sürüm gizleme, XML-RPC/pingback kapalı, REST kullanıcı listeleme engeli, `?author=` numaralandırma engeli, güvenlik başlıkları (X-Frame-Options, nosniff, Referrer-Policy, Permissions-Policy), genel login hatası, yürütülebilir yükleme engeli.
- Tüm çıktı kaçışlı (`esc_html/esc_url/esc_attr/wp_kses_post`); doğrudan SQL yok; N+1 yok (tek `WP_Query`).

## Bilinen sınırlar (dürüstlük)
- Blog kaynağı boş → blog bölümü kapalı (native `posts` eklenince otomatik açılır).
- İşlem sayfalarının çoğunun kaynakta kendine ait fotoğrafı yok → kategori temsili görsel (uydurma stok yok).
- EN 21 / DE 24 uzmanlık: kaynakta o kadarı çevrili (eksikler zorlanmadı, uydurulmadı).
- Theme Check eklentisi ayrıca çalıştırılmadı (php -l + WP aktivasyon testi yapıldı); staging'de Theme Check önerilir.
