#!/usr/bin/env python3
"""dr-alper-uslu teması için en_US.mo + de_DE.mo derler (görünür UI stringleri)."""
import struct, os

# TR -> (EN, DE)
T = {
 # Nav + core
 "Hakkımda": ("About", "Über mich"),
 "Uzmanlıklar": ("Procedures", "Fachgebiete"),
 "Başarılar": ("Achievements", "Erfolge"),
 "Blog": ("Blog", "Blog"),
 "İletişim": ("Contact", "Kontakt"),
 "Ana Sayfa": ("Home", "Startseite"),
 "Ana Sayfaya Dön": ("Back to Home", "Zur Startseite"),
 "Randevu Al": ("Book Appointment", "Termin buchen"),
 "Hemen Ara": ("Call Now", "Jetzt anrufen"),
 "WhatsApp ile yazın": ("Message on WhatsApp", "Auf WhatsApp schreiben"),
 "Menü": ("Menu", "Menü"),
 "Ana menü": ("Main menu", "Hauptmenü"),
 "Ana Menü": ("Main Menu", "Hauptmenü"),
 "Kapat": ("Close", "Schließen"),
 "İçeriğe atla": ("Skip to content", "Zum Inhalt springen"),
 "Tüm Uzmanlıklar": ("All Procedures", "Alle Fachgebiete"),
 "Tüm Uzmanlıkları Gör": ("View All Procedures", "Alle Eingriffe ansehen"),
 "Tüm Kategoriler": ("All Categories", "Alle Kategorien"),
 "Uzmanlık": ("Procedure", "Eingriff"),
 "işlem": ("procedures", "Eingriffe"),
 # Kategoriler
 "Yüz Estetiği": ("Facial Aesthetics", "Gesichtsästhetik"),
 "Vücut Estetiği": ("Body Aesthetics", "Körperästhetik"),
 "Göğüs Estetiği": ("Breast Aesthetics", "Brustästhetik"),
 "Ameliyatsız Estetik": ("Non-Surgical Aesthetics", "Nicht-chirurgische Ästhetik"),
 # Hero + genel
 "Plastik, Rekonstrüktif ve Estetik Cerrahi": ("Plastic, Reconstructive and Aesthetic Surgery", "Plastische, rekonstruktive und ästhetische Chirurgie"),
 "Plastik, Rekonstrüktif ve Estetik Cerrahi · M.D, FEBOPRAS": ("Plastic, Reconstructive and Aesthetic Surgery · M.D, FEBOPRAS", "Plastische, rekonstruktive und ästhetische Chirurgie · M.D, FEBOPRAS"),
 "Estetik cerrahide bilimsel yaklaşım, doğal sonuçlar ve kişiye özel planlama.": ("A scientific approach in aesthetic surgery, natural results and individually tailored planning.", "Wissenschaftlicher Ansatz in der ästhetischen Chirurgie, natürliche Ergebnisse und individuelle Planung."),
 # Bölüm başlıkları
 "Uzmanlık Alanları": ("Areas of Expertise", "Fachgebiete"),
 "Estetik Cerrahi Uygulamaları": ("Aesthetic Surgery Procedures", "Ästhetische chirurgische Eingriffe"),
 "Nasıl İlerliyoruz?": ("How We Work", "Wie wir vorgehen"),
 "Süreç": ("Process", "Ablauf"),
 "Konsültasyon": ("Consultation", "Beratung"),
 "Kişisel Planlama": ("Personal Planning", "Persönliche Planung"),
 "Operasyon & Takip": ("Surgery & Follow-up", "Operation & Nachsorge"),
 "Beklentileriniz dinlenir, yüz/vücut analizi yapılır ve seçenekler açıkça anlatılır.": ("We listen to your expectations, analyse the face and body, and explain the options clearly.", "Wir hören Ihre Erwartungen, analysieren Gesicht und Körper und erklären die Optionen klar."),
 "Anatominize ve hedeflerinize uygun, gerçekçi ve kişiye özel bir plan hazırlanır.": ("A realistic, individual plan is prepared to suit your anatomy and goals.", "Ein realistischer, individueller Plan wird passend zu Ihrer Anatomie und Ihren Zielen erstellt."),
 "İşlem uygulanır; iyileşme sürecinde düzenli kontrollerle yanınızda olunur.": ("The procedure is performed and you are supported with regular check-ups during recovery.", "Der Eingriff wird durchgeführt und Sie werden während der Heilung mit regelmäßigen Kontrollen begleitet."),
 "Uzman": ("Specialist", "Facharzt"),
 "Sertifika & Üyelikler": ("Certificates & Memberships", "Zertifikate & Mitgliedschaften"),
 "Sertifika ve Belgeler": ("Certificates & Documents", "Zertifikate & Dokumente"),
 "Tanışalım": ("Get to Know", "Kennenlernen"),
 "Hakkımda Daha Fazlası": ("More About Me", "Mehr über mich"),
 "Neden Op. Dr. Alper Burak Uslu": ("Why Op. Dr. Alper Burak Uslu", "Warum Op. Dr. Alper Burak Uslu"),
 "Uluslararası Üyelik ve Sertifikalar": ("International Memberships and Certificates", "Internationale Mitgliedschaften und Zertifikate"),
 "Uluslararası Üyelikler": ("International Memberships", "Internationale Mitgliedschaften"),
 "Uluslararası ve ulusal plastik cerrahi kuruluşlarının aktif üyesi.": ("An active member of international and national plastic surgery associations.", "Aktives Mitglied internationaler und nationaler Gesellschaften für plastische Chirurgie."),
 "Kongre, Sertifika ve Bilimsel Faaliyetler": ("Congresses, Certificates and Scientific Activities", "Kongresse, Zertifikate und wissenschaftliche Aktivitäten"),
 "İlgili Uzmanlıklar": ("Related Procedures", "Verwandte Eingriffe"),
 "Sık Sorulan Sorular": ("Frequently Asked Questions", "Häufig gestellte Fragen"),
 "Ücretsiz Ön Görüşme İçin Bize Ulaşın": ("Contact Us for a Free Consultation", "Kontaktieren Sie uns für eine kostenlose Beratung"),
 "Sorularınızı yanıtlayalım, size en uygun yaklaşımı birlikte belirleyelim.": ("Let us answer your questions and decide on the best approach together.", "Wir beantworten Ihre Fragen und finden gemeinsam den besten Ansatz."),
 "Büyüt": ("Enlarge", "Vergrößern"),
 # Sayaçlar
 "Yıl Deneyim": ("Years Experience", "Jahre Erfahrung"),
 "Estetik İşlem": ("Aesthetic Procedures", "Ästhetische Eingriffe"),
 "Ameliyat": ("Surgeries", "Operationen"),
 "Bilimsel Atıf": ("Scientific Citations", "Wissenschaftliche Zitate"),
 # Footer
 "Adres": ("Address", "Adresse"),
 "Hızlı İletişim": ("Quick Contact", "Schneller Kontakt"),
 "Tüm hakları saklıdır.": ("All rights reserved.", "Alle Rechte vorbehalten."),
 "Bu web sitesindeki bilgiler yalnızca bilgilendirme amaçlıdır ve tıbbi tavsiye yerine geçmez. Sonuçlar kişiden kişiye değişebilir.": ("The information on this website is for information only and does not replace medical advice. Results may vary from person to person.", "Die Informationen auf dieser Website dienen nur zur Information und ersetzen keine ärztliche Beratung. Die Ergebnisse können von Person zu Person variieren."),
 # Blog UI
 "Kısa Özet": ("Summary", "Kurzfassung"),
 "İçindekiler": ("Contents", "Inhalt"),
 "Yazar": ("Author", "Autor"),
 "Randevu Talebi": ("Appointment Request", "Terminanfrage"),
 "Son Yazılar": ("Recent Posts", "Neueste Beiträge"),
 "Kategoriler": ("Categories", "Kategorien"),
 "Paylaş": ("Share", "Teilen"),
 "Önceki yazı": ("Previous post", "Vorheriger Beitrag"),
 "Sonraki yazı": ("Next post", "Nächster Beitrag"),
 "Detaylı Özgeçmiş & Bilimsel Faaliyetler": ("Full CV & Scientific Activities", "Ausführlicher Lebenslauf & wissenschaftliche Aktivitäten"),
 "Özgeçmişi Gizle": ("Hide CV", "Lebenslauf ausblenden"),
 "İçerikler Yakında": ("Content Coming Soon", "Inhalte in Kürze"),
 "Estetik cerrahi, iyileşme süreçleri ve bakım üzerine bilgilendirici yazılar.": ("Informative articles on aesthetic surgery, recovery and care.", "Informative Artikel über ästhetische Chirurgie, Heilung und Pflege."),
 "Estetik cerrahi, iyileşme süreçleri ve bakım üzerine bilgilendirici yazılar çok yakında burada olacak.": ("Informative articles on aesthetic surgery, recovery and care will be here very soon.", "Informative Artikel über ästhetische Chirurgie, Heilung und Pflege sind bald hier verfügbar."),
 "Devamını Oku": ("Read More", "Weiterlesen"),
 # İletişim formu
 "Randevu ve sorularınız için bize ulaşın. En kısa sürede dönüş yapıyoruz.": ("Contact us for appointments and questions. We respond as soon as possible.", "Kontaktieren Sie uns für Termine und Fragen. Wir antworten so schnell wie möglich."),
 "Telefon": ("Phone", "Telefon"),
 "Çalışma Saatleri": ("Working Hours", "Öffnungszeiten"),
 "Formu doldurun, WhatsApp üzerinden hızlıca dönelim.": ("Fill in the form and we will reply quickly via WhatsApp.", "Füllen Sie das Formular aus, wir antworten schnell über WhatsApp."),
 "Ad Soyad": ("Full Name", "Name"),
 "İlgilendiğiniz İşlem (opsiyonel)": ("Procedure of interest (optional)", "Eingriff von Interesse (optional)"),
 "Mesajınız": ("Your Message", "Ihre Nachricht"),
 "Kişisel verilerimin randevu talebim doğrultusunda işlenmesine (KVKK) onay veriyorum.": ("I consent to the processing of my personal data for my appointment request.", "Ich stimme der Verarbeitung meiner personenbezogenen Daten für meine Terminanfrage zu."),
 "WhatsApp ile Gönder": ("Send via WhatsApp", "Per WhatsApp senden"),
 "Yol Tarifi": ("Directions", "Route"),
 "Üyelik & Sertifikalar": ("Memberships & Certificates", "Mitgliedschaften & Zertifikate"),
 "Kişiye özel değerlendirme için bize ulaşın.": ("Contact us for a personal assessment.", "Kontaktieren Sie uns für eine persönliche Beurteilung."),
 "Randevu Alın": ("Book an Appointment", "Termin vereinbaren"),
 "Sorularınızı yanıtlayalım, size uygun planı birlikte belirleyelim.": ("Let us answer your questions and plan the option that suits you.", "Wir beantworten Ihre Fragen und planen die passende Option."),
 "Uluslararası üyelikler, sertifikalar ve bilimsel faaliyetler.": ("International memberships, certificates and scientific activities.", "Internationale Mitgliedschaften, Zertifikate und wissenschaftliche Aktivitäten."),
 "Estetik cerrahi, iyileşme süreçleri ve bakım üzerine bilgilendirici yazılar çok yakında burada olacak.": ("Informative articles on aesthetic surgery, recovery and care will be here very soon.", "Informative Artikel über ästhetische Chirurgie, Heilung und Pflege sind bald hier verfügbar."),
 "Sayfa bulunamadı": ("Page not found", "Seite nicht gefunden"),
 "Aradığınız sayfa taşınmış veya kaldırılmış olabilir.": ("The page you are looking for may have been moved or removed.", "Die gesuchte Seite wurde möglicherweise verschoben oder entfernt."),
}

