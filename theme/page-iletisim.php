<?php
/**
 * Sayfa şablonu: İletişim — bilgi kartları + çalışan WhatsApp formu + stilize harita.
 * mail() sunucuda kapalı olduğundan form, alanları WhatsApp mesajına dönüştürür (wa.me).
 *
 * @package dr-alper-uslu
 */

get_header();

$adres = dau_opt( 'adres' );
$saat  = dau_opt( 'calisma_saati' );
$maps  = dau_opt( 'maps' );
$wanum = preg_replace( '/\D/', '', dau_opt( 'whatsapp' ) );
?>
<section class="mesh-teal"><div class="container py-12 md:py-16">
	<nav class="flex items-center gap-2 text-sm text-ink-500 mb-4" aria-label="breadcrumb">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-brand-700"><?php esc_html_e( 'Ana Sayfa', 'dr-alper-uslu' ); ?></a>
		<span aria-hidden="true">/</span>
		<span class="text-ink-700"><?php the_title(); ?></span>
	</nav>
	<h1 class="text-hero !text-[clamp(2rem,4vw,3.2rem)] font-bold text-ink-900"><?php the_title(); ?></h1>
	<p class="text-lead text-ink-700 mt-3 max-w-xl"><?php esc_html_e( 'Randevu ve sorularınız için bize ulaşın. En kısa sürede dönüş yapıyoruz.', 'dr-alper-uslu' ); ?></p>
</div></section>

<section class="section bg-white"><div class="container grid lg:grid-cols-2 gap-12 items-start">
	<!-- Sol: iletişim bilgileri -->
	<div class="reveal space-y-4">
		<?php if ( dau_opt( 'telefon' ) ) : ?>
		<a href="<?php echo esc_attr( dau_tel() ); ?>" class="card card-hover p-5 flex items-center gap-4">
			<div class="w-12 h-12 rounded-full bg-brand-50 text-brand-600 grid place-content-center shrink-0"><?php echo dau_icon( 'phone' ); // phpcs:ignore ?></div>
			<div><div class="text-xs text-ink-500 uppercase tracking-wide"><?php esc_html_e( 'Telefon', 'dr-alper-uslu' ); ?></div>
			<div class="font-semibold text-ink-900"><?php echo esc_html( dau_opt( 'telefon' ) ); ?></div></div>
		</a>
		<?php endif; ?>
		<?php if ( $wanum ) : ?>
		<a href="<?php echo esc_url( dau_wa_link() ); ?>" target="_blank" rel="noopener" class="card card-hover p-5 flex items-center gap-4">
			<div class="w-12 h-12 rounded-full bg-brand-50 text-brand-600 grid place-content-center shrink-0"><?php echo dau_icon( 'wa' ); // phpcs:ignore ?></div>
			<div><div class="text-xs text-ink-500 uppercase tracking-wide">WhatsApp</div>
			<div class="font-semibold text-ink-900"><?php echo esc_html( dau_opt( 'telefon' ) ); ?></div></div>
		</a>
		<?php endif; ?>
		<?php if ( $adres ) : ?>
		<a href="<?php echo esc_url( $maps ); ?>" target="_blank" rel="noopener" class="card card-hover p-5 flex items-center gap-4">
			<div class="w-12 h-12 rounded-full bg-brand-50 text-brand-600 grid place-content-center shrink-0"><?php echo dau_icon( 'map' ); // phpcs:ignore ?></div>
			<div><div class="text-xs text-ink-500 uppercase tracking-wide"><?php esc_html_e( 'Adres', 'dr-alper-uslu' ); ?></div>
			<div class="font-semibold text-ink-900"><?php echo esc_html( $adres ); ?></div></div>
		</a>
		<?php endif; ?>
		<?php if ( $saat ) : ?>
		<div class="card p-5 flex items-center gap-4">
			<div class="w-12 h-12 rounded-full bg-brand-50 text-brand-600 grid place-content-center shrink-0"><?php echo dau_icon( 'clock' ); // phpcs:ignore ?></div>
			<div><div class="text-xs text-ink-500 uppercase tracking-wide"><?php esc_html_e( 'Çalışma Saatleri', 'dr-alper-uslu' ); ?></div>
			<div class="font-semibold text-ink-900"><?php echo esc_html( $saat ); ?></div></div>
		</div>
		<?php endif; ?>
	</div>

	<!-- Sağ: WhatsApp formu -->
	<div class="reveal">
		<div class="card p-6 sm:p-8">
			<h2 class="font-display text-h3 font-semibold text-ink-900 mb-1"><?php esc_html_e( 'Randevu Talebi', 'dr-alper-uslu' ); ?></h2>
			<p class="text-sm text-ink-500 mb-6"><?php esc_html_e( 'Formu doldurun, WhatsApp üzerinden hızlıca dönelim.', 'dr-alper-uslu' ); ?></p>
			<form class="grid gap-4" onsubmit="return dauWa(event)">
				<div class="grid sm:grid-cols-2 gap-4">
					<label class="block"><span class="text-sm font-medium text-ink-700"><?php esc_html_e( 'Ad Soyad', 'dr-alper-uslu' ); ?></span>
						<input name="ad" required class="mt-1 w-full rounded-xl ring-1 ring-line px-4 py-3 focus:ring-brand-500 outline-none" autocomplete="name"></label>
					<label class="block"><span class="text-sm font-medium text-ink-700"><?php esc_html_e( 'Telefon', 'dr-alper-uslu' ); ?></span>
						<input name="tel" type="tel" required class="mt-1 w-full rounded-xl ring-1 ring-line px-4 py-3 focus:ring-brand-500 outline-none" autocomplete="tel"></label>
				</div>
				<label class="block"><span class="text-sm font-medium text-ink-700"><?php esc_html_e( 'İlgilendiğiniz İşlem (opsiyonel)', 'dr-alper-uslu' ); ?></span>
					<input name="islem" class="mt-1 w-full rounded-xl ring-1 ring-line px-4 py-3 focus:ring-brand-500 outline-none"></label>
				<label class="block"><span class="text-sm font-medium text-ink-700"><?php esc_html_e( 'Mesajınız', 'dr-alper-uslu' ); ?></span>
					<textarea name="mesaj" rows="4" class="mt-1 w-full rounded-xl ring-1 ring-line px-4 py-3 focus:ring-brand-500 outline-none"></textarea></label>
				<label class="flex items-start gap-2.5 text-xs text-ink-500">
					<input type="checkbox" required class="mt-0.5 accent-brand-600 w-4 h-4">
					<span><?php esc_html_e( 'Kişisel verilerimin randevu talebim doğrultusunda işlenmesine (KVKK) onay veriyorum.', 'dr-alper-uslu' ); ?></span>
				</label>
				<button type="submit" class="btn-primary justify-center"><?php echo dau_icon( 'wa' ); // phpcs:ignore ?><span><?php esc_html_e( 'WhatsApp ile Gönder', 'dr-alper-uslu' ); ?></span></button>
			</form>
		</div>
	</div>
