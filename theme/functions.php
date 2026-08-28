<?php
/**
 * Op. Dr. Alper Burak Uslu — tema önyükleme.
 *
 * @package dr-alper-uslu
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Doğrudan erişim yok.
}

define( 'DAU_VERSION', '1.0.0' );
define( 'DAU_DIR', get_template_directory() );
define( 'DAU_URI', get_template_directory_uri() );

/**
 * Tema desteği ve kayıtlar.
 */
function dau_setup() {
	load_theme_textdomain( 'dr-alper-uslu', DAU_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 320, 'flex-height' => true, 'flex-width' => true ) );
	add_theme_support( 'responsive-embeds' );

	register_nav_menus( array(
		'primary' => __( 'Ana Menü', 'dr-alper-uslu' ),
		'footer'  => __( 'Alt Menü', 'dr-alper-uslu' ),
	) );

	// LCP/kart görselleri için boyutlar.
	add_image_size( 'dau-card', 640, 480, true );
	add_image_size( 'dau-hero', 1280, 960, true );
}
add_action( 'after_setup_theme', 'dau_setup' );

/**
 * Polylang + tema çevirileri: gettext filtresiyle aktif dile göre çeviri döndür.
 * WP 6.7 textdomain reload timing'ine bağlı kalmadan (pll_current_language güvenilir),
 * .l10n.php mesaj tablolarını kullanır. TR = kaynak dil (dokunma).
 */
function dau_gettext_filter( $translation, $text, $domain ) {
	if ( 'dr-alper-uslu' !== $domain || ! function_exists( 'pll_current_language' ) ) {
		return $translation;
	}
	$lang = pll_current_language();
	if ( ! $lang || 'tr' === $lang ) {
		return $translation;
	}
	static $cache = array();
	if ( ! isset( $cache[ $lang ] ) ) {
		$loc  = array( 'en' => 'en_US', 'de' => 'de_DE' );
		$file = DAU_DIR . '/languages/dr-alper-uslu-' . ( isset( $loc[ $lang ] ) ? $loc[ $lang ] : $lang ) . '.l10n.php';
		$data = is_file( $file ) ? include $file : array();
		$cache[ $lang ] = ( is_array( $data ) && isset( $data['messages'] ) ) ? $data['messages'] : array();
	}
	return isset( $cache[ $lang ][ $text ] ) ? $cache[ $lang ][ $text ] : $translation;
}
add_filter( 'gettext', 'dau_gettext_filter', 20, 3 );

/**
 * İçerik genişliği.
 */
function dau_content_width() {
	$GLOBALS['content_width'] = 1200;
}
add_action( 'after_setup_theme', 'dau_content_width', 0 );

require_once DAU_DIR . '/inc/helpers.php';
require_once DAU_DIR . '/inc/security.php';
require_once DAU_DIR . '/inc/cpt.php';
require_once DAU_DIR . '/inc/enqueue.php';
require_once DAU_DIR . '/inc/acf.php';
require_once DAU_DIR . '/inc/schema.php';
require_once DAU_DIR . '/inc/landing-data.php';
