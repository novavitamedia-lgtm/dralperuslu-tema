<?php
/**
 * Bölüm: "What Sets Us Apart" — üyelik/sertifika rozetleri
 * (ACF repeater: rozetler[logo, isim, aciklama]; yoksa varsayılan set).
 *
 * @package dr-alper-uslu
 */

$rows = ( function_exists( 'have_rows' ) && have_rows( 'rozetler' ) );
$defaults = array(
	array( 'ISAPS', 'International Society of Aesthetic Plastic Surgery' ),
	array( 'ASPS', 'American Society of Plastic Surgeons' ),
	array( 'EBOPRAS', 'European Board of Plastic, Reconstructive and Aesthetic Surgery' ),
	array( 'TPRECD', 'Türk Plastik Rekonstrüktif ve Estetik Cerrahi Derneği' ),
	array( 'UEMS', 'Union Européenne des Médecins Spécialistes' ),
);
?>
<section class="section bg-white"><div class="container">
	<div class="text-center max-w-2xl mx-auto mb-12 reveal">
		<span class="kicker justify-center mb-3"><?php esc_html_e( 'Neden Op. Dr. Alper Burak Uslu', 'dr-alper-uslu' ); ?></span>
		<h2 class="section-title mt-3"><?php esc_html_e( 'Uluslararası Üyelik ve Sertifikalar', 'dr-alper-uslu' ); ?></h2>
		<p class="text-ink-500 mt-4"><?php esc_html_e( 'Uluslararası ve ulusal plastik cerrahi kuruluşlarının aktif üyesi.', 'dr-alper-uslu' ); ?></p>
	</div>
	<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-5">
		<?php
		$render = function ( $code, $name, $logo = 0 ) {
			echo '<div class="reveal card p-6 text-center card-hover">';
			if ( $logo ) {
				echo '<div class="h-14 flex items-center justify-center mb-4">' . dau_image( $logo, 'medium', 'max-h-14 w-auto' ) . '</div>'; // phpcs:ignore
			} else {
				echo '<div class="w-14 h-14 mx-auto grid place-content-center rounded-full bg-brand-50 text-brand-600 mb-4">' . dau_icon( 'badge' ) . '</div>'; // phpcs:ignore
			}
			printf( '<div class="font-display font-bold text-ink-900 text-lg">%s</div><div class="text-xs text-ink-500 mt-2 leading-snug">%s</div></div>', esc_html( $code ), esc_html( $name ) );
		};
		if ( $rows ) {
			while ( have_rows( 'rozetler' ) ) {
				the_row();
				$logo = get_sub_field( 'logo' );
				$render( get_sub_field( 'isim' ), get_sub_field( 'aciklama' ), $logo ? ( is_array( $logo ) ? $logo['ID'] : $logo ) : 0 );
			}
		} else {
			foreach ( $defaults as $d ) {
				$render( $d[0], $d[1] );
			}
		}
		?>
	</div>
</div></section>
