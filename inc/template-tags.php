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
			$term = get_queried_object();
			if ( $term instanceof WP_Term && $term->parent ) {
				foreach ( array_reverse( get_ancestors( $term->term_id, $term->taxonomy, 'taxonomy' ) ) as $ancestor_id ) {
					$ancestor = get_term( $ancestor_id, $term->taxonomy );
					if ( $ancestor instanceof WP_Term ) {
						$items[] = array(
							'label' => $ancestor->name,
							'url'   => get_term_link( $ancestor ),
						);
					}
				}
			}
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
 * Image sizes the Customizer crops to and validates against.
 *
 * Both hero modes share one 13:5 ratio so switching modes never distorts a
 * banner; only the pixel size changes.
 *
 * @return array
 */
function dmd_hero_image_specs() {
	return apply_filters(
		'dmd_hero_image_specs',
		array(
			'container' => array(
				'width'     => 1300,
				'height'    => 500,
				'min_width' => 1040,
			),
			'full'      => array(
				'width'     => 1950,
				'height'    => 750,
				'min_width' => 1560,
			),
			'mobile'    => array(
				'width'     => 800,
				'height'    => 600,
				'min_width' => 600,
			),
			'promo'     => array(
				'width'     => 800,
				'height'    => 400,
				'min_width' => 600,
			),
		)
	);
}

/**
 * Saved hero width mode.
 *
 * @return string `container` or `full`.
 */
function dmd_get_hero_mode() {
	return 'full' === get_theme_mod( 'dmd_slider_width', 'container' ) ? 'full' : 'container';
}

/**
 * Resolve an image theme mod (attachment ID or legacy URL).
 *
 * @param int|string $value Attachment ID or URL.
 * @param string     $size  Image size.
 * @return array{id:int,url:string,srcset:string,width:int,height:int}|null
 */
function dmd_resolve_image( $value, $size = 'full' ) {
	if ( empty( $value ) ) {
		return null;
	}

	$id = is_numeric( $value ) ? absint( $value ) : attachment_url_to_postid( $value );

	if ( $id ) {
		$src = wp_get_attachment_image_src( $id, $size );
		if ( $src ) {
			return array(
				'id'     => $id,
				'url'    => $src[0],
				'srcset' => (string) wp_get_attachment_image_srcset( $id, $size ),
				'width'  => (int) $src[1],
				'height' => (int) $src[2],
			);
		}
	}

	if ( is_numeric( $value ) ) {
		return null;
	}

	return array(
		'id'     => 0,
		'url'    => $value,
		'srcset' => '',
		'width'  => 0,
		'height' => 0,
	);
}

/**
 * Hero slides.
 *
 * Slides are managed in the Customizer (up to five). Each slide can point at an
 * image, an optional phone image, a headline, a supporting line and a link.
 * When no slide is set up, the fallback banner is used.
 *
 * @return array
 */
function dmd_get_hero_slides() {
	$slides = array();

	for ( $i = 1; $i <= 5; $i++ ) {
		$image = dmd_resolve_image( get_theme_mod( "dmd_slide_{$i}_image" ) );
		$title = get_theme_mod( "dmd_slide_{$i}_title" );

		if ( ! $image && ! $title ) {
			continue;
		}

		$slides[] = array(
			'image'        => $image,
			'image_mobile' => dmd_resolve_image( get_theme_mod( "dmd_slide_{$i}_image_mobile" ) ),
			'title'        => $title,
			'subtitle'     => get_theme_mod( "dmd_slide_{$i}_subtitle" ),
			'cta_text'     => get_theme_mod( "dmd_slide_{$i}_cta_text" ),
			'cta_url'      => get_theme_mod( "dmd_slide_{$i}_cta_url" ),
		);
	}

	if ( ! $slides ) {
		$image = dmd_resolve_image( get_theme_mod( 'dmd_hero_fallback_image' ) );
		$title = get_theme_mod( 'dmd_hero_fallback_title' );

		if ( $image || $title ) {
			$slides[] = array(
				'image'        => $image,
				'image_mobile' => null,
				'title'        => $title,
				'subtitle'     => '',
				'cta_text'     => '',
				'cta_url'      => get_theme_mod( 'dmd_hero_fallback_url' ),
			);
		}
	}

	return apply_filters( 'dmd_hero_slides', $slides );
}

