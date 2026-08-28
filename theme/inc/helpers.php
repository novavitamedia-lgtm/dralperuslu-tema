<?php
/**
 * Yardımcı fonksiyonlar: ACF Options getters, telefon/whatsapp, kategori, görsel.
 * ACF yoksa güvenli varsayılanlarla çalışır.
 *
 * @package dr-alper-uslu
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ACF alt alanı (flexible content içinde), ACF yoksa null.
 */
function dau_sub( $key ) {
	return function_exists( 'get_sub_field' ) ? get_sub_field( $key ) : null;
}

/**
 * ACF Options alanı (ACF yoksa varsayılan).
 */
function dau_opt( $key, $default = '' ) {
	if ( function_exists( 'get_field' ) ) {
		$val = get_field( $key, 'option' );
		if ( null !== $val && '' !== $val ) {
			return $val;
		}
	}
	$defaults = array(
		'telefon'        => '+90 532 569 31 99',
		'telefon_raw'    => '+905325693199',
		'whatsapp'       => '905325693199',
		'adres'          => 'Fenerbahçe Mah. Bağdat Cad. 134/11 Kadıköy / İstanbul',
		'calisma_saati'  => 'Pazartesi – Cumartesi: 09:00 – 19:00',
		'facebook'       => 'https://www.facebook.com/op.dr.alperburakuslu/',
		'instagram'      => 'https://www.instagram.com/dralperburakuslu/',
		'youtube'        => 'https://www.youtube.com/channel/UCaZ98blTdpHjctnXJNrethQ',
		'maps'           => 'https://www.google.com/maps/search/?api=1&query=Fenerbah%C3%A7e+Mah.+Ba%C4%9Fdat+Cad.+134%2F11+Kad%C4%B1k%C3%B6y',
	);
	return isset( $defaults[ $key ] ) ? $defaults[ $key ] : $default;
}

/**
 * WhatsApp bağlantısı.
 */
function dau_wa_link( $text = '' ) {
	$base = 'https://wa.me/' . rawurlencode( preg_replace( '/\D/', '', dau_opt( 'whatsapp' ) ) );
	return $text ? $base . '?text=' . rawurlencode( $text ) : $base;
}

/**
 * tel: bağlantısı.
 */
function dau_tel() {
	return 'tel:' . preg_replace( '/[^\d+]/', '', dau_opt( 'telefon_raw' ) );
}

/**
 * Tahmini okuma süresi (dakika).
 */
function dau_reading_time( $content ) {
	$words = str_word_count( wp_strip_all_tags( $content ) );
	$min   = max( 1, (int) ceil( $words / 200 ) );
	/* translators: %d: dakika */
	return sprintf( __( '%d dk okuma', 'dr-alper-uslu' ), $min );
}

/**
 * Tarihi aktif dile göre biçimlendir (WP core dil paketi gerektirmeden).
 * @param int|null $post_id Yazı ID.
 * @param bool $modified true ise güncellenme tarihi.
 */
function dau_format_date( $post_id = null, $modified = false ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$ts = $modified ? (int) get_post_modified_time( 'U', true, $post_id ) : (int) get_post_time( 'U', true, $post_id );
	$lang = function_exists( 'pll_current_language' ) && pll_current_language() ? pll_current_language() : 'tr';
	$months = array(
		'tr' => array( 'Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık' ),
		'en' => array( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' ),
		'de' => array( 'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember' ),
	);
	$m = isset( $months[ $lang ] ) ? $months[ $lang ] : $months['tr'];
	$d = (int) wp_date( 'j', $ts );
	$mo = $m[ (int) wp_date( 'n', $ts ) - 1 ];
	$y = wp_date( 'Y', $ts );
	if ( 'en' === $lang ) { return "$mo $d, $y"; }
	if ( 'de' === $lang ) { return "$d. $mo $y"; }
	return "$d $mo $y";
}

/**
 * Sorumluluk reddi metni.
 */
function dau_legal_note() {
	return dau_opt( 'uyari_metni', __( 'Bu web sitesindeki bilgiler yalnızca bilgilendirme amaçlıdır ve tıbbi tavsiye yerine geçmez. Sonuçlar kişiden kişiye değişebilir.', 'dr-alper-uslu' ) );
}

/**
 * Responsive görsel (srcset/sizes ile), yoksa boş.
 */
function dau_image( $id, $size = 'dau-card', $class = '', $eager = false ) {
	if ( ! $id ) {
		return '';
	}
	$attr = array( 'class' => $class, 'loading' => $eager ? 'eager' : 'lazy' );
	if ( $eager ) {
		$attr['fetchpriority'] = 'high';
	}
	return wp_get_attachment_image( $id, $size, false, $attr );
}

/**
 * Bir uzmanlığın birincil kategorisi (slug + isim).
 */
function dau_primary_category( $post_id ) {
	$terms = get_the_terms( $post_id, 'uzmanlik-kategori' );
	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return null;
	}
	return $terms[0];
}

