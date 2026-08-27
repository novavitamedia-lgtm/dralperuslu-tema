<?php
/**
 * Bölüm: Metin / görsel split (ACF: baslik, metin, gorsel, buton).
 * Varsayılan: "Hakkımda" sayfası özeti + öne çıkan görsel.
 *
 * @package dr-alper-uslu
 */

$title = dau_sub( 'baslik' );
$body  = dau_sub( 'metin' );
$img   = dau_sub( 'gorsel' );

if ( ! $title || ! $body ) {
	$about = get_page_by_path( 'hakkimda' );
	if ( ! $about ) {
		$q = get_posts( array( 'post_type' => 'page', 'posts_per_page' => 1, 'orderby' => 'menu_order', 's' => 'Hakkımda' ) );
		$about = $q ? $q[0] : null;
	}
	if ( $about ) {
		$title = $title ? $title : __( 'Hakkımda', 'dr-alper-uslu' );
		$excerpt = wp_trim_words( wp_strip_all_tags( $about->post_content ), 90 );
		$body = $body ? $body : wpautop( esc_html( $excerpt ) );
		if ( ! $img ) {
			$img = get_post_thumbnail_id( $about->ID );
		}
	}
}
if ( ! $title ) {
	return;
}
?>
<section class="section bg-cream-50"><div class="container grid lg:grid-cols-2 gap-12 items-center">
	<div class="reveal order-2 lg:order-1">
		<?php echo $img ? dau_image( is_array( $img ) ? $img['ID'] : $img, 'dau-hero', 'rounded-xl2 object-cover w-full shadow-card ring-1 ring-line' ) : ''; // phpcs:ignore ?>
	</div>
	<div class="reveal order-1 lg:order-2">
		<span class="kicker mb-4"><?php esc_html_e( 'Tanışalım', 'dr-alper-uslu' ); ?></span>
		<h2 class="section-title mt-3 mb-5"><?php echo esc_html( $title ); ?></h2>
		<div class="prose-clinic"><?php echo wp_kses_post( $body ); ?></div>
		<?php
		$about_link = get_page_by_path( 'hakkimda' );
		if ( $about_link ) {
			printf( '<a href="%s" class="btn-ghost mt-6">%s%s</a>', esc_url( get_permalink( $about_link ) ), esc_html__( 'Hakkımda Daha Fazlası', 'dr-alper-uslu' ), dau_icon( 'arrow' ) );
		}
		?>
	</div>
</div></section>
