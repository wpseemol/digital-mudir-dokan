<?php
/**
 * General template behaviour.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

/**
 * Extra body classes.
 *
 * @param array $classes Body classes.
 * @return array
 */
function dmd_body_classes( $classes ) {
	$classes[] = 'no-js';

	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	if ( is_singular() && ! is_front_page() ) {
		$classes[] = 'singular';
	}

	return $classes;
}
add_filter( 'body_class', 'dmd_body_classes' );

/**
 * Swap the `no-js` class the moment scripting is available.
 */
function dmd_no_js_class() {
	?>
	<script>document.body.className = document.body.className.replace(/\bno-js\b/, 'js');</script>
	<?php
}
add_action( 'wp_body_open', 'dmd_no_js_class', 1 );

/**
 * Excerpt length.
 *
 * @param int $length Default length.
 * @return int
 */
function dmd_excerpt_length( $length ) {
	return is_admin() ? $length : 22;
}
add_filter( 'excerpt_length', 'dmd_excerpt_length' );

/**
 * Excerpt ellipsis.
 *
 * @return string
 */
function dmd_excerpt_more() {
	return is_admin() ? '[…]' : '…';
}
add_filter( 'excerpt_more', 'dmd_excerpt_more' );

/**
 * Add a `rel="noopener"` safe target to the skip link container.
 *
 * @param string $title Archive title.
 * @return string
 */
function dmd_archive_title( $title ) {
	if ( is_category() || is_tag() || is_tax() ) {
		$title = single_term_title( '', false );
	} elseif ( is_author() ) {
		$title = get_the_author();
	} elseif ( is_post_type_archive() ) {
		$title = post_type_archive_title( '', false );
	}
	return $title;
}
add_filter( 'get_the_archive_title', 'dmd_archive_title' );

/**
 * Return a theme SVG icon.
 *
 * @param string $name  Icon name.
 * @param int    $size  Pixel size.
 * @param string $class Extra classes.
 * @return string
 */
function dmd_icon( $name, $size = 20, $class = '' ) {
	$paths = array(
		'search'     => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.2-3.2"/>',
		'cart'       => '<path d="M3 4h2l2.4 10.4A2 2 0 0 0 9.35 16h7.5a2 2 0 0 0 1.95-1.55L20.5 7H6"/><circle cx="10" cy="20" r="1.4"/><circle cx="17" cy="20" r="1.4"/>',
		'heart'      => '<path d="M20.2 5.6a5 5 0 0 0-7.1 0L12 6.7l-1.1-1.1a5 5 0 1 0-7.1 7.1l8.2 8.2 8.2-8.2a5 5 0 0 0 0-7.1Z"/>',
		'user'       => '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>',
		'menu'       => '<path d="M4 7h16M4 12h16M4 17h16"/>',
		'close'      => '<path d="M6 6l12 12M18 6 6 18"/>',
		'chevron-l'  => '<path d="m15 6-6 6 6 6"/>',
		'chevron-r'  => '<path d="m9 6 6 6-6 6"/>',
		'chevron-d'  => '<path d="m6 9 6 6 6-6"/>',
		'phone'      => '<path d="M6 3h3l2 5-2.5 1.5a12 12 0 0 0 6 6L16 13l5 2v3a2 2 0 0 1-2.2 2A17 17 0 0 1 4 5.2 2 2 0 0 1 6 3Z"/>',
		'mail'       => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/>',
		'pin'        => '<path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11Z"/><circle cx="12" cy="10" r="2.6"/>',
		'check'      => '<path d="m5 13 4 4L19 7"/>',
		'truck'      => '<path d="M3 7h11v9H3z"/><path d="M14 10h4l3 3v3h-7z"/><circle cx="7" cy="18" r="1.6"/><circle cx="17.5" cy="18" r="1.6"/>',
		'shield'     => '<path d="M12 3l7 3v6c0 4.4-3 8-7 9-4-1-7-4.6-7-9V6Z"/><path d="m9 12 2 2 4-4"/>',
		'refresh'    => '<path d="M20 11a8 8 0 1 0-.7 4"/><path d="M20 5v6h-6"/>',
		'zoom'       => '<circle cx="11" cy="11" r="7"/><path d="M11 8v6M8 11h6M20 20l-3.2-3.2"/>',
		'plus'       => '<path d="M12 5v14M5 12h14"/>',
		'minus'      => '<path d="M5 12h14"/>',
	);

	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}

	return sprintf(
		'<svg class="dmd-icon %1$s" width="%2$d" height="%2$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%3$s</svg>',
		esc_attr( $class ),
		(int) $size,
		$paths[ $name ] // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static markup.
	);
}

/**
 * Echo a theme icon.
 *
 * @param string $name  Icon name.
 * @param int    $size  Pixel size.
 * @param string $class Extra classes.
 */
