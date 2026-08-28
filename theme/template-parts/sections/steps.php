<?php
/**
 * Bölüm: What To Expect — numaralı satırlar (teal daire-ok) + yan görsel (embrace dili).
 * @package dr-alper-uslu
 */
$rows = ( function_exists( 'have_rows' ) && have_rows( 'adimlar' ) );
$defaults = array(
	array( '01', __( 'Konsültasyon', 'dr-alper-uslu' ), __( 'Beklentileriniz dinlenir, yüz/vücut analizi yapılır ve seçenekler açıkça anlatılır.', 'dr-alper-uslu' ) ),
	array( '02', __( 'Kişisel Planlama', 'dr-alper-uslu' ), __( 'Anatominize ve hedeflerinize uygun, gerçekçi ve kişiye özel bir plan hazırlanır.', 'dr-alper-uslu' ) ),
	array( '03', __( 'Operasyon & Takip', 'dr-alper-uslu' ), __( 'İşlem uygulanır; iyileşme sürecinde düzenli kontrollerle yanınızda olunur.', 'dr-alper-uslu' ) ),
);
$row = function ( $n, $tt, $dd ) {
	printf(
		'<div class="reveal flex items-start gap-5 py-5 border-b border-line last:border-0"><span class="font-display italic text-brand-600 text-2xl font-bold w-10 shrink-0">%s</span><div class="flex-1"><h3 class="font-display text-[1.35rem] font-bold text-ink-900">%s</h3><p class="text-ink-500 mt-1.5 text-[0.95rem] leading-relaxed">%s</p></div><span class="circle-arrow mt-1">%s</span></div>',
		esc_html( $n ), esc_html( $tt ), esc_html( $dd ), dau_icon( 'arrow' )
	);
};
$about = get_page_by_path( 'hakkimda' );
$img_id = $about ? get_post_thumbnail_id( $about->ID ) : 0;
?>
<section class="section bg-cream-50 overflow-hidden"><div class="container grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
	<div class="reveal order-2 lg:order-1">
		<span class="kicker mb-4"><?php esc_html_e( 'Süreç', 'dr-alper-uslu' ); ?></span>
		<h2 class="section-title mt-3 mb-6"><?php esc_html_e( 'Nasıl İlerliyoruz?', 'dr-alper-uslu' ); ?></h2>
		<div><?php if ( $rows ) { while ( have_rows( 'adimlar' ) ) { the_row(); $row( get_sub_field('numara'), get_sub_field('baslik'), get_sub_field('aciklama') ); } } else { foreach ( $defaults as $d ) { $row( $d[0], $d[1], $d[2] ); } } ?></div>
	</div>
	<div class="reveal order-1 lg:order-2">
		<?php if ( $img_id ) : ?><div class="aspect-[4/5] rounded-[2rem] overflow-hidden ring-1 ring-line shadow-card"><?php echo dau_image( $img_id, 'dau-hero', 'w-full h-full object-cover' ); // phpcs:ignore ?></div><?php endif; ?>
	</div>
</div></section>
