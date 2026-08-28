<?php
/**
 * Bölüm: Editorial hero (embraceyoursmile dili) — dev Playfair başlık + üstüne binen portre + coral daire-ok CTA.
 * ACF: baslik, alt_baslik, portre. Yoksa varsayılan.
 *
 * @package dr-alper-uslu
 */

$title  = dau_sub( 'baslik' ) ?: get_bloginfo( 'name' );
$lead   = dau_sub( 'alt_baslik' ) ?: __( 'Estetik cerrahide bilimsel yaklaşım, doğal sonuçlar ve kişiye özel planlama.', 'dr-alper-uslu' );
$portre = dau_sub( 'portre' );
$portre = $portre ? ( is_array( $portre ) ? $portre['ID'] : $portre ) : 0;
$name_html = '<h1 class="text-hero font-display font-black text-ink-900 mt-5 tracking-[-0.03em]">Op. Dr. Alper<br><span class="italic font-bold">Burak Uslu</span></h1>';
?>
<section class="relative overflow-hidden bg-white">
	<svg class="absolute inset-0 w-full h-full opacity-[0.5] pointer-events-none" preserveAspectRatio="none" viewBox="0 0 1440 700" aria-hidden="true">
		<path d="M-50 520 Q400 380 760 470 T1500 360" fill="none" stroke="#E3E8E7" stroke-width="1.5"/>
		<path d="M-50 600 Q450 470 820 560 T1500 450" fill="none" stroke="#E3E8E7" stroke-width="1"/>
	</svg>
	<div class="container relative pt-8 pb-14 md:pt-14 md:pb-24">
		<div class="grid lg:grid-cols-12 gap-6 lg:gap-4 items-center">
			<div class="lg:col-span-7 relative z-10 reveal">
				<span class="kicker mb-6"><?php esc_html_e( 'Plastik, Rekonstrüktif ve Estetik Cerrahi', 'dr-alper-uslu' ); ?></span>
				<?php echo $name_html; // phpcs:ignore ?>
			</div>
			<div class="lg:col-span-5 lg:-ml-8 relative z-0 reveal">
				<div class="aspect-[4/5] max-w-md ml-auto rounded-[2rem] overflow-hidden ring-1 ring-line shadow-cardHover">
					<?php echo $portre ? dau_image( $portre, 'dau-hero', 'w-full h-full object-cover', true ) : ''; // phpcs:ignore ?>
				</div>
			</div>
		</div>
		<div class="lg:pl-[54%] mt-8 lg:mt-6 relative z-10 reveal">
			<p class="font-display italic text-[1.5rem] md:text-[1.9rem] text-ink-900 leading-[1.25] max-w-lg"><?php echo esc_html( $lead ); ?></p>
			<div class="flex flex-wrap items-center gap-4 mt-7">
				<a href="<?php echo esc_url( dau_wa_link() ); ?>" target="_blank" rel="noopener" class="btn-primary"><?php esc_html_e( 'Randevu Al', 'dr-alper-uslu' ); ?></a>
				<a href="<?php echo esc_url( get_post_type_archive_link( 'uzmanlik' ) ); ?>" class="text-ink-900 font-semibold underline decoration-brand-500 decoration-2 underline-offset-4 hover:text-brand-700"><?php esc_html_e( 'Tüm Uzmanlıklar', 'dr-alper-uslu' ); ?></a>
			</div>
		</div>
	</div>
</section>
