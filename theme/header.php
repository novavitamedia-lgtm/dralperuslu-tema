<?php
/**
 * Başlık + sabit navigasyon.
 *
 * @package dr-alper-uslu
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="scroll-smooth">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
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
				'<a href="%s" class="flex flex-col justify-center" aria-label="%s"><span class="font-display text-[1.15rem] font-bold leading-none text-ink-900">%s</span><span class="block text-[0.66rem] uppercase tracking-[0.18em] text-ink-500 mt-1">%s</span></a>',
				esc_url( home_url( '/' ) ),
				esc_attr( get_bloginfo( 'name' ) ),
				esc_html( get_bloginfo( 'name' ) ),
				esc_html__( 'Plastik, Rekonstrüktif ve Estetik Cerrahi', 'dr-alper-uslu' )
			);
		}
		?>
		<div class="flex items-center gap-3">
			<nav class="hidden lg:flex items-center gap-1" aria-label="<?php esc_attr_e( 'Ana menü', 'dr-alper-uslu' ); ?>">
				<?php
				if ( has_nav_menu( 'primary' ) ) {
					wp_nav_menu( array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'flex items-center gap-1',
						'depth'          => 2,
						'fallback_cb'    => false,
						'link_before'    => '',
					) );
				} else {
					// Menü atanmamışsa: ana bağlantılar.
					printf( '<a class="px-3 py-2 text-[0.92rem] font-medium text-ink-700 hover:text-brand-700 whitespace-nowrap" href="%s">%s</a>', esc_url( home_url( '/' ) ), esc_html__( 'Ana Sayfa', 'dr-alper-uslu' ) );
					printf( '<a class="px-3 py-2 text-[0.92rem] font-medium text-ink-700 hover:text-brand-700 whitespace-nowrap" href="%s">%s</a>', esc_url( get_post_type_archive_link( 'uzmanlik' ) ), esc_html__( 'Uzmanlıklar', 'dr-alper-uslu' ) );
				}
				?>
			</nav>
			<a href="<?php echo esc_url( dau_wa_link() ); ?>" target="_blank" rel="noopener" class="hidden sm:inline-flex btn-primary !px-5 !py-2.5"><?php echo dau_icon( 'phone' ); // phpcs:ignore ?><span><?php esc_html_e( 'Randevu Al', 'dr-alper-uslu' ); ?></span></a>
			<button @click="mobile=true" class="lg:hidden p-2 text-ink-900" aria-label="<?php esc_attr_e( 'Menü', 'dr-alper-uslu' ); ?>"><svg viewBox="0 0 24 24" class="w-7 h-7" fill="none"><path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></button>
		</div>
	</div>

	<!-- Mobil menü -->
	<div x-show="mobile" x-cloak x-transition.opacity class="fixed inset-0 z-50 bg-ink-900/40 lg:hidden" @click="mobile=false"></div>
	<div x-show="mobile" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="fixed top-0 right-0 z-50 h-full w-[86%] max-w-sm bg-white shadow-2xl p-6 overflow-y-auto lg:hidden">
		<div class="flex items-center justify-between mb-6">
			<span class="font-display font-bold text-ink-900"><?php esc_html_e( 'Menü', 'dr-alper-uslu' ); ?></span>
			<button @click="mobile=false" class="p-2" aria-label="<?php esc_attr_e( 'Kapat', 'dr-alper-uslu' ); ?>"><svg viewBox="0 0 24 24" class="w-6 h-6" fill="none"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></button>
		</div>
		<?php
		if ( has_nav_menu( 'primary' ) ) {
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'space-y-1',
				'depth'          => 2,
				'fallback_cb'    => false,
			) );
		}
		?>
		<a href="<?php echo esc_url( dau_wa_link() ); ?>" target="_blank" rel="noopener" class="btn-primary w-full mt-5"><?php esc_html_e( 'Randevu Al', 'dr-alper-uslu' ); ?></a>
	</div>
</header>

<main id="content" class="flex-1" style="padding-top:var(--nav-h)">