</div></section>

<?php if ( $adres ) : ?>
<section class="pb-16 md:pb-24 bg-white"><div class="container">
	<a href="<?php echo esc_url( $maps ); ?>" target="_blank" rel="noopener" class="reveal group block relative rounded-xl2 overflow-hidden ring-1 ring-line shadow-card h-[340px] mesh-teal" aria-label="<?php echo esc_attr( $adres ); ?>">
		<svg class="absolute inset-0 w-full h-full opacity-[0.5]" preserveAspectRatio="xMidYMid slice" viewBox="0 0 400 300" aria-hidden="true">
			<defs><pattern id="gm" width="28" height="28" patternUnits="userSpaceOnUse"><path d="M28 0H0V28" fill="none" stroke="#12857D" stroke-width="0.5" stroke-opacity="0.18"/></pattern></defs>
			<rect width="400" height="300" fill="url(#gm)"/>
			<path d="M-20 210 Q140 170 210 200 T430 150" fill="none" stroke="#12857D" stroke-width="7" stroke-opacity="0.14"/>
			<path d="M60 -20 Q90 120 180 180 T300 320" fill="none" stroke="#12857D" stroke-width="5" stroke-opacity="0.12"/>
		</svg>
		<div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-full flex flex-col items-center">
			<span class="w-14 h-14 rounded-full bg-brand-600 text-white grid place-content-center shadow-cardHover ring-4 ring-white transition group-hover:scale-110"><?php echo dau_icon( 'map' ); // phpcs:ignore ?></span>
			<span class="w-3 h-3 rounded-full bg-brand-700/30 mt-1"></span>
		</div>
		<div class="absolute inset-x-4 bottom-4 sm:inset-x-6 sm:bottom-6 bg-white/95 backdrop-blur rounded-2xl shadow-card p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center gap-3 justify-between">
			<div class="flex items-start gap-3 text-ink-700"><span class="text-brand-600 mt-0.5"><?php echo dau_icon( 'map' ); // phpcs:ignore ?></span><span class="text-sm font-medium"><?php echo esc_html( $adres ); ?></span></div>
			<span class="btn-primary !py-2.5 shrink-0"><?php esc_html_e( 'Yol Tarifi', 'dr-alper-uslu' ); ?><?php echo dau_icon( 'arrow' ); // phpcs:ignore ?></span>
		</div>
	</a>
</div></section>
<?php endif; ?>

<script>
function dauWa(e){
	e.preventDefault();
	var f=e.target, num=<?php echo wp_json_encode( $wanum ); ?>;
	var ad=(f.ad.value||'').trim(), tel=(f.tel.value||'').trim(), islem=(f.islem.value||'').trim(), mesaj=(f.mesaj.value||'').trim();
	var t='Merhaba, randevu talebim var.\n\nAd Soyad: '+ad+'\nTelefon: '+tel;
	if(islem) t+='\nİşlem: '+islem;
	if(mesaj) t+='\nMesaj: '+mesaj;
	window.open('https://wa.me/'+num+'?text='+encodeURIComponent(t),'_blank','noopener');
	return false;
}
</script>

<?php
get_footer();
