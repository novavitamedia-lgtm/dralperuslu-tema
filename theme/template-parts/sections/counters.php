<?php
/**
 * Bölüm: Teal Playfair istatistikler + misyon bloğu (embraceyoursmile dili).
 * ACF repeater 'sayaclar' (sayi, son_ek, etiket); yoksa varsayılan.
 *
 * @package dr-alper-uslu
 */

$rows = ( function_exists( 'have_rows' ) && have_rows( 'sayaclar' ) );
$defaults = array(
	array( '12', '', __( 'Yıl Deneyim', 'dr-alper-uslu' ) ),
	array( '2000', '+', __( 'Estetik İşlem', 'dr-alper-uslu' ) ),
	array( '4000', '+', __( 'Ameliyat', 'dr-alper-uslu' ) ),
	array( '35', '+', __( 'Bilimsel Atıf', 'dr-alper-uslu' ) ),
);
$stat = function ( $n, $s, $label ) {
	printf(
		'<div class="reveal"><div class="font-display font-bold text-brand-600 text-[2.6rem] md:text-[3rem] leading-none"><span data-count="%s">0</span>%s</div><div class="text-ink-700 mt-2 text-sm font-medium">%s</div></div>',
		esc_attr( $n ), esc_html( $s ), esc_html( $label )
	);
};
?>
<section class="py-14 md:py-20 bg-white"><div class="container">
	<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8 md:gap-10 items-start">
		<?php
		if ( $rows ) {
			while ( have_rows( 'sayaclar' ) ) { the_row(); $stat( get_sub_field( 'sayi' ), get_sub_field( 'son_ek' ), get_sub_field( 'etiket' ) ); }
		} else {
			foreach ( $defaults as $d ) { $stat( $d[0], $d[1], $d[2] ); }
		}
		?>
		<div class="reveal lg:border-l lg:border-line lg:pl-8">
			<h2 class="font-display text-[1.5rem] font-bold text-ink-900 leading-snug"><?php esc_html_e( 'Estetik Cerrahide Öncü Yaklaşım', 'dr-alper-uslu' ); ?></h2>
			<p class="text-ink-500 mt-3 text-[0.95rem] leading-relaxed"><?php esc_html_e( 'Kapsamlı, sanatsal ve minimal invaziv çözümlerle her hastanın kendine en uygun, doğal sonuca ulaşmasını hedefliyoruz.', 'dr-alper-uslu' ); ?></p>
		</div>
	</div>
</div></section>
