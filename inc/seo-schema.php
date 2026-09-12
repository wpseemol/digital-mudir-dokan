<?php
/**
 * SEO output: meta description, Open Graph, Twitter cards and JSON-LD.
 *
 * Every block here defers to a dedicated SEO plugin when one is active, so the
 * theme never prints competing tags alongside Yoast, Rank Math or SEOPress.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

/**
 * Is a third-party SEO plugin handling meta output?
 *
 * @return bool
 */
function dmd_seo_plugin_active() {
	return (
		defined( 'WPSEO_VERSION' ) ||
		class_exists( 'RankMath' ) ||
		defined( 'SEOPRESS_VERSION' ) ||
		function_exists( 'aioseo' )
	);
}

/**
 * A trimmed, tag-free description for the current view.
 *
 * @return string
 */
function dmd_meta_description() {
	$description = '';

	if ( is_singular() ) {
		$post = get_queried_object();
		$raw  = has_excerpt( $post ) ? get_the_excerpt( $post ) : $post->post_content;
		$description = wp_strip_all_tags( strip_shortcodes( $raw ) );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$description = wp_strip_all_tags( term_description() );
	} elseif ( is_home() || is_front_page() ) {
		$description = get_bloginfo( 'description' );
	}

	if ( ! $description ) {
		$description = get_bloginfo( 'description' );
	}

	return trim( wp_html_excerpt( $description, 160, '…' ) );
}

/**
 * The best available sharing image for the current view.
 *
 * @return string
 */
function dmd_share_image() {
	if ( is_singular() && has_post_thumbnail() ) {
		return get_the_post_thumbnail_url( get_the_ID(), 'large' );
	}

	$logo_id = get_theme_mod( 'custom_logo' );
	if ( $logo_id ) {
		$src = wp_get_attachment_image_src( $logo_id, 'full' );
		if ( $src ) {
			return $src[0];
		}
	}

	return '';
}

/**
 * Print meta description, canonical and social tags.
 */
