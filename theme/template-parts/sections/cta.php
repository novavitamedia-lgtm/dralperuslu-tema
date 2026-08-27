<?php
/**
 * Bölüm: CTA şeridi (ACF: baslik, metin; yoksa varsayılan).
 *
 * @package dr-alper-uslu
 */

$title = dau_sub( 'baslik' ) ?: __( 'Ücretsiz Ön Görüşme İçin Bize Ulaşın', 'dr-alper-uslu' );
$desc  = dau_sub( 'metin' ) ?: __( 'Sorularınızı yanıtlayalım, size en uygun yaklaşımı birlikte belirleyelim.', 'dr-alper-uslu' );
?>
<section class="py-16 md:py-20 bg-brand-600 text-white"><div class="container flex flex-col lg:flex-row items-center justify-between gap-8 text-center lg:text-left">
	<div class="reveal">
		<h2 class="font-display text-h2 font-bold"><?php echo esc_html( $title ); ?></h2>
		<p class="text-white/80 mt-3 max-w-xl"><?php echo esc_html( $desc ); ?></p>
	</div>
	<div class="flex flex-wrap gap-3 justify-center reveal">
		<a href="<?php echo esc_url( dau_tel() ); ?>" class="btn bg-white text-brand-700 hover:bg-cream-50"><?php echo dau_icon( 'phone' ); // phpcs:ignore ?><?php esc_html_e( 'Hemen Ara', 'dr-alper-uslu' ); ?></a>
		<a href="<?php echo esc_url( dau_wa_link() ); ?>" target="_blank" rel="noopener" class="btn bg-[#25D366] text-white hover:opacity-90"><?php echo dau_icon( 'wa' ); // phpcs:ignore ?>WhatsApp</a>
	</div>
</div></section>
