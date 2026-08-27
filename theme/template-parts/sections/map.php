<?php
/**
 * Bölüm: Tasarlanmış harita bloğu (dış bağımlılık yok; tıklayınca Google Maps).
 *
 * @package dr-alper-uslu
 */
?>
<section class="pb-16 md:pb-24 bg-white"><div class="container">
	<a href="<?php echo esc_url( dau_opt( 'maps' ) ); ?>" target="_blank" rel="noopener"
		class="reveal group block relative rounded-xl2 overflow-hidden ring-1 ring-line shadow-card h-[360px] mesh-teal"
		aria-label="<?php echo esc_attr( __( 'Yol Tarifi', 'dr-alper-uslu' ) . ' — ' . dau_opt( 'adres' ) ); ?>">
		<svg class="absolute inset-0 w-full h-full opacity-[0.5]" preserveAspectRatio="xMidYMid slice" viewBox="0 0 400 300" aria-hidden="true">
			<defs><pattern id="dau-map-g" width="28" height="28" patternUnits="userSpaceOnUse"><path d="M28 0H0V28" fill="none" stroke="#12857D" stroke-width="0.5" stroke-opacity="0.18"/></pattern></defs>
			<rect width="400" height="300" fill="url(#dau-map-g)"/>
			<path d="M-20 210 Q140 170 210 200 T430 150" fill="none" stroke="#12857D" stroke-width="7" stroke-opacity="0.14"/>
			<path d="M60 -20 Q90 120 180 180 T300 320" fill="none" stroke="#12857D" stroke-width="5" stroke-opacity="0.12"/>
			<path d="M-20 90 Q160 70 260 110 T430 90" fill="none" stroke="#12857D" stroke-width="4" stroke-opacity="0.1"/>
		</svg>
		<div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-full flex flex-col items-center">
			<span class="w-14 h-14 rounded-full bg-brand-600 text-white grid place-content-center shadow-cardHover ring-4 ring-white transition group-hover:scale-110"><?php echo dau_icon( 'map' ); // phpcs:ignore ?></span>
			<span class="w-3 h-3 rounded-full bg-brand-700/30 mt-1"></span>
		</div>
		<div class="absolute inset-x-4 bottom-4 sm:inset-x-6 sm:bottom-6 bg-white/95 backdrop-blur rounded-2xl shadow-card p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center gap-3 justify-between">
			<div class="flex items-start gap-3 text-ink-700"><span class="text-brand-600 mt-0.5"><?php echo dau_icon( 'map' ); // phpcs:ignore ?></span><span class="text-sm font-medium"><?php echo esc_html( dau_opt( 'adres' ) ); ?></span></div>
			<span class="btn-primary !py-2.5 shrink-0"><?php esc_html_e( 'Yol Tarifi', 'dr-alper-uslu' ); ?><?php echo dau_icon( 'arrow' ); // phpcs:ignore ?></span>
		</div>
	</a>
</div></section>