function dmd_head_meta() {
	if ( dmd_seo_plugin_active() ) {
		return;
	}

	$description = dmd_meta_description();
	$image       = dmd_share_image();
	$title       = wp_get_document_title();
	$url         = is_singular() ? get_permalink() : home_url( add_query_arg( array() ) );

	if ( $description ) {
		printf( '<meta name="description" content="%s" />' . "\n", esc_attr( $description ) );
	}

	printf( '<meta property="og:site_name" content="%s" />' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
	printf( '<meta property="og:title" content="%s" />' . "\n", esc_attr( $title ) );
	printf( '<meta property="og:type" content="%s" />' . "\n", esc_attr( is_singular( 'post' ) ? 'article' : 'website' ) );
	printf( '<meta property="og:url" content="%s" />' . "\n", esc_url( $url ) );

	if ( $description ) {
		printf( '<meta property="og:description" content="%s" />' . "\n", esc_attr( $description ) );
	}

	if ( $image ) {
		printf( '<meta property="og:image" content="%s" />' . "\n", esc_url( $image ) );
		echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
	} else {
		echo '<meta name="twitter:card" content="summary" />' . "\n";
	}

	printf( '<meta name="twitter:title" content="%s" />' . "\n", esc_attr( $title ) );

	if ( $description ) {
		printf( '<meta name="twitter:description" content="%s" />' . "\n", esc_attr( $description ) );
	}
}
add_action( 'wp_head', 'dmd_head_meta', 2 );

/**
 * JSON-LD graph: Organization, WebSite, and the page-specific node.
 */
function dmd_json_ld() {
	if ( dmd_seo_plugin_active() ) {
		return;
	}

	$contact  = dmd_contact();
	$site_url = home_url( '/' );
	$graph    = array();

	$organization = array(
		'@type'  => 'Organization',
		'@id'    => $site_url . '#organization',
		'name'   => get_bloginfo( 'name' ),
		'url'    => $site_url,
	);

	$logo_id = get_theme_mod( 'custom_logo' );
	if ( $logo_id ) {
		$src = wp_get_attachment_image_src( $logo_id, 'full' );
		if ( $src ) {
			$organization['logo'] = array(
				'@type'  => 'ImageObject',
				'url'    => $src[0],
				'width'  => $src[1],
				'height' => $src[2],
			);
		}
	}

	if ( $contact['phone_1'] ) {
		$organization['contactPoint'] = array(
			'@type'       => 'ContactPoint',
			'telephone'   => $contact['phone_1'],
			'contactType' => 'customer service',
		);
	}

	$profiles = wp_list_pluck( dmd_social_links(), 'url' );
	if ( $profiles ) {
		$organization['sameAs'] = array_values( $profiles );
	}

	$graph[] = $organization;

	$graph[] = array(
		'@type'           => 'WebSite',
		'@id'             => $site_url . '#website',
		'url'             => $site_url,
		'name'            => get_bloginfo( 'name' ),
		'description'     => get_bloginfo( 'description' ),
		'publisher'       => array( '@id' => $site_url . '#organization' ),
		'potentialAction' => array(
			'@type'       => 'SearchAction',
			'target'      => array(
				'@type'       => 'EntryPoint',
				'urlTemplate' => $site_url . '?s={search_term_string}',
			),
			'query-input' => 'required name=search_term_string',
		),
	);

	// Product.
	if ( function_exists( 'is_product' ) && is_product() ) {
		$product = wc_get_product( get_the_ID() );

		if ( $product ) {
			$node = array(
				'@type'       => 'Product',
				'@id'         => get_permalink() . '#product',
				'name'        => $product->get_name(),
				'description' => wp_strip_all_tags( $product->get_short_description() ? $product->get_short_description() : $product->get_description() ),
				'url'         => get_permalink(),
				'sku'         => $product->get_sku() ? $product->get_sku() : (string) $product->get_id(),
				'brand'       => array(
					'@type' => 'Brand',
					'name'  => get_bloginfo( 'name' ),
				),
			);

			$image_id = $product->get_image_id();
			if ( $image_id ) {
				$node['image'] = wp_get_attachment_image_url( $image_id, 'full' );
			}

			$node['offers'] = array(
				'@type'         => 'Offer',
				'url'           => get_permalink(),
				'price'         => wc_get_price_to_display( $product ),
				'priceCurrency' => get_woocommerce_currency(),
				'availability'  => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
				'seller'        => array( '@id' => $site_url . '#organization' ),
			);

			if ( $product->get_review_count() > 0 ) {
				$node['aggregateRating'] = array(
					'@type'       => 'AggregateRating',
					'ratingValue' => (string) $product->get_average_rating(),
					'reviewCount' => (string) $product->get_review_count(),
				);
			}

			$graph[] = $node;
		}
	} elseif ( is_singular( 'post' ) ) {
		$node = array(
			'@type'            => 'BlogPosting',
			'@id'              => get_permalink() . '#article',
			'headline'         => get_the_title(),
			'datePublished'    => get_the_date( DATE_W3C ),
			'dateModified'     => get_the_modified_date( DATE_W3C ),
			'mainEntityOfPage' => get_permalink(),
			'author'           => array(
				'@type' => 'Person',
				'name'  => get_the_author_meta( 'display_name', get_post_field( 'post_author', get_the_ID() ) ),
			),
			'publisher'        => array( '@id' => $site_url . '#organization' ),
		);

		if ( has_post_thumbnail() ) {
			$node['image'] = get_the_post_thumbnail_url( get_the_ID(), 'full' );
		}

		$graph[] = $node;
	}

	printf(
		'<script type="application/ld+json">%s</script>' . "\n",
		wp_json_encode(
			array(
				'@context' => 'https://schema.org',
				'@graph'   => $graph,
			),
			JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
		)
	);
}
add_action( 'wp_head', 'dmd_json_ld', 5 );

/**
 * Drop WooCommerce's own Product node when the theme is printing one, so a
 * product page never carries two competing Product entities.
 *
 * @param array $markup Structured data for the product.
 * @return array
 */
function dmd_dedupe_wc_schema( $markup ) {
	if ( ! dmd_seo_plugin_active() && function_exists( 'is_product' ) && is_product() ) {
		return array();
	}
	return $markup;
}
add_filter( 'woocommerce_structured_data_product', 'dmd_dedupe_wc_schema', 20, 1 );
