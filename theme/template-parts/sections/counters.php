<?php
/**
 * Bölüm: 4 animasyonlu sayaç (ACF repeater: sayaclar[sayi, son_ek, etiket]).
 *
 * @package dr-alper-uslu
 */

$rows = ( function_exists( 'have_rows' ) && have_rows( 'sayaclar' ) ) ? true : false;
$defaults = array(
	array( '12', '', __( 'Yıl Deneyim', 'dr-alper-uslu' ) ),
	array( '2000', '+', __( 'Estetik İşlem', 'dr-alper-uslu' ) ),
	array( '4000', '+', __( 'Ameliyat', 'dr-alper-uslu' ) ),
	array( '35', '+', __( 'Bilimsel Atıf', 'dr-alper-uslu' ) ),
);
?>
<section class="section bg-white"><div class="container">
	<div class="grid grid-cols-2 lg:grid-cols-4 gap-8 md:gap-6 py-4 border-y border-line">
		<?php
		if ( $rows ) {
			while ( have_rows( 'sayaclar' ) ) {
				the_row();
				printf(
					'<div class="reveal text-center"><div class="font-display text-4xl md:text-5xl font-bold text-brand-600"><span data-count="%s">0</span>%s</div><div class="text-ink-500 mt-2 text-sm uppercase tracking-wide">%s</div></div>',
					esc_attr( get_sub_field( 'sayi' ) ), esc_html( get_sub_field( 'son_ek' ) ), esc_html( get_sub_field( 'etiket' ) )
				);
			}
		} else {
			foreach ( $defaults as $d ) {
				printf(
					'<div class="reveal text-center"><div class="font-display text-4xl md:text-5xl font-bold text-brand-600"><span data-count="%s">0</span>%s</div><div class="text-ink-500 mt-2 text-sm uppercase tracking-wide">%s</div></div>',
					esc_attr( $d[0] ), esc_html( $d[1] ), esc_html( $d[2] )
				);
			}
		}
		?>
	</div>
</div></section>
