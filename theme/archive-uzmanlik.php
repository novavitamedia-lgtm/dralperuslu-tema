<?php
/**
 * Uzmanlık arşivi: kategoriye göre gruplu grid.
 *
 * @package dr-alper-uslu
 */

get_header();
?>
<section class="mesh-teal"><div class="container py-12 md:py-16">
	<nav class="text-sm text-ink-500" aria-label="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-brand-700"><?php esc_html_e( 'Ana Sayfa', 'dr-alper-uslu' ); ?></a><span class="mx-2 text-ink-500/50">/</span><span class="text-ink-900"><?php esc_html_e( 'Uzmanlıklar', 'dr-alper-uslu' ); ?></span></nav>
	<h1 class="text-hero !text-[clamp(2rem,4vw,3.2rem)] font-bold text-ink-900 mt-5"><?php esc_html_e( 'Tüm Uzmanlıklar', 'dr-alper-uslu' ); ?></h1>
</div></section>

<section class="section bg-white"><div class="container">
	<?php
	$terms = get_terms( array( 'taxonomy' => 'uzmanlik-kategori', 'hide_empty' => true ) );
	if ( ! is_wp_error( $terms ) && $terms ) :
		foreach ( $terms as $term ) :
			$q = new WP_Query( array(
				'post_type'      => 'uzmanlik',
				'posts_per_page' => -1,
				'orderby'        => 'menu_order title',
				'order'          => 'ASC',
				'no_found_rows'  => true,
				'tax_query'      => array( array( 'taxonomy' => 'uzmanlik-kategori', 'field' => 'term_id', 'terms' => $term->term_id ) ),
			) );
			if ( ! $q->have_posts() ) {
				continue;
			}
			?>
			<div class="mb-14">
				<div class="flex items-center gap-4 mb-6 reveal">
					<h2 class="section-title"><?php echo esc_html( $term->name ); ?></h2>
					<span class="text-ink-500"><?php echo esc_html( $q->post_count ); ?> <?php esc_html_e( 'işlem', 'dr-alper-uslu' ); ?></span>
				</div>
				<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 reveal">
					<?php while ( $q->have_posts() ) : $q->the_post(); echo dau_uzmanlik_card( get_the_ID() ); endwhile; wp_reset_postdata(); // phpcs:ignore ?>
				</div>
			</div>
			<?php
		endforeach;
	endif;
	?>
</div></section>

<?php
get_footer();