/**
 * Aynı kategoriden ilgili uzmanlıklar (N+1'siz tek WP_Query).
 */
function dau_related_uzmanlik( $post_id, $limit = 3 ) {
	$term = dau_primary_category( $post_id );
	$args = array(
		'post_type'      => 'uzmanlik',
		'posts_per_page' => $limit,
		'post__not_in'   => array( $post_id ),
		'no_found_rows'  => true,
		'orderby'        => 'menu_order title',
		'order'          => 'ASC',
	);
	if ( $term ) {
		$args['tax_query'] = array( array(
			'taxonomy' => 'uzmanlik-kategori',
			'field'    => 'term_id',
			'terms'    => $term->term_id,
		) );
	}
	return new WP_Query( $args );
}

/**
 * SVG ikon (güvenli, sabit set).
 */
function dau_icon( $name ) {
	$icons = array(
		// Premium ikon seti — Lucide dili (stroke 1.7-2, yuvarlak uç), shadcn/ui ile aynı görsel dil.
		'phone'  => '<svg viewBox="0 0 24 24" fill="none" class="w-4 h-4" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>',
		'arrow'  => '<svg viewBox="0 0 24 24" fill="none" class="w-4 h-4" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>',
		'chevron'=> '<svg viewBox="0 0 24 24" fill="none" class="w-4 h-4" aria-hidden="true"><path d="m6 9 6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
		'globe'  => '<svg viewBox="0 0 24 24" fill="none" class="w-[15px] h-[15px]" aria-hidden="true"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.7"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 0 20 15.3 15.3 0 0 1 0-20Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>',
		'menu'   => '<svg viewBox="0 0 24 24" fill="none" class="w-6 h-6" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>',
		'close'  => '<svg viewBox="0 0 24 24" fill="none" class="w-6 h-6" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>',
		'cat_yuz'         => '<svg viewBox="0 0 24 24" fill="none" class="w-[18px] h-[18px]" aria-hidden="true"><circle cx="12" cy="12" r="9.2" stroke="currentColor" stroke-width="1.7"/><path d="M8.5 14.5s1.3 1.8 3.5 1.8 3.5-1.8 3.5-1.8M9 9.5h.01M15 9.5h.01" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>',
		'cat_vucut'       => '<svg viewBox="0 0 24 24" fill="none" class="w-[18px] h-[18px]" aria-hidden="true"><circle cx="12" cy="4.5" r="1.8" stroke="currentColor" stroke-width="1.7"/><path d="m8.5 21 3.5-6 3.5 6M6 8.5l6 2 6-2M12 10.5V15" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>',
		'cat_gogus'       => '<svg viewBox="0 0 24 24" fill="none" class="w-[18px] h-[18px]" aria-hidden="true"><path d="M19 14c1.5-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.29 1.5 4.04 3 5.5l7 7 7-7Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg>',
		'cat_ameliyatsiz' => '<svg viewBox="0 0 24 24" fill="none" class="w-[18px] h-[18px]" aria-hidden="true"><path d="m12 3-1.9 5.8a2 2 0 0 1-1.3 1.3L3 12l5.8 1.9a2 2 0 0 1 1.3 1.3L12 21l1.9-5.8a2 2 0 0 1 1.3-1.3L21 12l-5.8-1.9a2 2 0 0 1-1.3-1.3L12 3Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg>',
		'wa'     => '<svg viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5" aria-hidden="true"><path d="M12 2a10 10 0 00-8.6 15l-1 3.7 3.8-1A10 10 0 1012 2Zm5.3 13.9c-.2.6-1.3 1.2-1.8 1.2-.5.1-1 .1-1.6-.1-.4-.1-.9-.3-1.5-.6a9 9 0 01-3.9-3.9c-.3-.6-.5-1.1-.5-1.6 0-.5.5-1.2 1-1.5.2-.1.4-.1.5 0l.9 1.3c.1.2.1.4 0 .6l-.4.6c-.1.2-.1.3 0 .5.4.7 1.2 1.5 1.9 1.9.2.1.3.1.5 0l.6-.5c.2-.1.4-.1.6 0l1.3.9c.2.1.2.3.1.5Z"/></svg>',
		'fb'     => '<svg viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5" aria-hidden="true"><path d="M14 9V7c0-.8.2-1 1-1h2V3h-3c-2.5 0-4 1.5-4 4v2H8v3h2v9h4v-9h2.5l.5-3h-3Z"/></svg>',
		'ig'     => '<svg viewBox="0 0 24 24" fill="none" class="w-5 h-5" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.6"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/></svg>',
		'yt'     => '<svg viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5" aria-hidden="true"><path d="M22 12c0-2-.2-3.4-.4-4.2a2.6 2.6 0 00-1.8-1.8C18.4 5.7 12 5.7 12 5.7s-6.4 0-7.8.3A2.6 2.6 0 002.4 7.8C2.2 8.6 2 10 2 12s.2 3.4.4 4.2a2.6 2.6 0 001.8 1.8c1.4.3 7.8.3 7.8.3s6.4 0 7.8-.3a2.6 2.6 0 001.8-1.8c.2-.8.4-2.2.4-4.2ZM10 15V9l5 3-5 3Z"/></svg>',
		'map'    => '<svg viewBox="0 0 24 24" fill="none" class="w-5 h-5" aria-hidden="true"><path d="M12 21s7-6 7-11a7 7 0 10-14 0c0 5 7 11 7 11Z" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="10" r="2.4" stroke="currentColor" stroke-width="1.6"/></svg>',
		'clock'  => '<svg viewBox="0 0 24 24" fill="none" class="w-5 h-5" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.6"/><path d="M12 7v5l3 2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>',
		'badge'  => '<svg viewBox="0 0 24 24" fill="none" class="w-6 h-6" aria-hidden="true"><path d="M12 3l2.5 1.8 3-.3 1 2.9 2.4 1.8-1 2.9 1 2.9-2.4 1.8-1 2.9-3-.3L12 21l-2.5-1.9-3 .3-1-2.9L3.1 15l1-2.9-1-2.9L5.5 7.4l1-2.9 3 .3L12 3Z" stroke="currentColor" stroke-width="1.4"/><path d="m9 12 2 2 4-4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>',
		'star'   => '<svg viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4" aria-hidden="true"><path d="M12 2l2.9 6.3 6.9.7-5.1 4.6 1.4 6.8L12 17.8 5.9 21.2l1.4-6.8L2.2 9.8l6.9-.7L12 2Z"/></svg>',
	);
	return isset( $icons[ $name ] ) ? $icons[ $name ] : '';
}

