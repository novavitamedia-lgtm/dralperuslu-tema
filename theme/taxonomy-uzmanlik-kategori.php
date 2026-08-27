<?php
/**
 * Kategori arşivi (uzmanlik-kategori).
 *
 * @package dr-alper-uslu
 */

get_header();
$term = get_queried_object();
?>
<section class="mesh-teal"><div class="container py-12 md:py-16">
	<nav class="text-sm text-ink-500 flex flex-wrap items-center" aria-label="breadcrumb">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-brand-700"><?php esc_html_e( 'Ana Sayfa', 'dr-alper-uslu' ); ?></a>
		<span class="mx-2 text-ink-500/50">/</span>
		<a href="<?php echo esc_url( get_post_type_archive_link( 'uzmanlik' ) ); ?>" class="hover:text-brand-700"><?php esc_html_e( 'Uzmanlıklar', 'dr-alper-uslu' ); ?></a>
		<span class="mx-2 text-ink-500/50">/</span>
		<span class="text-ink-900"><?php echo esc_html( $term->name ); ?></span>
	</nav>
	<h1 class="text-hero !text-[clamp(2rem,4vw,3.2rem)] font-bold text-ink-900 mt-5"><?php echo esc_html( $term->name ); ?></h1>
	<?php if ( $term->description ) : ?><p class="text-ink-500 mt-3 max-w-2xl"><?php echo esc_html( $term->description ); ?></p><?php endif; ?>
</div></section>

<section class="section bg-white"><div class="container grid sm:grid-cols-2 lg:grid-cols-3 gap-6 reveal">
	<?php
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			echo dau_uzmanlik_card( get_the_ID() ); // phpcs:ignore
		}
	}
	?>
</div>
<div class="container mt-10"><?php the_posts_pagination( array( 'mid_size' => 1, 'class' => 'flex gap-2 justify-center' ) ); ?></div>
</section>

<?php
get_footer();
