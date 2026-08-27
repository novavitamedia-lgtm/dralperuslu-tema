<?php
/**
 * ACF Pro entegrasyonu: JSON senkron noktası + Options sayfası.
 * Field group'lar acf-json/ altında sürüm kontrolünde tutulur.
 *
 * @package dr-alper-uslu
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Field group'ları acf-json/ klasörüne kaydet/oku.
add_filter( 'acf/settings/save_json', function () {
	return DAU_DIR . '/acf-json';
} );
add_filter( 'acf/settings/load_json', function ( $paths ) {
	$paths[] = DAU_DIR . '/acf-json';
	return $paths;
} );

// Ayarlar (Options) sayfası: logo, telefon, sosyal, adres, saatler.
add_action( 'acf/init', function () {
	if ( function_exists( 'acf_add_options_page' ) ) {
		acf_add_options_page( array(
			'page_title' => __( 'Tema Ayarları', 'dr-alper-uslu' ),
			'menu_title' => __( 'Tema Ayarları', 'dr-alper-uslu' ),
			'menu_slug'  => 'tema-ayarlari',
			'capability' => 'manage_options',
			'icon_url'   => 'dashicons-admin-generic',
			'position'   => 59,
		) );
	}
} );

// ACF Pro yoksa yönetici uyarısı (tema yine de varsayılanlarla çalışır).
add_action( 'admin_notices', function () {
	if ( ! class_exists( 'ACF' ) && current_user_can( 'activate_plugins' ) ) {
		echo '<div class="notice notice-warning"><p>';
		echo esc_html__( 'Op. Dr. Alper Burak Uslu teması, bölümlerin düzenlenebilmesi için ACF Pro eklentisini önerir. ACF olmadan tema varsayılan içerikle çalışır.', 'dr-alper-uslu' );
		echo '</p></div>';
	}
} );

/**
 * Front-page esnek içerik layout'unu render et.
 */
function dau_render_flexible( $field = 'bolumler' ) {
	if ( ! function_exists( 'have_rows' ) || ! have_rows( $field ) ) {
		// ACF yoksa/boşsa: varsayılan bölüm dizisi (statik önizlemedeki sırayla).
		get_template_part( 'template-parts/sections/hero' );
		get_template_part( 'template-parts/sections/counters' );
		get_template_part( 'template-parts/sections/textimage' );
		get_template_part( 'template-parts/sections/services' );
		get_template_part( 'template-parts/sections/steps' );
		get_template_part( 'template-parts/sections/doctor' );
		get_template_part( 'template-parts/sections/certs' );
		get_template_part( 'template-parts/sections/values' );
		get_template_part( 'template-parts/sections/gallery' );
		get_template_part( 'template-parts/sections/cta' );
		return;
	}
	while ( have_rows( $field ) ) {
		the_row();
		$layout = get_row_layout();
		$slug   = str_replace( '_', '-', $layout );
		get_template_part( 'template-parts/sections/' . $slug );
	}
}
