<?php
/**
 * Sayfa: ACF Flexible Content varsa bölümler, yoksa içerik (prose).
 *
 * @package dr-alper-uslu
 */

get_header();

while ( have_posts() ) :
	the_post();

	if ( function_exists( 'have_rows' ) && have_rows( 'bolumler' ) ) {
		dau_render_flexible( 'bolumler' );
	} else {
		?>
		<section class="mesh-teal"><div class="container py-12 md:py-16">
			<h1 class="text-hero !text-[clamp(2rem,4vw,3.2rem)] font-bold text-ink-900"><?php the_title(); ?></h1>
		</div></section>
		<section class="section bg-white"><div class="container">
			<div class="prose-clinic mx-auto"><?php the_content(); ?></div>
			<?php
			if ( has_post_thumbnail() ) :
				// galeri varsa native gallery bloklarıyla gösterilir.
			endif;
			wp_link_pages();
			?>
		</div></section>
		<?php
	}

endwhile;

get_footer();
