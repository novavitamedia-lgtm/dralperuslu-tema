<?php
/**
 * Bölüm: "Yaklaşımımız" değerler (ACF repeater: degerler[baslik, aciklama]; yoksa varsayılan).
 * Uydurma yorum yerine doktorun yaklaşımını yansıtan dürüst içerik.
 *
 * @package dr-alper-uslu
 */

$rows = ( function_exists( 'have_rows' ) && have_rows( 'degerler' ) );
$defaults = array(
	array( __( 'Bilimsel Yaklaşım', 'dr-alper-uslu' ), __( 'Güncel literatür ve kanıta dayalı yöntemlerle, sürekli güncellenen bir cerrahi anlayış.', 'dr-alper-uslu' ) ),
	array( __( 'Doğal Sonuçlar', 'dr-alper-uslu' ), __( 'Abartıdan uzak, yüz ve vücut bütünlüğüne saygılı, doğal görünen sonuçlar.', 'dr-alper-uslu' ) ),
	array( __( 'Kişiye Özel Plan', 'dr-alper-uslu' ), __( 'Her hastanın anatomisi ve beklentisine göre gerçekçi, bireysel planlama.', 'dr-alper-uslu' ) ),
	array( __( 'Etik ve Güven', 'dr-alper-uslu' ), __( 'Şeffaf bilgilendirme, gerçekçi beklenti yönetimi ve hasta güvenliği önceliği.', 'dr-alper-uslu' ) ),
);
$svgs = array(
	'<svg viewBox="0 0 24 24" fill="none" class="w-6 h-6"><path d="M12 3l2.5 5 5.5.8-4 3.9.9 5.5L12 21l-4.9 2.6.9-5.5-4-3.9 5.5-.8L12 3Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>',
	'<svg viewBox="0 0 24 24" fill="none" class="w-6 h-6"><path d="M4 12a8 8 0 018-8m8 8a8 8 0 01-8 8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.6"/></svg>',
	'<svg viewBox="0 0 24 24" fill="none" class="w-6 h-6"><path d="M12 3v18M5 8l7-5 7 5M5 8v10l7 3 7-3V8" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>',
	'<svg viewBox="0 0 24 24" fill="none" class="w-6 h-6"><path d="M12 3l7 3v5c0 4.5-3 8-7 10-4-2-7-5.5-7-10V6l7-3Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="m9 12 2 2 4-4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>',
);
?>
<section class="section bg-white"><div class="container">
	<div class="text-center max-w-2xl mx-auto mb-12 reveal">
		<span class="kicker justify-center mb-3"><?php esc_html_e( 'Yaklaşımımız', 'dr-alper-uslu' ); ?></span>
		<h2 class="section-title mt-3"><?php esc_html_e( 'Sizi Neyin Beklediğini Bilerek', 'dr-alper-uslu' ); ?></h2>
	</div>
	<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
		<?php
		$i = 0;
		$render = function ( $title, $desc, $icon ) {
			printf(
				'<div class="reveal card p-6 card-hover"><div class="w-12 h-12 rounded-xl2 bg-brand-50 text-brand-600 grid place-content-center mb-4">%s</div><h3 class="font-display text-h3 font-semibold text-ink-900">%s</h3><p class="text-ink-500 mt-2 text-[0.95rem]">%s</p></div>',
				$icon, esc_html( $title ), esc_html( $desc )
			);
		};
		if ( $rows ) {
			while ( have_rows( 'degerler' ) ) {
				the_row();
				$render( get_sub_field( 'baslik' ), get_sub_field( 'aciklama' ), $svgs[ $i % 4 ] );
				$i++;
			}
		} else {
			foreach ( $defaults as $d ) {
				$render( $d[0], $d[1], $svgs[ $i % 4 ] );
				$i++;
			}
		}
		?>
	</div>
</div></section>
