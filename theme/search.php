<?php
/**
 * Arama sonuçları.
 *
 * @package dr-alper-uslu
 */

get_header();
?>
<section class="mesh-teal"><div class="container py-12 md:py-16">
	<h1 class="font-display text-h2 font-bold text-ink-900"><?php printf( esc_html__( 'Arama: %s', 'dr-alper-uslu' ), '<span class="text-brand-600">' . esc_html( get_search_query() ) . '</span>' ); ?></h1>
	<div class="mt-6 max-w-md"><?php get_search_form(); ?></div>
</div></section>
<section class="section bg-white"><div class="container">
	<?php if ( have_posts() ) : ?>
		<div class="space-y-4">
			<?php while ( have_posts() ) : the_post(); ?>
				<a href="<?php the_permalink(); ?>" class="card p-6 block card-hover">
					<h2 class="font-display text-h3 font-semibold text-ink-900 group-hover:text-brand-700"><?php the_title(); ?></h2>
					<p class="text-sm text-ink-500 mt-2"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 30 ) ); ?></p>
				</a>
			<?php endwhile; ?>
		</div>
		<div class="mt-10"><?php the_posts_pagination( array( 'mid_size' => 1 ) ); ?></div>
	<?php else : ?>
		<p class="text-ink-500 text-center"><?php esc_html_e( 'Sonuç bulunamadı.', 'dr-alper-uslu' ); ?></p>
	<?php endif; ?>
</div></section>
<?php
get_footer();