function dmd_the_icon( $name, $size = 20, $class = '' ) {
	echo dmd_icon( $name, $size, $class ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Social channels configured in the Customizer.
 *
 * @return array
 */
function dmd_social_links() {
	$networks = array(
		'facebook'  => __( 'Facebook', 'digital-mudir-dokan' ),
		'tiktok'    => __( 'TikTok', 'digital-mudir-dokan' ),
		'instagram' => __( 'Instagram', 'digital-mudir-dokan' ),
		'twitter'   => __( 'X', 'digital-mudir-dokan' ),
		'youtube'   => __( 'YouTube', 'digital-mudir-dokan' ),
	);

	$links = array();
	foreach ( $networks as $key => $label ) {
		$url = get_theme_mod( 'dmd_social_' . $key );
		if ( $url ) {
			$links[ $key ] = array(
				'label' => $label,
				'url'   => $url,
			);
		}
	}
	return $links;
}

/**
 * Brand SVG marks for each social network.
 *
 * @param string $key Network key.
 * @return string
 */
function dmd_social_icon( $key ) {
	$icons = array(
		'facebook'  => '<path d="M14 8.5h2V6h-2c-1.9 0-3.2 1.3-3.2 3.2V11H9v2.5h1.8V20h2.6v-6.5H15L15.4 11h-2v-1.6c0-.6.2-.9.6-.9Z"/>',
		'tiktok'    => '<path d="M14.2 4h2.2a4.6 4.6 0 0 0 3.6 3.6v2.3a6.9 6.9 0 0 1-3.6-1.2v5.7a5.2 5.2 0 1 1-5.2-5.2c.3 0 .6 0 .8.1v2.4a2.8 2.8 0 1 0 2 2.7Z"/>',
		'instagram' => '<rect x="4" y="4" width="16" height="16" rx="4.5" fill="none" stroke="currentColor" stroke-width="1.7"/><circle cx="12" cy="12" r="3.4" fill="none" stroke="currentColor" stroke-width="1.7"/><circle cx="16.6" cy="7.4" r="1"/>',
		'twitter'   => '<path d="M4.5 4h4l3.4 4.7L16.3 4h3l-5.4 6.4L20 20h-4l-3.7-5.1L7.9 20H5l5.8-6.9Z"/>',
		'youtube'   => '<path d="M21 12s0-3-.4-4.4a2.3 2.3 0 0 0-1.6-1.6C17.6 5.6 12 5.6 12 5.6s-5.6 0-7 .4A2.3 2.3 0 0 0 3.4 7.6C3 9 3 12 3 12s0 3 .4 4.4A2.3 2.3 0 0 0 5 18c1.4.4 7 .4 7 .4s5.6 0 7-.4a2.3 2.3 0 0 0 1.6-1.6c.4-1.4.4-4.4.4-4.4ZM10.3 14.7V9.3l4.6 2.7Z"/>',
	);

	if ( ! isset( $icons[ $key ] ) ) {
		return '';
	}

	return '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false">' . $icons[ $key ] . '</svg>';
}

/**
 * Human readable posted-on markup.
 */
function dmd_posted_on() {
	printf(
		'<time class="dmd-post-date text-sm text-muted" datetime="%1$s">%2$s</time>',
		esc_attr( get_the_date( DATE_W3C ) ),
		esc_html( get_the_date() )
	);
}

/**
 * Primary menu fallback: list product categories so a fresh install is never empty.
 */
function dmd_primary_menu_fallback() {
	if ( ! taxonomy_exists( 'product_cat' ) ) {
		return;
	}

	$terms = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => true,
			'number'     => 9,
		)
	);

	if ( is_wp_error( $terms ) || ! $terms ) {
		return;
	}

	echo '<ul class="dmd-menu m-0 flex flex-wrap items-center justify-center gap-x-7 p-0 list-none">';
	foreach ( $terms as $term ) {
		printf(
			'<li class="list-none"><a class="dmd-menu-link inline-flex items-center py-3 text-[15px] font-medium hover:text-green" href="%s">%s</a></li>',
			esc_url( get_term_link( $term ) ),
			esc_html( $term->name )
		);
	}
	echo '</ul>';
}

/**
 * Tags allowed when echoing theme SVG markup through wp_kses().
 *
 * wp_kses_post() strips <svg> outright, so icon output needs its own allowlist.
 *
 * @return array
 */
function dmd_svg_allowed_html() {
	$attrs = array(
		'class'            => true,
		'width'            => true,
		'height'           => true,
		'viewbox'          => true,
		'fill'             => true,
		'stroke'           => true,
		'stroke-width'     => true,
		'stroke-linecap'   => true,
		'stroke-linejoin'  => true,
		'aria-hidden'      => true,
		'aria-label'       => true,
		'focusable'        => true,
		'role'             => true,
		'xmlns'            => true,
	);

	return array(
		'svg'    => $attrs,
		'g'      => $attrs,
		'path'   => array_merge( $attrs, array( 'd' => true ) ),
		'circle' => array_merge( $attrs, array( 'cx' => true, 'cy' => true, 'r' => true ) ),
		'rect'   => array_merge( $attrs, array( 'x' => true, 'y' => true, 'rx' => true, 'ry' => true ) ),
		'line'   => array_merge( $attrs, array( 'x1' => true, 'x2' => true, 'y1' => true, 'y2' => true ) ),
		'polygon' => array_merge( $attrs, array( 'points' => true ) ),
		'span'   => array( 'class' => true ),
		'a'      => array( 'class' => true, 'href' => true, 'title' => true ),
		'ul'     => array( 'class' => true ),
		'li'     => array( 'class' => true ),
		'nav'    => array( 'class' => true, 'aria-label' => true ),
	);
}
