<?php
/**
 * Sayfa şablonu: Başarılar — üyelik rozetleri + çerçeveli sertifika galerisi + lightbox.
 * Sertifika görselleri sayfa içeriğinden (the_content) çıkarılır; generic düz döküm yerine tasarımlı.
 *
 * @package dr-alper-uslu
 */

get_header();
the_post();

// İçerikteki görselleri çıkar (WP galeri / img blokları)
$rendered = apply_filters( 'the_content', get_the_content() );
preg_match_all( '/<img[^>]+src=["\\\']([^"\\\']+)["\\\'][^>]*>/i', $rendered, $m );
$images = array_values( array_unique( $m[1] ) );
// Görselleri çıkarınca kalan düz metin (varsa intro olarak)
$intro_txt = trim( wp_strip_all_tags( preg_replace( '/<img[^>]*>/i', '', $rendered ) ) );

$memberships = array(
	array( 'ISAPS', __( 'International Society of Aesthetic Plastic Surgery', 'dr-alper-uslu' ) ),
	array( 'ASPS', __( 'American Society of Plastic Surgeons', 'dr-alper-uslu' ) ),
	array( 'EBOPRAS', __( 'European Board of Plastic, Reconstructive and Aesthetic Surgery', 'dr-alper-uslu' ) ),
	array( 'TPRECD', __( 'Türk Plastik Rekonstrüktif ve Estetik Cerrahi Derneği', 'dr-alper-uslu' ) ),
	array( 'UEMS', __( 'Union Européenne des Médecins Spécialistes', 'dr-alper-uslu' ) ),
);
?>
<section class="mesh-teal"><div class="container py-12 md:py-16">
	<nav class="flex items-center gap-2 text-sm text-ink-500 mb-4" aria-label="breadcrumb">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-brand-700"><?php esc_html_e( 'Ana Sayfa', 'dr-alper-uslu' ); ?></a><span aria-hidden="true">/</span><span class="text-ink-700"><?php the_title(); ?></span>
	</nav>
	<h1 class="text-hero !text-[clamp(2rem,4vw,3.2rem)] font-bold text-ink-900"><?php the_title(); ?></h1>
	<p class="text-lead text-ink-700 mt-3 max-w-2xl"><?php esc_html_e( 'Uluslararası üyelikler, sertifikalar ve bilimsel faaliyetler.', 'dr-alper-uslu' ); ?></p>
</div></section>

<!-- Üyelik rozetleri -->
<section class="section bg-cream-50"><div class="container">
	<span class="kicker mb-4"><?php esc_html_e( 'Uluslararası Üyelikler', 'dr-alper-uslu' ); ?></span>
	<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mt-4">
		<?php foreach ( $memberships as $mb ) : ?>
		<div class="reveal card p-5 text-center">
			<div class="w-12 h-12 mx-auto grid place-content-center rounded-full bg-brand-50 text-brand-600 mb-3"><?php echo dau_icon( 'badge' ); // phpcs:ignore ?></div>
			<div class="font-display font-bold text-ink-900"><?php echo esc_html( $mb[0] ); ?></div>
			<div class="text-[0.7rem] text-ink-500 mt-1.5 leading-snug"><?php echo esc_html( $mb[1] ); ?></div>
		</div>
		<?php endforeach; ?>
	</div>
</div></section>

<!-- Sertifika galerisi + lightbox -->
<?php if ( $images ) : ?>
<section class="section bg-white" x-data="{ open:false, src:'' }">
	<div class="container">
		<span class="kicker mb-4"><?php esc_html_e( 'Sertifika ve Belgeler', 'dr-alper-uslu' ); ?></span>
		<h2 class="section-title mt-3 mb-8"><?php esc_html_e( 'Kongre, Sertifika ve Bilimsel Faaliyetler', 'dr-alper-uslu' ); ?></h2>
		<div class="grid grid-cols-2 md:grid-cols-3 gap-5">
			<?php foreach ( $images as $src ) : ?>
			<button type="button" @click="open=true; src='<?php echo esc_url( $src ); ?>'" class="group card card-hover overflow-hidden p-3 bg-white reveal">
				<div class="aspect-[4/5] overflow-hidden rounded-lg bg-cream-50 grid place-content-center">
					<img src="<?php echo esc_url( $src ); ?>" alt="<?php echo esc_attr__( 'Sertifika · Op. Dr. Alper Burak Uslu', 'dr-alper-uslu' ); ?>" loading="lazy" class="w-full h-full object-contain transition duration-500 group-hover:scale-[1.03]">
				</div>
				<div class="flex items-center justify-center gap-1.5 text-sm text-brand-600 font-medium mt-3"><?php esc_html_e( 'Büyüt', 'dr-alper-uslu' ); ?><?php echo dau_icon( 'arrow' ); // phpcs:ignore ?></div>
			</button>
			<?php endforeach; ?>
		</div>
	</div>
	<!-- Lightbox -->
	<div x-show="open" x-cloak x-transition.opacity @click="open=false" @keydown.escape.window="open=false" class="fixed inset-0 z-[60] bg-ink-900/85 backdrop-blur grid place-content-center p-4 sm:p-10">
		<img :src="src" alt="" class="max-w-[92vw] max-h-[86vh] object-contain rounded-xl shadow-2xl ring-1 ring-white/10">
		<button @click="open=false" class="absolute top-4 right-4 sm:top-6 sm:right-6 w-11 h-11 grid place-content-center rounded-full bg-white/10 text-white hover:bg-white/20 transition" aria-label="<?php esc_attr_e( 'Kapat', 'dr-alper-uslu' ); ?>"><?php echo dau_icon( 'close' ); // phpcs:ignore ?></button>
	</div>
</section>
<?php elseif ( $intro_txt ) : ?>
<section class="section bg-white"><div class="container"><div class="prose-clinic mx-auto"><?php the_content(); ?></div></div></section>
<?php endif; ?>

<?php
get_footer();
