<?php
/**
 * Reklam landing page içerikleri (slug bazlı). template-landing.php kullanır.
 * Uydurma hasta yorumu/öncesi-sonrası YOK; güven + bilgi + CTA odaklı.
 *
 * @package dr-alper-uslu
 */

function dau_landing_data() {
	return array(
		'meme-buyutme-lp' => array(
			'category' => 'Göğüs Estetiği',
			'title'    => 'Meme Büyütme',
			'h1'       => 'Doğal ve Kişiye Özel<br>Meme Büyütme',
			'sub'      => 'Anatominize uygun implant seçimi ve doğal görünen sonuçlarla kendinizi daha rahat hissedin. Planlama muayenede birlikte yapılır.',
			'wa_text'  => 'Merhaba, meme büyütme hakkında bilgi almak istiyorum.',
			'videos'   => array( 'of7G8g-ntak', '8GfEdaPMgbU' ),
			'benefits' => array(
				array( 'Doğal Görünüm', 'Vücut oranlarınıza uygun, abartıdan uzak ve doğal duran sonuçlar.' ),
				array( 'Kişiye Özel İmplant', 'Anatominize ve beklentilerinize göre implant tipi, şekli ve boyutu belirlenir.' ),
				array( 'Güvenli Teknik', 'Uluslararası board yeterliliklerine sahip bir cerrah tarafından, güncel yöntemlerle.' ),
				array( 'Kalıcı ve Öngörülebilir', 'Doğru planlama ile uzun süreli, öngörülebilir bir sonuç hedeflenir.' ),
			),
			'faq' => array(
				'Meme büyütme ameliyatı ne kadar sürer?' => 'Genellikle 1-2 saat arasında değişir. Süre, seçilen teknik ve implant yerleşimine göre kişiden kişiye farklılık gösterebilir.',
				'İmplant seçimi nasıl yapılır?' => 'Göğüs kafesi ölçüleriniz, doku yapınız ve beklentileriniz değerlendirilerek muayenede birlikte karar verilir. Amaç, size en doğal duracak seçimdir.',
				'İyileşme süreci nasıldır?' => 'İlk günlerde hafif şişlik ve hassasiyet normaldir. Çoğu kişi kısa sürede günlük hayatına döner; ağır egzersizler için hekiminizin verdiği süreye uyulur.',
				'İzler belli olur mu?' => 'İz, tekniğe ve yerleşime göre gizli bölgelerde kalacak şekilde planlanır ve zamanla belirgin şekilde soluklaşır.',
			),
		),
		'facelift-lp' => array(
			'category' => 'Yüz Estetiği',
			'title'    => 'Yüz Germe (Facelift)',
			'h1'       => 'Dinlenmiş ve Doğal<br>Bir Yüz İfadesi',
			'sub'      => 'Yaşlanmayla gevşeyen dokuları, ifadenizi bozmadan yeniden şekillendirir. Amaç bambaşka biri değil, daha dinç bir siz.',
			'wa_text'  => 'Merhaba, yüz germe (facelift) hakkında bilgi almak istiyorum.',
			'videos'   => array( '8d3AZTKqTpo', '8GfEdaPMgbU' ),
			'benefits' => array(
				array( 'Doğal İfade', 'Gergin veya yapay bir görünüm değil, dinlenmiş ve doğal bir ifade hedeflenir.' ),
				array( 'Kişiye Özel Planlama', 'Yüz analiziniz yapılır; sadece ihtiyaç duyulan bölgelere odaklanılır.' ),
				array( 'Uzman Cerrahi', 'Plastik, rekonstrüktif ve estetik cerrahi uzmanlığı ve board yeterlilikleriyle.' ),
				array( 'Uzun Süreli Etki', 'Doğru teknikle yıllara yayılan, kalıcı bir tazelik hedeflenir.' ),
			),
			'faq' => array(
				'Yüz germe sonucu yapay mı durur?' => 'Doğru teknikle hayır. Amaç dokuları doğal konumuna taşımaktır; ifadeniz korunur, daha dinç bir görünüm elde edilir.',
				'İyileşme ne kadar sürer?' => 'İlk hafta şişlik ve morluk görülebilir. Sosyal hayata dönüş süresi kişiye ve tekniğe göre değişir; net bilgiyi muayenede alırsınız.',
				'Kimler için uygundur?' => 'Yüz ve boyun bölgesinde sarkma, gevşeme yaşayan ve gerçekçi beklentileri olan kişiler için uygundur. Uygunluk muayenede değerlendirilir.',
				'İzler nerede kalır?' => 'İzler saç çizgisi ve kulak çevresi gibi gizli bölgelerde planlanır ve zamanla soluklaşır.',
			),
		),
		'mommy-makeover-lp' => array(
			'category' => 'Vücut Estetiği',
			'title'    => 'Mommy Makeover',
			'h1'       => 'Doğum Sonrası<br>Yeniden Kendiniz',
			'sub'      => 'Gebelik ve doğum sonrası değişen bölgeleri tek planda ele alan, kişiye özel bir toparlanma yaklaşımı. İçerik ihtiyacınıza göre belirlenir.',
			'wa_text'  => 'Merhaba, mommy makeover hakkında bilgi almak istiyorum.',
			'videos'   => array( '1P5Hhub08JA', '8GfEdaPMgbU' ),
			'benefits' => array(
				array( 'Bütüncül Planlama', 'Karın, göğüs ve gerektiğinde vücut şekillendirme tek plan altında değerlendirilir.' ),
				array( 'Kişiye Özel', 'Her annenin ihtiyacı farklıdır; kombinasyon size göre belirlenir.' ),
				array( 'Tek İyileşme Süreci', 'İhtiyaçların birlikte planlanması, sürecin daha derli toplu ilerlemesini sağlayabilir.' ),
				array( 'Uzman Yaklaşım', 'Deneyimli bir cerrah eşliğinde, güvenlik ve doğallık önceliğiyle.' ),
			),
			'faq' => array(
				'Mommy makeover neleri kapsar?' => 'Genellikle karın germe, liposuction ve göğüs (dikleştirme/büyütme) işlemlerinin ihtiyaca göre kombinasyonudur. Kapsam muayenede kişiye özel belirlenir.',
				'Ne zaman yaptırabilirim?' => 'Genellikle doğum ve emzirme döneminin tamamlanması, kilonun stabil hale gelmesi önerilir. Uygun zamanlama muayenede konuşulur.',
				'Tek seansta mı yapılır?' => 'Uygun durumlarda işlemler tek operasyonda birleştirilebilir; ancak bu karar güvenlik değerlendirmesiyle verilir.',
				'İyileşme nasıl olur?' => 'Kombinasyona göre değişir. İlk dönemde destekleyici önlemler ve düzenli kontrollerle süreç yönetilir.',
			),
		),

		/* ---------- İNGİLİZCE (EN) — reklam LP ---------- */
		'breast-augmentation-lp' => array(
			'category' => 'Breast Aesthetics',
			'title'    => 'Breast Augmentation',
			'h1'       => 'Natural and Personalised<br>Breast Augmentation',
			'sub'      => 'Feel more comfortable in your body with an implant choice suited to your anatomy and natural-looking results. Planning is done together during your consultation.',
			'wa_text'  => 'Hello, I would like to get information about breast augmentation.',
			'videos'   => array( 'of7G8g-ntak', '8GfEdaPMgbU' ),
			'benefits' => array(
				array( 'Natural Look', 'Results that suit your body proportions, never exaggerated and always natural.' ),
				array( 'Personalised Implant', 'Implant type, shape and size are chosen to match your anatomy and expectations.' ),
				array( 'Safe Technique', 'Performed by a surgeon with international board qualifications using current methods.' ),
				array( 'Lasting and Predictable', 'With correct planning, a long-lasting and predictable result is targeted.' ),
			),
			'faq' => array(
				'How long does breast augmentation surgery take?' => 'It usually takes between 1 and 2 hours. The duration may vary from person to person depending on the technique and implant placement.',
				'How is the implant chosen?' => 'Your chest measurements, tissue structure and expectations are evaluated and decided together during your consultation. The aim is the choice that looks most natural on you.',
				'What is the recovery process like?' => 'Mild swelling and tenderness in the first days is normal. Most people return to daily life quickly, and the timing for heavy exercise follows the guidance your doctor provides.',
				'Will the scars be visible?' => 'The scar is planned to remain in concealed areas depending on the technique and placement, and it fades noticeably over time.',
			),
		),
		'facelift-surgery-lp' => array(
			'category' => 'Facial Aesthetics',
			'title'    => 'Facelift',
			'h1'       => 'A Rested and Natural<br>Facial Expression',
			'sub'      => 'Reshapes tissues that have loosened with age without altering your expression. The goal is not a different person, but a fresher you.',
			'wa_text'  => 'Hello, I would like to get information about a facelift.',
			'videos'   => array( '8d3AZTKqTpo', '8GfEdaPMgbU' ),
			'benefits' => array(
				array( 'Natural Expression', 'Not a tight or artificial look; a rested and natural expression is targeted.' ),
				array( 'Personalised Planning', 'Your facial analysis is done and only the areas that need it are addressed.' ),
				array( 'Expert Surgery', 'With plastic, reconstructive and aesthetic surgery expertise and board qualifications.' ),
				array( 'Long-Lasting Effect', 'With the right technique, a lasting freshness spread over years is targeted.' ),
			),
			'faq' => array(
				'Does a facelift look artificial?' => 'With the right technique, no. The aim is to move the tissues to their natural position, so your expression is preserved and a fresher look is achieved.',
				'How long does recovery take?' => 'Swelling and bruising may be seen in the first week. The return to social life varies by person and technique, and you receive precise information during your consultation.',
				'Who is it suitable for?' => 'It is suitable for people with sagging and loosening in the face and neck who have realistic expectations. Suitability is assessed during the consultation.',
				'Where do the scars remain?' => 'Scars are planned in concealed areas such as the hairline and around the ears, and they fade over time.',
			),
		),
		'mommy-makeover-surgery-lp' => array(
			'category' => 'Body Aesthetics',
			'title'    => 'Mommy Makeover',
			'h1'       => 'Yourself Again<br>After Pregnancy',
			'sub'      => 'A personalised recovery approach that addresses the areas changed by pregnancy and birth in a single plan. The content is determined according to your needs.',
			'wa_text'  => 'Hello, I would like to get information about a mommy makeover.',
			'videos'   => array( '1P5Hhub08JA', '8GfEdaPMgbU' ),
			'benefits' => array(
				array( 'Holistic Planning', 'Abdomen, breasts and, when needed, body contouring are evaluated under a single plan.' ),
				array( 'Personalised', 'The needs of every mother are different, so the combination is determined for you.' ),
				array( 'Single Recovery Process', 'Planning the needs together can help the process progress in a more organised way.' ),
				array( 'Expert Approach', 'With an experienced surgeon, prioritising safety and natural results.' ),
			),
			'faq' => array(
				'What does a mommy makeover include?' => 'It is usually a combination of tummy tuck, liposuction and breast (lift or augmentation) procedures according to need. The scope is determined personally during the consultation.',
				'When can I have it?' => 'It is usually recommended after pregnancy and breastfeeding are complete and weight has stabilised. The right timing is discussed during the consultation.',
				'Is it done in a single session?' => 'In suitable cases the procedures can be combined in one operation; however, this decision is made together with a safety assessment.',
				'What is recovery like?' => 'It varies by combination. In the early period the process is managed with supportive measures and regular check-ups.',
			),
		),

		/* ---------- ALMANCA (DE) — reklam LP ---------- */
		'brustvergroesserung-lp' => array(
			'category' => 'Brustästhetik',
			'title'    => 'Brustvergrößerung',
			'h1'       => 'Natürliche und individuelle<br>Brustvergrößerung',
			'sub'      => 'Fühlen Sie sich wohler in Ihrem Körper, mit einer zu Ihrer Anatomie passenden Implantatwahl und natürlich wirkenden Ergebnissen. Die Planung erfolgt gemeinsam in der Beratung.',
			'wa_text'  => 'Hallo, ich möchte Informationen über eine Brustvergrößerung erhalten.',
			'videos'   => array( 'of7G8g-ntak', '8GfEdaPMgbU' ),
			'benefits' => array(
				array( 'Natürliches Aussehen', 'Ergebnisse, die zu Ihren Körperproportionen passen, nie übertrieben und immer natürlich.' ),
				array( 'Individuelles Implantat', 'Implantattyp, -form und -größe werden passend zu Ihrer Anatomie und Ihren Erwartungen gewählt.' ),
				array( 'Sichere Technik', 'Durchgeführt von einem Chirurgen mit internationalen Board-Qualifikationen und modernen Methoden.' ),
				array( 'Dauerhaft und Vorhersehbar', 'Mit der richtigen Planung wird ein langfristiges und vorhersehbares Ergebnis angestrebt.' ),
			),
			'faq' => array(
				'Wie lange dauert eine Brustvergrößerung?' => 'In der Regel zwischen 1 und 2 Stunden. Die Dauer kann je nach Technik und Implantatlage von Person zu Person variieren.',
				'Wie wird das Implantat ausgewählt?' => 'Ihre Brustkorbmaße, Gewebestruktur und Erwartungen werden bewertet und gemeinsam in der Beratung entschieden. Ziel ist die Wahl, die bei Ihnen am natürlichsten aussieht.',
				'Wie verläuft die Heilung?' => 'Leichte Schwellungen und Empfindlichkeit in den ersten Tagen sind normal. Die meisten kehren schnell in den Alltag zurück, und für anstrengenden Sport gilt die Vorgabe Ihres Arztes.',
				'Sind die Narben sichtbar?' => 'Die Narbe wird je nach Technik und Lage in verdeckten Bereichen geplant und verblasst mit der Zeit deutlich.',
			),
		),
		'facelifting-lp' => array(
			'category' => 'Gesichtsästhetik',
			'title'    => 'Facelift',
			'h1'       => 'Ein ausgeruhter und<br>natürlicher Gesichtsausdruck',
			'sub'      => 'Formt mit dem Alter erschlaffte Gewebe neu, ohne Ihren Ausdruck zu verändern. Das Ziel ist kein anderer Mensch, sondern ein frischeres Sie.',
			'wa_text'  => 'Hallo, ich möchte Informationen über ein Facelift erhalten.',
			'videos'   => array( '8d3AZTKqTpo', '8GfEdaPMgbU' ),
			'benefits' => array(
				array( 'Natürlicher Ausdruck', 'Kein straffes oder künstliches Aussehen, sondern ein ausgeruhter und natürlicher Ausdruck.' ),
				array( 'Individuelle Planung', 'Ihre Gesichtsanalyse wird durchgeführt und nur die Bereiche, die es benötigen, werden behandelt.' ),
				array( 'Fachärztliche Chirurgie', 'Mit Fachwissen in plastischer, rekonstruktiver und ästhetischer Chirurgie und Board-Qualifikationen.' ),
				array( 'Langanhaltender Effekt', 'Mit der richtigen Technik wird eine über Jahre anhaltende Frische angestrebt.' ),
			),
			'faq' => array(
				'Wirkt ein Facelift künstlich?' => 'Mit der richtigen Technik nein. Ziel ist es, die Gewebe in ihre natürliche Position zu bringen, sodass Ihr Ausdruck erhalten bleibt und ein frischeres Aussehen entsteht.',
				'Wie lange dauert die Heilung?' => 'In der ersten Woche können Schwellungen und Blutergüsse auftreten. Die Rückkehr ins soziale Leben variiert je nach Person und Technik, und genaue Informationen erhalten Sie in der Beratung.',
				'Für wen ist es geeignet?' => 'Geeignet für Personen mit Erschlaffung im Gesichts- und Halsbereich, die realistische Erwartungen haben. Die Eignung wird in der Beratung beurteilt.',
				'Wo bleiben die Narben?' => 'Narben werden in verdeckten Bereichen wie Haaransatz und rund um die Ohren geplant und verblassen mit der Zeit.',
			),
		),
		'mommy-makeover-op-lp' => array(
			'category' => 'Körperästhetik',
			'title'    => 'Mommy Makeover',
			'h1'       => 'Nach der Schwangerschaft<br>wieder Sie selbst',
			'sub'      => 'Ein individueller Erholungsansatz, der die durch Schwangerschaft und Geburt veränderten Bereiche in einem einzigen Plan behandelt. Der Inhalt richtet sich nach Ihrem Bedarf.',
			'wa_text'  => 'Hallo, ich möchte Informationen über ein Mommy Makeover erhalten.',
			'videos'   => array( '1P5Hhub08JA', '8GfEdaPMgbU' ),
			'benefits' => array(
				array( 'Ganzheitliche Planung', 'Bauch, Brust und bei Bedarf Körperkonturierung werden unter einem Plan bewertet.' ),
				array( 'Individuell', 'Die Bedürfnisse jeder Mutter sind unterschiedlich, daher wird die Kombination für Sie festgelegt.' ),
				array( 'Ein Heilungsprozess', 'Die gemeinsame Planung der Bedürfnisse kann den Prozess geordneter gestalten.' ),
				array( 'Fachärztlicher Ansatz', 'Mit einem erfahrenen Chirurgen, mit Priorität auf Sicherheit und natürliche Ergebnisse.' ),
			),
			'faq' => array(
				'Was umfasst ein Mommy Makeover?' => 'In der Regel eine Kombination aus Bauchdeckenstraffung, Liposuktion und Brust (-straffung oder -vergrößerung) je nach Bedarf. Der Umfang wird individuell in der Beratung festgelegt.',
				'Wann kann ich es machen lassen?' => 'In der Regel wird es nach Abschluss von Schwangerschaft und Stillzeit und nach Stabilisierung des Gewichts empfohlen. Der richtige Zeitpunkt wird in der Beratung besprochen.',
				'Wird es in einer einzigen Sitzung durchgeführt?' => 'In geeigneten Fällen können die Eingriffe in einer Operation kombiniert werden; diese Entscheidung wird jedoch gemeinsam mit einer Sicherheitsbewertung getroffen.',
				'Wie verläuft die Heilung?' => 'Sie variiert je nach Kombination. In der Anfangsphase wird der Prozess mit unterstützenden Maßnahmen und regelmäßigen Kontrollen begleitet.',
			),
		),
	);
}
