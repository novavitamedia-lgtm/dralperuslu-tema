# Canlı Siteye Kurulum Kılavuzu

## Ön koşullar
- Tam yedek (dosya + veritabanı). Geri alma planı hazır.
- PHP 8.1+, WordPress 6.x.
- (Önerilen) ACF Pro lisansı.

## Adımlar
1. **Yedek al:** cPanel/hosting üzerinden dosya + DB yedeği.
2. **Temayı yükle:** `theme/` klasörünü `dr-alper-uslu` adıyla `wp-content/themes/` altına kopyala.
3. **CSS:** `assets/dist/main.css` derlenmiş halde gelir. Değişiklik yapacaksan `cd theme && npm install && npm run build`.
4. **Etkinleştir:** Görünüm → Temalar → "Op. Dr. Alper Burak Uslu" → Etkinleştir.
   - `uzmanlik` CPT + `uzmanlik-kategori` taksonomisi ve 4 kategori otomatik oluşur.
   - Kalıcı bağlantılar otomatik temizlenir (gerekirse Ayarlar → Kalıcı Bağlantılar → Kaydet).
5. **ACF Pro:** Kur/etkinleştir → field group'lar `acf-json/`'dan otomatik yüklenir.
   - **Tema Ayarları** menüsünden telefon, WhatsApp, adres, sosyal medya, çalışma saatlerini gir.
6. **Menü:** Görünüm → Menüler → menüyü `Ana Menü (primary)` konumuna ata. Kategori dropdownları için alt öğeleri ekle.
7. **İçerik:**
   - Mevcut siteye kuruluyorsa içerik yerinde kalır (ID/URL/görsel korunur).
   - Temiz kurulumsa: `wp eval-file scripts/wp-import.php tr` (sonra `en`, `de`).
8. **Ana sayfa:** Ayarlar → Okuma → statik ana sayfa seç. Sayfayı düzenle → ACF **Bölümler**'den hero/sayaç/hizmet/adım/doktor/rozet/galeri/CTA ekle ve sırala.
9. **SEO eklentisi:** Yoast/RankMath aktifse ve schema'yı o üretsin istiyorsan `add_filter('dau_output_schema','__return_false');` ile temanın JSON-LD'sini kapat.

## Doğrulama
- Ana sayfa, bir uzmanlık, kategori arşivi, iletişim, başarılar sayfalarını aç.
- WP_DEBUG açıkken hata olmadığını kontrol et.
- 390/768/1024/1440 genişliklerinde görünümü test et.
- Türkçe karakterleri (ı/ğ/ş/ç/ö/ü) başlık, slug ve metada doğrula.
