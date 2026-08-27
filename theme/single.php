<?php
/**
 * Tekil blog yazısı.
 *
 * @package dr-alper-uslu
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<section class="mesh-teal"><div class="container py-12 md:py-16 max-w-3xl">
		<nav class="text-sm text-ink-500" aria-label="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-brand-700"><?php esc_html_e( 'Ana Sayfa', 'dr-alper-uslu' ); ?></a><span class="mx-2 text-ink-500/50">/</span><span class="text-ink-900"><?php esc_html_e( 'Blog', 'dr-alper-uslu' ); ?></span></nav>
		<h1 class="font-display text-h2 font-bold text-ink-900 mt-5"><?php the_title(); ?></h1>
		<div class="text-sm text-ink-500 mt-3"><?php echo esc_html( get_the_date() ); ?> · <?php the_author(); ?></div>
	</div></section>
	<section class="section bg-white"><div class="container max-w-3xl">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="rounded-xl2 overflow-hidden mb-8"><?php the_post_thumbnail( 'dau-hero', array( 'class' => 'w-full object-cover' ) ); ?></div>
		<?php endif; ?>
		<article class="prose-clinic"><?php the_content(); wp_link_pages(); ?></article>
		<?php
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
		?>
	</div></section>
	<?php
endwhile;

get_footer();
