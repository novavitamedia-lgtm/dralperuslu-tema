<?php
/**
 * Bölüm: What Sets Us Apart (embrace — sol metin+buton, sağ rozet ızgarası).
 * @package dr-alper-uslu
 */
$rows = ( function_exists('have_rows') && have_rows('rozetler') );
$defaults = array(
	array('ISAPS','International Society of Aesthetic Plastic Surgery'),
	array('ASPS','American Society of Plastic Surgeons'),
	array('EBOPRAS','European Board of Plastic, Reconstructive and Aesthetic Surgery'),
	array('TPRECD','Türk Plastik Rekonstrüktif ve Estetik Cerrahi Derneği'),
	array('UEMS','Union Européenne des Médecins Spécialistes'),
);
$badge = function ( $code, $name ) {
	printf('<div class="reveal card p-5 text-center"><div class="w-12 h-12 mx-auto grid place-content-center rounded-full bg-brand-50 text-brand-600 mb-3">%s</div><div class="font-display font-bold text-ink-900 text-lg">%s</div><div class="text-[0.7rem] text-ink-500 mt-1.5 leading-snug">%s</div></div>', dau_icon('badge'), esc_html($code), esc_html($name));
};
?>
<section class="section bg-cream-50 overflow-hidden"><div class="container grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
	<div class="reveal">
		<span class="kicker mb-4"><?php esc_html_e( 'Neden Op. Dr. Alper Burak Uslu', 'dr-alper-uslu' ); ?></span>
		<h2 class="section-title mt-3"><?php esc_html_e( 'Uluslararası Üyelik ve Sertifikalar', 'dr-alper-uslu' ); ?></h2>
		<p class="text-ink-500 mt-4 text-lg leading-relaxed"><?php esc_html_e( 'Uluslararası ve ulusal plastik cerrahi kuruluşlarının aktif üyesi.', 'dr-alper-uslu' ); ?></p>
		<a href="<?php echo esc_url( dau_wa_link() ); ?>" target="_blank" rel="noopener" class="btn-primary mt-7"><?php esc_html_e( 'Randevu Al', 'dr-alper-uslu' ); ?></a>
	</div>
	<div class="grid grid-cols-2 sm:grid-cols-3 gap-4 reveal">
		<?php if ( $rows ) { while ( have_rows('rozetler') ) { the_row(); $badge( get_sub_field('isim'), get_sub_field('aciklama') ); } } else { foreach ( $defaults as $d ) { $badge( $d[0], $d[1] ); } } ?>
	</div>
</div></section>
