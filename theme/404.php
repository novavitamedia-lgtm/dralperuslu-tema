<?php
/**
 * 404.
 *
 * @package dr-alper-uslu
 */

get_header();
?>
<section class="mesh-teal min-h-[60vh] grid place-content-center text-center"><div class="container py-20">
	<div class="font-display text-[6rem] font-bold text-brand-200 leading-none">404</div>
	<h1 class="font-display text-h2 font-bold text-ink-900 mt-2"><?php esc_html_e( 'Sayfa bulunamadı', 'dr-alper-uslu' ); ?></h1>
	<p class="text-ink-500 mt-3 max-w-md mx-auto"><?php esc_html_e( 'Aradığınız sayfa taşınmış veya kaldırılmış olabilir.', 'dr-alper-uslu' ); ?></p>
	<div class="flex gap-3 justify-center mt-8">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-primary"><?php esc_html_e( 'Ana Sayfaya Dön', 'dr-alper-uslu' ); ?></a>
		<a href="<?php echo esc_url( get_post_type_archive_link( 'uzmanlik' ) ); ?>" class="btn-ghost"><?php esc_html_e( 'Tüm Uzmanlıklar', 'dr-alper-uslu' ); ?></a>
	</div>
	<div class="mt-8 max-w-md mx-auto"><?php get_search_form(); ?></div>
</div></section>
<?php
get_footer();