/**
 * Section heading with an optional eyebrow line and "view all" link.
 *
 * @param array $args {
 *     @type string $title     Heading text.
 *     @type string $eyebrow   Small line above the heading.
 *     @type string $id        Heading id, for aria-labelledby.
 *     @type string $link_url  "View all" URL.
 *     @type string $link_text "View all" label.
 *     @type string $tag       Heading tag, h2 by default.
 * }
 */
function dmd_section_heading( $args ) {
	$args = wp_parse_args(
		$args,
		array(
			'title'     => '',
			'eyebrow'   => '',
			'id'        => '',
			'link_url'  => '',
			'link_text' => __( 'View all', 'digital-mudir-dokan' ),
			'tag'       => 'h2',
		)
	);

	$tag = in_array( $args['tag'], array( 'h1', 'h2', 'h3' ), true ) ? $args['tag'] : 'h2';

	echo '<div class="dmd-section-head">';
	echo '<div class="min-w-0">';
	if ( $args['eyebrow'] ) {
		echo '<p class="dmd-eyebrow">' . esc_html( $args['eyebrow'] ) . '</p>';
	}
	printf(
		'<%1$s%2$s class="dmd-heading">%3$s</%1$s>',
		esc_html( $tag ),
		$args['id'] ? ' id="' . esc_attr( $args['id'] ) . '"' : '',
		esc_html( $args['title'] )
	);
	echo '</div>';

	if ( $args['link_url'] ) {
		printf(
			'<a class="dmd-link-arrow" href="%1$s">%2$s %3$s</a>',
			esc_url( $args['link_url'] ),
			esc_html( $args['link_text'] ),
			dmd_icon( 'chevron-r', 16 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG.
		);
	}
	echo '</div>';
}

/**
 * Top-level product categories in the shop's admin sort order.
 *
 * @param int  $limit      Max terms, 0 for all.
 * @param bool $hide_empty Skip categories without products.
 * @return WP_Term[]
 */
function dmd_get_top_categories( $limit = 0, $hide_empty = false ) {
	if ( ! taxonomy_exists( 'product_cat' ) ) {
		return array();
	}

	$terms = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'parent'     => 0,
			'hide_empty' => $hide_empty,
			'number'     => $limit,
			'exclude'    => array( (int) get_option( 'default_product_cat' ) ),
		)
	);

	return is_wp_error( $terms ) ? array() : $terms;
}

/**
 * Category image URL: the category thumbnail, otherwise the image of its
 * newest product. Lookups are cached until products or categories change.
 *
 * @param WP_Term $term Product category.
 * @param string  $size Image size.
 * @return string
 */
function dmd_get_term_image_url( $term, $size = 'woocommerce_thumbnail' ) {
	$thumb_id = (int) get_term_meta( $term->term_id, 'thumbnail_id', true );
	if ( $thumb_id ) {
		$url = wp_get_attachment_image_url( $thumb_id, $size );
		if ( $url ) {
			return $url;
		}
	}

	$cache = get_transient( 'dmd_term_images' );
	$cache = is_array( $cache ) ? $cache : array();
	$key   = $term->term_id . ':' . $size;

	if ( ! isset( $cache[ $key ] ) ) {
		$ids = get_posts(
			array(
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'meta_key'       => '_thumbnail_id', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'no_found_rows'  => true,
				'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					array(
						'taxonomy' => 'product_cat',
						'terms'    => $term->term_id,
					),
				),
			)
		);

		$cache[ $key ] = $ids ? (string) get_the_post_thumbnail_url( $ids[0], $size ) : '';
		set_transient( 'dmd_term_images', $cache, DAY_IN_SECONDS );
	}

	return $cache[ $key ];
}

/**
 * Drop cached category images when products or categories change.
 */
function dmd_flush_term_images() {
	delete_transient( 'dmd_term_images' );
}
add_action( 'save_post_product', 'dmd_flush_term_images' );
add_action( 'edited_product_cat', 'dmd_flush_term_images' );
add_action( 'created_product_cat', 'dmd_flush_term_images' );
add_action( 'delete_product_cat', 'dmd_flush_term_images' );

/**
 * URL of the page using the "All Categories" template, or the shop page.
 *
 * @return string
 */
function dmd_get_all_categories_url() {
	static $url = null;

	if ( null !== $url ) {
		return $url;
	}

	$pages = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
			'meta_key'       => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value'     => 'page-templates/template-all-categories.php', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
		)
	);

	if ( $pages ) {
		$url = get_permalink( $pages[0] );
	} elseif ( function_exists( 'wc_get_page_permalink' ) ) {
		$url = wc_get_page_permalink( 'shop' );
	} else {
		$url = home_url( '/' );
	}

	return $url;
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
