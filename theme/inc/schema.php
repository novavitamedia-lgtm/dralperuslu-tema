<?php
/**
 * JSON-LD schema (elle, SEO eklentisinden bağımsız).
 * Physician / MedicalProcedure / FAQPage / BreadcrumbList.
 * SEO eklentisi aktifse çakışmayı önlemek için filtreyle kapatılabilir.
 *
 * @package dr-alper-uslu
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dau_schema_enabled() {
	// Yoast/RankMath aktifse ve kullanıcı isterse kapatılabilir.
	return apply_filters( 'dau_output_schema', true );
}

function dau_print_jsonld( $data ) {
	if ( empty( $data ) ) {
		return;
	}
	echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}

function dau_physician_node() {
	$node = array(
		'@context'         => 'https://schema.org',
		'@type'            => array( 'Physician', 'MedicalClinic' ),
		'name'             => 'Op. Dr. Alper Burak Uslu',
		'medicalSpecialty' => 'PlasticSurgery',
		'telephone'        => dau_opt( 'telefon' ),
		'url'              => home_url( '/' ),
		'priceRange'       => '$$$',
		'areaServed'       => array( '@type' => 'City', 'name' => 'İstanbul' ),
		'address'          => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => 'Fenerbahçe Mah. Bağdat Cad. 134/11',
			'addressLocality' => 'Kadıköy',
			'addressRegion'   => 'İstanbul',
			'postalCode'      => '34726',
			'addressCountry'  => 'TR',
		),
		'geo'              => array( '@type' => 'GeoCoordinates', 'latitude' => 40.9785, 'longitude' => 29.0455 ),
		'openingHoursSpecification' => array( array(
			'@type'     => 'OpeningHoursSpecification',
			'dayOfWeek' => array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' ),
			'opens'     => '09:00',
			'closes'    => '19:00',
		) ),
		'sameAs'           => array_values( array_filter( array( dau_opt( 'facebook' ), dau_opt( 'instagram' ), dau_opt( 'youtube' ) ) ) ),
	);
	$logo = get_theme_mod( 'custom_logo' );
	if ( $logo ) {
		$node['image'] = wp_get_attachment_image_url( $logo, 'full' );
	}
	return $node;
}

function dau_output_schema() {
	if ( ! dau_schema_enabled() ) {
		return;
	}

	if ( is_front_page() ) {
		dau_print_jsonld( dau_physician_node() );
	}

	if ( is_singular( 'uzmanlik' ) ) {
		$title = get_the_title();
		$desc  = wp_strip_all_tags( get_the_excerpt() );
		dau_print_jsonld( array(
			'@context'    => 'https://schema.org',
			'@type'       => 'MedicalProcedure',
			'name'        => $title,
			'description' => $desc,
			'howPerformed'=> 'Surgical',
			'provider'    => array( '@type' => 'Physician', 'name' => 'Op. Dr. Alper Burak Uslu' ),
		) );

		// FAQ (ACF repeater 'sss' varsa).
		if ( function_exists( 'have_rows' ) && have_rows( 'sss' ) ) {
			$items = array();
			while ( have_rows( 'sss' ) ) {
				the_row();
				$items[] = array(
					'@type'          => 'Question',
					'name'           => wp_strip_all_tags( get_sub_field( 'soru' ) ),
					'acceptedAnswer' => array( '@type' => 'Answer', 'text' => wp_strip_all_tags( get_sub_field( 'cevap' ) ) ),
				);
			}
			if ( $items ) {
				dau_print_jsonld( array( '@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $items ) );
			}
		}

		// Breadcrumb.
		$term  = dau_primary_category( get_the_ID() );
		$trail = array( __( 'Ana Sayfa', 'dr-alper-uslu' ) );
		if ( $term ) {
			$trail[] = $term->name;
		}
		$trail[] = $title;
		$items   = array();
		foreach ( $trail as $i => $name ) {
			$items[] = array( '@type' => 'ListItem', 'position' => $i + 1, 'name' => $name );
		}
		dau_print_jsonld( array( '@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => $items ) );
	}
}
add_action( 'wp_head', 'dau_output_schema', 20 );
