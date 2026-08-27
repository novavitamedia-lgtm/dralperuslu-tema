<?php
/**
 * Genel yedek şablon / blog listesi.
 *
 * @package dr-alper-uslu
 */

get_header();
?>
<section class="mesh-teal"><div class="container py-12 md:py-16">
	<h1 class="text-hero !text-[clamp(2rem,4vw,3.2rem)] font-bold text-ink-900"><?php echo esc_html( is_home() ? __( 'Blog', 'dr-alper-uslu' ) : get_the_archive_title() ); ?></h1>
</div></section>
<section class="section bg-white"><div class="container">
	<?php if ( have_posts() ) : ?>
		<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
			<?php while ( have_posts() ) : the_post(); ?>
				<a href="<?php the_permalink(); ?>" class="group card card-hover overflow-hidden block">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="aspect-[16/10] overflow-hidden"><?php the_post_thumbnail( 'dau-card', array( 'class' => 'w-full h-full object-cover transition duration-500 group-hover:scale-105', 'loading' => 'lazy' ) ); ?></div>
					<?php endif; ?>
					<div class="p-5">
						<div class="text-xs text-ink-500"><?php echo esc_html( get_the_date() ); ?></div>
						<h2 class="font-display text-h3 font-semibold text-ink-900 mt-1 group-hover:text-brand-700"><?php the_title(); ?></h2>
						<p class="text-sm text-ink-500 mt-2 line-clamp-3"><?php echo esc_html( get_the_excerpt() ); ?></p>
					</div>
				</a>
			<?php endwhile; ?>
		</div>
		<div class="mt-10"><?php the_posts_pagination( array( 'mid_size' => 1 ) ); ?></div>
	<?php else : ?>
		<p class="text-ink-500 text-center"><?php esc_html_e( 'Henüz içerik yok.', 'dr-alper-uslu' ); ?></p>
	<?php endif; ?>
</div></section>
<?php
get_footer();
