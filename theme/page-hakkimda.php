<?php
/**
 * Sayfa şablonu: Hakkımda — editorial (portre + istatistik + bio prose + kredibilite sidebar + CTA).
 * "Düz metin duvarı" yerine çerçeveli, taranabilir tasarım.
 *
 * @package dr-alper-uslu
 */

get_header();
the_post();

$portrait_id = get_post_thumbnail_id();
$stats = array(
	array( '12', '', __( 'Yıl Deneyim', 'dr-alper-uslu' ) ),
	array( '2000', '+', __( 'Estetik İşlem', 'dr-alper-uslu' ) ),
	array( '4000', '+', __( 'Ameliyat', 'dr-alper-uslu' ) ),
);
$certs = array(
	array( 'ISAPS', __( 'International Society of Aesthetic Plastic Surgery', 'dr-alper-uslu' ) ),
	array( 'ASPS', __( 'American Society of Plastic Surgeons', 'dr-alper-uslu' ) ),
	array( 'EBOPRAS', __( 'European Board of Plastic, Reconstructive and Aesthetic Surgery', 'dr-alper-uslu' ) ),
	array( 'TPRECD', __( 'Türk Plastik Rekonstrüktif ve Estetik Cerrahi Derneği', 'dr-alper-uslu' ) ),
	array( 'UEMS', __( 'Union Européenne des Médecins Spécialistes', 'dr-alper-uslu' ) ),
);
?>
<section class="mesh-teal overflow-hidden"><div class="container py-12 md:py-16">
	<nav class="flex items-center gap-2 text-sm text-ink-500 mb-6" aria-label="breadcrumb">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-brand-700"><?php esc_html_e( 'Ana Sayfa', 'dr-alper-uslu' ); ?></a>
		<span aria-hidden="true">/</span><span class="text-ink-700"><?php the_title(); ?></span>
	</nav>
	<div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
		<div class="reveal">
			<span class="kicker mb-3"><?php esc_html_e( 'Tanışalım', 'dr-alper-uslu' ); ?></span>
			<h1 class="font-display font-black text-ink-900 text-[clamp(2.2rem,5vw,3.8rem)] leading-[1.02] tracking-[-0.02em] mt-2">Op. Dr. Alper<br><span class="italic font-bold">Burak Uslu</span></h1>
			<p class="text-lead text-ink-700 mt-4"><?php esc_html_e( 'Plastik, Rekonstrüktif ve Estetik Cerrahi', 'dr-alper-uslu' ); ?> · M.D, FEBOPRAS</p>
			<div class="grid grid-cols-3 gap-4 mt-8 max-w-md">
				<?php foreach ( $stats as $s ) : ?>
					<div class="rounded-2xl bg-white/70 ring-1 ring-line p-4 text-center">
						<div class="font-display font-bold text-brand-600 text-2xl sm:text-3xl"><?php echo esc_html( $s[0] . $s[1] ); ?></div>
						<div class="text-ink-500 text-xs mt-1"><?php echo esc_html( $s[2] ); ?></div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="reveal">
			<?php if ( $portrait_id ) : ?>
				<div class="aspect-[4/5] max-w-md ml-auto rounded-[2rem] overflow-hidden ring-1 ring-line shadow-cardHover">
					<?php echo dau_image( $portrait_id, 'dau-hero', 'w-full h-full object-cover', true ); // phpcs:ignore ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div></section>

