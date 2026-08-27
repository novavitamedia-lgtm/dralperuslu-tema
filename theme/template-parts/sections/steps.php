<?php
/**
 * Bölüm: 01/02/03 süreç adımları (ACF repeater: adimlar[numara, baslik, aciklama]).
 *
 * @package dr-alper-uslu
 */

$rows = ( function_exists( 'have_rows' ) && have_rows( 'adimlar' ) );
$defaults = array(
	array( '01', __( 'Konsültasyon', 'dr-alper-uslu' ), __( 'Beklentileriniz dinlenir, yüz/vücut analizi yapılır ve seçenekler açıkça anlatılır.', 'dr-alper-uslu' ) ),
	array( '02', __( 'Kişisel Planlama', 'dr-alper-uslu' ), __( 'Anatominize ve hedeflerinize uygun, gerçekçi ve kişiye özel bir plan hazırlanır.', 'dr-alper-uslu' ) ),
	array( '03', __( 'Operasyon & Takip', 'dr-alper-uslu' ), __( 'İşlem uygulanır; iyileşme sürecinde düzenli kontrollerle yanınızda olunur.', 'dr-alper-uslu' ) ),
);
?>
<section class="section bg-cream-50"><div class="container">
	<div class="text-center max-w-2xl mx-auto mb-12 reveal">
		<span class="kicker justify-center mb-3"><?php esc_html_e( 'Süreç', 'dr-alper-uslu' ); ?></span>
		<h2 class="section-title mt-3"><?php esc_html_e( 'Nasıl İlerliyoruz?', 'dr-alper-uslu' ); ?></h2>
	</div>
	<div class="grid md:grid-cols-3 gap-10">
		<?php
		$render = function ( $n, $t, $d ) {
			printf( '<div class="reveal relative"><div class="font-display text-6xl font-bold text-brand-100">%s</div><h3 class="font-display text-h3 font-semibold text-ink-900 mt-2">%s</h3><p class="text-ink-500 mt-2 text-[0.95rem]">%s</p></div>',
				esc_html( $n ), esc_html( $t ), esc_html( $d ) );
		};
		if ( $rows ) {
			while ( have_rows( 'adimlar' ) ) {
				the_row();
				$render( get_sub_field( 'numara' ), get_sub_field( 'baslik' ), get_sub_field( 'aciklama' ) );
			}
		} else {
			foreach ( $defaults as $d ) {
				$render( $d[0], $d[1], $d[2] );
			}
		}
		?>
	</div>
</div></section>
