<?php
/**
 * Bölüm: Swiper galeri (ACF gallery: galeri_gorseller; yoksa "Başarılar" sayfası galerisi).
 *
 * @package dr-alper-uslu
 */

$images = dau_sub( 'galeri_gorseller' );
if ( empty( $images ) ) {
	// Varsayılan: "Başarılar" sayfasının ekli görsellerinden.
	$page = get_page_by_path( 'basarilar' );
	if ( $page ) {
		$att = get_attached_media( 'image', $page->ID );
		$images = array();
		foreach ( $att as $a ) {
			$images[] = array( 'ID' => $a->ID, 'alt' => get_post_meta( $a->ID, '_wp_attachment_image_alt', true ) );
		}
	}
}
if ( empty( $images ) ) {
	return;
}
?>
<section class="section bg-cream-50 overflow-hidden"><div class="container">
	<div class="flex items-end justify-between gap-4 mb-10 reveal">
		<div>
			<span class="kicker mb-3"><?php esc_html_e( 'Başarılar', 'dr-alper-uslu' ); ?></span>
			<h2 class="section-title mt-3"><?php esc_html_e( 'Kongre, Sertifika ve Bilimsel Faaliyetler', 'dr-alper-uslu' ); ?></h2>
		</div>
		<div class="hidden sm:flex gap-2">
			<button class="gal-prev w-11 h-11 rounded-full ring-1 ring-line grid place-content-center hover:bg-white" aria-label="<?php esc_attr_e( 'Önceki', 'dr-alper-uslu' ); ?>"><span class="rotate-180"><?php echo dau_icon( 'arrow' ); // phpcs:ignore ?></span></button>
			<button class="gal-next w-11 h-11 rounded-full ring-1 ring-line grid place-content-center hover:bg-white" aria-label="<?php esc_attr_e( 'Sonraki', 'dr-alper-uslu' ); ?>"><?php echo dau_icon( 'arrow' ); // phpcs:ignore ?></button>
		</div>
	</div>
	<div class="swiper reveal" data-swiper="gallery"><div class="swiper-wrapper">
		<?php
		foreach ( $images as $im ) {
			$id = is_array( $im ) ? $im['ID'] : $im;
			echo '<div class="swiper-slide"><div class="aspect-[3/4] rounded-xl2 overflow-hidden ring-1 ring-line shadow-soft">' . dau_image( $id, 'dau-card', 'w-full h-full object-cover' ) . '</div></div>'; // phpcs:ignore
		}
		?>
	</div></div>
</div></section>
