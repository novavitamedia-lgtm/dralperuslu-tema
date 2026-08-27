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
		'phone'  => '<svg viewBox="0 0 24 24" fill="none" class="w-4 h-4" aria-hidden="true"><path d="M2.5 5.5C2.5 4 3.7 3 5 3h1.6c.6 0 1.1.4 1.3 1l.9 3c.1.5 0 1-.4 1.3l-1.3 1a11 11 0 005 5l1-1.3c.3-.4.8-.5 1.3-.4l3 .9c.6.2 1 .7 1 1.3V18c0 1.3-1 2.5-2.5 2.5C10 20.5 3.5 14 2.5 5.5Z" stroke="currentColor" stroke-width="1.6"/></svg>',
		'arrow'  => '<svg viewBox="0 0 24 24" fill="none" class="w-4 h-4" aria-hidden="true"><path d="M5 12h14m0 0-5-5m5 5-5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>',
		'chevron'=> '<svg viewBox="0 0 24 24" fill="none" class="w-4 h-4" aria-hidden="true"><path d="m6 9 6 6 6-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>',
		'wa'     => '<svg viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5" aria-hidden="true"><path d="M12 2a10 10 0 00-8.6 15l-1 3.7 3.8-1A10 10 0 1012 2Zm5.3 13.9c-.2.6-1.3 1.2-1.8 1.2-.5.1-1 .1-1.6-.1-.4-.1-.9-.3-1.5-.6a9 9 0 01-3.9-3.9c-.3-.6-.5-1.1-.5-1.6 0-.5.5-1.2 1-1.5.2-.1.4-.1.5 0l.9 1.3c.1.2.1.4 0 .6l-.4.6c-.1.2-.1.3 0 .5.4.7 1.2 1.5 1.9 1.9.2.1.3.1.5 0l.6-.5c.2-.1.4-.1.6 0l1.3.9c.2.1.2.3.1.5Z"/></svg>',
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
			<div class="aspect-[4/3] bg-gradient-to-br from-brand-500 to-brand-700 grid place-content-center text-white/90 font-display text-xl px-4 text-center"><?php echo esc_html( $title ); ?></div>
		<?php endif; ?>
		<div class="p-5">
			<?php if ( $cat ) : ?><span class="text-[0.7rem] uppercase tracking-wider text-brand-600 font-semibold"><?php echo esc_html( $cat ); ?></span><?php endif; ?>
			<h3 class="font-display text-h3 font-semibold text-ink-900 mt-1 group-hover:text-brand-700 transition"><?php echo esc_html( $title ); ?></h3>
			<span class="inline-flex items-center gap-1 text-sm text-brand-600 font-medium mt-3"><?php esc_html_e( 'Uzmanlık', 'dr-alper-uslu' ); ?><?php echo dau_icon( 'arrow' ); // phpcs:ignore ?></span>
		</div>
	</a>
	<?php
	return ob_get_clean();
}
