<?php
/**
 * Reusable markup helpers.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

/**
 * Semantic breadcrumb trail with schema.org markup.
 *
 * Outputs a <nav> containing an ordered list, which is what search engines and
 * screen readers both expect from a breadcrumb.
 */
function dmd_breadcrumbs() {
	if ( is_front_page() ) {
		return;
	}

	$items = array(
		array(
			'label' => __( 'Home', 'digital-mudir-dokan' ),
			'url'   => home_url( '/' ),
		),
	);

	if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
		$shop_id = wc_get_page_id( 'shop' );
		if ( $shop_id && ! is_shop() ) {
			$items[] = array(
				'label' => get_the_title( $shop_id ),
				'url'   => get_permalink( $shop_id ),
			);
		}

		if ( is_product_category() || is_product_tag() ) {
			$items[] = array(
				'label' => single_term_title( '', false ),
				'url'   => '',
			);
		} elseif ( is_product() ) {
			$terms = get_the_terms( get_the_ID(), 'product_cat' );
			if ( $terms && ! is_wp_error( $terms ) ) {
				$term    = array_shift( $terms );
				$items[] = array(
					'label' => $term->name,
					'url'   => get_term_link( $term ),
				);
			}
			$items[] = array(
				'label' => get_the_title(),
				'url'   => '',
			);
		} elseif ( is_shop() ) {
			$items[] = array(
				'label' => get_the_title( $shop_id ),
				'url'   => '',
			);
		}
	} elseif ( is_singular( 'post' ) ) {
		$cats = get_the_category();
		if ( $cats ) {
			$items[] = array(
				'label' => $cats[0]->name,
				'url'   => get_category_link( $cats[0]->term_id ),
			);
		}
		$items[] = array(
			'label' => get_the_title(),
			'url'   => '',
		);
	} elseif ( is_page() ) {
		foreach ( array_reverse( get_post_ancestors( get_the_ID() ) ) as $ancestor ) {
			$items[] = array(
				'label' => get_the_title( $ancestor ),
				'url'   => get_permalink( $ancestor ),
			);
		}
		$items[] = array(
			'label' => get_the_title(),
			'url'   => '',
		);
	} elseif ( is_search() ) {
		$items[] = array(
			/* translators: %s: search term. */
			'label' => sprintf( __( 'Search results for “%s”', 'digital-mudir-dokan' ), get_search_query() ),
			'url'   => '',
		);
	} elseif ( is_archive() ) {
		$items[] = array(
			'label' => wp_strip_all_tags( get_the_archive_title() ),
			'url'   => '',
		);
	} elseif ( is_404() ) {
		$items[] = array(
			'label' => __( 'Page not found', 'digital-mudir-dokan' ),
			'url'   => '',
		);
	}

	echo '<nav class="dmd-breadcrumb text-[13px] text-muted" aria-label="' . esc_attr__( 'Breadcrumb', 'digital-mudir-dokan' ) . '">';
	echo '<ol class="flex flex-wrap items-center gap-1.5 m-0 p-0 list-none" itemscope itemtype="https://schema.org/BreadcrumbList">';

	$total = count( $items );
	foreach ( $items as $index => $item ) {
		echo '<li class="flex items-center gap-1.5 list-none" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';

		if ( $item['url'] ) {
			printf(
				'<a class="hover:text-green" href="%s" itemprop="item"><span itemprop="name">%s</span></a>',
				esc_url( $item['url'] ),
				esc_html( $item['label'] )
			);
		} else {
			printf(
				'<span class="text-ink" aria-current="page" itemprop="name">%s</span>',
				esc_html( $item['label'] )
			);
		}

		printf( '<meta itemprop="position" content="%d" />', (int) $index + 1 );

		if ( $index + 1 < $total ) {
			echo '<span class="text-line" aria-hidden="true">/</span>';
		}

		echo '</li>';
	}

	echo '</ol></nav>';
}

/**
 * Archive pagination wrapped in a labelled nav.
 */
function dmd_pagination() {
	$links = paginate_links(
		array(
			'type'      => 'list',
			'mid_size'  => 1,
			'prev_text' => dmd_icon( 'chevron-l', 16 ),
			'next_text' => dmd_icon( 'chevron-r', 16 ),
		)
	);

	if ( ! $links ) {
		return;
	}

	echo '<nav class="dmd-pagination mt-10" aria-label="' . esc_attr__( 'Posts pagination', 'digital-mudir-dokan' ) . '">';
	echo wp_kses( $links, dmd_svg_allowed_html() );
	echo '</nav>';
}

/**
 * Hero slides.
 *
 * Slides are managed in the Customizer (up to four). Each slide can point at an
 * image, a headline, a supporting line and a link.
 *
 * @return array
 */
function dmd_get_hero_slides() {
	$slides = array();

	for ( $i = 1; $i <= 4; $i++ ) {
		$image = get_theme_mod( "dmd_slide_{$i}_image" );
		$title = get_theme_mod( "dmd_slide_{$i}_title" );

		if ( ! $image && ! $title ) {
			continue;
		}

		$slides[] = array(
			'image'    => $image,
			'title'    => $title,
			'subtitle' => get_theme_mod( "dmd_slide_{$i}_subtitle" ),
			'cta_text' => get_theme_mod( "dmd_slide_{$i}_cta_text" ),
			'cta_url'  => get_theme_mod( "dmd_slide_{$i}_cta_url" ),
		);
	}

	// Nothing configured yet — show one editorial placeholder so the front page
	// never renders an empty band on a fresh install.
	if ( ! $slides ) {
		$slides[] = array(
			'image'    => '',
			'title'    => __( 'রোগ প্রতিরোধ ক্ষমতা বাড়ান', 'digital-mudir-dokan' ),
			'subtitle' => __( 'সুস্থ থাকুন স্বাভাবিকভাবে — ১০০% খাঁটি ও প্রাকৃতিক পণ্য।', 'digital-mudir-dokan' ),
			'cta_text' => __( 'Shop now', 'digital-mudir-dokan' ),
			'cta_url'  => function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' ),
		);
	}

	return apply_filters( 'dmd_hero_slides', $slides );
}

/**
 * Contact details used by the header bar and footer.
 *
 * @return array
 */
function dmd_contact() {
	return array(
		'whatsapp' => get_theme_mod( 'dmd_whatsapp', '+8801621611589' ),
		'hotline'  => get_theme_mod( 'dmd_hotline', '09647460074' ),
		'phone_1'  => get_theme_mod( 'dmd_phone_1', '+880 1621 611 589' ),
		'phone_2'  => get_theme_mod( 'dmd_phone_2', '+880 1788 871 247' ),
		'email_1'  => get_theme_mod( 'dmd_email_1', 'hello@example.com' ),
		'email_2'  => get_theme_mod( 'dmd_email_2', 'info@example.com' ),
		'address'  => get_theme_mod( 'dmd_address', __( 'Add your shop address in Customizer → Shop details.', 'digital-mudir-dokan' ) ),
		'messenger' => get_theme_mod( 'dmd_messenger_url', '' ),
	);
}

/**
 * Strip everything but digits from a phone number so it can be dialled.
 *
 * @param string $number Raw number.
 * @return string
 */
function dmd_tel( $number ) {
	return preg_replace( '/[^0-9+]/', '', (string) $number );
}
