<?php
/**
 * Bölüm: Hizmet kartları grid + Tümünü Gör.
 * Varsayılan: her kategoriden öne çıkan uzmanlıklar (tek WP_Query).
 *
 * @package dr-alper-uslu
 */

$q = new WP_Query( array(
	'post_type'      => 'uzmanlik',
	'posts_per_page' => 8,
	'orderby'        => 'menu_order title',
	'order'          => 'ASC',
	'no_found_rows'  => true,
) );

$terms = get_terms( array( 'taxonomy' => 'uzmanlik-kategori', 'hide_empty' => true ) );
?>
<section class="section bg-white"><div class="container">
	<div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10 reveal">
		<div>
			<span class="kicker mb-3"><?php esc_html_e( 'Uzmanlık Alanları', 'dr-alper-uslu' ); ?></span>
			<h2 class="section-title mt-3"><?php esc_html_e( 'Estetik Cerrahi Uygulamaları', 'dr-alper-uslu' ); ?></h2>
		</div>
		<div class="flex flex-wrap gap-2">
			<?php
			if ( ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					printf( '<a href="%s" class="px-4 py-2 rounded-full text-sm font-medium ring-1 ring-line hover:ring-brand-600 hover:text-brand-700 transition bg-white">%s</a>', esc_url( get_term_link( $term ) ), esc_html( $term->name ) );
				}
			}
			?>
		</div>
	</div>
	<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 reveal">
		<?php
		if ( $q->have_posts() ) {
			while ( $q->have_posts() ) {
				$q->the_post();
				echo dau_uzmanlik_card( get_the_ID() ); // phpcs:ignore
			}
			wp_reset_postdata();
		}
		?>
	</div>
	<div class="text-center mt-10"><a href="<?php echo esc_url( get_post_type_archive_link( 'uzmanlik' ) ); ?>" class="btn-primary"><?php esc_html_e( 'Tüm Uzmanlıkları Gör', 'dr-alper-uslu' ); ?><?php echo dau_icon( 'arrow' ); // phpcs:ignore ?></a></div>
</div></section>
