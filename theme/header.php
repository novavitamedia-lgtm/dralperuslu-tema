<?php
/**
 * Başlık + sabit navigasyon (embrace dili: tek Uzmanlıklar mega-dropdown +
 * belirgin dil switcher + koyu CTA pill). Menü CPT verisinden programatik üretilir,
 * bu yüzden admin'de menü atanmasa da her zaman doğru görünür.
 *
 * @package dr-alper-uslu
 */

// Sayfa linki çözücü (TR slug -> permalink; yoksa ana sayfa).
if ( ! function_exists( 'dau_page_link' ) ) {
	function dau_page_link( $slug ) {
		$p = get_page_by_path( $slug );
		return $p ? get_permalink( $p ) : home_url( '/' );
	}
}

$dau_tree = dau_specialties_tree();
$dau_lang = dau_lang_switcher();
$dau_blog_page = get_page_by_path( 'blog' );
$dau_blog = $dau_blog_page ? get_permalink( $dau_blog_page ) : home_url( '/blog/' );
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="scroll-smooth">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="icon" type="image/svg+xml" href="<?php echo esc_url( DAU_URI . '/assets/favicon.svg' ); ?>">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'min-h-screen flex flex-col' ); ?>>
<style>[x-cloak]{display:none!important}</style>
<?php wp_body_open(); ?>

<a class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 focus:z-[100] btn-primary" href="#content"><?php esc_html_e( 'İçeriğe atla', 'dr-alper-uslu' ); ?></a>

