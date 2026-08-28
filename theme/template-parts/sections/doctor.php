<?php
/**
 * Bölüm: Doktor (embrace "Meet Our..." — açık editorial, büyük portre + coral buton).
 * @package dr-alper-uslu
 */
$img = dau_sub( 'gorsel' ); $img = $img ? ( is_array($img)?$img['ID']:$img ) : 0;
if ( ! $img ) { $ab = get_page_by_path( 'hakkimda' ); $img = $ab ? get_post_thumbnail_id( $ab->ID ) : 0; }
$counters = array( array('12','','Yıl Deneyim'), array('2000','+','Estetik İşlem'), array('4000','+','Ameliyat') );
$about_link = get_page_by_path( 'hakkimda' );
?>
<section class="section bg-white overflow-hidden"><div class="container grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
	<div class="reveal max-w-md">
		<?php if ( $img ) : ?><div class="aspect-[4/5] rounded-[2rem] overflow-hidden ring-1 ring-line shadow-cardHover"><?php echo dau_image( $img, 'dau-hero', 'w-full h-full object-cover' ); // phpcs:ignore ?></div><?php endif; ?>
	</div>
	<div class="reveal">
		<span class="kicker mb-4"><?php esc_html_e( 'Uzman', 'dr-alper-uslu' ); ?></span>
		<h2 class="section-title mt-3">Op. Dr. Alper<br><span class="italic">Burak Uslu</span></h2>
		<p class="text-ink-500 text-lg mt-3"><?php esc_html_e( 'Plastik, Rekonstrüktif ve Estetik Cerrahi', 'dr-alper-uslu' ); ?> · M.D, FEBOPRAS</p>
		<div class="grid grid-cols-3 gap-6 mt-8 py-6 border-y border-line">
			<?php foreach ( $counters as $c ) printf('<div><div class="font-display font-bold text-brand-600 text-3xl">%s%s</div><div class="text-ink-500 text-sm mt-1">%s</div></div>', esc_html($c[0]), esc_html($c[1]), esc_html($c[2])); ?>
		</div>
		<a href="<?php echo esc_url( $about_link ? get_permalink($about_link) : home_url('/') ); ?>" class="btn-primary mt-8"><?php esc_html_e( 'Sertifika & Üyelikler', 'dr-alper-uslu' ); ?></a>
	</div>
</div></section>
