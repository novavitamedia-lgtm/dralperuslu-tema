<?php
/**
 * Asset kuyruğu: self-host fontlar, derlenmiş Tailwind CSS, Alpine, Swiper, tema JS.
 * jQuery yüklenmez (WP zaten yüklemişse dokunulmaz).
 *
 * @package dr-alper-uslu
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dau_enqueue_assets() {
	$css = DAU_URI . '/assets/dist/main.css';
	$ver = file_exists( DAU_DIR . '/assets/dist/main.css' ) ? filemtime( DAU_DIR . '/assets/dist/main.css' ) : DAU_VERSION;

	wp_enqueue_style( 'dau-main', $css, array(), $ver );
	wp_enqueue_style( 'dau-swiper', DAU_URI . '/assets/vendor/swiper-bundle.min.css', array(), '11.2.10' );

	// Alpine + Swiper + tema JS (defer).
	wp_enqueue_script( 'dau-alpine', DAU_URI . '/assets/vendor/alpine.min.js', array(), '3.14.1', array( 'strategy' => 'defer' ) );
	wp_enqueue_script( 'dau-swiper', DAU_URI . '/assets/vendor/swiper-bundle.min.js', array(), '11.2.10', array( 'strategy' => 'defer' ) );
	wp_enqueue_script( 'dau-main', DAU_URI . '/assets/src/main.js', array(), $ver, array( 'strategy' => 'defer' ) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'dau_enqueue_assets' );

/**
 * <html> öğesine JS sınıfı (no-JS'te reveal gizlenmesin) + font preload.
 */
function dau_head_preload() {
	echo '<script>document.documentElement.classList.add("js")</script>' . "\n";
	$fonts = array( 'jakarta-500-latin-ext.woff2', 'fraunces-700-latin-ext.woff2' );
	foreach ( $fonts as $f ) {
		printf( '<link rel="preload" as="font" type="font/woff2" crossorigin href="%s">' . "\n",
			esc_url( DAU_URI . '/assets/fonts/' . $f ) );
	}
}
add_action( 'wp_head', 'dau_head_preload', 1 );

/**
 * Preload'ları da kapsayan font-face zaten main.css içinde (../fonts/ göreli).
 */
