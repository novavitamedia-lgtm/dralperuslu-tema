<?php
/**
 * Template Name: Reklam Landing
 *
 * Reklam için özel, odaklı landing page (site navigasyonu YOK, noindex).
 * İçerik slug bazlı: inc/landing-data.php. Site tarzı (Playfair + coral + teal).
 *
 * @package dr-alper-uslu
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
the_post();
$slug = get_post_field( 'post_name', get_the_ID() );
$data = function_exists( 'dau_landing_data' ) ? dau_landing_data() : array();
$lp   = isset( $data[ $slug ] ) ? $data[ $slug ] : null;
if ( ! $lp ) {
	$lp = array( 'category' => '', 'title' => get_the_title(), 'h1' => get_the_title(), 'sub' => '', 'wa_text' => '', 'benefits' => array(), 'faq' => array() );
}
$wa     = dau_wa_link( $lp['wa_text'] );
$tel    = dau_tel();
$phone  = dau_opt( 'telefon' );
$steps  = array(
	array( '01', __( 'Ücretsiz Ön Görüşme', 'dr-alper-uslu' ), __( 'Beklentileriniz dinlenir, uygunluk ve seçenekler açıkça anlatılır.', 'dr-alper-uslu' ) ),
	array( '02', __( 'Kişiye Özel Planlama', 'dr-alper-uslu' ), __( 'Anatominize ve hedefinize uygun, gerçekçi bir plan hazırlanır.', 'dr-alper-uslu' ) ),
	array( '03', __( 'Operasyon', 'dr-alper-uslu' ), __( 'İşlem, güncel ve güvenli tekniklerle uzman eşliğinde uygulanır.', 'dr-alper-uslu' ) ),
	array( '04', __( 'İyileşme & Takip', 'dr-alper-uslu' ), __( 'Düzenli kontrollerle iyileşme süreciniz boyunca yanınızdayız.', 'dr-alper-uslu' ) ),
);
$certs  = array( 'ISAPS', 'ASPS', 'EBOPRAS', 'TPRECD', 'UEMS' );
$wanum  = preg_replace( '/\D/', '', dau_opt( 'whatsapp' ) );
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="scroll-smooth">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex,nofollow">
	<link rel="icon" type="image/png" sizes="512x512" href="<?php echo esc_url( DAU_URI . '/assets/img/favicon-512.png' ); ?>">
	<link rel="apple-touch-icon" href="<?php echo esc_url( DAU_URI . '/assets/img/favicon-180.png' ); ?>">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'min-h-screen flex flex-col bg-white' ); ?>>
<style>[x-cloak]{display:none!important}</style>
<?php wp_body_open(); ?>

<!-- Minimal header: logo + tek CTA -->
<header class="sticky top-0 z-50 bg-white/90 backdrop-blur border-b border-line">
	<div class="container flex items-center justify-between gap-4 py-3">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center" aria-label="Op. Dr. Alper Burak Uslu">
			<img src="<?php echo esc_url( DAU_URI . '/assets/img/logo.jpg' ); ?>" alt="Op. Dr. Alper Burak Uslu" width="557" height="70" class="h-9 sm:h-10 w-auto">
		</a>
		<a href="<?php echo esc_url( $wa ); ?>" target="_blank" rel="noopener" class="group/cta inline-flex items-center gap-2.5 rounded-full bg-ink-900 text-white pl-5 pr-1.5 py-1.5 text-[0.86rem] font-semibold hover:bg-ink-700 transition-colors">
			<span><?php esc_html_e( 'Randevu Al', 'dr-alper-uslu' ); ?></span>
			<span class="grid place-content-center w-8 h-8 rounded-full bg-coral-500 text-white group-hover/cta:bg-coral-600 transition-colors"><?php echo dau_icon( 'arrow' ); // phpcs:ignore ?></span>
		</a>
	</div>
</header>

<main class="flex-1">
	<!-- Hero -->
	<section class="relative overflow-hidden bg-cream-50">
		<svg class="absolute inset-0 w-full h-full opacity-50 pointer-events-none" preserveAspectRatio="none" viewBox="0 0 1440 700" aria-hidden="true"><path d="M-50 520 Q400 380 760 470 T1500 360" fill="none" stroke="#E3E8E7" stroke-width="1.5"/></svg>
		<div class="container relative py-14 md:py-20 grid lg:grid-cols-12 gap-8 items-center">
			<div class="lg:col-span-7">
				<span class="kicker mb-4"><?php echo esc_html( $lp['category'] ); ?></span>
				<h1 class="font-display font-black text-ink-900 text-[clamp(2.2rem,6vw,4.2rem)] leading-[1.03] tracking-[-0.02em] mt-3"><?php echo wp_kses_post( $lp['h1'] ); ?></h1>
				<p class="text-lead text-ink-700 mt-5 max-w-xl"><?php echo esc_html( $lp['sub'] ); ?></p>
				<div class="flex flex-wrap items-center gap-3 mt-8">
					<a href="<?php echo esc_url( $wa ); ?>" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-2 rounded-full bg-[#0E7A3A] text-white px-6 py-3.5 font-semibold hover:brightness-110 transition"><?php echo dau_icon( 'wa' ); // phpcs:ignore ?><?php esc_html_e( 'WhatsApp ile Yazın', 'dr-alper-uslu' ); ?></a>
					<a href="<?php echo esc_attr( $tel ); ?>" class="inline-flex items-center justify-center gap-2 rounded-full ring-1 ring-line bg-white text-ink-900 px-6 py-3.5 font-semibold hover:bg-cream-50 transition"><?php echo dau_icon( 'phone' ); // phpcs:ignore ?><?php esc_html_e( 'Hemen Ara', 'dr-alper-uslu' ); ?></a>
				</div>
				<a href="https://www.trustpilot.com/review/dralperuslu.com" target="_blank" rel="noopener" class="inline-flex items-center gap-2 mt-7 rounded-full bg-white ring-1 ring-line px-4 py-2 hover:bg-cream-50 transition">
					<span class="text-[#00b67a] text-sm tracking-tight">★★★★★</span>
					<span class="text-sm font-semibold text-ink-900">4,5</span>
					<span class="text-sm text-ink-500"><?php esc_html_e( 'Trustpilot\'ta 87 değerlendirme', 'dr-alper-uslu' ); ?></span>
				</a>
				<div class="flex flex-wrap items-center gap-x-5 gap-y-2 mt-5 text-sm text-ink-500">
					<?php foreach ( $certs as $c ) : ?><span class="inline-flex items-center gap-1.5"><span class="text-brand-600"><?php echo dau_icon( 'badge' ); // phpcs:ignore ?></span><?php echo esc_html( $c ); ?></span><?php endforeach; ?>
				</div>
			</div>
			<div class="lg:col-span-5">
				<div class="aspect-[4/5] max-w-sm ml-auto rounded-[2rem] overflow-hidden ring-1 ring-line shadow-cardHover bg-white">
					<img src="<?php echo esc_url( DAU_URI . '/assets/img/dr-alper.jpg' ); ?>" alt="Op. Dr. Alper Burak Uslu" class="w-full h-full object-cover object-[34%_15%]">
				</div>
			</div>
		</div>
	</section>

	<!-- Neden -->
	<?php if ( $lp['benefits'] ) : ?>
	<section class="section bg-white"><div class="container">
		<span class="kicker mb-3"><?php esc_html_e( 'Neden', 'dr-alper-uslu' ); ?></span>
		<h2 class="section-title mt-3 mb-10"><?php printf( esc_html__( 'Neden %s?', 'dr-alper-uslu' ), esc_html( $lp['title'] ) ); ?></h2>
		<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
			<?php foreach ( $lp['benefits'] as $b ) : ?>
			<div class="card p-6">
				<div class="w-12 h-12 grid place-content-center rounded-full bg-brand-50 text-brand-600 mb-4"><?php echo dau_icon( 'badge' ); // phpcs:ignore ?></div>
				<h3 class="font-display text-h3 font-semibold text-ink-900"><?php echo esc_html( $b[0] ); ?></h3>
				<p class="text-ink-500 text-sm mt-2 leading-relaxed"><?php echo esc_html( $b[1] ); ?></p>
			</div>
			<?php endforeach; ?>
		</div>
	</div></section>
	<?php endif; ?>

	<!-- Tedavi adımları -->
	<section class="section bg-cream-50"><div class="container">
		<span class="kicker mb-3"><?php esc_html_e( 'Süreç', 'dr-alper-uslu' ); ?></span>
		<h2 class="section-title mt-3 mb-10"><?php esc_html_e( 'Nasıl İlerliyoruz?', 'dr-alper-uslu' ); ?></h2>
		<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
			<?php foreach ( $steps as $st ) : ?>
			<div class="relative">
				<div class="font-display text-4xl font-black text-brand-500/25"><?php echo esc_html( $st[0] ); ?></div>
				<h3 class="font-display text-h3 font-semibold text-ink-900 mt-1"><?php echo esc_html( $st[1] ); ?></h3>
				<p class="text-ink-500 text-sm mt-2 leading-relaxed"><?php echo esc_html( $st[2] ); ?></p>
			</div>
			<?php endforeach; ?>
		</div>
	</div></section>

	<!-- Doktor -->
	<section class="section bg-white"><div class="container grid lg:grid-cols-2 gap-12 items-center">
		<div class="max-w-sm">
			<div class="aspect-[4/5] rounded-[2rem] overflow-hidden ring-1 ring-line shadow-cardHover">
				<img src="<?php echo esc_url( DAU_URI . '/assets/img/dr-alper.jpg' ); ?>" alt="Op. Dr. Alper Burak Uslu" class="w-full h-full object-cover object-[34%_15%]">
			</div>
		</div>
		<div>
			<span class="kicker mb-3"><?php esc_html_e( 'Uzman', 'dr-alper-uslu' ); ?></span>
			<h2 class="section-title mt-3">Op. Dr. Alper<br><span class="italic">Burak Uslu</span></h2>
			<p class="text-ink-500 text-lg mt-3"><?php esc_html_e( 'Plastik, Rekonstrüktif ve Estetik Cerrahi · M.D, FEBOPRAS', 'dr-alper-uslu' ); ?></p>
			<p class="text-ink-700 mt-4 leading-relaxed max-w-lg"><?php esc_html_e( 'Uluslararası ve ulusal plastik cerrahi kuruluşlarının aktif üyesi. Yüz, vücut ve göğüs estetiğinde doğal ve kişiye özel sonuçları önceler.', 'dr-alper-uslu' ); ?></p>
			<a href="<?php echo esc_url( $wa ); ?>" target="_blank" rel="noopener" class="btn-primary mt-7"><?php esc_html_e( 'Randevu Al', 'dr-alper-uslu' ); ?></a>
		</div>
	</div></section>

	<!-- Gerçek yorumlar (Trustpilot) -->
	<section class="section bg-cream-50"><div class="container">
		<span class="kicker mb-3"><?php esc_html_e( 'Değerlendirmeler', 'dr-alper-uslu' ); ?></span>
		<h2 class="section-title mt-3 mb-8"><?php esc_html_e( 'Hastaların Gerçek Yorumları', 'dr-alper-uslu' ); ?></h2>
		<div class="trustpilot-widget" data-locale="tr-TR" data-template-id="53aa8912dec7e10d38f59f36" data-businessunit-id="641326d15a153ec4fec127ef" data-style-height="240px" data-style-width="100%" data-theme="light" data-stars="4,5">
			<a href="https://www.trustpilot.com/review/dralperuslu.com" target="_blank" rel="noopener">Trustpilot</a>
		</div>
	</div></section>

	<!-- Video & Instagram -->
	<?php if ( ! empty( $lp['videos'] ) ) : ?>
	<section class="section bg-white"><div class="container">
		<span class="kicker mb-3"><?php esc_html_e( 'Klinikten', 'dr-alper-uslu' ); ?></span>
		<h2 class="section-title mt-3 mb-8"><?php esc_html_e( 'Videolar & Sosyal Medya', 'dr-alper-uslu' ); ?></h2>
		<div class="grid md:grid-cols-2 gap-6">
			<?php foreach ( $lp['videos'] as $vid ) : ?>
			<div class="aspect-video rounded-xl2 overflow-hidden ring-1 ring-line shadow-card bg-ink-900">
				<iframe src="https://www.youtube-nocookie.com/embed/<?php echo esc_attr( $vid ); ?>" title="Op. Dr. Alper Burak Uslu" loading="lazy" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="w-full h-full"></iframe>
			</div>
			<?php endforeach; ?>
		</div>
		<div class="mt-8 flex flex-wrap items-center gap-3">
			<a href="https://www.instagram.com/dralperburakuslu/" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-full ring-1 ring-line bg-white px-5 py-3 font-semibold text-ink-900 hover:bg-cream-50 transition"><?php echo dau_icon( 'ig' ); // phpcs:ignore ?>@dralperburakuslu</a>
			<a href="https://www.youtube.com/@dr.alperburakuslu" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-full ring-1 ring-line bg-white px-5 py-3 font-semibold text-ink-900 hover:bg-cream-50 transition"><?php echo dau_icon( 'yt' ); // phpcs:ignore ?>YouTube</a>
		</div>
	</div></section>
	<?php endif; ?>

	<!-- SSS -->
	<?php if ( $lp['faq'] ) : ?>
	<section class="section bg-cream-50"><div class="container max-w-3xl">
		<span class="kicker mb-3"><?php esc_html_e( 'Merak Edilenler', 'dr-alper-uslu' ); ?></span>
		<h2 class="section-title mt-3 mb-8"><?php esc_html_e( 'Sık Sorulan Sorular', 'dr-alper-uslu' ); ?></h2>
		<div class="space-y-3">
			<?php foreach ( $lp['faq'] as $q => $a ) : ?>
			<div class="card p-0 overflow-hidden" x-data="{ o:false }">
				<button @click="o=!o" class="w-full flex items-center justify-between gap-4 text-left px-5 py-4 font-medium text-ink-900">
					<span><?php echo esc_html( $q ); ?></span>
					<span class="text-brand-600 shrink-0 transition-transform" :class="o && 'rotate-180'"><?php echo dau_icon( 'chevron' ); // phpcs:ignore ?></span>
				</button>
				<div x-show="o" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="px-5 pb-5 text-ink-700 leading-relaxed"><?php echo esc_html( $a ); ?></div>
			</div>
			<?php endforeach; ?>
		</div>
		<?php
		// FAQPage schema
		$faq_items = array();
		foreach ( $lp['faq'] as $q => $a ) {
			$faq_items[] = array( '@type' => 'Question', 'name' => $q, 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $a ) );
		}
		echo '<script type="application/ld+json">' . wp_json_encode( array( '@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $faq_items ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>';
		?>
	</div></section>
	<?php endif; ?>

	<!-- Final CTA + WhatsApp form -->
	<section class="section bg-ink-900 text-white"><div class="container grid lg:grid-cols-2 gap-12 items-center">
		<div>
			<h2 class="font-display text-h2 font-bold"><?php esc_html_e( 'Ücretsiz Ön Görüşme İçin İlk Adımı Atın', 'dr-alper-uslu' ); ?></h2>
			<p class="text-white/70 mt-4 max-w-md leading-relaxed"><?php printf( esc_html__( '%s hakkında sorularınızı yanıtlayalım, size en uygun yaklaşımı birlikte belirleyelim.', 'dr-alper-uslu' ), esc_html( $lp['title'] ) ); ?></p>
			<div class="flex flex-wrap gap-3 mt-7">
				<a href="<?php echo esc_url( $wa ); ?>" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-2 rounded-full bg-[#0E7A3A] text-white px-6 py-3.5 font-semibold hover:brightness-110 transition"><?php echo dau_icon( 'wa' ); // phpcs:ignore ?>WhatsApp</a>
				<a href="<?php echo esc_attr( $tel ); ?>" class="inline-flex items-center justify-center gap-2 rounded-full bg-white/10 ring-1 ring-white/20 text-white px-6 py-3.5 font-semibold hover:bg-white/15 transition"><?php echo dau_icon( 'phone' ); // phpcs:ignore ?><?php echo esc_html( $phone ); ?></a>
			</div>
		</div>
		<div class="bg-white text-ink-900 rounded-2xl p-6 sm:p-8">
			<h3 class="font-display text-h3 font-semibold mb-1"><?php esc_html_e( 'Randevu Talebi', 'dr-alper-uslu' ); ?></h3>
			<p class="text-sm text-ink-500 mb-5"><?php esc_html_e( 'Formu doldurun, WhatsApp üzerinden hızlıca dönelim.', 'dr-alper-uslu' ); ?></p>
			<form class="grid gap-3" onsubmit="return dauLpWa(event)">
				<input name="ad" required placeholder="<?php esc_attr_e( 'Ad Soyad', 'dr-alper-uslu' ); ?>" class="w-full rounded-xl ring-1 ring-line px-4 py-3 focus:ring-brand-500 outline-none" autocomplete="name">
				<input name="tel" type="tel" required placeholder="<?php esc_attr_e( 'Telefon', 'dr-alper-uslu' ); ?>" class="w-full rounded-xl ring-1 ring-line px-4 py-3 focus:ring-brand-500 outline-none" autocomplete="tel">
				<label class="flex items-start gap-2.5 text-xs text-ink-500"><input type="checkbox" required class="mt-0.5 accent-brand-600 w-4 h-4"><span><?php esc_html_e( 'Kişisel verilerimin randevu talebim doğrultusunda işlenmesine (KVKK) onay veriyorum.', 'dr-alper-uslu' ); ?></span></label>
				<button type="submit" class="btn-primary justify-center"><?php echo dau_icon( 'wa' ); // phpcs:ignore ?><span><?php esc_html_e( 'WhatsApp ile Gönder', 'dr-alper-uslu' ); ?></span></button>
			</form>
		</div>
	</div></section>
</main>

<!-- Minimal footer -->
<footer class="bg-ink-900 text-white/60 border-t border-white/10 py-6">
	<div class="container flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
		<span>© <?php echo esc_html( wp_date( 'Y' ) ); ?> Op. Dr. Alper Burak Uslu</span>
		<span><?php echo esc_html( dau_opt( 'adres' ) ); ?></span>
	</div>
</footer>

<!-- Sticky mobil CTA -->
<div class="fixed bottom-0 inset-x-0 z-50 grid grid-cols-2 gap-px bg-line sm:hidden">
	<a href="<?php echo esc_attr( $tel ); ?>" class="flex items-center justify-center gap-2 bg-brand-600 text-white py-3.5 font-semibold text-sm"><?php echo dau_icon( 'phone' ); // phpcs:ignore ?><?php esc_html_e( 'Hemen Ara', 'dr-alper-uslu' ); ?></a>
	<a href="<?php echo esc_url( $wa ); ?>" target="_blank" rel="noopener" class="flex items-center justify-center gap-2 bg-[#0E7A3A] text-white py-3.5 font-semibold text-sm"><?php echo dau_icon( 'wa' ); // phpcs:ignore ?>WhatsApp</a>
</div>

<script>
function dauLpWa(e){
	e.preventDefault();
	var f=e.target, num=<?php echo wp_json_encode( $wanum ); ?>, konu=<?php echo wp_json_encode( $lp['title'] ); ?>;
	var t='Merhaba, '+konu+' icin randevu talebim var.\n\nAd Soyad: '+(f.ad.value||'')+'\nTelefon: '+(f.tel.value||'');
	window.open('https://wa.me/'+num+'?text='+encodeURIComponent(t),'_blank','noopener');
	return false;
}
</script>
<script src="//widget.trustpilot.com/bootstrap/v5/tp.widget.bootstrap.min.js" async></script>
<?php wp_footer(); ?>
</body>
</html>
