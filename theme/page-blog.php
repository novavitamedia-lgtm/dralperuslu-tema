<?php
/**
 * Sayfa şablonu: Blog (slug: blog) — yazı listesi + şık boş durum.
 * front-page ayarlarına dokunmadan /blog/ altında native postları listeler.
 *
 * @package dr-alper-uslu
 */

get_header();

$paged = max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
$q = new WP_Query( array(
	'post_type'      => 'post',
	'post_status'    => 'publish',
	'posts_per_page' => 9,
	'paged'          => $paged,
) );
?>
<section class="mesh-teal"><div class="container py-12 md:py-16">
	<nav class="flex items-center gap-2 text-sm text-ink-500 mb-4" aria-label="breadcrumb">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-brand-700"><?php esc_html_e( 'Ana Sayfa', 'dr-alper-uslu' ); ?></a>
		<span aria-hidden="true">/</span><span class="text-ink-700"><?php the_title(); ?></span>
	</nav>
	<h1 class="text-hero !text-[clamp(2rem,4vw,3.2rem)] font-bold text-ink-900"><?php the_title(); ?></h1>
	<p class="text-lead text-ink-700 mt-3 max-w-xl"><?php esc_html_e( 'Estetik cerrahi, iyileşme süreçleri ve bakım üzerine bilgilendirici yazılar.', 'dr-alper-uslu' ); ?></p>
</div></section>

<section class="section bg-white"><div class="container">
	<?php if ( $q->have_posts() ) : ?>
		<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
			<?php while ( $q->have_posts() ) : $q->the_post(); ?>
				<a href="<?php the_permalink(); ?>" class="group card card-hover overflow-hidden block">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="aspect-[16/10] overflow-hidden"><?php the_post_thumbnail( 'dau-card', array( 'class' => 'w-full h-full object-cover transition duration-500 group-hover:scale-105', 'loading' => 'lazy' ) ); ?></div>
					<?php endif; ?>
					<div class="p-5">
						<div class="text-xs text-ink-500"><?php echo esc_html( get_the_date() ); ?></div>
						<h2 class="font-display text-h3 font-semibold text-ink-900 mt-1 group-hover:text-brand-700 transition"><?php the_title(); ?></h2>
						<p class="text-sm text-ink-500 mt-2 line-clamp-3"><?php echo esc_html( get_the_excerpt() ); ?></p>
						<span class="inline-flex items-center gap-1 text-sm text-brand-600 font-medium mt-3"><?php esc_html_e( 'Devamını Oku', 'dr-alper-uslu' ); ?><?php echo dau_icon( 'arrow' ); // phpcs:ignore ?></span>
					</div>
				</a>
			<?php endwhile; ?>
		</div>
		<div class="mt-10 flex justify-center gap-2">
			<?php
			echo paginate_links( array( // phpcs:ignore
				'total'     => $q->max_num_pages,
				'current'   => $paged,
				'mid_size'  => 1,
				'prev_text' => esc_html__( 'Önceki', 'dr-alper-uslu' ),
				'next_text' => esc_html__( 'Sonraki', 'dr-alper-uslu' ),
			) );
			?>
		</div>
	<?php else : ?>
		<div class="max-w-lg mx-auto text-center py-12">
			<div class="w-16 h-16 mx-auto rounded-2xl bg-brand-50 text-brand-600 grid place-content-center mb-6">
				<svg viewBox="0 0 24 24" fill="none" class="w-8 h-8" aria-hidden="true"><path d="M4 5a2 2 0 0 1 2-2h8l6 6v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M14 3v6h6M8 13h8M8 17h5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
			</div>
			<h2 class="font-display text-h2 font-semibold text-ink-900"><?php esc_html_e( 'İçerikler Yakında', 'dr-alper-uslu' ); ?></h2>
			<p class="text-ink-500 mt-3 leading-relaxed"><?php esc_html_e( 'Estetik cerrahi, iyileşme süreçleri ve bakım üzerine bilgilendirici yazılar çok yakında burada olacak.', 'dr-alper-uslu' ); ?></p>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-primary mt-7 justify-center inline-flex"><?php esc_html_e( 'Ana Sayfaya Dön', 'dr-alper-uslu' ); ?><?php echo dau_icon( 'arrow' ); // phpcs:ignore ?></a>
		</div>
	<?php endif; wp_reset_postdata(); ?>
</div></section>

<?php
get_footer();