/**
 * Uzmanlık kart parçası (arşiv/ilgili/grid için ortak).
 */
function dau_uzmanlik_card( $post_id ) {
	$term  = dau_primary_category( $post_id );
	$cat   = $term ? $term->name : '';
	$title = get_the_title( $post_id );
	$url   = get_permalink( $post_id );
	$thumb = get_post_thumbnail_id( $post_id );
	ob_start(); ?>
	<a href="<?php echo esc_url( $url ); ?>" class="group card card-hover overflow-hidden block">
		<?php if ( $thumb ) : ?>
			<div class="aspect-[4/3] overflow-hidden"><?php echo dau_image( $thumb, 'dau-card', 'w-full h-full object-cover transition duration-500 group-hover:scale-105' ); // phpcs:ignore ?></div>
		<?php else : ?>
			<div class="aspect-[4/3] relative overflow-hidden bg-gradient-to-br from-brand-500 to-brand-700 grid place-content-center">
				<svg viewBox="0 0 200 150" class="absolute inset-0 w-full h-full opacity-[0.16]" preserveAspectRatio="xMidYMid slice" aria-hidden="true"><circle cx="28" cy="122" r="72" fill="none" stroke="white" stroke-width="1"/><circle cx="28" cy="122" r="52" fill="none" stroke="white" stroke-width="1"/><circle cx="178" cy="22" r="56" fill="none" stroke="white" stroke-width="1"/></svg>
				<span class="relative w-14 h-14 rounded-full ring-1 ring-white/40 grid place-content-center text-white/90 transition duration-500 group-hover:scale-110"><?php echo dau_icon( 'badge' ); // phpcs:ignore ?></span>
			</div>
		<?php endif; ?>
		<div class="p-5">
			<?php if ( $cat ) : ?><span class="text-[0.7rem] uppercase tracking-wider text-brand-600 font-semibold"><?php echo esc_html( $cat ); ?></span><?php endif; ?>
			<h3 class="font-display text-h3 font-semibold text-ink-900 mt-1 group-hover:text-brand-700 transition"><?php echo esc_html( $title ); ?></h3>
			<span class="inline-flex items-center gap-1 text-sm text-brand-600 font-medium mt-3"><?php esc_html_e( 'İncele', 'dr-alper-uslu' ); ?><?php echo dau_icon( 'arrow' ); // phpcs:ignore ?></span>
		</div>
	</a>
	<?php
	return ob_get_clean();
}