def compile_mo(idx):  # idx 0=EN, 1=DE
    loc = "en_US" if idx == 0 else "de_DE"
    header = ("Project-Id-Version: dr-alper-uslu\nMIME-Version: 1.0\n"
              "Content-Type: text/plain; charset=UTF-8\nContent-Transfer-Encoding: 8bit\n"
              "Language: " + loc + "\n")
    items = [(b"", header.encode("utf-8"))]
    items += [(k.encode("utf-8"), v[idx].encode("utf-8")) for k, v in T.items() if v[idx]]
    items.sort(key=lambda x: x[0])
    n = len(items)
    o_off = 7 * 4
    t_off = o_off + n * 8
    data_off = t_off + n * 8
    # NUL-sonlandırmalı string verisi (gettext mend<buflen şartı için)
    key_tbl = b""; keys_data = b""; ko = data_off
    for k, _ in items:
        key_tbl += struct.pack("<II", len(k), ko)
        keys_data += k + b"\x00"; ko += len(k) + 1
    val_tbl = b""; vals_data = b""; vo = data_off + len(keys_data)
    for _, v in items:
        val_tbl += struct.pack("<II", len(v), vo)
        vals_data += v + b"\x00"; vo += len(v) + 1
    header = struct.pack("<Iiiiiii", 0x950412de, 0, n, o_off, t_off, 0, 0)
    return header + key_tbl + val_tbl + keys_data + vals_data

os.makedirs("mo", exist_ok=True)
for idx, loc in [(0, "en_US"), (1, "de_DE")]:
    with open(f"mo/dr-alper-uslu-{loc}.mo", "wb") as f:
        f.write(compile_mo(idx))
    print(f"dr-alper-uslu-{loc}.mo: {sum(1 for v in T.values() if v[idx])} string")
print("toplam TR anahtar:", len(T))
