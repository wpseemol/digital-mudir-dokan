<?php
/**
 * Digital Mudir Dokan — theme bootstrap.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

define( 'DMD_VERSION', '1.0.0' );
define( 'DMD_DIR', get_template_directory() );
define( 'DMD_URI', get_template_directory_uri() );

/**
 * Theme setup.
 */
function dmd_theme_setup() {
	load_theme_textdomain( 'digital-mudir-dokan', DMD_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );

	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);

	add_theme_support(
		'custom-logo',
		array(
			'height'      => 60,
			'width'       => 180,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	// WooCommerce.
	add_theme_support(
		'woocommerce',
		array(
			'thumbnail_image_width' => 450,
			'single_image_width'    => 900,
			'product_grid'          => array(
				'default_rows'    => 3,
				'min_rows'        => 1,
				'default_columns' => 4,
				'min_columns'     => 2,
				'max_columns'     => 5,
			),
		)
	);
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	add_image_size( 'dmd-product-card', 450, 450, true );
	add_image_size( 'dmd-hero', 1600, 620, true );
	add_image_size( 'dmd-blog-card', 600, 420, true );

	register_nav_menus(
		array(
			'primary' => __( 'Primary menu', 'digital-mudir-dokan' ),
			'footer'  => __( 'Footer menu', 'digital-mudir-dokan' ),
			'mobile'  => __( 'Mobile menu', 'digital-mudir-dokan' ),
		)
	);

	// Editor palette mirrors the Tailwind tokens.
	add_theme_support(
		'editor-color-palette',
		array(
			array(
				'name'  => __( 'Brand green', 'digital-mudir-dokan' ),
				'slug'  => 'brand-green',
				'color' => '#113D21',
			),
			array(
				'name'  => __( 'Deep green', 'digital-mudir-dokan' ),
				'slug'  => 'brand-green-dark',
				'color' => '#058a36',
			),
			array(
				'name'  => __( 'Ink', 'digital-mudir-dokan' ),
				'slug'  => 'ink',
				'color' => '#1b1b1b',
			),
			array(
				'name'  => __( 'Surface', 'digital-mudir-dokan' ),
				'slug'  => 'surface',
				'color' => '#f3f5f7',
			),
		)
	);
}
add_action( 'after_setup_theme', 'dmd_theme_setup' );

/**
 * Content width.
 */
function dmd_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'dmd_content_width', 1200 );
}
add_action( 'after_setup_theme', 'dmd_content_width', 0 );

/**
 * Front-end assets.
 */
function dmd_assets() {
	// Google Fonts: Poppins for Latin display, Hind Siliguri for Bangla body copy.
	wp_enqueue_style(
		'dmd-fonts',
		'https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap',
		array(),
		null
	);

	// Swiper.js
	wp_enqueue_style( 'swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '11.0.0' );
	wp_enqueue_script( 'swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11.0.0', true );

	wp_enqueue_style( 'dmd-theme', DMD_URI . '/assets/css/theme.css', array(), time() );
	wp_enqueue_style( 'dmd-overrides', DMD_URI . '/assets/css/woocommerce-overrides.css', array( 'dmd-theme' ), time() );
	wp_enqueue_style( 'dmd-style', get_stylesheet_uri(), array( 'dmd-theme' ), time() );

	wp_enqueue_script( 'dmd-theme', DMD_URI . '/assets/js/theme.js', array( 'swiper-js' ), DMD_VERSION, true );
	wp_enqueue_script( 'dmd-live-search', DMD_URI . '/assets/js/live-search.js', array( 'jquery' ), DMD_VERSION, true );
	wp_localize_script(
		'dmd-live-search',
		'dmd_ajax_object',
		array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'dmd_search_nonce' ),
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'dmd_assets', 20 );

/**
 * Preconnect to the font CDN so the first paint is not blocked.
 *
 * @param array  $urls           URLs to print.
 * @param string $relation_type  Relation type.
 * @return array
 */
function dmd_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type && wp_style_is( 'dmd-fonts', 'queue' ) ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'dmd_resource_hints', 10, 2 );

/**
 * Widget areas.
 */
function dmd_widgets() {
	register_sidebar(
		array(
			'name'          => __( 'Blog sidebar', 'digital-mudir-dokan' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Shows beside posts and pages.', 'digital-mudir-dokan' ),
			'before_widget' => '<section id="%1$s" class="dmd-widget %2$s mb-6 rounded-lg border border-line p-5">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="dmd-widget__title mb-3 text-base font-semibold">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Shop sidebar', 'digital-mudir-dokan' ),
			'id'            => 'shop-sidebar',
			'description'   => __( 'Shows on shop and product archive pages.', 'digital-mudir-dokan' ),
			'before_widget' => '<section id="%1$s" class="dmd-widget %2$s mb-5 rounded-lg border border-line bg-white p-5">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="dmd-widget__title mb-3 text-base font-semibold">',
			'after_title'   => '</h2>',
		)
	);

	for ( $i = 1; $i <= 3; $i++ ) {
		register_sidebar(
			array(
				/* translators: %d: footer column number. */
				'name'          => sprintf( __( 'Footer column %d', 'digital-mudir-dokan' ), $i ),
				'id'            => 'footer-' . $i,
				'before_widget' => '<section id="%1$s" class="dmd-widget %2$s mb-6">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="mb-3 text-sm font-semibold uppercase tracking-wide">',
				'after_title'   => '</h2>',
			)
		);
	}
}
add_action( 'widgets_init', 'dmd_widgets' );

require DMD_DIR . '/inc/template-functions.php';
require DMD_DIR . '/inc/template-tags.php';
require DMD_DIR . '/inc/class-dmd-nav-walker.php';
require DMD_DIR . '/inc/customizer.php';
require DMD_DIR . '/inc/seo-schema.php';

if ( class_exists( 'WooCommerce' ) ) {
	require DMD_DIR . '/inc/woocommerce.php';
}
