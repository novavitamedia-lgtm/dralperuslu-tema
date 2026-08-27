<?php
/**
 * WP-CLI içerik aktarım script'i.
 * Kullanım:  wp eval-file scripts/wp-import.php [tr|en|de]
 * content/inventory.json + content/media içeriğini WordPress'e aktarır:
 *  - uzmanlik-kategori terimleri
 *  - uzmanlik CPT (içerik + SSS + öne çıkan görsel)
 *  - sayfalar (Hakkımda, İletişim, Yasal Uyarı, Başarılar)
 *  - medya kütüphanesi (tekilleştirilmiş)
 *
 * NOT: Yalnızca temiz/staging kurulumda çalıştırın. WPML aktifse dil parametresi verin.
 *
 * @package dr-alper-uslu
 */

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	fwrite( STDERR, "Bu script yalnızca WP-CLI ile çalışır: wp eval-file scripts/wp-import.php\n" );
	return;
}

$lang = isset( $args[0] ) ? $args[0] : 'tr';
$root = dirname( __DIR__ );
$inv_path = $root . '/content/inventory.json';
$media_dir = $root . '/content/media';

if ( ! file_exists( $inv_path ) ) {
	WP_CLI::error( "inventory.json bulunamadı: $inv_path" );
}
$inv = json_decode( file_get_contents( $inv_path ), true );
if ( empty( $inv['languages'][ $lang ] ) ) {
	WP_CLI::error( "Dil bulunamadı: $lang" );
}
$data = $inv['languages'][ $lang ];

require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

$media_cache = array(); // local dosya adı -> attachment ID

/**
 * Yerel medya dosyasını kütüphaneye yükle (tekilleştirilmiş).
 */
function dau_import_media( $local, $alt, $media_dir, &$cache ) {
	if ( empty( $local ) ) {
		return 0;
	}
	if ( isset( $cache[ $local ] ) ) {
		return $cache[ $local ];
	}
	$path = $media_dir . '/' . $local;
	if ( ! file_exists( $path ) ) {
		return 0;
	}
	// Aynı dosya adı daha önce yüklendiyse tekrar kullan.
	$existing = get_posts( array(
		'post_type'   => 'attachment',
		'meta_key'    => '_dau_src',
		'meta_value'  => $local,
		'numberposts' => 1,
		'fields'      => 'ids',
	) );
	if ( $existing ) {
		$cache[ $local ] = $existing[0];
		return $existing[0];
	}
	$tmp = wp_tempnam( $local );
	copy( $path, $tmp );
	$file_array = array( 'name' => $local, 'tmp_name' => $tmp );
	$id = media_handle_sideload( $file_array, 0, null );
	if ( is_wp_error( $id ) ) {
		@unlink( $tmp );
		return 0;
	}
	update_post_meta( $id, '_dau_src', $local );
	if ( $alt ) {
		update_post_meta( $id, '_wp_attachment_image_alt', $alt );
	}
	$cache[ $local ] = $id;
	return $id;
}

/**
 * Kategori kelime → terim slug eşlemesi (basit anahtar kelime).
 */
function dau_guess_term( $slug, $title ) {
	$s = strtolower( $slug . ' ' . $title );
	if ( preg_match( '/meme|breast|brust/', $s ) ) return 'gogus-estetigi';
	if ( preg_match( '/lipo|bbl|karin|tummy|abdomen|bauch|kalca|hip|popo|gesa|butt|kol|arm|vaser|genital|gidi|jowl|korper|body|fettabsaugung/', $s ) ) return 'vucut-estetigi';
	if ( preg_match( '/botoks|botox|dolgu|filler|full|fett|prp|genclik|meso|non-surgical|nicht|ameliyatsiz|dermal/', $s ) ) return 'ameliyatsiz';
	return 'yuz-estetigi';
}

// 1) Kategoriler.
$cat_names = array(
	'yuz-estetigi'   => 'Yüz Estetiği',
	'vucut-estetigi' => 'Vücut Estetiği',
	'gogus-estetigi' => 'Göğüs Estetiği',
	'ameliyatsiz'    => 'Ameliyatsız Estetik',
);
foreach ( $cat_names as $slug => $name ) {
	if ( ! term_exists( $slug, 'uzmanlik-kategori' ) ) {
		wp_insert_term( $name, 'uzmanlik-kategori', array( 'slug' => $slug ) );
	}
}

