<?php
/**
 * Bölüm: Koyu teal zemin doktor kartı (ACF: gorsel, ad, unvan, kisa_bio).
 *
 * @package dr-alper-uslu
 */

$img = dau_sub( 'gorsel' );
$img = $img ? ( is_array( $img ) ? $img['ID'] : $img ) : 0;
?>
<section class="py-20 md:py-28 bg-brand-700 text-white relative overflow-hidden">
	<div class="absolute -top-20 -right-20 w-96 h-96 rounded-full bg-brand-500/30 blur-3xl" aria-hidden="true"></div>
	<div class="container grid lg:grid-cols-5 gap-10 items-center relative">
		<div class="lg:col-span-2 reveal">
			<?php echo $img ? dau_image( $img, 'dau-hero', 'rounded-xl2 object-cover w-full ring-1 ring-white/20' ) : ''; // phpcs:ignore ?>
		</div>
		<div class="lg:col-span-3 reveal">
			<span class="kicker !text-brand-300 mb-4"><?php esc_html_e( 'Uzman', 'dr-alper-uslu' ); ?></span>
			<h2 class="font-display text-h2 font-bold mt-3"><?php echo esc_html( dau_sub( 'ad' ) ?: get_bloginfo( 'name' ) ); ?></h2>
			<p class="text-white/70 text-lg mt-1"><?php echo esc_html( dau_sub( 'unvan' ) ?: __( 'Plastik, Rekonstrüktif ve Estetik Cerrahi · M.D, FEBOPRAS', 'dr-alper-uslu' ) ); ?></p>
			<a href="<?php echo esc_url( get_page_by_path( 'hakkimda' ) ? get_permalink( get_page_by_path( 'hakkimda' ) ) : home_url( '/' ) ); ?>" class="btn-light mt-8"><?php esc_html_e( 'Sertifika & Üyelikler', 'dr-alper-uslu' ); ?><?php echo dau_icon( 'arrow' ); // phpcs:ignore ?></a>
		</div>
	</div>
</section>
