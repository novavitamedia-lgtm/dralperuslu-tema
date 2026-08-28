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
	);
}