// 2) Uzmanlıklar (portfolio → uzmanlik).
$count_u = 0;
foreach ( $data['portfolio'] as $p ) {
	$existing = get_page_by_path( $p['slug'], OBJECT, 'uzmanlik' );
	$postarr = array(
		'post_type'    => 'uzmanlik',
		'post_status'  => 'publish',
		'post_title'   => $p['title'],
		'post_name'    => $p['slug'],
		'post_content' => dau_text_to_blocks( $p['text'] ),
		'post_excerpt' => mb_substr( trim( preg_replace( '/\s+/', ' ', $p['text'] ) ), 0, 155 ),
	);
	if ( $existing ) {
		$postarr['ID'] = $existing->ID;
	}
	$pid = wp_insert_post( $postarr );
	if ( is_wp_error( $pid ) ) {
		continue;
	}
	// Kategori.
	wp_set_object_terms( $pid, dau_guess_term( $p['slug'], $p['title'] ), 'uzmanlik-kategori' );
	// Öne çıkan görsel.
	if ( ! empty( $p['images'][0]['local'] ) ) {
		$img = dau_import_media( $p['images'][0]['local'], $p['images'][0]['alt'] ?? '', $media_dir, $media_cache );
		if ( $img ) {
			set_post_thumbnail( $pid, $img );
		}
	}
	// SSS (ACF varsa).
	if ( ! empty( $p['faq'] ) && function_exists( 'update_field' ) ) {
		$rows = array();
		foreach ( $p['faq'] as $f ) {
			$rows[] = array( 'soru' => $f['q'], 'cevap' => $f['a'] );
		}
		update_field( 'sss', $rows, $pid );
	}
	$count_u++;
	WP_CLI::log( "  uzmanlik: {$p['title']}" );
}

// 3) Sayfalar.
$count_pg = 0;
foreach ( $data['pages'] as $pg ) {
	if ( in_array( $pg['slug'], array( 'ana-sayfa', 'home', 'startseite' ), true ) ) {
		continue; // ana sayfa tema ile kurulur
	}
	$existing = get_page_by_path( $pg['slug'] );
	$postarr = array(
		'post_type'   => 'page',
		'post_status' => 'publish',
		'post_title'  => $pg['title'],
		'post_name'   => $pg['slug'],
		'post_content'=> dau_text_to_blocks( $pg['text'] ),
	);
	if ( $existing ) {
		$postarr['ID'] = $existing->ID;
	}
	$pid = wp_insert_post( $postarr );
	if ( is_wp_error( $pid ) ) {
		continue;
	}
	if ( ! empty( $pg['images'][0]['local'] ) ) {
		$img = dau_import_media( $pg['images'][0]['local'], $pg['images'][0]['alt'] ?? '', $media_dir, $media_cache );
		if ( $img ) {
			set_post_thumbnail( $pid, $img );
		}
	}
	$count_pg++;
	WP_CLI::log( "  sayfa: {$pg['title']}" );
}

WP_CLI::success( "İçe aktarım tamam ($lang): $count_u uzmanlik, $count_pg sayfa, " . count( $media_cache ) . ' medya.' );

/**
 * Düz metni basit Gutenberg bloklarına çevir.
 */
function dau_text_to_blocks( $text ) {
	$out = '';
	foreach ( preg_split( "/\n{2,}/", trim( $text ) ) as $blk ) {
		$blk = trim( $blk );
		if ( '' === $blk ) {
			continue;
		}
		if ( mb_strlen( $blk ) < 70 && ( str_ends_with( $blk, '?' ) || ctype_upper( str_replace( ' ', '', $blk ) ) ) ) {
			$out .= "<!-- wp:heading -->\n<h2>" . esc_html( $blk ) . "</h2>\n<!-- /wp:heading -->\n\n";
		} else {
			$out .= "<!-- wp:paragraph -->\n<p>" . esc_html( $blk ) . "</p>\n<!-- /wp:paragraph -->\n\n";
		}
	}
	return $out;
}
