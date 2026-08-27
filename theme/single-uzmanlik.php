<?php
/**
 * Tekil uzmanlık: hero + içerik + SSS akordeon + ilgili + CTA sidebar.
 *
 * @package dr-alper-uslu
 */

get_header();

while ( have_posts() ) :
	the_post();
	$term  = dau_primary_category( get_the_ID() );
	$thumb = get_post_thumbnail_id();
	?>
	<section class="mesh-teal"><div class="container py-10 md:py-14">
		<nav class="text-sm text-ink-500 flex flex-wrap items-center" aria-label="breadcrumb">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-brand-700"><?php esc_html_e( 'Ana Sayfa', 'dr-alper-uslu' ); ?></a>
			<span class="mx-2 text-ink-500/50">/</span>
			<?php if ( $term ) : ?>
				<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="hover:text-brand-700"><?php echo esc_html( $term->name ); ?></a>
				<span class="mx-2 text-ink-500/50">/</span>
			<?php endif; ?>
			<span class="text-ink-900"><?php the_title(); ?></span>
		</nav>
		<div class="grid lg:grid-cols-2 gap-10 items-center mt-6">
			<div class="reveal">
				<?php if ( $term ) : ?><span class="kicker mb-3"><?php echo esc_html( $term->name ); ?></span><?php endif; ?>
				<h1 class="text-hero !text-[clamp(2rem,4vw,3.2rem)] font-bold text-ink-900 mt-2"><?php the_title(); ?></h1>
				<p class="text-lead text-ink-700 mt-4"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
				<div class="flex flex-wrap gap-3 mt-6">
					<a href="<?php echo esc_url( dau_wa_link() ); ?>" target="_blank" rel="noopener" class="btn-primary"><?php esc_html_e( 'Randevu Al', 'dr-alper-uslu' ); ?></a>
					<a href="<?php echo esc_url( dau_tel() ); ?>" class="btn-ghost"><?php echo dau_icon( 'phone' ); // phpcs:ignore ?><?php esc_html_e( 'Hemen Ara', 'dr-alper-uslu' ); ?></a>
				</div>
			</div>
			<div class="reveal"><?php echo $thumb ? dau_image( $thumb, 'dau-hero', 'rounded-xl2 object-cover w-full shadow-card ring-1 ring-line', true ) : ''; // phpcs:ignore ?></div>
		</div>
	</div></section>

	<section class="section bg-white"><div class="container grid lg:grid-cols-3 gap-12">
		<article class="lg:col-span-2 prose-clinic reveal">
			<?php the_content(); ?>

			<?php if ( function_exists( 'have_rows' ) && have_rows( 'sss' ) ) : ?>
				<div class="mt-12 not-prose">
					<h2 class="font-display text-h2 font-bold text-ink-900 mb-4"><?php esc_html_e( 'Sık Sorulan Sorular', 'dr-alper-uslu' ); ?></h2>
					<div class="card p-2 sm:p-6">
						<?php $i = 0; while ( have_rows( 'sss' ) ) : the_row(); $open = 0 === $i ? 'true' : 'false'; ?>
							<div class="border-b border-line" x-data="{ o:<?php echo esc_attr( $open ); ?> }">
								<button @click="o=!o" class="w-full flex items-center justify-between gap-4 py-4 text-left font-medium text-ink-900" :aria-expanded="o">
									<span><?php echo esc_html( get_sub_field( 'soru' ) ); ?></span>
									<span class="text-brand-600 transition-transform" :class="o && 'rotate-180'"><?php echo dau_icon( 'chevron' ); // phpcs:ignore ?></span>
								</button>
								<div x-show="o" x-collapse><div class="pb-4 text-ink-700"><?php echo wp_kses_post( wpautop( get_sub_field( 'cevap' ) ) ); ?></div></div>
							</div>
						<?php $i++; endwhile; ?>
					</div>
				</div>
			<?php endif; ?>
		</article>

		<aside class="lg:col-span-1">
			<div class="sticky top-28 card p-6 reveal">
				<h3 class="font-display text-h3 font-semibold text-ink-900 mb-4"><?php esc_html_e( 'Randevu Al', 'dr-alper-uslu' ); ?></h3>
				<p class="text-sm text-ink-500 mb-4"><?php esc_html_e( 'Sorularınızı yanıtlayalım, size en uygun yaklaşımı birlikte belirleyelim.', 'dr-alper-uslu' ); ?></p>
				<a href="<?php echo esc_url( dau_wa_link() ); ?>" target="_blank" rel="noopener" class="btn-primary w-full mb-2"><?php echo dau_icon( 'wa' ); // phpcs:ignore ?>WhatsApp</a>
				<a href="<?php echo esc_url( dau_tel() ); ?>" class="btn-ghost w-full"><?php echo dau_icon( 'phone' ); // phpcs:ignore ?><?php echo esc_html( dau_opt( 'telefon' ) ); ?></a>
				<div class="mt-5 pt-5 border-t border-line text-sm text-ink-500 flex gap-2"><?php echo dau_icon( 'map' ); // phpcs:ignore ?><?php echo esc_html( dau_opt( 'adres' ) ); ?></div>
			</div>
		</aside>
	</div></section>

	<?php
	$related = dau_related_uzmanlik( get_the_ID(), 3 );
	if ( $related->have_posts() ) : ?>
		<section class="section bg-cream-50"><div class="container">
			<h2 class="section-title mb-8 reveal"><?php esc_html_e( 'İlgili Uzmanlıklar', 'dr-alper-uslu' ); ?></h2>
			<div class="grid sm:grid-cols-3 gap-6 reveal">
				<?php while ( $related->have_posts() ) : $related->the_post(); echo dau_uzmanlik_card( get_the_ID() ); endwhile; wp_reset_postdata(); // phpcs:ignore ?>
			</div>
		</div></section>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/sections/cta' ); ?>
	<?php
endwhile;

get_footer();
