#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
AŞAMA 5 — Statik önizleme üreticisi.
inventory.json (gerçek içerik) + tasarım sistemi → çok dilli statik site (/preview).
Aynı bileşen HTML'i + Tailwind class'ları WP temasıyla paylaşılır.
Sıfır uydurma içerik; yalnızca A kaynağı verisi.
"""
import json, os, re, shutil, html as htmllib, unicodedata

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
CONTENT = os.path.join(ROOT, "content")
PREVIEW = os.path.join(ROOT, "preview")
INV = json.load(open(os.path.join(CONTENT, "inventory.json"), encoding="utf-8"))
MENU_TR = json.load(open(os.path.join(CONTENT, "menu_groups_tr.json"), encoding="utf-8"))
MEDIA_FILES = set(os.listdir(os.path.join(CONTENT, "media")))

LANGS = ["tr", "en", "de"]
LANG_LABEL = {"tr": "TR", "en": "EN", "de": "DE"}
BASE_URL = "https://novavitamedia-lgtm.github.io/dralperuslu-tema"  # önizleme mutlak taban (OG için)

# ---- Site geneli (A kaynağından) ----
SITE = {
    "phone_display": "+90 532 569 31 99",
    "phone_tel": "+905325693199",
    "whatsapp": "905325693199",
    "address": "Fenerbahçe Mah. Bağdat Cad. 134/11 Kadıköy / İstanbul",
    "maps": "https://www.google.com/maps/search/?api=1&query=Fenerbah%C3%A7e+Mah.+Ba%C4%9Fdat+Cad.+134%2F11+Kad%C4%B1k%C3%B6y",
    "social": {
        "facebook": "https://www.facebook.com/op.dr.alperburakuslu/",
        "instagram": "https://www.instagram.com/dralperburakuslu/",
        "youtube": "https://www.youtube.com/channel/UCaZ98blTdpHjctnXJNrethQ",
    },
    "certs": [
        ("ISAPS", "International Society of Aesthetic Plastic Surgery"),
        ("ASPS", "American Society of Plastic Surgeons"),
        ("EBOPRAS", "European Board of Plastic, Reconstructive and Aesthetic Surgery"),
        ("TPRECD", "Türk Plastik Rekonstrüktif ve Estetik Cerrahi Derneği"),
        ("UEMS", "Union Européenne des Médecins Spécialistes"),
    ],
}

# ---- 4 kanonik kategori ----
CATS = ["yuz", "vucut", "gogus", "ameliyatsiz"]
CAT_LABEL = {
    "tr": {"yuz": "Yüz Estetiği", "vucut": "Vücut Estetiği", "gogus": "Göğüs Estetiği", "ameliyatsiz": "Ameliyatsız Estetik"},
    "en": {"yuz": "Face Aesthetics", "vucut": "Body Aesthetics", "gogus": "Breast Aesthetics", "ameliyatsiz": "Non-Surgical"},
    "de": {"yuz": "Gesichtsästhetik", "vucut": "Körperästhetik", "gogus": "Brustästhetik", "ameliyatsiz": "Nicht-chirurgisch"},
}

# ---- i18n arayüz metinleri ----
T = {
  "tr": {
    "nav_home": "Ana Sayfa", "nav_about": "Hakkımda", "nav_procedures": "Uzmanlıklar",
    "nav_achievements": "Başarılar", "nav_contact": "İletişim", "nav_blog": "Blog",
    "cta_appt": "Randevu Al", "cta_call": "Hemen Ara", "cta_wa": "WhatsApp",
    "hero_role": "Plastik, Rekonstrüktif ve Estetik Cerrahi",
    "hero_sub": "Estetik cerrahide bilimsel yaklaşım, doğal sonuçlar ve kişiye özel planlama.",
    "mission_kicker": "Yaklaşım", "counters_kicker": "Rakamlarla", "mission_title": "Estetik Cerrahide Öncü Yaklaşım", "mission_text": "Kapsamlı, sanatsal ve minimal invaziv çözümlerle her hastanın kendine en uygun, doğal sonuca ulaşmasını hedefliyoruz.",
    "c_years": "Yıl Deneyim", "c_aesthetic": "Estetik İşlem", "c_surgery": "Ameliyat", "c_citation": "Bilimsel Atıf",
    "about_kicker": "Tanışalım", "about_more": "Hakkımda Daha Fazlası",
    "services_kicker": "Uzmanlık Alanları", "services_title": "Estetik Cerrahi Uygulamaları",
    "services_all": "Tüm Uzmanlıkları Gör", "in_category": "işlem", "card_cta": "İncele",
    "steps_kicker": "Süreç", "steps_title": "Nasıl İlerliyoruz?",
    "step1_t": "Konsültasyon", "step1_d": "Beklentileriniz dinlenir, yüz/vücut analizi yapılır ve seçenekler açıkça anlatılır.",
    "step2_t": "Kişisel Planlama", "step2_d": "Anatominize ve hedeflerinize uygun, gerçekçi ve kişiye özel bir plan hazırlanır.",
    "step3_t": "Operasyon & Takip", "step3_d": "İşlem uygulanır; iyileşme sürecinde düzenli kontrollerle yanınızda olunur.",
    "doctor_kicker": "Uzman", "doctor_cta": "Sertifika & Üyelikler",
    "apart_kicker": "Neden Op. Dr. Alper Burak Uslu", "apart_title": "Uluslararası Üyelik ve Sertifikalar",
    "apart_desc": "Uluslararası ve ulusal plastik cerrahi kuruluşlarının aktif üyesi.",
    "reviews_kicker": "Görüşler", "reviews_title": "Hasta Yorumları",
    "gallery_kicker": "Başarılar", "gallery_title": "Kongre, Sertifika ve Bilimsel Faaliyetler",
    "cta_title": "Ücretsiz Ön Görüşme İçin Bize Ulaşın",
    "cta_desc": "Sorularınızı yanıtlayalım, size en uygun yaklaşımı birlikte belirleyelim.",
    "related_title": "İlgili Uzmanlıklar", "faq_title": "Sık Sorulan Sorular",
    "form_name": "Ad Soyad", "form_phone": "Telefon", "form_msg": "Mesajınız", "form_send": "Gönder",
    "form_note": "Bu bir önizleme formudur; canlı sürümde başvurular e-posta ve WhatsApp'a iletilir.",
    "hours_title": "Çalışma Saatleri", "hours": "Pazartesi – Cumartesi: 09:00 – 19:00",
    "footer_quick": "Hızlı İletişim", "footer_addr": "Adres", "footer_follow": "Takip Edin",
    "legal_note": "Bu web sitesindeki bilgiler yalnızca bilgilendirme amaçlıdır ve tıbbi tavsiye yerine geçmez. Sonuçlar kişiden kişiye değişebilir.",
    "all_procedures": "Tüm Uzmanlıklar", "back_home": "Ana Sayfaya Dön",
    "preview_banner": "Bu, tasarımın statik önizlemesidir. Canlı sürüm WordPress temasıdır.",
    "menu": "Menü", "close": "Kapat", "directions": "Yol Tarifi", "to_top": "Yukarı çık", "notfound": "Sayfa bulunamadı", "notfound_desc": "Aradığınız sayfa taşınmış veya kaldırılmış olabilir.",
  },
  "en": {
    "nav_home": "Home", "nav_about": "About", "nav_procedures": "Procedures",
    "nav_achievements": "Achievements", "nav_contact": "Contact", "nav_blog": "Blog",
    "cta_appt": "Book Appointment", "cta_call": "Call Now", "cta_wa": "WhatsApp",
    "hero_role": "Plastic, Reconstructive and Aesthetic Surgery",
    "hero_sub": "A scientific approach to aesthetic surgery, natural results and personalized planning.",
    "mission_kicker": "Approach", "counters_kicker": "In Numbers", "mission_title": "Leading the Way in Aesthetic Surgery", "mission_text": "We aim to help every patient reach the most suitable, natural result through comprehensive, artistic and minimally invasive solutions.",
    "c_years": "Years Experience", "c_aesthetic": "Aesthetic Procedures", "c_surgery": "Surgeries", "c_citation": "Scientific Citations",
    "about_kicker": "Get to know", "about_more": "More About Me",
    "services_kicker": "Areas of Expertise", "services_title": "Aesthetic Surgery Procedures",
    "services_all": "View All Procedures", "in_category": "procedures", "card_cta": "Explore",
    "steps_kicker": "Process", "steps_title": "How We Proceed",
    "step1_t": "Consultation", "step1_d": "We listen to your expectations, perform a face/body analysis and explain the options clearly.",
    "step2_t": "Personalized Plan", "step2_d": "A realistic, personalized plan is prepared according to your anatomy and goals.",
    "step3_t": "Surgery & Follow-up", "step3_d": "The procedure is performed; we stay by your side with regular check-ups during recovery.",
    "doctor_kicker": "The Surgeon", "doctor_cta": "Certificates & Memberships",
    "apart_kicker": "Why Dr. Alper Burak Uslu", "apart_title": "International Memberships & Certificates",
    "apart_desc": "Active member of international and national plastic surgery associations.",
    "reviews_kicker": "Reviews", "reviews_title": "Patient Testimonials",
    "gallery_kicker": "Achievements", "gallery_title": "Congresses, Certificates & Scientific Activities",
    "cta_title": "Contact Us for a Free Preliminary Consultation",
    "cta_desc": "Let us answer your questions and determine the most suitable approach together.",
    "related_title": "Related Procedures", "faq_title": "Frequently Asked Questions",
    "form_name": "Full Name", "form_phone": "Phone", "form_msg": "Your Message", "form_send": "Send",
    "form_note": "This is a preview form; in the live version submissions are sent to email and WhatsApp.",
    "hours_title": "Working Hours", "hours": "Monday – Saturday: 09:00 – 19:00",
    "footer_quick": "Quick Contact", "footer_addr": "Address", "footer_follow": "Follow",
    "legal_note": "The information on this website is for informational purposes only and is not a substitute for medical advice. Results may vary from person to person.",
    "all_procedures": "All Procedures", "back_home": "Back to Home",
    "preview_banner": "This is a static preview of the design. The live version is the WordPress theme.",
    "menu": "Menu", "close": "Close", "directions": "Directions", "to_top": "Back to top", "notfound": "Page not found", "notfound_desc": "The page you are looking for may have been moved or removed.",
  },
  "de": {
    "nav_home": "Startseite", "nav_about": "Über mich", "nav_procedures": "Spezialisierungen",
    "nav_achievements": "Erfolge", "nav_contact": "Kontakt", "nav_blog": "Blog",
    "cta_appt": "Termin buchen", "cta_call": "Jetzt anrufen", "cta_wa": "WhatsApp",
    "hero_role": "Plastische, Rekonstruktive und Ästhetische Chirurgie",
    "hero_sub": "Ein wissenschaftlicher Ansatz in der ästhetischen Chirurgie, natürliche Ergebnisse und individuelle Planung.",
    "mission_kicker": "Ansatz", "counters_kicker": "In Zahlen", "mission_title": "Wegweisend in der ästhetischen Chirurgie", "mission_text": "Wir helfen jedem Patienten, durch umfassende, künstlerische und minimalinvasive Lösungen das passendste, natürliche Ergebnis zu erreichen.",
    "c_years": "Jahre Erfahrung", "c_aesthetic": "Ästhetische Eingriffe", "c_surgery": "Operationen", "c_citation": "Wissenschaftliche Zitate",
    "about_kicker": "Kennenlernen", "about_more": "Mehr über mich",
    "services_kicker": "Fachgebiete", "services_title": "Ästhetische chirurgische Eingriffe",
    "services_all": "Alle Spezialisierungen", "in_category": "Eingriffe", "card_cta": "Ansehen",
    "steps_kicker": "Ablauf", "steps_title": "Wie wir vorgehen",
    "step1_t": "Beratung", "step1_d": "Wir hören Ihren Erwartungen zu, führen eine Gesichts-/Körperanalyse durch und erklären die Optionen klar.",
    "step2_t": "Individuelle Planung", "step2_d": "Ein realistischer, individueller Plan wird entsprechend Ihrer Anatomie und Ziele erstellt.",
    "step3_t": "Operation & Nachsorge", "step3_d": "Der Eingriff wird durchgeführt; wir stehen Ihnen mit regelmäßigen Kontrollen zur Seite.",
    "doctor_kicker": "Der Chirurg", "doctor_cta": "Zertifikate & Mitgliedschaften",
    "apart_kicker": "Warum Dr. Alper Burak Uslu", "apart_title": "Internationale Mitgliedschaften & Zertifikate",
    "apart_desc": "Aktives Mitglied internationaler und nationaler Gesellschaften für plastische Chirurgie.",
    "reviews_kicker": "Bewertungen", "reviews_title": "Patientenbewertungen",
    "gallery_kicker": "Erfolge", "gallery_title": "Kongresse, Zertifikate & wissenschaftliche Aktivitäten",
    "cta_title": "Kontaktieren Sie uns für eine kostenlose Erstberatung",
    "cta_desc": "Wir beantworten Ihre Fragen und bestimmen gemeinsam den passenden Ansatz.",
    "related_title": "Ähnliche Eingriffe", "faq_title": "Häufig gestellte Fragen",
    "form_name": "Vollständiger Name", "form_phone": "Telefon", "form_msg": "Ihre Nachricht", "form_send": "Senden",
    "form_note": "Dies ist ein Vorschau-Formular; in der Live-Version werden Anfragen per E-Mail und WhatsApp gesendet.",
    "hours_title": "Öffnungszeiten", "hours": "Montag – Samstag: 09:00 – 19:00",
    "footer_quick": "Schnellkontakt", "footer_addr": "Adresse", "footer_follow": "Folgen",
    "legal_note": "Die Informationen auf dieser Website dienen nur zu Informationszwecken und ersetzen keine medizinische Beratung. Die Ergebnisse können von Person zu Person variieren.",
    "all_procedures": "Alle Spezialisierungen", "back_home": "Zur Startseite",
    "preview_banner": "Dies ist eine statische Vorschau des Designs. Die Live-Version ist das WordPress-Theme.",
    "menu": "Menü", "close": "Schließen", "directions": "Route", "to_top": "Nach oben", "notfound": "Seite nicht gefunden", "notfound_desc": "Die gesuchte Seite wurde möglicherweise verschoben oder entfernt.",
  },
}

COUNTERS = [
    ("12", "", "c_years"), ("2000", "+", "c_aesthetic"),
    ("4000", "+", "c_surgery"), ("35", "+", "c_citation"),
]

# Yaklaşım/değerler (doktorun bio'daki felsefesinden — uydurma yorum yerine dürüst içerik)
VALUES = {
    "tr": [("Bilimsel Yaklaşım", "Güncel literatür ve kanıta dayalı yöntemlerle, sürekli güncellenen bir cerrahi anlayış."),
           ("Doğal Sonuçlar", "Abartıdan uzak, yüz ve vücut bütünlüğüne saygılı, doğal görünen sonuçlar."),
           ("Kişiye Özel Plan", "Her hastanın anatomisi ve beklentisine göre gerçekçi, bireysel planlama."),
           ("Etik ve Güven", "Şeffaf bilgilendirme, gerçekçi beklenti yönetimi ve hasta güvenliği önceliği.")],
    "en": [("Scientific Approach", "An evidence-based surgical philosophy, continuously updated with current literature."),
           ("Natural Results", "Natural-looking outcomes that respect facial and body harmony, free of exaggeration."),
           ("Personalized Plan", "Realistic, individual planning based on each patient's anatomy and expectations."),
           ("Ethics and Trust", "Transparent information, realistic expectation management and patient safety first.")],
    "de": [("Wissenschaftlicher Ansatz", "Eine evidenzbasierte chirurgische Philosophie, stets auf dem neuesten Stand der Literatur."),
           ("Natürliche Ergebnisse", "Natürlich wirkende Ergebnisse, die Gesichts- und Körperharmonie respektieren."),
           ("Individueller Plan", "Realistische, individuelle Planung nach Anatomie und Erwartungen jedes Patienten."),
           ("Ethik und Vertrauen", "Transparente Information, realistisches Erwartungsmanagement und Patientensicherheit.")],
}
VALUE_KICKER = {"tr": "Yaklaşımımız", "en": "Our Approach", "de": "Unser Ansatz"}
VALUE_TITLE = {"tr": "Sizi Neyin Beklediğini Bilerek", "en": "Knowing What to Expect", "de": "Wissen, was Sie erwartet"}

# ---------------------------------------------------------------- yardımcılar
def norm(s):
    s = unicodedata.normalize("NFKD", s.lower())
    s = "".join(c for c in s if not unicodedata.combining(c))
    return s

def esc(s): return htmllib.escape(s or "", quote=True)

def categorize(slug, title):
    s = norm(slug) + " " + norm(title)
    if any(k in s for k in ["meme", "breast", "brust"]): return "gogus"
    if any(k in s for k in ["liposuction", "lipo", "bbl", "karin", "tummy", "abdomen", "bauch",
                            "kalca", "hip", "hufte", "popo", "gesas", "butt", "gluteal", "kol", "arm",
                            "vaser", "genital", "gidi", "double-chin", "korper", "body", "vucut",
                            "fettabsaugung", "absaugung", "jowl", "po-ast", "brasilianische", "pobacke"]): return "vucut"
    if any(k in s for k in ["botoks", "botox", "dolgu", "filler", "full", "fett", "prp", "genclik",
                            "mezoterapi", "meso", "non-surgical", "nicht", "ameliyatsiz", "dermal",
                            "verjungung", "vitamin"]): return "ameliyatsiz"
    return "yuz"

# TR: menüden kesin eşleme
TR_SLUG_CAT = {}
_cat_of_group = {"YÜZ ESTETİĞİ": "yuz", "VÜCUT ESTETİĞİ": "vucut", "GÖĞÜS ESTETİĞİ": "gogus", "AMELİYATSIZ ESTETİK": "ameliyatsiz"}
for grp, slugs in MENU_TR.items():
    for sl in slugs:
        TR_SLUG_CAT[sl] = _cat_of_group.get(grp, "yuz")

def img_exists(name): return name in MEDIA_FILES

# kategori temsili görsel (mevcut medyadan)
CAT_IMG = {
    "yuz": "Yuz-Germe-400x300.jpg", "vucut": "Liposuction-400x300.jpg",
    "gogus": "Meme-Buyutme-400x300.jpg", "ameliyatsiz": "Popo-Estetigi-400x300.jpg",
}
# işleme özgü thumbnail (alt eşleşen home görselleri)
def proc_image(proc, cat):
    if proc.get("images"):
        return proc["images"][0]["local"]
    # başlığa göre home thumbnaili ara
    nt = norm(proc["title"])
    for f in MEDIA_FILES:
        if f.endswith(("-400x300.jpg", "-400x300.webp")):
            base = norm(f.replace("-400x300", "").rsplit(".", 1)[0]).replace("-", " ")
            if base and (base in nt or nt in base):
                return f
    return None  # benzersiz görsel yoksa tekrarlı stok koyma → şık gradient kart kullanılır

DOCTOR_IMG = next((f for f in ["alper-burak-uslu.jpg", "alper-burak-uslu_.jpg", "alper-burak-uslu-1.webp"] if img_exists(f)), None)
# hero videosu (Instagram'dan) — media/hero.mp4 varsa hero'da otomatik oynar
HERO_VIDEO = next((f for f in ["hero.mp4", "hero-alper.mp4", "hero.webm"] if img_exists(f)), None)
# candid/ameliyat karesi (steps/apart yan görselleri için)
SURGERY_IMG = next((f for f in ["alper-burak-uslu_.jpg", "alper-burak-uslu_-1.jpg", "alper-burak-uslu_-2.jpg"] if img_exists(f) and f != DOCTOR_IMG), None)
LOGO_IMG = "logo-alper-burak-uslu.jpg" if img_exists("logo-alper-burak-uslu.jpg") else None

def classify_page(slug):
    s = norm(slug)
    if any(k in s for k in ["hakkimda", "about", "uber"]): return "about"
    if any(k in s for k in ["iletisim", "contact", "kontakt"]): return "contact"
    if any(k in s for k in ["yasal", "legal", "haftung", "recht", "impressum"]): return "legal"
    if any(k in s for k in ["basarilar", "achiev", "archiev", "erfolg"]): return "achievements"
    if any(k in s for k in ["testimonial", "yorum", "bewert", "referenz"]): return "testimonials"
    if any(k in s for k in ["ana-sayfa", "home", "startseite"]): return "home"
    return "other"

# ---------------------------------------------------------------- URL bağlamı
class Ctx:
    def __init__(self, lang, depth, ptype="other"):
        self.lang = lang; self.depth = depth; self.ptype = ptype
        self.t = T[lang]
    def link(self, p):            # lang-köküne göreli hedef
        return ("../" * self.depth) + p
    def asset(self, a):
        return ("../" * (self.depth + 1)) + "assets/" + a
    def media(self, name):
        return self.asset("media/" + name)
    def other(self, lang2, p2):
        return ("../" * (self.depth + 1)) + lang2 + "/" + p2

# lang -> tip -> (lang kökünden göreli dosya)
def type_path(ptype):
    return {
        "home": "index.html", "about": "hakkimda.html", "contact": "iletisim.html",
        "legal": "yasal-uyari.html", "achievements": "basarilar.html",
        "procedures": "uzmanliklar/index.html",
    }.get(ptype, "index.html")

def page_url(lang, ptype):
    # her dilde gerçek slug'lı dosya (SEO devamlılığı gösterimi)
    slugmap = {
        "tr": {"about": "hakkimda.html", "contact": "iletisim.html", "legal": "yasal-uyari.html", "achievements": "basarilar.html", "blog": "blog.html"},
        "en": {"about": "about.html", "contact": "contact.html", "legal": "legal.html", "achievements": "achievements.html", "blog": "blog.html"},
        "de": {"about": "ueber-mich.html", "contact": "kontakt.html", "legal": "impressum.html", "achievements": "erfolge.html", "blog": "blog.html"},
    }
    if ptype == "home": return "index.html"
    if ptype == "procedures": return "uzmanliklar/index.html"
    return slugmap[lang].get(ptype, "index.html")

def wa_link(text=""):
    import urllib.parse
    base = "https://wa.me/" + SITE["whatsapp"]
    return base + ("?text=" + urllib.parse.quote(text) if text else "")

# ---------------------------------------------------------------- SVG ikonlar
IC = {
 # Premium ikon seti — Lucide dili (stroke 2, yuvarlak uç/köşe), shadcn/ui ile aynı görsel dil.
 "phone": '<svg viewBox="0 0 24 24" fill="none" class="w-4 h-4" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>',
 "wa": '<svg viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5" aria-hidden="true"><path d="M12 2a10 10 0 00-8.6 15l-1 3.7 3.8-1A10 10 0 1012 2Zm5.3 13.9c-.2.6-1.3 1.2-1.8 1.2-.5.1-1 .1-1.6-.1-.4-.1-.9-.3-1.5-.6a9 9 0 01-3.9-3.9c-.3-.6-.5-1.1-.5-1.6 0-.5.5-1.2 1-1.5.2-.1.4-.1.5 0l.9 1.3c.1.2.1.4 0 .6l-.4.6c-.1.2-.1.3 0 .5.4.7 1.2 1.5 1.9 1.9.2.1.3.1.5 0l.6-.5c.2-.1.4-.1.6 0l1.3.9c.2.1.2.3.1.5Z"/></svg>',
 "arrow": '<svg viewBox="0 0 24 24" fill="none" class="w-4 h-4" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>',
 "chevron": '<svg viewBox="0 0 24 24" fill="none" class="w-4 h-4" aria-hidden="true"><path d="m6 9 6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
 "globe": '<svg viewBox="0 0 24 24" fill="none" class="w-[15px] h-[15px]" aria-hidden="true"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.7"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 0 20 15.3 15.3 0 0 1 0-20Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>',
 "menu": '<svg viewBox="0 0 24 24" fill="none" class="w-6 h-6" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>',
 "close": '<svg viewBox="0 0 24 24" fill="none" class="w-6 h-6" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>',
 "star": '<svg viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4" aria-hidden="true"><path d="M12 2l2.9 6.3 6.9.7-5.1 4.6 1.4 6.8L12 17.8 5.9 21.2l1.4-6.8L2.2 9.8l6.9-.7L12 2Z"/></svg>',
 "google": '<svg viewBox="0 0 24 24" class="w-4 h-4" aria-hidden="true"><path fill="#4285F4" d="M23 12.3c0-.8-.1-1.6-.2-2.3H12v4.5h6.2a5.3 5.3 0 01-2.3 3.5v2.9h3.7C21.7 18.9 23 15.9 23 12.3Z"/><path fill="#34A853" d="M12 24c3.2 0 5.9-1.1 7.8-2.9l-3.7-2.9c-1 .7-2.3 1.1-4.1 1.1-3.1 0-5.8-2.1-6.7-5H1.4v3C3.3 21.3 7.3 24 12 24Z"/><path fill="#FBBC05" d="M5.3 14.3a7.2 7.2 0 010-4.6v-3H1.4a12 12 0 000 10.6l3.9-3Z"/><path fill="#EA4335" d="M12 4.8c1.8 0 3.3.6 4.5 1.8l3.4-3.4C17.9 1.2 15.2 0 12 0 7.3 0 3.3 2.7 1.4 6.7l3.9 3c.9-2.9 3.6-5 6.7-5Z"/></svg>',
 "map": '<svg viewBox="0 0 24 24" fill="none" class="w-5 h-5" aria-hidden="true"><path d="M12 21s7-6 7-11a7 7 0 10-14 0c0 5 7 11 7 11Z" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="10" r="2.4" stroke="currentColor" stroke-width="1.6"/></svg>',
 "clock": '<svg viewBox="0 0 24 24" fill="none" class="w-5 h-5" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.6"/><path d="M12 7v5l3 2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>',
 "badge": '<svg viewBox="0 0 24 24" fill="none" class="w-6 h-6" aria-hidden="true"><path d="M12 3l2.5 1.8 3-.3 1 2.9 2.4 1.8-1 2.9 1 2.9-2.4 1.8-1 2.9-3-.3L12 21l-2.5-1.9-3 .3-1-2.9L3.1 15l1-2.9-1-2.9L5.5 7.4l1-2.9 3 .3L12 3Z" stroke="currentColor" stroke-width="1.4"/><path d="m9 12 2 2 4-4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>',
 "fb": '<svg viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5" aria-hidden="true"><path d="M14 9V7c0-.8.2-1 1-1h2V3h-3c-2.5 0-4 1.5-4 4v2H8v3h2v9h4v-9h2.5l.5-3h-3Z"/></svg>',
 "ig": '<svg viewBox="0 0 24 24" fill="none" class="w-5 h-5" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.6"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/></svg>',
 "yt": '<svg viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5" aria-hidden="true"><path d="M22 12c0-2-.2-3.4-.4-4.2a2.6 2.6 0 00-1.8-1.8C18.4 5.7 12 5.7 12 5.7s-6.4 0-7.8.3A2.6 2.6 0 002.4 7.8C2.2 8.6 2 10 2 12s.2 3.4.4 4.2a2.6 2.6 0 001.8 1.8c1.4.3 7.8.3 7.8.3s6.4 0 7.8-.3a2.6 2.6 0 001.8-1.8c.2-.8.4-2.2.4-4.2ZM10 15V9l5 3-5 3Z"/></svg>',
}
SOCIAL_IC = {"facebook": IC["fb"], "instagram": IC["ig"], "youtube": IC["yt"]}

# Mega-menü kategori ikonları (Lucide dili, stroke 1.7)
CAT_ICON = {
 "yuz": '<svg viewBox="0 0 24 24" fill="none" class="w-[18px] h-[18px]" aria-hidden="true"><circle cx="12" cy="12" r="9.2" stroke="currentColor" stroke-width="1.7"/><path d="M8.5 14.5s1.3 1.8 3.5 1.8 3.5-1.8 3.5-1.8M9 9.5h.01M15 9.5h.01" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>',
 "vucut": '<svg viewBox="0 0 24 24" fill="none" class="w-[18px] h-[18px]" aria-hidden="true"><circle cx="12" cy="4.5" r="1.8" stroke="currentColor" stroke-width="1.7"/><path d="m8.5 21 3.5-6 3.5 6M6 8.5l6 2 6-2M12 10.5V15" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>',
 "gogus": '<svg viewBox="0 0 24 24" fill="none" class="w-[18px] h-[18px]" aria-hidden="true"><path d="M19 14c1.5-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.29 1.5 4.04 3 5.5l7 7 7-7Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg>',
 "ameliyatsiz": '<svg viewBox="0 0 24 24" fill="none" class="w-[18px] h-[18px]" aria-hidden="true"><path d="m12 3-1.9 5.8a2 2 0 0 1-1.3 1.3L3 12l5.8 1.9a2 2 0 0 1 1.3 1.3L12 21l1.9-5.8a2 2 0 0 1 1.3-1.3L21 12l-5.8-1.9a2 2 0 0 1-1.3-1.3L12 3Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg>',
}

# ---------------------------------------------------------------- shell/head
def render_head(ctx, title, desc, canonical, alternates, jsonld_list, preload_img=None):
    t = ctx.t
    lines = []
    lines.append('<!DOCTYPE html>')
    lines.append('<html lang="' + ctx.lang + '" class="scroll-smooth">')
    lines.append('<head>')
    lines.append('<meta charset="utf-8">')
    lines.append('<meta name="viewport" content="width=device-width, initial-scale=1">')
    lines.append('<script>document.documentElement.classList.add("js")</script>')
    lines.append('<title>' + esc(title) + '</title>')
    if desc:
        lines.append('<meta name="description" content="' + esc(desc) + '">')
    lines.append('<meta name="robots" content="noindex,nofollow">')  # önizleme: indexlenmesin
    # Canonical + hreflang MUTLAK (göreli path derinlik hatasına yol açıyordu)
    import urllib.parse as _up
    page_abs = BASE_URL + "/" + ctx.lang + "/" + canonical.lstrip("./")
    lines.append('<link rel="canonical" href="' + esc(page_abs) + '">')
    for lg, url in (alternates or {}).items():
        if not url:
            continue
        alt_abs = _up.urljoin(page_abs, url)
        lines.append('<link rel="alternate" hreflang="' + lg + '" href="' + esc(alt_abs) + '">')
    if alternates and alternates.get("tr"):
        lines.append('<link rel="alternate" hreflang="x-default" href="' + esc(_up.urljoin(page_abs, alternates["tr"])) + '">')
    lines.append('<meta property="og:type" content="website">')
    lines.append('<meta property="og:title" content="' + esc(title) + '">')
    if desc: lines.append('<meta property="og:description" content="' + esc(desc) + '">')
    lines.append('<meta property="og:site_name" content="Op. Dr. Alper Burak Uslu">')
    lines.append('<meta property="og:locale" content="' + {"tr": "tr_TR", "en": "en_US", "de": "de_DE"}[ctx.lang] + '">')
    _og = preload_img or DOCTOR_IMG
    if _og:
        lines.append('<meta property="og:image" content="' + BASE_URL + '/assets/media/' + _og + '">')
    lines.append('<meta name="twitter:card" content="summary_large_image">')
    lines.append('<meta name="theme-color" content="#12857D">')
    lines.append('<link rel="icon" type="image/svg+xml" href="' + ctx.asset("favicon.svg") + '">')
    lines.append('<link rel="apple-touch-icon" href="' + ctx.asset("favicon.svg") + '">')
    # fontlar preload (kritik)
    lines.append('<link rel="preload" as="font" type="font/woff2" crossorigin href="' + ctx.asset("fonts/jakarta-500-latin-ext.woff2") + '">')
    lines.append('<link rel="preload" as="font" type="font/woff2" crossorigin href="' + ctx.asset("fonts/playfair-900-latin.woff2") + '">')
    lines.append('<link rel="preload" as="font" type="font/woff2" crossorigin href="' + ctx.asset("fonts/playfair-700-latin.woff2") + '">')
    if preload_img:
        lines.append('<link rel="preload" as="image" href="' + ctx.media(preload_img) + '">')
    lines.append('<link rel="stylesheet" href="' + ctx.asset("main.css") + '">')
    lines.append('<link rel="stylesheet" href="' + ctx.asset("vendor/swiper-bundle.min.css") + '">')
    lines.append('<script defer src="' + ctx.asset("vendor/alpine.min.js") + '"></script>')
    lines.append('<script defer src="' + ctx.asset("vendor/swiper-bundle.min.js") + '"></script>')
    lines.append('<script defer src="' + ctx.asset("main.js") + '"></script>')
    for j in jsonld_list:
        lines.append('<script type="application/ld+json">' + json.dumps(j, ensure_ascii=False) + '</script>')
    lines.append('</head>')
    return "\n".join(lines)

def logo_markup(ctx, on_dark=False):
    color = "text-white" if on_dark else "text-ink-900"
    sub = "text-white/70" if on_dark else "text-ink-500"
    inner = ('<span class="font-display text-[1.02rem] sm:text-[1.12rem] font-bold leading-tight whitespace-nowrap ' + color + '">Op. Dr. Alper Burak Uslu</span>'
             '<span class="block text-[0.6rem] sm:text-[0.66rem] uppercase tracking-[0.16em] ' + sub + ' mt-1 whitespace-nowrap">' + esc(ctx.t["hero_role"]) + '</span>')
    return '<a href="' + ctx.link("index.html") + '" class="flex flex-col justify-center">' + inner + '</a>'

def render_header(ctx, nav, langswitch):
    t = ctx.t

    # --- Uzmanlıklar mega-dropdown (tek, geniş 4 kolonlu panel — embrace dili) ---
    mega_cols = []
    for cat in CATS:
        procs = nav.get(cat, [])
        if not procs: continue
        links = "".join(
            '<a href="' + ctx.link("uzmanliklar/" + slug + ".html") + '" class="block py-1.5 text-[0.85rem] text-ink-700 hover:text-brand-700 transition-colors">' + esc(title) + '</a>'
            for slug, title in procs)
        mega_cols.append(
          '<div class="min-w-0">'
          '<a href="' + ctx.link("kategori/" + cat + ".html") + '" class="group/cat flex items-center gap-2.5 mb-2.5 pb-2.5 border-b border-line">'
          '<span class="grid place-content-center w-8 h-8 rounded-lg bg-brand-50 text-brand-600 shrink-0 transition-colors group-hover/cat:bg-brand-100">' + CAT_ICON.get(cat, '') + '</span>'
          '<span class="font-display font-semibold text-[0.92rem] text-ink-900 leading-tight">' + esc(CAT_LABEL[ctx.lang][cat]) + '</span></a>'
          + links + '</div>')
    mega = (
      '<div class="relative" x-data="{ open:false }" @mouseenter="open=true" @mouseleave="open=false" @focusin="open=true" @focusout="open=false">'
      '<button type="button" class="inline-flex items-center gap-1 px-3.5 py-2 text-[0.92rem] font-medium text-ink-700 hover:text-brand-700 transition-colors" :aria-expanded="open" :class="open && \'text-brand-700\'">'
      + esc(t["nav_procedures"]) + '<span class="transition-transform duration-200" :class="open && \'rotate-180\'">' + IC["chevron"] + '</span></button>'
      '<div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-cloak class="absolute left-1/2 -translate-x-1/2 top-full pt-3 z-40 w-[min(92vw,860px)]">'
      '<div class="bg-white rounded-2xl shadow-cardHover ring-1 ring-line p-6 grid grid-cols-2 md:grid-cols-4 gap-x-6 gap-y-4">'
      + "".join(mega_cols) + '</div></div></div>')

    # --- Dil switcher (belirgin pill: globe + TR/EN/DE) ---
    def build_langsw():
        chips = []
        for lg in LANGS:
            url = langswitch.get(lg)
            if lg == ctx.lang:
                chips.append('<span class="px-2 py-0.5 rounded-full bg-ink-900 text-white">' + LANG_LABEL[lg] + '</span>')
            elif url:
                chips.append('<a href="' + url + '" class="px-2 py-0.5 rounded-full text-ink-700 hover:text-ink-900 hover:bg-cream-50 transition-colors">' + LANG_LABEL[lg] + '</a>')
            else:
                chips.append('<span class="px-2 py-0.5 rounded-full text-ink-500 opacity-40">' + LANG_LABEL[lg] + '</span>')
        return ('<div class="flex items-center gap-0.5 rounded-full ring-1 ring-line bg-white/70 pl-2 pr-1 py-1 text-[0.78rem] font-semibold" aria-label="' + esc(t.get("lang_label", "Dil")) + '">'
                '<span class="text-ink-500 mr-0.5">' + IC["globe"] + '</span>' + "".join(chips) + '</div>')
    langsw = build_langsw()

    # --- CTA (embrace koyu pill + coral daire-ok) ---
    cta = ('<a href="' + wa_link() + '" target="_blank" rel="noopener" class="group/cta hidden sm:inline-flex items-center gap-2.5 rounded-full bg-ink-900 text-white pl-5 pr-1.5 py-1.5 text-[0.86rem] font-semibold hover:bg-ink-700 transition-colors">'
           '<span>' + esc(t["cta_appt"]) + '</span>'
           '<span class="grid place-content-center w-8 h-8 rounded-full bg-coral-500 text-white transition-colors group-hover/cta:bg-coral-600">' + IC["arrow"] + '</span></a>')

    desktop_nav = (
      '<nav class="hidden lg:flex items-center gap-0.5" aria-label="' + esc(t["menu"]) + '">'
      '<a href="' + ctx.link("hakkimda.html" if ctx.lang=="tr" else page_url(ctx.lang,"about")) + '" class="px-3.5 py-2 text-[0.92rem] font-medium text-ink-700 hover:text-brand-700 transition-colors whitespace-nowrap">' + esc(t["nav_about"]) + '</a>'
      + mega +
      '<a href="' + ctx.link(page_url(ctx.lang,"achievements")) + '" class="px-3.5 py-2 text-[0.92rem] font-medium text-ink-700 hover:text-brand-700 transition-colors whitespace-nowrap">' + esc(t["nav_achievements"]) + '</a>'
      '<a href="' + ctx.link(page_url(ctx.lang,"blog")) + '" class="px-3.5 py-2 text-[0.92rem] font-medium text-ink-700 hover:text-brand-700 transition-colors whitespace-nowrap">Blog</a>'
      '<a href="' + ctx.link(page_url(ctx.lang,"contact")) + '" class="px-3.5 py-2 text-[0.92rem] font-medium text-ink-700 hover:text-brand-700 transition-colors whitespace-nowrap">' + esc(t["nav_contact"]) + '</a>'
      '</nav>')

    # --- mobil menü içeriği (ikonlu akordeon kategoriler) ---
    mob_cats = []
    for cat in CATS:
        procs = nav.get(cat, [])
        if not procs: continue
        links = "".join('<a href="' + ctx.link("uzmanliklar/" + slug + ".html") + '" class="block py-1.5 text-[0.9rem] text-ink-500 hover:text-brand-700" @click="mobile=false">' + esc(title) + '</a>' for slug, title in procs)
        mob_cats.append(
          '<div x-data="{ o:false }" class="border-b border-line/70">'
          '<button @click="o=!o" class="w-full flex items-center justify-between py-3 font-medium text-ink-900">'
          '<span class="flex items-center gap-2.5"><span class="text-brand-600">' + CAT_ICON.get(cat, '') + '</span>' + esc(CAT_LABEL[ctx.lang][cat]) + '</span>'
          '<span class="transition-transform" :class="o && \'rotate-180\'">' + IC["chevron"] + '</span></button>'
          '<div x-show="o" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="pb-3 pl-[2.75rem]">' + links + '</div></div>')

    header = (
      '<header data-header class="fixed top-0 inset-x-0 z-50 transition-all duration-300 [&.is-scrolled]:bg-white/95 [&.is-scrolled]:backdrop-blur [&.is-scrolled]:shadow-soft bg-white/80 backdrop-blur-sm" x-data="{ mobile:false }">'
      '<div class="container flex items-center justify-between gap-4" style="height:var(--nav-h)">'
      + logo_markup(ctx)
      + desktop_nav +
      '<div class="flex items-center gap-2.5">'
      '<div class="hidden md:block">' + langsw + '</div>'
      + cta +
      '<button @click="mobile=true" class="lg:hidden p-1.5 -mr-1 text-ink-900" aria-label="' + esc(t["menu"]) + '">' + IC["menu"] + '</button>'
      '</div></div>'
      # mobil overlay
      '<div x-show="mobile" x-cloak x-transition.opacity class="fixed inset-0 z-50 bg-ink-900/40 lg:hidden" @click="mobile=false"></div>'
      '<div x-show="mobile" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="fixed top-0 right-0 z-50 h-full w-[86%] max-w-sm bg-white shadow-2xl p-6 overflow-y-auto lg:hidden">'
      '<div class="flex items-center justify-between mb-5"><span class="font-display font-bold text-ink-900 text-lg">' + esc(t["menu"]) + '</span>'
      '<button @click="mobile=false" class="p-1.5" aria-label="' + esc(t["close"]) + '">' + IC["close"] + '</button></div>'
      '<div class="mb-4">' + build_langsw() + '</div>'
      '<a href="' + ctx.link("hakkimda.html" if ctx.lang=="tr" else page_url(ctx.lang,"about")) + '" class="block py-3 font-medium text-ink-900 border-b border-line/70" @click="mobile=false">' + esc(t["nav_about"]) + '</a>'
      + "".join(mob_cats) +
      '<a href="' + ctx.link(page_url(ctx.lang,"achievements")) + '" class="block py-3 font-medium text-ink-900 border-b border-line/70" @click="mobile=false">' + esc(t["nav_achievements"]) + '</a>'
      '<a href="' + ctx.link(page_url(ctx.lang,"blog")) + '" class="block py-3 font-medium text-ink-900 border-b border-line/70" @click="mobile=false">Blog</a>'
      '<a href="' + ctx.link(page_url(ctx.lang,"contact")) + '" class="block py-3 font-medium text-ink-900 border-b border-line/70" @click="mobile=false">' + esc(t["nav_contact"]) + '</a>'
      '<a href="' + wa_link() + '" target="_blank" rel="noopener" class="btn-primary w-full mt-6 justify-center">' + esc(t["cta_appt"]) + '</a>'
      '</div>'
      '</header>')
    return header

def render_mobilebar(ctx):
    t = ctx.t
    return (
      '<div class="fixed bottom-0 inset-x-0 z-40 grid grid-cols-2 gap-px bg-line sm:hidden">'
      '<a href="tel:' + SITE["phone_tel"] + '" class="flex items-center justify-center gap-2 bg-brand-600 text-white py-3.5 font-semibold text-sm">' + IC["phone"] + esc(t["cta_call"]) + '</a>'
      '<a href="' + wa_link() + '" target="_blank" rel="noopener" class="flex items-center justify-center gap-2 bg-[#0E7A3A] text-white py-3.5 font-semibold text-sm">' + IC["wa"] + esc(t["cta_wa"]) + '</a>'
      '</div>')

def render_footer(ctx, nav):
    t = ctx.t
    cats = "".join('<li><a href="' + ctx.link("kategori/" + c + ".html") + '" class="text-white/70 hover:text-white transition">' + esc(CAT_LABEL[ctx.lang][c]) + '</a></li>' for c in CATS if nav.get(c))
    socials = "".join('<a href="' + url + '" target="_blank" rel="noopener" aria-label="' + k + '" class="w-10 h-10 grid place-content-center rounded-full bg-white/10 hover:bg-brand-500 transition">' + SOCIAL_IC[k] + '</a>' for k, url in SITE["social"].items())
    form = (
      '<form class="grid gap-3" onsubmit="event.preventDefault(); this.querySelector(\'[data-ok]\').classList.remove(\'hidden\');">'
      '<input type="text" required placeholder="' + esc(t["form_name"]) + '" class="w-full rounded-xl bg-white/10 ring-1 ring-white/15 px-4 py-3 text-white placeholder-white/50 focus:ring-brand-400 outline-none">'
      '<input type="tel" required placeholder="' + esc(t["form_phone"]) + '" class="w-full rounded-xl bg-white/10 ring-1 ring-white/15 px-4 py-3 text-white placeholder-white/50 focus:ring-brand-400 outline-none">'
      '<textarea rows="3" placeholder="' + esc(t["form_msg"]) + '" class="w-full rounded-xl bg-white/10 ring-1 ring-white/15 px-4 py-3 text-white placeholder-white/50 focus:ring-brand-400 outline-none"></textarea>'
      '<button type="submit" class="btn-gold w-full">' + esc(t["form_send"]) + '</button>'
      '<p data-ok class="hidden text-brand-300 text-sm">✓ ' + esc(t["form_note"]) + '</p>'
      '</form>')
    return (
      '<footer class="bg-ink-900 text-white pt-16 pb-24 sm:pb-10 mt-0">'
      '<div class="container grid gap-12 lg:grid-cols-4">'
      '<div class="lg:col-span-1">' + logo_markup(ctx, on_dark=True) +
        '<p class="text-white/60 text-sm mt-4 max-w-xs">' + esc(t["legal_note"]) + '</p>'
        '<div class="flex gap-2 mt-5">' + socials + '</div></div>'
      '<div><h2 class="font-display text-lg mb-4 text-white">' + esc(t["nav_procedures"]) + '</h2><ul class="space-y-2 text-sm">' + cats +
        '<li><a href="' + ctx.link("uzmanliklar/index.html") + '" class="text-brand-300 hover:text-white font-medium">' + esc(t["all_procedures"]) + ' →</a></li></ul></div>'
      '<div><h2 class="font-display text-lg mb-4 text-white">' + esc(t["footer_addr"]) + '</h2>'
        '<a href="' + SITE["maps"] + '" target="_blank" rel="noopener" class="flex gap-2 text-white/70 text-sm hover:text-white">' + IC["map"] + '<span>' + esc(SITE["address"]) + '</span></a>'
        '<a href="tel:' + SITE["phone_tel"] + '" class="flex gap-2 text-white/70 text-sm hover:text-white mt-3">' + IC["phone"] + esc(SITE["phone_display"]) + '</a>'
        '<div class="flex gap-2 text-white/70 text-sm mt-3">' + IC["clock"] + '<div><div class="text-white/90">' + esc(t["hours_title"]) + '</div>' + esc(t["hours"]) + '</div></div></div>'
      '<div><h2 class="font-display text-lg mb-4 text-white">' + esc(t["footer_quick"]) + '</h2>' + form + '</div>'
      '</div>'
      '<div class="container mt-12 pt-6 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-3 text-white/50 text-xs">'
      '<span>© 2026 Op. Dr. Alper Burak Uslu. Tüm hakları saklıdır.</span>'
      '<a href="' + ctx.link(page_url(ctx.lang,"legal")) + '" class="hover:text-white">' + esc(T[ctx.lang]["nav_about"] if False else ("Yasal Uyarı" if ctx.lang=="tr" else ("Legal Notice" if ctx.lang=="en" else "Impressum"))) + '</a>'
      '</div></footer>')

def preview_banner(ctx):
    return ('<div x-data="{s:true}" x-show="s" class="bg-brand-700 text-white text-center text-[0.8rem] py-2 px-4 relative">'
            '<span>' + esc(ctx.t["preview_banner"]) + '</span>'
            '<button @click="s=false" class="absolute right-3 top-1/2 -translate-y-1/2 opacity-70 hover:opacity-100" aria-label="x">✕</button></div>')

def page_shell(ctx, title, desc, canonical, alternates, jsonld, body, nav, langswitch, preload_img=None):
    head = render_head(ctx, title, desc, canonical, alternates, jsonld, preload_img)
    return (head + '\n<body class="min-h-screen flex flex-col" x-cloak-init>\n'
            + '<style>[x-cloak]{display:none!important}</style>'
            + preview_banner(ctx)
            + render_header(ctx, nav, langswitch)
            + '<main class="flex-1" style="padding-top:var(--nav-h)">' + body + '</main>'
            + render_footer(ctx, nav)
            + render_mobilebar(ctx)
            + render_whatsapp_float(ctx)
            + render_scrolltop(ctx)
            + '\n</body></html>')

def render_whatsapp_float(ctx):
    # masaüstü sabit WhatsApp (mobilde alt çubuk zaten var → sm ve üstü göster)
    return ('<a href="' + wa_link() + '" target="_blank" rel="noopener" '
            'aria-label="WhatsApp" '
            'class="hidden sm:flex fixed z-40 right-5 bottom-5 items-center gap-2 rounded-full bg-[#0E7A3A] text-white pl-4 pr-5 py-3 shadow-cardHover '
            'hover:pr-6 transition-all duration-300 group">' + IC["wa"] +
            '<span class="font-semibold text-sm">WhatsApp</span></a>')

def render_scrolltop(ctx):
    # uzun sayfalarda "yukarı çık" — kaydırınca belirir (mobil sabit çubuğun üstünde)
    return ('<button x-data="{ show:false }" x-init="window.addEventListener(\'scroll\',()=>show=window.scrollY>600)" '
            'x-show="show" x-transition @click="window.scrollTo({top:0,behavior:\'smooth\'})" x-cloak '
            'aria-label="' + esc(ctx.t.get("to_top", "Yukarı çık")) + '" '
            'class="fixed z-40 right-4 sm:right-5 bottom-20 sm:bottom-[5.5rem] w-11 h-11 rounded-full bg-white text-brand-700 ring-1 ring-line shadow-cardHover grid place-content-center hover:bg-brand-50 transition">'
            '<svg viewBox="0 0 24 24" class="w-5 h-5" fill="none"><path d="M12 19V5m0 0-6 6m6-6 6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></button>')

# ---------------------------------------------------------------- bölümler
def sec_hero(ctx, about_intro):
    t = ctx.t
    # hero medyası: video varsa embrace gibi otomatik oynayan sessiz döngü, yoksa portre
    if HERO_VIDEO:
        poster = (' poster="' + ctx.media('hero-poster.jpg') + '"') if img_exists('hero-poster.jpg') else ((' poster="' + ctx.media(DOCTOR_IMG) + '"') if DOCTOR_IMG else '')
        img = ('<video autoplay muted loop playsinline preload="metadata"' + poster +
               ' class="w-full h-full object-cover"><source src="' + ctx.media(HERO_VIDEO) + '" type="video/mp4"></video>')
        aspect = "aspect-[16/10]"
    else:
        img = ('<img src="' + ctx.media(DOCTOR_IMG) + '" alt="Op. Dr. Alper Burak Uslu" width="560" height="680" fetchpriority="high" '
               'class="w-full h-full object-cover">') if DOCTOR_IMG else ''
        aspect = "aspect-[4/5]"
    # editorial: dev Playfair başlık + üstüne binen yuvarlak fotoğraf + coral buton
    swoosh = ('<svg class="absolute inset-0 w-full h-full opacity-[0.5] pointer-events-none" preserveAspectRatio="none" viewBox="0 0 1440 700" aria-hidden="true">'
              '<path d="M-50 520 Q400 380 760 470 T1500 360" fill="none" stroke="#E3E8E7" stroke-width="1.5"/>'
              '<path d="M-50 600 Q450 470 820 560 T1500 450" fill="none" stroke="#E3E8E7" stroke-width="1"/></svg>')
    return (
      '<section class="relative overflow-hidden bg-white">' + swoosh +
      '<div class="container relative pt-8 pb-14 md:pt-14 md:pb-24">'
      '<div class="grid lg:grid-cols-12 gap-6 lg:gap-4 items-center">'
      # dev başlık
      '<div class="lg:col-span-7 relative z-10 reveal">'
      '<span class="kicker mb-6">' + esc(t["hero_role"]) + '</span>'
      '<h1 class="text-hero font-display font-black text-ink-900 mt-5 tracking-[-0.03em]">Op. Dr. Alper<br><span class="italic font-bold">Burak Uslu</span></h1>'
      '</div>'
      # üstüne binen medya (video/foto)
      '<div class="lg:col-span-5 lg:-ml-8 relative z-0 reveal">'
      '<div class="' + aspect + ' ' + ('max-w-xl' if HERO_VIDEO else 'max-w-md') + ' ml-auto rounded-[2rem] overflow-hidden ring-1 ring-line shadow-cardHover">' + img + '</div>'
      '</div></div>'
      # alt: subhead + buton (sağ hizalı, editorial — fotoğrafın altında, overlap yok)
      '<div class="lg:pl-[54%] mt-8 lg:mt-6 relative z-10 reveal">'
      '<p class="font-display italic text-[1.5rem] md:text-[1.9rem] text-ink-900 leading-[1.25] max-w-lg">' + esc(t["hero_sub"]) + '</p>'
      '<div class="flex flex-wrap items-center gap-4 mt-7">'
      '<a href="' + wa_link() + '" target="_blank" rel="noopener" class="btn-primary">' + esc(t["cta_appt"]) + '</a>'
      '<a href="' + ctx.link("uzmanliklar/index.html") + '" class="text-ink-900 font-semibold underline decoration-brand-500 decoration-2 underline-offset-4 hover:text-brand-700">' + esc(t["all_procedures"]) + '</a>'
      '</div></div>'
      '</div></section>')

def sec_counters(ctx):
    t = ctx.t
    # embrace: teal Playfair serif istatistikler + misyon bloğu (dikey ayraç)
    items = "".join(
      '<div class="reveal">'
      '<div class="font-display font-bold text-brand-600 text-[2.6rem] md:text-[3rem] leading-none"><span data-count="' + n + '">0</span>' + s + '</div>'
      '<div class="text-ink-700 mt-2 text-sm font-medium">' + esc(t[k]) + '</div></div>'
      for n, s, k in COUNTERS)
    mission = (
      '<div class="reveal lg:border-l lg:border-line lg:pl-8">'
      '<h2 class="font-display text-[1.5rem] font-bold text-ink-900 leading-snug">' + esc(t.get("mission_title", "Estetik Cerrahide Öncü Yaklaşım")) + '</h2>'
      '<p class="text-ink-500 mt-3 text-[0.95rem] leading-relaxed">' + esc(t.get("mission_text", "Kapsamlı, sanatsal ve minimal invaziv çözümlerle her hastanın kendine en uygun sonuca ulaşmasını hedefliyoruz.")) + '</p></div>')
    return (
      '<section class="py-14 md:py-20 bg-white"><div class="container">'
      '<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8 md:gap-10 items-start">' + items + mission + '</div>'
      '</div></section>')

def sec_about_split(ctx, about):
    t = ctx.t
    img = about["images"][0]["local"] if about.get("images") else DOCTOR_IMG
    imgblock = ('<img src="' + ctx.media(img) + '" alt="Op. Dr. Alper Burak Uslu" width="600" height="720" loading="lazy" class="rounded-xl2 object-cover w-full shadow-card ring-1 ring-line">') if img else ''
    # ilk 2 paragraf
    paras = [p for p in about["text"].split("\n") if len(p.strip()) > 40][:3]
    body = "".join('<p class="mb-4">' + esc(p.strip()) + '</p>' for p in paras)
    return (
      '<section class="section bg-cream-50"><div class="container grid lg:grid-cols-2 gap-12 items-center">'
      '<div class="reveal order-2 lg:order-1">' + imgblock + '</div>'
      '<div class="reveal order-1 lg:order-2">'
      '<span class="kicker mb-4">' + esc(t["about_kicker"]) + '</span>'
      '<h2 class="section-title mt-3 mb-5">' + esc(t["nav_about"]) + '</h2>'
      '<div class="prose-clinic">' + body + '</div>'
      '<a href="' + ctx.link(page_url(ctx.lang,"about")) + '" class="btn-outline mt-6">' + esc(t["about_more"]) + '</a>'
      '</div></div></section>')

CAT_MONO = {"yuz": "Yüz", "vucut": "Vücut", "gogus": "Göğüs", "ameliyatsiz": "Estetik"}

def _card(ctx, slug, title, cat, excerpt=""):
    img = None
    proc = PROC_BY_SLUG.get((ctx.lang, slug))
    if proc:
        img = proc_image(proc, cat)
        if not excerpt:
            excerpt = meta_desc(proc)
    if img:
        imgtag = ('<div class="aspect-[4/3] overflow-hidden"><img src="' + ctx.media(img) + '" alt="' + esc(title) + '" width="640" height="480" loading="lazy" class="w-full h-full object-cover transition duration-500 group-hover:scale-105"></div>')
    else:
        # benzersiz foto yoksa: tasarlanmış gradient kapak (tekrarlı stok yerine)
        deco = ('<svg viewBox="0 0 200 150" class="absolute inset-0 w-full h-full opacity-[0.16]" preserveAspectRatio="xMidYMid slice" aria-hidden="true">'
                '<circle cx="28" cy="122" r="72" fill="none" stroke="white" stroke-width="1"/>'
                '<circle cx="28" cy="122" r="52" fill="none" stroke="white" stroke-width="1"/>'
                '<circle cx="178" cy="22" r="56" fill="none" stroke="white" stroke-width="1"/></svg>')
        emblem = ('<span class="relative w-14 h-14 rounded-full ring-1 ring-white/40 grid place-content-center text-white/90 '
                  'transition duration-500 group-hover:scale-110">' + IC["badge"] + '</span>')
        imgtag = ('<div class="aspect-[4/3] relative overflow-hidden bg-gradient-to-br from-brand-500 to-brand-700 grid place-content-center">'
                  + deco + emblem + '</div>')
    return (
      '<a href="' + ctx.link("uzmanliklar/" + slug + ".html") + '" class="group card card-hover overflow-hidden block">'
      + imgtag +
      '<div class="p-5">'
      '<span class="text-[0.7rem] uppercase tracking-wider text-brand-600 font-semibold">' + esc(CAT_LABEL[ctx.lang][cat]) + '</span>'
      '<h3 class="font-display text-h3 font-semibold text-ink-900 mt-1 group-hover:text-brand-700 transition">' + esc(title) + '</h3>'
      + ('<p class="text-sm text-ink-500 mt-2 line-clamp-2">' + esc(excerpt) + '</p>' if excerpt else '') +
      '<span class="inline-flex items-center gap-1 text-sm text-brand-600 font-medium mt-3">' + esc(ctx.t["card_cta"]) + IC["arrow"] + '</span>'
      '</div></a>')

def sec_services(ctx, nav):
    t = ctx.t
    # Vitrin: GÖRSELLİ işlemleri öncele (tekrarlı teal placeholder yerine gerçek fotoğraf),
    # kategori-çeşitli round-robin ile 8 kart seç.
    pools = {}
    for cat in CATS:
        pools[cat] = [ (slug, title) for slug, title in nav.get(cat, [])
                       if PROC_BY_SLUG.get((ctx.lang, slug)) and proc_image(PROC_BY_SLUG[(ctx.lang, slug)], cat) ]
    cards = []
    idx = { c: 0 for c in CATS }
    while len(cards) < 8 and any( idx[c] < len(pools[c]) for c in CATS ):
        for cat in CATS:
            if idx[cat] < len(pools[cat]):
                slug, title = pools[cat][idx[cat]]; idx[cat] += 1
                cards.append(_card(ctx, slug, title, cat))
                if len(cards) >= 8: break
    # Nadir: 8'e ulaşılmadıysa görselsizlerle tamamla (grid boş kalmasın)
    if len(cards) < 8:
        for cat in CATS:
            for slug, title in nav.get(cat, []):
                proc = PROC_BY_SLUG.get((ctx.lang, slug))
                if proc and proc_image(proc, cat): continue
                cards.append(_card(ctx, slug, title, cat))
                if len(cards) >= 8: break
            if len(cards) >= 8: break
    cards = cards[:8]
    cat_pills = "".join('<a href="' + ctx.link("kategori/" + c + ".html") + '" class="px-4 py-2 rounded-full text-sm font-medium ring-1 ring-line hover:ring-brand-600 hover:text-brand-700 transition bg-white">' + esc(CAT_LABEL[ctx.lang][c]) + '</a>' for c in CATS if nav.get(c))
    return (
      '<section class="section bg-white"><div class="container">'
      '<div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10 reveal">'
      '<div><span class="kicker mb-3">' + esc(t["services_kicker"]) + '</span><h2 class="section-title mt-3">' + esc(t["services_title"]) + '</h2></div>'
      '<div class="flex flex-wrap gap-2">' + cat_pills + '</div></div>'
      '<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 reveal">' + "".join(cards) + '</div>'
      '<div class="text-center mt-10"><a href="' + ctx.link("uzmanliklar/index.html") + '" class="btn-primary">' + esc(t["services_all"]) + IC["arrow"] + '</a></div>'
      '</div></section>')

def sec_steps(ctx):
    t = ctx.t
    steps = [("01", t["step1_t"], t["step1_d"]), ("02", t["step2_t"], t["step2_d"]), ("03", t["step3_t"], t["step3_d"])]
    # embrace "What To Expect": numaralı satırlar (teal daire-ok) solda + görsel sağda
    rows = "".join(
      '<div class="reveal flex items-start gap-5 py-5 border-b border-line last:border-0">'
      '<span class="font-display italic text-brand-600 text-2xl font-bold w-10 shrink-0">' + n + '</span>'
      '<div class="flex-1"><h3 class="font-display text-[1.35rem] font-bold text-ink-900">' + esc(tt) + '</h3>'
      '<p class="text-ink-500 mt-1.5 text-[0.95rem] leading-relaxed">' + esc(dd) + '</p></div>'
      '<span class="circle-arrow mt-1">' + IC["arrow"] + '</span></div>'
      for n, tt, dd in steps)
    img = ('<div class="aspect-[4/5] rounded-[2rem] overflow-hidden ring-1 ring-line shadow-card">'
           '<img src="' + ctx.media(SURGERY_IMG) + '" alt="' + esc(t["steps_title"]) + '" width="560" height="700" loading="lazy" class="w-full h-full object-cover"></div>') if SURGERY_IMG else ''
    return (
      '<section class="section bg-cream-50 overflow-hidden"><div class="container grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">'
      '<div class="reveal order-2 lg:order-1">'
      '<span class="kicker mb-4">' + esc(t["steps_kicker"]) + '</span>'
      '<h2 class="section-title mt-3 mb-6">' + esc(t["steps_title"]) + '</h2>'
      '<div>' + rows + '</div></div>'
      '<div class="reveal order-1 lg:order-2">' + img + '</div>'
      '</div></section>')

def sec_doctor(ctx):
    t = ctx.t
    # embrace "Meet Our...": açık editorial, büyük portre + isim + coral buton
    img = ('<div class="aspect-[4/5] rounded-[2rem] overflow-hidden ring-1 ring-line shadow-cardHover">'
           '<img src="' + ctx.media(DOCTOR_IMG) + '" alt="Op. Dr. Alper Burak Uslu" width="520" height="650" loading="lazy" class="w-full h-full object-cover"></div>') if DOCTOR_IMG else ''
    stats = "".join('<div><div class="font-display font-bold text-brand-600 text-3xl">' + n + s + '</div><div class="text-ink-500 text-sm mt-1">' + esc(t[k]) + '</div></div>' for n, s, k in COUNTERS[:3])
    return (
      '<section class="section bg-white overflow-hidden"><div class="container grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">'
      '<div class="reveal max-w-md">' + img + '</div>'
      '<div class="reveal">'
      '<span class="kicker mb-4">' + esc(t["doctor_kicker"]) + '</span>'
      '<h2 class="section-title mt-3">Op. Dr. Alper<br><span class="italic">Burak Uslu</span></h2>'
      '<p class="text-ink-500 text-lg mt-3">' + esc(t["hero_role"]) + ' · M.D, FEBOPRAS</p>'
      '<div class="grid grid-cols-3 gap-6 mt-8 py-6 border-y border-line">' + stats + '</div>'
      '<a href="' + ctx.link(page_url(ctx.lang,"about")) + '" class="btn-primary mt-8">' + esc(t["doctor_cta"]) + '</a>'
      '</div></div></section>')

def sec_apart(ctx):
    t = ctx.t
    # embrace "What Sets Us Apart": sol metin+buton, sağ rozet ızgarası
    badges = "".join(
      '<div class="reveal card p-5 text-center">'
      '<div class="w-12 h-12 mx-auto grid place-content-center rounded-full bg-brand-50 text-brand-600 mb-3">' + IC["badge"] + '</div>'
      '<div class="font-display font-bold text-ink-900 text-lg">' + esc(code) + '</div>'
      '<div class="text-[0.7rem] text-ink-500 mt-1.5 leading-snug">' + esc(name) + '</div></div>'
      for code, name in SITE["certs"])
    return (
      '<section class="section bg-cream-50 overflow-hidden"><div class="container grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">'
      '<div class="reveal">'
      '<span class="kicker mb-4">' + esc(t["apart_kicker"]) + '</span>'
      '<h2 class="section-title mt-3">' + esc(t["apart_title"]) + '</h2>'
      '<p class="text-ink-500 mt-4 text-lg leading-relaxed">' + esc(t["apart_desc"]) + '</p>'
      '<a href="' + wa_link() + '" target="_blank" rel="noopener" class="btn-primary mt-7">' + esc(t["cta_appt"]) + '</a>'
      '</div>'
      '<div class="grid grid-cols-2 sm:grid-cols-3 gap-4 reveal">' + badges + '</div>'
      '</div></section>')

def sec_gallery(ctx, images, title=None):
    if not images: return ''
    t = ctx.t
    slides = "".join(
      '<div class="swiper-slide"><div class="aspect-[3/4] rounded-xl2 overflow-hidden ring-1 ring-line shadow-soft bg-white p-2">'
      '<img src="' + ctx.media(im["local"]) + '" alt="' + esc(im.get("alt") or t["gallery_title"]) + '" width="480" height="600" loading="lazy" class="w-full h-full object-contain"></div></div>'
      for im in images if im.get("local"))
    return (
      '<section class="section bg-cream-50 overflow-hidden"><div class="container">'
      '<div class="flex items-end justify-between gap-4 mb-10 reveal"><div>'
      '<span class="kicker mb-3">' + esc(t["gallery_kicker"]) + '</span><h2 class="section-title mt-3">' + esc(title or t["gallery_title"]) + '</h2></div>'
      '<div class="hidden sm:flex gap-2"><button aria-label="Önceki" class="gal-prev w-11 h-11 rounded-full ring-1 ring-line grid place-content-center hover:bg-white"><svg viewBox="0 0 24 24" class="w-5 h-5 rotate-180" fill="none"><path d="M5 12h14m0 0-5-5m5 5-5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></button>'
      '<button aria-label="Sonraki" class="gal-next w-11 h-11 rounded-full ring-1 ring-line grid place-content-center hover:bg-white">' + IC["arrow"] + '</button></div></div>'
      '<div class="swiper reveal" data-swiper="gallery"><div class="swiper-wrapper">' + slides + '</div></div>'
      '</div></section>')

def sec_reviews(ctx, reviews):
    if not reviews: return ''
    t = ctx.t
    slides = ""
    for r in reviews:
        stars = IC["star"] * 5
        slides += ('<div class="swiper-slide h-auto"><div class="card p-6 h-full flex flex-col">'
          '<div class="flex items-center gap-2 mb-3"><div class="flex text-gold-500">' + stars + '</div>' + IC["google"] + '</div>'
          '<p class="text-ink-700 flex-1">' + esc(r["text"]) + '</p>'
          '<div class="font-semibold text-ink-900 mt-4">' + esc(r["name"]) + '</div></div></div>')
    return (
      '<section class="section bg-white overflow-hidden"><div class="container">'
      '<div class="text-center max-w-2xl mx-auto mb-10 reveal"><span class="kicker justify-center mb-3">' + esc(t["reviews_kicker"]) + '</span><h2 class="section-title mt-3">' + esc(t["reviews_title"]) + '</h2></div>'
      '<div class="swiper reveal" data-swiper="testimonials"><div class="swiper-wrapper">' + slides + '</div><div class="swiper-pagination mt-6"></div></div>'
      '</div></section>')

def sec_values(ctx):
    icons = [IC["badge"], IC["star"], IC["map"], IC["phone"]]
    cards = ""
    for i, (title, desc) in enumerate(VALUES[ctx.lang]):
        cards += ('<div class="reveal card p-6 card-hover">'
                  '<div class="w-12 h-12 rounded-xl2 bg-brand-50 text-brand-600 grid place-content-center mb-4">'
                  + ['<svg viewBox="0 0 24 24" fill="none" class="w-6 h-6"><path d="M12 3l2.5 5 5.5.8-4 3.9.9 5.5L12 21l-4.9 2.6.9-5.5-4-3.9 5.5-.8L12 3Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>',
                     '<svg viewBox="0 0 24 24" fill="none" class="w-6 h-6"><path d="M4 12a8 8 0 018-8m8 8a8 8 0 01-8 8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.6"/></svg>',
                     '<svg viewBox="0 0 24 24" fill="none" class="w-6 h-6"><path d="M12 3v18M5 8l7-5 7 5M5 8v10l7 3 7-3V8" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>',
                     '<svg viewBox="0 0 24 24" fill="none" class="w-6 h-6"><path d="M12 3l7 3v5c0 4.5-3 8-7 10-4-2-7-5.5-7-10V6l7-3Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="m9 12 2 2 4-4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>'][i]
                  + '</div>'
                  '<h3 class="font-display text-h3 font-semibold text-ink-900">' + esc(title) + '</h3>'
                  '<p class="text-ink-500 mt-2 text-[0.95rem]">' + esc(desc) + '</p></div>')
    return ('<section class="section bg-white"><div class="container">'
            '<div class="text-center max-w-2xl mx-auto mb-12 reveal">'
            '<span class="kicker justify-center mb-3">' + esc(VALUE_KICKER[ctx.lang]) + '</span>'
            '<h2 class="section-title mt-3">' + esc(VALUE_TITLE[ctx.lang]) + '</h2></div>'
            '<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">' + cards + '</div>'
            '</div></section>')

def sec_cta(ctx):
    t = ctx.t
    return (
      '<section class="py-16 md:py-20 bg-brand-600 text-white"><div class="container flex flex-col lg:flex-row items-center justify-between gap-8 text-center lg:text-left">'
      '<div class="reveal"><h2 class="font-display text-h2 font-bold">' + esc(t["cta_title"]) + '</h2>'
      '<p class="text-white/80 mt-3 max-w-xl">' + esc(t["cta_desc"]) + '</p></div>'
      '<div class="flex flex-wrap gap-3 justify-center reveal">'
      '<a href="tel:' + SITE["phone_tel"] + '" class="btn-solid bg-white text-brand-700 hover:bg-cream-50">' + IC["phone"] + esc(t["cta_call"]) + '</a>'
      '<a href="' + wa_link() + '" target="_blank" rel="noopener" class="btn-solid bg-[#0E7A3A] text-white hover:opacity-90">' + IC["wa"] + esc(t["cta_wa"]) + '</a>'
      '</div></div></section>')

# ---------------------------------------------------------------- global indeksler
PROC_BY_SLUG = {}   # (lang, slug) -> proc
NAV = {}            # lang -> {cat: [(slug,title)]}

def _is_heading(b):
    return len(b) < 70 and " " in b and (b.endswith("?") or b.isupper() or b.istitle())

def meta_desc(item):
    d = (item.get("seo") or {}).get("meta_description", "")
    if d: return d[:300]
    # ilk kısa başlık satırını atla (kart özetinde "Başlık Başlık..." kekemeliğini önle)
    blocks = [b.strip() for b in item.get("text", "").split("\n") if b.strip()]
    body = [b for b in blocks if not _is_heading(b)]
    txt = re.sub(r"\s+", " ", " ".join(body) if body else " ".join(blocks)).strip()
    return (txt[:155].rsplit(" ", 1)[0] + "…") if len(txt) > 156 else txt

def breadcrumb(ctx, trail):
    parts = []
    for i, (label, url) in enumerate(trail):
        if url:
            parts.append('<a href="' + url + '" class="hover:text-brand-700">' + esc(label) + '</a>')
        else:
            parts.append('<span class="text-ink-900">' + esc(label) + '</span>')
    sep = '<span class="mx-2 text-ink-500/50">/</span>'
    return '<nav class="text-sm text-ink-500 flex flex-wrap items-center" aria-label="breadcrumb">' + sep.join(parts) + '</nav>'

def content_to_html(text, drop_questions=False):
    """Envanterdeki düz metni prose HTML'e çevir.
    drop_questions=True ise 'Soru?' başlıklı bölümler (başlık + cevabı) atlanır
    (SSS akordeonuyla tekrarı önlemek için)."""
    out = []
    blocks = [b.strip() for b in text.split("\n") if b.strip()]
    skipping = False
    for b in blocks:
        is_h = _is_heading(b)
        if is_h:
            if drop_questions and b.endswith("?"):
                skipping = True
                continue
            skipping = False
            out.append("<h2>" + esc(b) + "</h2>")
            continue
        if skipping:
            continue  # soru cevabını atla
        if b.startswith("•"):
            out.append("<li>" + esc(b.lstrip("• ").strip()) + "</li>")
        else:
            out.append("<p>" + esc(b) + "</p>")
    # ardışık li'leri ul'e sar
    html_str = ""
    in_ul = False
    for chunk in out:
        if chunk.startswith("<li>"):
            if not in_ul: html_str += "<ul>"; in_ul = True
            html_str += chunk
        else:
            if in_ul: html_str += "</ul>"; in_ul = False
            html_str += chunk
    if in_ul: html_str += "</ul>"
    return html_str

def jsonld_physician(ctx):
    node = {
        "@context": "https://schema.org",
        "@type": ["Physician", "MedicalClinic"],
        "name": "Op. Dr. Alper Burak Uslu",
        "medicalSpecialty": "PlasticSurgery",
        "telephone": SITE["phone_display"],
        "url": BASE_URL + "/" + ctx.lang + "/",
        "priceRange": "$$$",
        "areaServed": {"@type": "City", "name": "İstanbul"},
        "address": {"@type": "PostalAddress", "streetAddress": "Fenerbahçe Mah. Bağdat Cad. 134/11",
                    "addressLocality": "Kadıköy", "addressRegion": "İstanbul", "postalCode": "34726", "addressCountry": "TR"},
        "geo": {"@type": "GeoCoordinates", "latitude": 40.9785, "longitude": 29.0455},
        "openingHoursSpecification": [{
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
            "opens": "09:00", "closes": "19:00"}],
        "sameAs": list(SITE["social"].values()),
    }
    if DOCTOR_IMG:
        node["image"] = BASE_URL + "/assets/media/" + DOCTOR_IMG
    return node

def jsonld_procedure(title, desc):
    return {"@context": "https://schema.org", "@type": "MedicalProcedure", "name": title,
            "description": desc, "howPerformed": "Surgical",
            "provider": {"@type": "Physician", "name": "Op. Dr. Alper Burak Uslu"}}

def jsonld_faq(faqs):
    return {"@context": "https://schema.org", "@type": "FAQPage",
            "mainEntity": [{"@type": "Question", "name": q, "acceptedAnswer": {"@type": "Answer", "text": a}} for q, a in faqs]}

def jsonld_breadcrumb(trail_abs):
    return {"@context": "https://schema.org", "@type": "BreadcrumbList",
            "itemListElement": [{"@type": "ListItem", "position": i + 1, "name": n} for i, n in enumerate(trail_abs)]}

# ---------------------------------------------------------------- sayfa: ana
def build_home(ctx, data, langswitch):
    nav = NAV[ctx.lang]
    about = data["about"]
    intro = ""
    if about:
        ps = [p for p in about["text"].split("\n") if len(p.strip()) > 60]
        intro = ps[0].strip()[:170] if ps else ""
    gal = data["achievements"]["images"] if data["achievements"] else []
    body = (sec_hero(ctx, ctx.t["hero_sub"])
            + sec_counters(ctx)
            + (sec_about_split(ctx, about) if about else "")
            + sec_services(ctx, nav)
            + sec_steps(ctx)
            + sec_doctor(ctx)
            + sec_apart(ctx)
            + sec_values(ctx)
            + sec_reviews(ctx, data.get("reviews", []))
            + sec_gallery(ctx, gal[:9])
            + sec_cta(ctx))
    title = "Op. Dr. Alper Burak Uslu · " + ctx.t["hero_role"]
    desc = ctx.t["hero_sub"]
    jl = [jsonld_physician(ctx)]
    return page_shell(ctx, title, desc, "index.html", langswitch["home"], jl, body, nav, {lg: langswitch["home"].get(lg) for lg in LANGS}, preload_img=DOCTOR_IMG)

# ---------------------------------------------------------------- sayfa: işlem
def build_procedure(ctx, proc, cat, data, langswitch):
    nav = NAV[ctx.lang]
    t = ctx.t
    home_u = ctx.link("index.html")
    cat_u = ctx.link("kategori/" + cat + ".html")
    trail = [(t["nav_home"], home_u), (CAT_LABEL[ctx.lang][cat], cat_u), (proc["title"], None)]
    img = proc_image(proc, cat)
    hero_img = ('<img src="' + ctx.media(img) + '" alt="' + esc(proc["title"]) + '" width="640" height="480" class="rounded-xl2 object-cover w-full shadow-card ring-1 ring-line" loading="eager">') if img else ''
    # Makale: SSS "?" bölümlerini akordeona taşı, geri kalanı prose olarak göster (tekrar yok).
    faqs = proc.get("faq", [])
    faq_qs = {f["q"] for f in faqs}
    # İlk 3 "?" bölümü (Nedir/Neden/Kimlere) makalede kalsın; gerisi akordeona.
    keep_in_prose = set(list(faq_qs)[:0])  # tüm ? bölümleri akordeona
    prose = content_to_html(proc["text"], drop_questions=True)
    faq_html = ""
    if faqs:
        items = ""
        for i, f in enumerate(faqs):
            items += ('<div class="border-b border-line last:border-0" x-data="{ o:' + ('true' if i == 0 else 'false') + ' }">'
              '<button @click="o=!o" class="w-full flex items-center justify-between gap-4 py-4 text-left font-medium text-ink-900 hover:text-brand-700" :aria-expanded="o">'
              '<span>' + esc(f["q"]) + '</span><span class="text-brand-600 transition-transform shrink-0" :class="o && \'rotate-180\'">' + IC["chevron"] + '</span></button>'
              '<div x-show="o" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"><div class="pb-5 text-ink-700 prose-clinic max-w-none">' + content_to_html(f["a"]) + '</div></div></div>')
        faq_html = ('<div class="mt-12"><h2 class="font-display text-h2 font-bold text-ink-900 mb-4">' + esc(t["faq_title"]) + '</h2>'
                    '<div class="card p-4 sm:p-6">' + items + '</div></div>')
    # ilgili işlemler (aynı kategori)
    rel = [(s, ti) for s, ti in nav.get(cat, []) if s != proc["slug"]][:3]
    rel_cards = "".join(_card(ctx, s, ti, cat) for s, ti in rel)
    related = ('<section class="section bg-cream-50"><div class="container">'
               '<h2 class="section-title mb-8 reveal">' + esc(t["related_title"]) + '</h2>'
               '<div class="grid sm:grid-cols-3 gap-6 reveal">' + rel_cards + '</div></div></section>') if rel_cards else ''
    body = (
      '<section class="mesh-teal"><div class="container py-10 md:py-14">'
      + breadcrumb(ctx, trail) +
      '<div class="grid lg:grid-cols-2 gap-10 items-center mt-6">'
      '<div class="reveal"><span class="kicker mb-3">' + esc(CAT_LABEL[ctx.lang][cat]) + '</span>'
      '<h1 class="text-hero !text-[clamp(2rem,4vw,3.2rem)] font-bold text-ink-900 mt-2">' + esc(proc["title"]) + '</h1>'
      '<p class="text-lead text-ink-700 mt-4">' + esc(meta_desc(proc)) + '</p>'
      '<div class="flex flex-wrap gap-3 mt-6"><a href="' + wa_link() + '" target="_blank" rel="noopener" class="btn-primary">' + esc(t["cta_appt"]) + '</a>'
      '<a href="tel:' + SITE["phone_tel"] + '" class="btn-outline">' + IC["phone"] + esc(t["cta_call"]) + '</a></div></div>'
      '<div class="reveal">' + hero_img + '</div></div></div></section>'
      '<section class="section bg-white"><div class="container grid lg:grid-cols-3 gap-12">'
      '<article class="lg:col-span-2 prose-clinic reveal">' + prose + faq_html + '</article>'
      '<aside class="lg:col-span-1"><div class="sticky top-28 card p-6 reveal">'
      '<h3 class="font-display text-h3 font-semibold text-ink-900 mb-4">' + esc(t["cta_appt"]) + '</h3>'
      '<p class="text-sm text-ink-500 mb-4">' + esc(t["cta_desc"]) + '</p>'
      '<a href="' + wa_link() + '" target="_blank" rel="noopener" class="btn-primary w-full mb-2">' + IC["wa"] + esc(t["cta_wa"]) + '</a>'
      '<a href="tel:' + SITE["phone_tel"] + '" class="btn-outline w-full">' + IC["phone"] + esc(SITE["phone_display"]) + '</a>'
      '<div class="mt-5 pt-5 border-t border-line text-sm text-ink-500 flex gap-2">' + IC["map"] + esc(SITE["address"]) + '</div>'
      '</div></aside></div></section>'
      + related)
    title = (proc.get("seo") or {}).get("meta_title") or (proc["title"] + " · Op. Dr. Alper Burak Uslu")
    desc = meta_desc(proc)
    jl = [jsonld_procedure(proc["title"], desc), jsonld_breadcrumb([t["nav_home"], CAT_LABEL[ctx.lang][cat], proc["title"]])]
    if faqs: jl.append(jsonld_faq([(f["q"], f["a"]) for f in faqs]))
    ls = {lg: (langswitch["home"].get(lg)) for lg in LANGS}  # işlemde dil→ana sayfa
    return page_shell(ctx, title, desc, "uzmanliklar/" + proc["slug"] + ".html", {}, jl, body, nav, ls, preload_img=img)

# ---------------------------------------------------------------- sayfa: kategori
def build_category(ctx, cat, data, langswitch):
    nav = NAV[ctx.lang]
    t = ctx.t
    procs = nav.get(cat, [])
    cards = "".join(_card(ctx, s, ti, cat) for s, ti in procs)
    trail = [(t["nav_home"], ctx.link("index.html")), (t["nav_procedures"], ctx.link("uzmanliklar/index.html")), (CAT_LABEL[ctx.lang][cat], None)]
    body = (
      '<section class="mesh-teal"><div class="container py-12 md:py-16">'
      + breadcrumb(ctx, trail) +
      '<h1 class="text-hero !text-[clamp(2rem,4vw,3.2rem)] font-bold text-ink-900 mt-5">' + esc(CAT_LABEL[ctx.lang][cat]) + '</h1>'
      '<p class="text-ink-500 mt-3">' + str(len(procs)) + ' ' + esc(t["in_category"]) + '</p></div></section>'
      '<section class="section bg-white"><div class="container grid sm:grid-cols-2 lg:grid-cols-3 gap-6 reveal">' + cards + '</div></section>')
    title = CAT_LABEL[ctx.lang][cat] + " · Op. Dr. Alper Burak Uslu"
    ls = {lg: langswitch["procedures"].get(lg) for lg in LANGS}
    return page_shell(ctx, title, t["services_title"], "kategori/" + cat + ".html", {}, [], body, nav, ls)

def build_procedures_index(ctx, data, langswitch):
    nav = NAV[ctx.lang]
    t = ctx.t
    blocks = ""
    for cat in CATS:
        procs = nav.get(cat, [])
        if not procs: continue
        cards = "".join(_card(ctx, s, ti, cat) for s, ti in procs)
        blocks += ('<div class="mb-14"><div class="flex items-center gap-4 mb-6 reveal">'
          '<h2 class="section-title">' + esc(CAT_LABEL[ctx.lang][cat]) + '</h2>'
          '<span class="text-ink-500">' + str(len(procs)) + ' ' + esc(t["in_category"]) + '</span></div>'
          '<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 reveal">' + cards + '</div></div>')
    trail = [(t["nav_home"], ctx.link("index.html")), (t["nav_procedures"], None)]
    body = ('<section class="mesh-teal"><div class="container py-12 md:py-16">' + breadcrumb(ctx, trail)
            + '<h1 class="text-hero !text-[clamp(2rem,4vw,3.2rem)] font-bold text-ink-900 mt-5">' + esc(t["all_procedures"]) + '</h1></div></section>'
            + '<section class="section bg-white"><div class="container">' + blocks + '</div></section>')
    ls = {lg: langswitch["procedures"].get(lg) for lg in LANGS}
    return page_shell(ctx, t["all_procedures"] + " · Op. Dr. Alper Burak Uslu", t["services_title"], "uzmanliklar/index.html", langswitch["procedures"], [], body, nav, ls)

# ---------------------------------------------------------------- sayfa: basit (hakkında/yasal)
def build_simple(ctx, item, ptype, langswitch, extra_after=""):
    nav = NAV[ctx.lang]
    t = ctx.t
    title_map = {"about": t["nav_about"], "legal": (item["title"] or "Yasal Uyarı")}
    heading = item["title"] or title_map.get(ptype, "")
    body_text = item.get("text", "")
    about_intro_html, about_rest_html = "", ""
    if ptype == "about" and body_text:
        # Makale başındaki isim/ünvan tekrarını at (hero zaten gösteriyor)
        blocks = [b.strip() for b in body_text.split("\n") if b.strip()]
        # yalnızca ilk KISA başlık/ünvan satırlarını at (uzun bio paragrafları korunur)
        while blocks and len(blocks[0]) < 60 and (
                "alper burak uslu" in norm(blocks[0]) or
                any(k in norm(blocks[0]) for k in ["cerrahi uzman", "febopras"])):
            blocks.pop(0)
        body_text = "\n".join(blocks)
        # Narratif giriş + açılır uzun CV (ilk KISA BÜYÜK-HARF başlıkta kes: DENEYİM/EĞİTİM...)
        cut = None
        for i, b in enumerate(blocks):
            if i > 0 and b and len(b) < 40 and b == b.upper():
                cut = i; break
        if cut is None or cut > 6:
            cut = min(6, len(blocks))
        about_intro_html = content_to_html("\n".join(blocks[:cut]))
        about_rest_html = content_to_html("\n".join(blocks[cut:])) if blocks[cut:] else ""
    prose = content_to_html(body_text) if body_text else '<p class="text-ink-500">İçerik yakında.</p>'
    img = item["images"][0]["local"] if (ptype == "about" and item.get("images")) else None
    hero_img = ('<img src="' + ctx.media(img) + '" alt="' + esc(heading) + '" width="560" height="680" class="rounded-xl2 object-cover w-full shadow-card ring-1 ring-line" loading="lazy">') if img else ''
    trail = [(t["nav_home"], ctx.link("index.html")), (heading, None)]
    if ptype == "about":
        stats = [("12", "", t["c_years"]), ("2000", "+", t["c_aesthetic"]), ("4000", "+", t["c_surgery"])]
        stats_html = ('<div class="grid grid-cols-3 gap-4 mt-8 max-w-md">' + "".join(
            '<div class="rounded-2xl bg-white/70 ring-1 ring-line p-4 text-center"><div class="font-display font-bold text-brand-600 text-2xl sm:text-3xl">' + esc(a + b) + '</div><div class="text-ink-500 text-xs mt-1">' + esc(c) + '</div></div>'
            for a, b, c in stats) + '</div>')
        cv_lbl = {"tr": ("Detaylı Özgeçmiş & Bilimsel Faaliyetler", "Özgeçmişi Gizle"),
                  "en": ("Full CV & Scientific Activities", "Hide CV"),
                  "de": ("Ausführlicher Lebenslauf", "Lebenslauf ausblenden")}[ctx.lang]
        collapse = ""
        if about_rest_html:
            collapse = ('<div class="mt-8" x-data="{ open:false }">'
                        '<button @click="open=!open" class="inline-flex items-center gap-2 rounded-full ring-1 ring-line px-5 py-2.5 text-sm font-semibold text-ink-900 hover:bg-cream-50 transition" :aria-expanded="open">'
                        '<span x-show="!open">' + esc(cv_lbl[0]) + '</span><span x-show="open" x-cloak>' + esc(cv_lbl[1]) + '</span>'
                        '<span class="transition-transform text-brand-600" :class="open && \'rotate-180\'">' + IC["chevron"] + '</span></button>'
                        '<div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="prose-clinic max-w-none mt-6 pt-6 border-t border-line">' + about_rest_html + '</div></div>')
        article = ('<article class="lg:col-span-2 reveal"><div class="prose-clinic max-w-none">' + (about_intro_html or prose) + '</div>' + collapse + '</article>')
        body = ('<section class="mesh-teal overflow-hidden"><div class="container py-12 md:py-16">' + breadcrumb(ctx, trail)
                + '<div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center mt-6">'
                '<div class="reveal"><span class="kicker mb-3">' + esc(t["about_kicker"]) + '</span>'
                '<h1 class="font-display font-black text-ink-900 text-[clamp(2.2rem,5vw,3.8rem)] leading-[1.02] tracking-[-0.02em] mt-2">Op. Dr. Alper<br><span class="italic font-bold">Burak Uslu</span></h1>'
                '<p class="text-lead text-ink-700 mt-4">' + esc(t["hero_role"]) + ' · M.D, FEBOPRAS</p>' + stats_html + '</div>'
                '<div class="reveal">' + hero_img + '</div></div></div></section>'
                '<section class="section bg-white"><div class="container grid lg:grid-cols-3 gap-12 lg:gap-16">'
                + article +
                '<aside class="lg:col-span-1"><div class="sticky top-28 space-y-4 reveal">'
                + sec_apart_side(ctx) + '</div></aside></div></section>'
                + extra_after)
    else:
        body = ('<section class="mesh-teal"><div class="container py-12 md:py-16">' + breadcrumb(ctx, trail)
                + '<h1 class="text-hero !text-[clamp(2rem,4vw,3.2rem)] font-bold text-ink-900 mt-5">' + esc(heading) + '</h1></div></section>'
                '<section class="section bg-white"><div class="container"><div class="prose-clinic mx-auto reveal">' + prose + '</div></div></section>')
    ls = {lg: langswitch[ptype].get(lg) for lg in LANGS}
    return page_shell(ctx, heading + " · Op. Dr. Alper Burak Uslu", meta_desc(item), page_url(ctx.lang, ptype), langswitch[ptype], [jsonld_physician(ctx)] if ptype == "about" else [], body, nav, ls, preload_img=img)

def sec_apart_side(ctx):
    certs = "".join('<div class="flex items-center gap-3 py-2"><div class="text-brand-600">' + IC["badge"] + '</div><div><div class="font-semibold text-ink-900 text-sm">' + esc(c) + '</div><div class="text-xs text-ink-500">' + esc(n) + '</div></div></div>' for c, n in SITE["certs"])
    return '<div class="card p-6"><h3 class="font-display text-h3 font-semibold mb-3">' + esc(ctx.t["doctor_cta"]) + '</h3>' + certs + '</div>'

# ---------------------------------------------------------------- sayfa: iletişim
def build_contact(ctx, item, langswitch):
    nav = NAV[ctx.lang]
    t = ctx.t
    trail = [(t["nav_home"], ctx.link("index.html")), (t["nav_contact"], None)]
    form = (
      '<form class="grid gap-4" onsubmit="return dauWaPv(event)">'
      '<div class="grid sm:grid-cols-2 gap-4">'
      '<label class="block"><span class="text-sm font-medium text-ink-700">' + esc(t["form_name"]) + '</span>'
      '<input name="ad" required class="mt-1 w-full rounded-xl ring-1 ring-line px-4 py-3 focus:ring-brand-500 outline-none" autocomplete="name"></label>'
      '<label class="block"><span class="text-sm font-medium text-ink-700">' + esc(t["form_phone"]) + '</span>'
      '<input name="tel" required type="tel" class="mt-1 w-full rounded-xl ring-1 ring-line px-4 py-3 focus:ring-brand-500 outline-none" autocomplete="tel"></label></div>'
      '<label class="block"><span class="text-sm font-medium text-ink-700">' + esc(t["form_msg"]) + '</span>'
      '<textarea name="mesaj" rows="4" class="mt-1 w-full rounded-xl ring-1 ring-line px-4 py-3 focus:ring-brand-500 outline-none"></textarea></label>'
      '<button class="btn-primary justify-center">' + IC["wa"] + '<span>' + esc(t["form_send"]) + '</span></button>'
      '</form>'
      '<script>function dauWaPv(e){e.preventDefault();var f=e.target;'
      'var t="Merhaba, randevu talebim var.\\n\\nAd Soyad: "+(f.ad.value||"")+"\\nTelefon: "+(f.tel.value||"");'
      'if(f.mesaj.value)t+="\\nMesaj: "+f.mesaj.value;'
      'window.open("https://wa.me/' + SITE["whatsapp"] + '?text="+encodeURIComponent(t),"_blank","noopener");return false;}</script>')
    info = (
      '<div class="space-y-5">'
      '<a href="tel:' + SITE["phone_tel"] + '" class="card p-5 flex items-center gap-4 card-hover"><div class="w-12 h-12 rounded-full bg-brand-50 text-brand-600 grid place-content-center">' + IC["phone"] + '</div><div><div class="text-xs text-ink-500 uppercase tracking-wide">' + esc(t["cta_call"]) + '</div><div class="font-semibold text-ink-900">' + esc(SITE["phone_display"]) + '</div></div></a>'
      '<a href="' + SITE["maps"] + '" target="_blank" rel="noopener" class="card p-5 flex items-center gap-4 card-hover"><div class="w-12 h-12 rounded-full bg-brand-50 text-brand-600 grid place-content-center">' + IC["map"] + '</div><div><div class="text-xs text-ink-500 uppercase tracking-wide">' + esc(t["footer_addr"]) + '</div><div class="font-semibold text-ink-900">' + esc(SITE["address"]) + '</div></div></a>'
      '<div class="card p-5 flex items-center gap-4"><div class="w-12 h-12 rounded-full bg-brand-50 text-brand-600 grid place-content-center">' + IC["clock"] + '</div><div><div class="text-xs text-ink-500 uppercase tracking-wide">' + esc(t["hours_title"]) + '</div><div class="font-semibold text-ink-900">' + esc(t["hours"]) + '</div></div></div>'
      '</div>')
    body = ('<section class="mesh-teal"><div class="container py-12 md:py-16">' + breadcrumb(ctx, trail)
            + '<h1 class="text-hero !text-[clamp(2rem,4vw,3.2rem)] font-bold text-ink-900 mt-5">' + esc(t["nav_contact"]) + '</h1></div></section>'
            '<section class="section bg-white"><div class="container grid lg:grid-cols-2 gap-12">'
            '<div class="reveal">' + info + '</div>'
            '<div class="reveal"><div class="card p-6 sm:p-8">' + form + '</div></div>'
            '</div></section>'
            '<section class="pb-16 md:pb-24 bg-white"><div class="container">'
            '<a href="' + SITE["maps"] + '" target="_blank" rel="noopener" '
            'class="reveal group block relative rounded-xl2 overflow-hidden ring-1 ring-line shadow-card h-[360px] mesh-teal" '
            'aria-label="' + esc(t.get("directions", "Yol Tarifi")) + ' — ' + esc(SITE["address"]) + '">'
            # stilize harita zemini (ızgara + yollar)
            '<svg class="absolute inset-0 w-full h-full opacity-[0.5]" preserveAspectRatio="xMidYMid slice" viewBox="0 0 400 300" aria-hidden="true">'
            '<defs><pattern id="g" width="28" height="28" patternUnits="userSpaceOnUse"><path d="M28 0H0V28" fill="none" stroke="#12857D" stroke-width="0.5" stroke-opacity="0.18"/></pattern></defs>'
            '<rect width="400" height="300" fill="url(#g)"/>'
            '<path d="M-20 210 Q140 170 210 200 T430 150" fill="none" stroke="#12857D" stroke-width="7" stroke-opacity="0.14"/>'
            '<path d="M60 -20 Q90 120 180 180 T300 320" fill="none" stroke="#12857D" stroke-width="5" stroke-opacity="0.12"/>'
            '<path d="M-20 90 Q160 70 260 110 T430 90" fill="none" stroke="#12857D" stroke-width="4" stroke-opacity="0.1"/></svg>'
            # pin
            '<div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-full flex flex-col items-center">'
            '<span class="w-14 h-14 rounded-full bg-brand-600 text-white grid place-content-center shadow-cardHover ring-4 ring-white transition group-hover:scale-110">' + IC["map"] + '</span>'
            '<span class="w-3 h-3 rounded-full bg-brand-700/30 mt-1"></span></div>'
            # adres kartı + buton
            '<div class="absolute inset-x-4 bottom-4 sm:inset-x-6 sm:bottom-6 bg-white/95 backdrop-blur rounded-2xl shadow-card p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center gap-3 justify-between">'
            '<div class="flex items-start gap-3 text-ink-700"><span class="text-brand-600 mt-0.5">' + IC["map"] + '</span><span class="text-sm font-medium">' + esc(SITE["address"]) + '</span></div>'
            '<span class="btn-primary !py-2.5 shrink-0">' + esc(t.get("directions", "Yol Tarifi")) + IC["arrow"] + '</span>'
            '</div></a></div></section>')
    ls = {lg: langswitch["contact"].get(lg) for lg in LANGS}
    jl = [jsonld_physician(ctx)]
    return page_shell(ctx, t["nav_contact"] + " · Op. Dr. Alper Burak Uslu", SITE["address"], page_url(ctx.lang, "contact"), langswitch["contact"], jl, body, nav, ls)

def build_achievements(ctx, item, langswitch):
    nav = NAV[ctx.lang]
    t = ctx.t
    imgs = item.get("images", []) if item else []
    grid = "".join('<a href="' + ctx.media(im["local"]) + '" target="_blank" rel="noopener" class="block aspect-[4/5] rounded-xl2 overflow-hidden ring-1 ring-line shadow-soft bg-white p-3 reveal"><img src="' + ctx.media(im["local"]) + '" alt="' + esc(im.get("alt") or t["gallery_title"]) + '" width="480" height="600" loading="lazy" class="w-full h-full object-contain"></a>' for im in imgs if im.get("local"))
    trail = [(t["nav_home"], ctx.link("index.html")), (t["nav_achievements"], None)]
    body = ('<section class="mesh-teal"><div class="container py-12 md:py-16">' + breadcrumb(ctx, trail)
            + '<h1 class="text-hero !text-[clamp(2rem,4vw,3.2rem)] font-bold text-ink-900 mt-5">' + esc(item["title"] if item else t["nav_achievements"]) + '</h1>'
            '<p class="text-ink-500 mt-3 max-w-2xl">' + esc(t["gallery_title"]) + '</p></div></section>'
            + ('<section class="section bg-white"><div class="container grid grid-cols-2 md:grid-cols-3 gap-5">' + grid + '</div></section>' if grid else
               '<section class="section bg-white"><div class="container text-center text-ink-500">İçerik yakında.</div></section>'))
    ls = {lg: langswitch["achievements"].get(lg) for lg in LANGS}
    return page_shell(ctx, (item["title"] if item else t["nav_achievements"]) + " · Op. Dr. Alper Burak Uslu", t["gallery_title"], page_url(ctx.lang, "achievements"), langswitch["achievements"], [], body, nav, ls)

def build_blog(ctx, langswitch):
    nav = NAV[ctx.lang]; t = ctx.t
    lbl = {"tr": ("Blog", "Estetik cerrahi, iyileşme süreçleri ve bakım üzerine bilgilendirici yazılar.", "İçerikler Yakında", "Estetik cerrahi, iyileşme süreçleri ve bakım üzerine bilgilendirici yazılar çok yakında burada olacak.", "Ana Sayfaya Dön"),
           "en": ("Blog", "Informative articles on aesthetic surgery, recovery and care.", "Content Coming Soon", "Informative articles on aesthetic surgery, recovery and care will be here very soon.", "Back to Home"),
           "de": ("Blog", "Informative Artikel über ästhetische Chirurgie, Heilung und Pflege.", "Inhalte in Kürze", "Informative Artikel über ästhetische Chirurgie, Heilung und Pflege sind bald hier verfügbar.", "Zur Startseite")}[ctx.lang]
    trail = [(t["nav_home"], ctx.link("index.html")), (lbl[0], None)]
    doc_icon = '<svg viewBox="0 0 24 24" fill="none" class="w-8 h-8" aria-hidden="true"><path d="M4 5a2 2 0 0 1 2-2h8l6 6v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M14 3v6h6M8 13h8M8 17h5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>'
    body = ('<section class="mesh-teal"><div class="container py-12 md:py-16">' + breadcrumb(ctx, trail)
            + '<h1 class="text-hero !text-[clamp(2rem,4vw,3.2rem)] font-bold text-ink-900 mt-4">' + esc(lbl[0]) + '</h1>'
            '<p class="text-lead text-ink-700 mt-3 max-w-xl">' + esc(lbl[1]) + '</p></div></section>'
            '<section class="section bg-white"><div class="container"><div class="max-w-lg mx-auto text-center py-12 reveal">'
            '<div class="w-16 h-16 mx-auto rounded-2xl bg-brand-50 text-brand-600 grid place-content-center mb-6">' + doc_icon + '</div>'
            '<h2 class="font-display text-h2 font-semibold text-ink-900">' + esc(lbl[2]) + '</h2>'
            '<p class="text-ink-500 mt-3 leading-relaxed">' + esc(lbl[3]) + '</p>'
            '<a href="' + ctx.link("index.html") + '" class="btn-primary mt-7 justify-center inline-flex">' + esc(lbl[4]) + IC["arrow"] + '</a>'
            '</div></div></section>')
    ls = {lg: langswitch["blog"].get(lg) for lg in LANGS}
    return page_shell(ctx, lbl[0] + " · Op. Dr. Alper Burak Uslu", lbl[1], page_url(ctx.lang, "blog"), langswitch["blog"], [], body, nav, ls)

# ---------------------------------------------------------------- montaj
def assemble(lang):
    L = INV["languages"][lang]
    data = {"home": None, "about": None, "contact": None, "legal": None, "achievements": None,
            "testimonials": None, "procedures": [], "reviews": []}
    for pg in L["pages"]:
        pt = classify_page(pg["slug"])
        if pt in data and pt != "procedures" and data[pt] is None:
            data[pt] = pg
    data["procedures"] = L["portfolio"]
    return data

def build_langswitch(ctx):
    d = {}
    for pt in ["home", "about", "contact", "legal", "achievements", "procedures", "blog"]:
        d[pt] = {}
        for lg in LANGS:
            d[pt][lg] = None if lg == ctx.lang else ctx.other(lg, page_url(lg, pt))
    return d

def write(path, content):
    os.makedirs(os.path.dirname(path), exist_ok=True)
    with open(path, "w", encoding="utf-8") as f:
        f.write(content)

def main():
    if os.path.isdir(PREVIEW):
        shutil.rmtree(PREVIEW)
    os.makedirs(PREVIEW)

    # 1) global NAV + PROC_BY_SLUG
    all_data = {}
    for lang in LANGS:
        data = assemble(lang)
        all_data[lang] = data
        nav = {c: [] for c in CATS}
        for proc in data["procedures"]:
            slug = proc["slug"]
            cat = TR_SLUG_CAT.get(slug) if lang == "tr" else None
            if not cat: cat = categorize(slug, proc["title"])
            PROC_BY_SLUG[(lang, slug)] = proc
            proc["_cat"] = cat
            nav[cat].append((slug, proc["title"]))
        # TR menü sırası, diğerleri başlığa göre
        if lang == "tr":
            order = {}
            for grp, slugs in MENU_TR.items():
                for i, sl in enumerate(slugs): order[sl] = i
            for c in CATS: nav[c].sort(key=lambda x: order.get(x[0], 99))
        else:
            for c in CATS: nav[c].sort(key=lambda x: x[1].lower())
        NAV[lang] = nav

    counts = {}
    # 2) sayfaları üret
    for lang in LANGS:
        data = all_data[lang]
        n = 0
        # home
        ctx = Ctx(lang, 0, "home"); ls = build_langswitch(ctx)
        write(os.path.join(PREVIEW, lang, "index.html"), build_home(ctx, data, ls)); n += 1
        # about
        if data["about"]:
            ctx = Ctx(lang, 0, "about"); ls = build_langswitch(ctx)
            write(os.path.join(PREVIEW, lang, page_url(lang, "about")), build_simple(ctx, data["about"], "about", ls)); n += 1
        # contact
        if data["contact"]:
            ctx = Ctx(lang, 0, "contact"); ls = build_langswitch(ctx)
            write(os.path.join(PREVIEW, lang, page_url(lang, "contact")), build_contact(ctx, data["contact"], ls)); n += 1
        # legal
        if data["legal"]:
            ctx = Ctx(lang, 0, "legal"); ls = build_langswitch(ctx)
            write(os.path.join(PREVIEW, lang, page_url(lang, "legal")), build_simple(ctx, data["legal"], "legal", ls)); n += 1
        # achievements
        if data["achievements"]:
            ctx = Ctx(lang, 0, "achievements"); ls = build_langswitch(ctx)
            write(os.path.join(PREVIEW, lang, page_url(lang, "achievements")), build_achievements(ctx, data["achievements"], ls)); n += 1
        # blog (şimdilik "içerikler yakında" — eski sitede blog yazısı yok)
        ctx = Ctx(lang, 0, "blog"); ls = build_langswitch(ctx)
        write(os.path.join(PREVIEW, lang, page_url(lang, "blog")), build_blog(ctx, ls)); n += 1
        # procedures index
        ctx = Ctx(lang, 1, "procedures"); ls = build_langswitch(ctx)
        write(os.path.join(PREVIEW, lang, "uzmanliklar", "index.html"), build_procedures_index(ctx, data, ls)); n += 1
        # categories
        for cat in CATS:
            if not NAV[lang].get(cat): continue
            ctx = Ctx(lang, 1, "category"); ls = build_langswitch(ctx)
            write(os.path.join(PREVIEW, lang, "kategori", cat + ".html"), build_category(ctx, cat, data, ls)); n += 1
        # procedures
        for proc in data["procedures"]:
            ctx = Ctx(lang, 1, "procedure"); ls = build_langswitch(ctx)
            write(os.path.join(PREVIEW, lang, "uzmanliklar", proc["slug"] + ".html"),
                  build_procedure(ctx, proc, proc["_cat"], data, ls)); n += 1
        counts[lang] = n

    # 3) kök yönlendirme
    write(os.path.join(PREVIEW, "index.html"),
          '<!DOCTYPE html><html lang="tr"><head><meta charset="utf-8">'
          '<meta http-equiv="refresh" content="0; url=./tr/index.html">'
          '<link rel="canonical" href="./tr/index.html"><title>Op. Dr. Alper Burak Uslu</title></head>'
          '<body><a href="./tr/index.html">Op. Dr. Alper Burak Uslu →</a></body></html>')
    write(os.path.join(PREVIEW, ".nojekyll"), "")

    # 3b) llms.txt (AEO/GEO — AI motorları için site kılavuzu, TR bazlı)
    trd = all_data["tr"]
    about_sum = ""
    if trd["about"]:
        ps = [p for p in trd["about"]["text"].split("\n") if len(p.strip()) > 60]
        about_sum = ps[0].strip()[:400] if ps else ""
    lines_llms = [
        "# Op. Dr. Alper Burak Uslu",
        "",
        "> Plastik, Rekonstrüktif ve Estetik Cerrahi Uzmanı (M.D, FEBOPRAS). "
        "İstanbul Kadıköy'de estetik cerrahi; yüz, vücut, göğüs ve ameliyatsız uygulamalar.",
        "",
        "## Hakkında",
        about_sum or "Op. Dr. Alper Burak Uslu, uluslararası üyeliklere sahip plastik cerrahi uzmanıdır.",
        "",
        "## İletişim",
        "- Telefon: " + SITE["phone_display"],
        "- Adres: " + SITE["address"],
        "- Instagram: " + SITE["social"]["instagram"],
        "",
        "## Uzmanlık Alanları",
    ]
    for cat in CATS:
        lines_llms.append("### " + CAT_LABEL["tr"][cat])
        for slug, title in NAV["tr"].get(cat, []):
            lines_llms.append("- [" + title + "](/tr/uzmanliklar/" + slug + ".html)")
        lines_llms.append("")
    lines_llms += ["## Sertifika ve Üyelikler",
                   ", ".join(c for c, _ in SITE["certs"]), ""]
    write(os.path.join(PREVIEW, "llms.txt"), "\n".join(lines_llms))
    # robots.txt (önizleme: indexlenmesin)
    write(os.path.join(PREVIEW, "robots.txt"),
          "User-agent: *\nDisallow: /\n\n# Önizleme sürümü — canlı site indexlenir.\n")
    # kök 404 (GitHub Pages otomatik sunar) — TR tasarımıyla, kök-göreli assetler
    t404 = T["tr"]
    _404 = ('<!DOCTYPE html><html lang="tr"><head><meta charset="utf-8">'
            '<meta name="viewport" content="width=device-width, initial-scale=1">'
            '<meta name="robots" content="noindex"><title>404 · Op. Dr. Alper Burak Uslu</title>'
            '<link rel="stylesheet" href="/dralperuslu-tema/assets/main.css">'
            '<script>document.documentElement.classList.add("js")</script></head>'
            '<body class="min-h-screen mesh-teal grid place-content-center text-center px-6">'
            '<div class="max-w-md mx-auto py-20">'
            '<div class="font-display text-[6rem] font-bold text-brand-300 leading-none">404</div>'
            '<h1 class="font-display text-h2 font-bold text-ink-900 mt-2">' + esc(t404["notfound"]) + '</h1>'
            '<p class="text-ink-500 mt-3">' + esc(t404["notfound_desc"]) + '</p>'
            '<a href="/dralperuslu-tema/tr/" class="btn-primary mt-8">' + esc(t404["back_home"]) + '</a>'
            '</div></body></html>')
    write(os.path.join(PREVIEW, "404.html"), _404)

    # 4) assetler
    dst_assets = os.path.join(PREVIEW, "assets")
    os.makedirs(dst_assets, exist_ok=True)
    shutil.copytree(os.path.join(ROOT, "theme", "assets", "fonts"), os.path.join(dst_assets, "fonts"))
    shutil.copytree(os.path.join(ROOT, "theme", "assets", "vendor"), os.path.join(dst_assets, "vendor"))
    shutil.copytree(os.path.join(CONTENT, "media"), os.path.join(dst_assets, "media"))
    css_src = os.path.join(ROOT, "theme", "assets", "dist", "main.css")
    if os.path.exists(css_src):
        # Tema yapısında main.css `assets/dist/` içinde olduğundan @font-face `../fonts/` kullanır.
        # Preview'de main.css `assets/` köküne düzleştiği için yolu `fonts/` yap (yoksa 404 → Playfair yüklenmez).
        css_txt = open(css_src, encoding="utf-8").read().replace('url(../fonts/', 'url(fonts/').replace('url("../fonts/', 'url("fonts/').replace("url('../fonts/", "url('fonts/")
        with open(os.path.join(dst_assets, "main.css"), "w", encoding="utf-8") as _f:
            _f.write(css_txt)
    shutil.copy(os.path.join(ROOT, "theme", "assets", "src", "main.js"), os.path.join(dst_assets, "main.js"))
    shutil.copy(os.path.join(ROOT, "theme", "assets", "favicon.svg"), os.path.join(dst_assets, "favicon.svg"))

    print("Statik önizleme üretildi:")
    for lang, c in counts.items():
        print(f"  {lang}: {c} sayfa")
    print(f"  toplam: {sum(counts.values())} sayfa + kök yönlendirme")

if __name__ == "__main__":
    main()