/**
 * Uzmanlık kategori ağacı (mega-menü için): sıralı [term => [uzmanlik postları]].
 * Seed sırasını korur (yüz, vücut, göğüs, ameliyatsız); boş kategorileri atlar.
 */
function dau_specialties_tree() {
	$order    = array( 'yuz-estetigi', 'vucut-estetigi', 'gogus-estetigi', 'ameliyatsiz' );
	$icon_map = array(
		'yuz-estetigi'   => 'cat_yuz',
		'vucut-estetigi' => 'cat_vucut',
		'gogus-estetigi' => 'cat_gogus',
		'ameliyatsiz'    => 'cat_ameliyatsiz',
	);
	$out = array();
	foreach ( $order as $slug ) {
		$term = get_term_by( 'slug', $slug, 'uzmanlik-kategori' );
		if ( ! $term || is_wp_error( $term ) ) {
			continue;
		}
		$posts = get_posts( array(
			'post_type'      => 'uzmanlik',
			'posts_per_page' => -1,
			'orderby'        => 'menu_order title',
			'order'          => 'ASC',
			'no_found_rows'  => true,
			'tax_query'      => array( array(
				'taxonomy' => 'uzmanlik-kategori',
				'field'    => 'term_id',
				'terms'    => $term->term_id,
			) ),
		) );
		if ( empty( $posts ) ) {
			continue;
		}
		$out[] = array(
			'term'  => $term,
			'icon'  => isset( $icon_map[ $slug ] ) ? $icon_map[ $slug ] : 'badge',
			'posts' => $posts,
		);
	}
	return $out;
}

/**
 * Dil değiştirici (globe + TR/EN/DE pill). Polylang/WPML varsa gerçek diller,
 * yoksa (tek dilli kurulum) hiçbir şey render etmez.
 * @return string HTML (boş olabilir).
 */
function dau_lang_switcher() {
	$langs = array(); // [ ['code'=>'TR','url'=>'...','current'=>bool], ... ]

	if ( function_exists( 'pll_the_languages' ) ) {
		$raw = pll_the_languages( array( 'raw' => 1, 'hide_if_empty' => 0 ) );
		if ( is_array( $raw ) ) {
			foreach ( $raw as $l ) {
				$langs[] = array(
					'code'    => strtoupper( $l['slug'] ),
					'url'     => $l['url'],
					'current' => ! empty( $l['current_lang'] ),
				);
			}
		}
	} elseif ( defined( 'ICL_LANGUAGE_CODE' ) && function_exists( 'icl_get_languages' ) ) {
		$raw = icl_get_languages( 'skip_missing=0' );
		if ( is_array( $raw ) ) {
			foreach ( $raw as $l ) {
				$langs[] = array(
					'code'    => strtoupper( $l['language_code'] ),
					'url'     => $l['url'],
					'current' => ! empty( $l['active'] ),
				);
			}
		}
	}

	// Tek dil ya da eklenti yoksa switcher gösterme.
	if ( count( $langs ) < 2 ) {
		return '';
	}

	$chips = '';
	foreach ( $langs as $l ) {
		if ( $l['current'] ) {
			$chips .= '<span class="px-2 py-0.5 rounded-full bg-ink-900 text-white">' . esc_html( $l['code'] ) . '</span>';
		} else {
			$chips .= '<a href="' . esc_url( $l['url'] ) . '" class="px-2 py-0.5 rounded-full text-ink-700 hover:text-ink-900 hover:bg-cream-50 transition-colors">' . esc_html( $l['code'] ) . '</a>';
		}
	}
	return '<div class="flex items-center gap-0.5 rounded-full ring-1 ring-line bg-white/70 pl-2 pr-1 py-1 text-[0.78rem] font-semibold" aria-label="' . esc_attr__( 'Dil', 'dr-alper-uslu' ) . '"><span class="text-ink-500 mr-0.5">' . dau_icon( 'globe' ) . '</span>' . $chips . '</div>';
}
