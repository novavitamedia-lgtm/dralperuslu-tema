<?php
/**
 * Custom Post Type: uzmanlik + taksonomi: uzmanlik-kategori.
 * Mevcut siteyle URL uyumu için taban 'uzmanliklar'.
 *
 * @package dr-alper-uslu
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dau_register_cpt() {
	$labels = array(
		'name'               => __( 'Uzmanlıklar', 'dr-alper-uslu' ),
		'singular_name'      => __( 'Uzmanlık', 'dr-alper-uslu' ),
		'add_new'            => __( 'Yeni Ekle', 'dr-alper-uslu' ),
		'add_new_item'       => __( 'Yeni Uzmanlık Ekle', 'dr-alper-uslu' ),
		'edit_item'          => __( 'Uzmanlık Düzenle', 'dr-alper-uslu' ),
		'new_item'           => __( 'Yeni Uzmanlık', 'dr-alper-uslu' ),
		'view_item'          => __( 'Uzmanlığı Görüntüle', 'dr-alper-uslu' ),
		'search_items'       => __( 'Uzmanlık Ara', 'dr-alper-uslu' ),
		'not_found'          => __( 'Bulunamadı', 'dr-alper-uslu' ),
		'menu_name'          => __( 'Uzmanlıklar', 'dr-alper-uslu' ),
	);

	register_post_type( 'uzmanlik', array(
		'labels'       => $labels,
		'public'       => true,
		'has_archive'  => true,
		'menu_icon'    => 'dashicons-heart',
		'menu_position'=> 20,
		'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'revisions' ),
		'rewrite'      => array( 'slug' => 'uzmanliklar', 'with_front' => false ),
		'show_in_rest' => true,
		'rest_base'    => 'uzmanlik',
	) );
}
add_action( 'init', 'dau_register_cpt' );

function dau_register_taxonomy() {
	$labels = array(
		'name'          => __( 'Uzmanlık Kategorileri', 'dr-alper-uslu' ),
		'singular_name' => __( 'Kategori', 'dr-alper-uslu' ),
		'menu_name'     => __( 'Kategoriler', 'dr-alper-uslu' ),
		'all_items'     => __( 'Tüm Kategoriler', 'dr-alper-uslu' ),
		'edit_item'     => __( 'Kategori Düzenle', 'dr-alper-uslu' ),
		'add_new_item'  => __( 'Yeni Kategori', 'dr-alper-uslu' ),
	);

	register_taxonomy( 'uzmanlik-kategori', array( 'uzmanlik' ), array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'public'            => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'uzmanlik-kategori', 'with_front' => false ),
	) );
}
add_action( 'init', 'dau_register_taxonomy' );

/**
 * Aktivasyonda kalıcı bağlantıları temizle.
 */
function dau_rewrite_flush() {
	dau_register_cpt();
	dau_register_taxonomy();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'dau_rewrite_flush' );

/**
 * Varsayılan 4 kategoriyi ilk kurulumda oluştur.
 */
function dau_seed_categories() {
	$cats = array(
		'yuz-estetigi'    => __( 'Yüz Estetiği', 'dr-alper-uslu' ),
		'vucut-estetigi'  => __( 'Vücut Estetiği', 'dr-alper-uslu' ),
		'gogus-estetigi'  => __( 'Göğüs Estetiği', 'dr-alper-uslu' ),
		'ameliyatsiz'     => __( 'Ameliyatsız Estetik', 'dr-alper-uslu' ),
	);
	foreach ( $cats as $slug => $name ) {
		if ( ! term_exists( $slug, 'uzmanlik-kategori' ) ) {
			wp_insert_term( $name, 'uzmanlik-kategori', array( 'slug' => $slug ) );
		}
	}
}
add_action( 'after_switch_theme', 'dau_seed_categories' );

/**
 * Polylang: `uzmanlik` CPT ve `uzmanlik-kategori` taksonomisini çevrilebilir yap.
 * Böylece işlem URL'leri de dil önekli olur ve dil filtresi doğru çalışır.
 */
add_filter( 'pll_get_post_types', function ( $post_types, $is_settings ) {
	$post_types['uzmanlik'] = 'uzmanlik';
	return $post_types;
}, 10, 2 );
add_filter( 'pll_get_taxonomies', function ( $taxonomies, $is_settings ) {
	$taxonomies['uzmanlik-kategori'] = 'uzmanlik-kategori';
	return $taxonomies;
}, 10, 2 );