<section class="section bg-white"><div class="container grid lg:grid-cols-3 gap-12 lg:gap-16">
	<article class="lg:col-span-2 reveal">
		<?php
		// İçerik çoğu zaman uzun bir özgeçmiş dökümü. Narratif giriş görünür,
		// uzun CV/bilimsel faaliyet listesi açılır bölümde: "text duvarı" yerine taranabilir.
		$raw   = apply_filters( 'the_content', get_the_content() );
		$parts = preg_split( '/(?<=<\/p>)/', $raw, -1, PREG_SPLIT_NO_EMPTY );
		if ( ! $parts ) {
			$parts = array( $raw );
		}
		// Baştaki isim/ünvan tekrarı paragraflarını at (hero zaten gösteriyor).
		while ( ! empty( $parts ) ) {
			$t = trim( wp_strip_all_tags( $parts[0] ) );
			if ( $t !== '' && mb_strlen( $t ) < 70 && ( mb_stripos( $t, 'Alper Burak Uslu' ) !== false || mb_stripos( $t, 'Estetik Cerrahi' ) !== false ) ) {
				array_shift( $parts );
			} else {
				break;
			}
		}
		// Narratif giriş, ilk CV başlığında (kısa BÜYÜK HARF satır: DENEYİM/EĞİTİM/KONGRE...) kesilir.
		$intro_max = 6;
		$cut = null;
		foreach ( $parts as $i => $p ) {
			$t = trim( wp_strip_all_tags( $p ) );
			if ( $i > 0 && $t !== '' && mb_strlen( $t ) < 40 && mb_strtoupper( $t, 'UTF-8' ) === $t ) {
				$cut = $i;
				break;
			}
		}
		if ( null === $cut || $cut > $intro_max ) {
			$cut = $intro_max;
		}
		$intro = implode( '', array_slice( $parts, 0, $cut ) );
		$rest  = implode( '', array_slice( $parts, $cut ) );
		?>
		<div class="prose-clinic max-w-none"><?php echo wp_kses_post( $intro ); ?></div>
		<?php if ( trim( wp_strip_all_tags( $rest ) ) !== '' ) : ?>
			<div class="mt-8" x-data="{ open:false }">
				<button @click="open=!open" class="inline-flex items-center gap-2 rounded-full ring-1 ring-line px-5 py-2.5 text-sm font-semibold text-ink-900 hover:bg-cream-50 transition" :aria-expanded="open">
					<span x-show="!open"><?php esc_html_e( 'Detaylı Özgeçmiş & Bilimsel Faaliyetler', 'dr-alper-uslu' ); ?></span>
					<span x-show="open" x-cloak><?php esc_html_e( 'Özgeçmişi Gizle', 'dr-alper-uslu' ); ?></span>
					<span class="transition-transform text-brand-600" :class="open && 'rotate-180'"><?php echo dau_icon( 'chevron' ); // phpcs:ignore ?></span>
				</button>
				<div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="prose-clinic max-w-none mt-6 pt-6 border-t border-line prose-sm"><?php echo wp_kses_post( $rest ); ?></div>
			</div>
		<?php endif; ?>
		<?php wp_link_pages(); ?>
	</article>
	<aside class="lg:col-span-1">
		<div class="sticky top-28 space-y-6 reveal">
			<div class="card p-6">
				<h2 class="font-display text-h3 font-semibold text-ink-900 mb-4"><?php esc_html_e( 'Üyelik & Sertifikalar', 'dr-alper-uslu' ); ?></h2>
				<div class="space-y-1">
				<?php foreach ( $certs as $c ) : ?>
					<div class="flex items-start gap-3 py-2 border-b border-line/60 last:border-0">
						<span class="text-brand-600 mt-0.5 shrink-0"><?php echo dau_icon( 'badge' ); // phpcs:ignore ?></span>
						<div><div class="font-semibold text-ink-900 text-sm"><?php echo esc_html( $c[0] ); ?></div>
						<div class="text-xs text-ink-500 leading-snug"><?php echo esc_html( $c[1] ); ?></div></div>
					</div>
				<?php endforeach; ?>
				</div>
			</div>
			<div class="card p-6 bg-ink-900 text-white">
				<h2 class="font-display text-h3 font-semibold mb-2"><?php esc_html_e( 'Randevu Alın', 'dr-alper-uslu' ); ?></h2>
				<p class="text-white/70 text-sm mb-4"><?php esc_html_e( 'Kişiye özel değerlendirme için bize ulaşın.', 'dr-alper-uslu' ); ?></p>
				<a href="<?php echo esc_url( dau_wa_link() ); ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-full bg-white text-ink-900 pl-5 pr-1.5 py-1.5 text-sm font-semibold hover:bg-cream-50 transition">
					<span><?php esc_html_e( 'Randevu Al', 'dr-alper-uslu' ); ?></span>
					<span class="grid place-content-center w-8 h-8 rounded-full bg-coral-500 text-white"><?php echo dau_icon( 'arrow' ); // phpcs:ignore ?></span>
				</a>
			</div>
		</div>
	</aside>
</div></section>

<?php
get_footer();
