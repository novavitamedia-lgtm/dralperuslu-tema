<?php
/**
 * Güvenlik sertleştirmesi (OWASP Top 10 farkındalıklı).
 * - Sürüm sızıntısını kapat, XML-RPC kapat, pingback kapat.
 * - REST kullanıcı listelemesini kısıtla, gereksiz başlıkları kaldır.
 * - Güvenlik başlıkları ekle.
 *
 * @package dr-alper-uslu
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// WordPress sürümünü gizle (bilgi ifşası — A05).
remove_action( 'wp_head', 'wp_generator' );
add_filter( 'the_generator', '__return_empty_string' );

// Gereksiz head bağlantıları.
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );

// XML-RPC'yi kapat (brute-force/DDoS yüzeyi — A05/A07).
add_filter( 'xmlrpc_enabled', '__return_false' );
add_filter( 'wp_headers', function ( $headers ) {
	unset( $headers['X-Pingback'] );
	return $headers;
} );

// Pingback yöntemlerini kaldır.
add_filter( 'xmlrpc_methods', function ( $methods ) {
	unset( $methods['pingback.ping'], $methods['pingback.extensions.getPingbacks'] );
	return $methods;
} );

// REST üzerinden kullanıcı sıralamasını engelle (kullanıcı adı ifşası — A01).
add_filter( 'rest_endpoints', function ( $endpoints ) {
	if ( ! is_user_logged_in() ) {
		unset( $endpoints['/wp/v2/users'] );
		unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
	}
	return $endpoints;
} );

// ?author=N numaralandırmasını engelle.
add_action( 'template_redirect', function () {
	if ( is_author() && ! is_user_logged_in() ) {
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}
	if ( ! is_admin() && isset( $_GET['author'] ) && '' !== $_GET['author'] ) { // phpcs:ignore WordPress.Security.NonceVerification
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}
} );

// Güvenlik başlıkları (Clickjacking/MIME/Referrer — A05).
add_filter( 'wp_headers', function ( $headers ) {
	$headers['X-Content-Type-Options'] = 'nosniff';
	$headers['X-Frame-Options']        = 'SAMEORIGIN';
	$headers['Referrer-Policy']        = 'strict-origin-when-cross-origin';
	$headers['Permissions-Policy']     = 'geolocation=(), microphone=(), camera=()';
	return $headers;
} );

// Login hata mesajını genelleştir (kullanıcı sayımı — A07).
add_filter( 'login_errors', function () {
	return __( 'Giriş bilgileri hatalı.', 'dr-alper-uslu' );
} );

// Yükleme sırasında yürütülebilir uzantıları engelle.
add_filter( 'upload_mimes', function ( $mimes ) {
	unset( $mimes['exe'], $mimes['php'], $mimes['js'], $mimes['svg'] );
	return $mimes;
} );