<header data-header class="fixed top-0 inset-x-0 z-50 transition-all duration-300 [&.is-scrolled]:bg-white/95 [&.is-scrolled]:backdrop-blur [&.is-scrolled]:shadow-soft bg-white/80 backdrop-blur-sm" x-data="{ mobile:false }">
	<div class="container flex items-center justify-between gap-4" style="height:var(--nav-h)">
		<?php
		if ( has_custom_logo() ) {
			the_custom_logo();
		} else {
			printf(
				'<a href="%s" class="flex flex-col justify-center"><span class="font-display text-[1.02rem] sm:text-[1.12rem] font-bold leading-tight whitespace-nowrap text-ink-900">%s</span><span class="block text-[0.6rem] sm:text-[0.66rem] uppercase tracking-[0.16em] text-ink-500 mt-1 whitespace-nowrap">%s</span></a>',
				esc_url( home_url( '/' ) ),
				esc_html( get_bloginfo( 'name' ) ),
				esc_html__( 'Plastik, Rekonstrüktif ve Estetik Cerrahi', 'dr-alper-uslu' )
			);
		}
		?>

		<!-- Masaüstü nav: Hakkımda · Uzmanlıklar(mega) · Başarılar · İletişim -->
		<nav class="hidden lg:flex items-center gap-0.5" aria-label="<?php esc_attr_e( 'Ana menü', 'dr-alper-uslu' ); ?>">
			<a href="<?php echo esc_url( dau_page_link( 'hakkimda' ) ); ?>" class="px-3.5 py-2 text-[0.92rem] font-medium text-ink-700 hover:text-brand-700 transition-colors whitespace-nowrap"><?php esc_html_e( 'Hakkımda', 'dr-alper-uslu' ); ?></a>

			<?php if ( ! empty( $dau_tree ) ) : ?>
			<div class="relative" x-data="{ open:false }" @mouseenter="open=true" @mouseleave="open=false" @focusin="open=true" @focusout="open=false">
				<button type="button" class="inline-flex items-center gap-1 px-3.5 py-2 text-[0.92rem] font-medium text-ink-700 hover:text-brand-700 transition-colors" :aria-expanded="open" :class="open && 'text-brand-700'">
					<?php esc_html_e( 'Uzmanlıklar', 'dr-alper-uslu' ); ?><span class="transition-transform duration-200" :class="open && 'rotate-180'"><?php echo dau_icon( 'chevron' ); // phpcs:ignore ?></span>
				</button>
				<div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-cloak class="absolute left-1/2 -translate-x-1/2 top-full pt-3 z-40 w-[min(92vw,860px)]">
					<div class="bg-white rounded-2xl shadow-cardHover ring-1 ring-line p-6 grid grid-cols-2 md:grid-cols-4 gap-x-6 gap-y-4">
						<?php foreach ( $dau_tree as $col ) : ?>
						<div class="min-w-0">
							<a href="<?php echo esc_url( get_term_link( $col['term'] ) ); ?>" class="group/cat flex items-center gap-2.5 mb-2.5 pb-2.5 border-b border-line">
								<span class="grid place-content-center w-8 h-8 rounded-lg bg-brand-50 text-brand-600 shrink-0 transition-colors group-hover/cat:bg-brand-100"><?php echo dau_icon( $col['icon'] ); // phpcs:ignore ?></span>
								<span class="font-display font-semibold text-[0.92rem] text-ink-900 leading-tight"><?php echo esc_html( $col['term']->name ); ?></span>
							</a>
							<?php foreach ( $col['posts'] as $p ) : ?>
								<a href="<?php echo esc_url( get_permalink( $p ) ); ?>" class="block py-1.5 text-[0.85rem] text-ink-700 hover:text-brand-700 transition-colors"><?php echo esc_html( get_the_title( $p ) ); ?></a>
							<?php endforeach; ?>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<?php else : ?>
				<a href="<?php echo esc_url( get_post_type_archive_link( 'uzmanlik' ) ); ?>" class="px-3.5 py-2 text-[0.92rem] font-medium text-ink-700 hover:text-brand-700 transition-colors whitespace-nowrap"><?php esc_html_e( 'Uzmanlıklar', 'dr-alper-uslu' ); ?></a>
			<?php endif; ?>

			<a href="<?php echo esc_url( dau_page_link( 'basarilar' ) ); ?>" class="px-3.5 py-2 text-[0.92rem] font-medium text-ink-700 hover:text-brand-700 transition-colors whitespace-nowrap"><?php esc_html_e( 'Başarılar', 'dr-alper-uslu' ); ?></a>
			<a href="<?php echo esc_url( $dau_blog ); ?>" class="px-3.5 py-2 text-[0.92rem] font-medium text-ink-700 hover:text-brand-700 transition-colors whitespace-nowrap"><?php esc_html_e( 'Blog', 'dr-alper-uslu' ); ?></a>
			<a href="<?php echo esc_url( dau_page_link( 'iletisim' ) ); ?>" class="px-3.5 py-2 text-[0.92rem] font-medium text-ink-700 hover:text-brand-700 transition-colors whitespace-nowrap"><?php esc_html_e( 'İletişim', 'dr-alper-uslu' ); ?></a>
		</nav>

		<div class="flex items-center gap-2.5">
			<?php if ( $dau_lang ) : ?><div class="hidden md:block"><?php echo $dau_lang; // phpcs:ignore ?></div><?php endif; ?>
			<a href="<?php echo esc_url( dau_wa_link() ); ?>" target="_blank" rel="noopener" class="group/cta hidden sm:inline-flex items-center gap-2.5 rounded-full bg-ink-900 text-white pl-5 pr-1.5 py-1.5 text-[0.86rem] font-semibold hover:bg-ink-700 transition-colors">
				<span><?php esc_html_e( 'Randevu Al', 'dr-alper-uslu' ); ?></span>
				<span class="grid place-content-center w-8 h-8 rounded-full bg-coral-500 text-white transition-colors group-hover/cta:bg-coral-600"><?php echo dau_icon( 'arrow' ); // phpcs:ignore ?></span>
			</a>
			<button @click="mobile=true" class="lg:hidden p-1.5 -mr-1 text-ink-900" aria-label="<?php esc_attr_e( 'Menü', 'dr-alper-uslu' ); ?>"><?php echo dau_icon( 'menu' ); // phpcs:ignore ?></button>
		</div>
	</div>

	<!-- Mobil menü -->
	<div x-show="mobile" x-cloak x-transition.opacity class="fixed inset-0 z-50 bg-ink-900/40 lg:hidden" @click="mobile=false"></div>
	<div x-show="mobile" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="fixed top-0 right-0 z-50 h-full w-[86%] max-w-sm bg-white shadow-2xl p-6 overflow-y-auto lg:hidden">
		<div class="flex items-center justify-between mb-5">
			<span class="font-display font-bold text-ink-900 text-lg"><?php esc_html_e( 'Menü', 'dr-alper-uslu' ); ?></span>
			<button @click="mobile=false" class="p-1.5" aria-label="<?php esc_attr_e( 'Kapat', 'dr-alper-uslu' ); ?>"><?php echo dau_icon( 'close' ); // phpcs:ignore ?></button>
		</div>
		<?php if ( $dau_lang ) : ?><div class="mb-4"><?php echo $dau_lang; // phpcs:ignore ?></div><?php endif; ?>
		<a href="<?php echo esc_url( dau_page_link( 'hakkimda' ) ); ?>" class="block py-3 font-medium text-ink-900 border-b border-line/70" @click="mobile=false"><?php esc_html_e( 'Hakkımda', 'dr-alper-uslu' ); ?></a>
		<?php foreach ( $dau_tree as $col ) : ?>
			<div x-data="{ o:false }" class="border-b border-line/70">
				<button @click="o=!o" class="w-full flex items-center justify-between py-3 font-medium text-ink-900">
					<span class="flex items-center gap-2.5"><span class="text-brand-600"><?php echo dau_icon( $col['icon'] ); // phpcs:ignore ?></span><?php echo esc_html( $col['term']->name ); ?></span>
					<span class="transition-transform" :class="o && 'rotate-180'"><?php echo dau_icon( 'chevron' ); // phpcs:ignore ?></span>
				</button>
				<div x-show="o" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="pb-3 pl-[2.75rem]">
					<?php foreach ( $col['posts'] as $p ) : ?>
						<a href="<?php echo esc_url( get_permalink( $p ) ); ?>" class="block py-1.5 text-[0.9rem] text-ink-500 hover:text-brand-700" @click="mobile=false"><?php echo esc_html( get_the_title( $p ) ); ?></a>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endforeach; ?>
		<a href="<?php echo esc_url( dau_page_link( 'basarilar' ) ); ?>" class="block py-3 font-medium text-ink-900 border-b border-line/70" @click="mobile=false"><?php esc_html_e( 'Başarılar', 'dr-alper-uslu' ); ?></a>
		<a href="<?php echo esc_url( $dau_blog ); ?>" class="block py-3 font-medium text-ink-900 border-b border-line/70" @click="mobile=false"><?php esc_html_e( 'Blog', 'dr-alper-uslu' ); ?></a>
		<a href="<?php echo esc_url( dau_page_link( 'iletisim' ) ); ?>" class="block py-3 font-medium text-ink-900 border-b border-line/70" @click="mobile=false"><?php esc_html_e( 'İletişim', 'dr-alper-uslu' ); ?></a>
		<a href="<?php echo esc_url( dau_wa_link() ); ?>" target="_blank" rel="noopener" class="btn-primary w-full mt-6 justify-center"><?php esc_html_e( 'Randevu Al', 'dr-alper-uslu' ); ?></a>
	</div>
</header>

<main id="content" class="flex-1" style="padding-top:var(--nav-h)">
