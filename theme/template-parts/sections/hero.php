<?php
/**
 * Bölüm: Full-bleed hero (ACF: baslik, alt_baslik, arka_gorsel/portre, ctalar).
 *
 * @package dr-alper-uslu
 */

$g      = function_exists( 'get_sub_field' ) && get_row_layout() ? 'get_sub_field' : 'get_field';
$sub    = ( function_exists( 'get_sub_field' ) && did_action( 'acf/init' ) && get_row_layout() ) ? true : false;
$title  = $sub ? get_sub_field( 'baslik' ) : '';
$lead   = $sub ? get_sub_field( 'alt_baslik' ) : '';
$portre = $sub ? get_sub_field( 'portre' ) : 0;

if ( ! $title ) {
	$title = get_bloginfo( 'name' );
}
if ( ! $lead ) {
	$lead = __( 'Estetik cerrahide bilimsel yaklaşım, doğal sonuçlar ve kişiye özel planlama.', 'dr-alper-uslu' );
}
?>
<section class="mesh-teal relative overflow-hidden">
	<div class="absolute top-24 -right-24 w-96 h-96 rounded-full bg-brand-400/10 blur-3xl animate-floaty" aria-hidden="true"></div>
	<div class="container grid lg:grid-cols-2 gap-12 items-center py-14 md:py-24">
		<div class="reveal">
			<span class="kicker mb-5"><?php esc_html_e( 'Plastik, Rekonstrüktif ve Estetik Cerrahi', 'dr-alper-uslu' ); ?></span>
			<h1 class="text-hero font-bold text-ink-900 mt-4"><?php echo esc_html( $title ); ?></h1>
			<p class="text-lead text-ink-700 mt-6 max-w-xl"><?php echo esc_html( $lead ); ?></p>
			<div class="flex flex-wrap gap-3 mt-8">
				<a href="<?php echo esc_url( dau_wa_link() ); ?>" target="_blank" rel="noopener" class="btn-primary"><?php esc_html_e( 'Randevu Al', 'dr-alper-uslu' ); ?><?php echo dau_icon( 'arrow' ); // phpcs:ignore ?></a>
				<a href="<?php echo esc_url( get_post_type_archive_link( 'uzmanlik' ) ); ?>" class="btn-ghost"><?php esc_html_e( 'Tüm Uzmanlıklar', 'dr-alper-uslu' ); ?></a>
			</div>
		</div>
		<?php if ( $portre ) : ?>
			<div class="relative reveal">
				<div class="absolute -inset-4 bg-brand-500/10 rounded-[2rem] blur-2xl"></div>
				<?php echo dau_image( $portre, 'dau-hero', 'relative w-full max-w-md mx-auto rounded-[2rem] object-cover shadow-cardHover ring-1 ring-white/60', true ); // phpcs:ignore ?>
			</div>
		<?php endif; ?>
	</div>
</section>
