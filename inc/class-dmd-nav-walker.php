<?php
/**
 * Primary navigation walker.
 *
 * Renders an accessible, keyboard-operable dropdown menu.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class DMD_Nav_Walker
 */
class DMD_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * Start a sub level.
	 *
	 * @param string   $output Markup.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Arguments.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$indent  = str_repeat( "\t", $depth );
		$output .= "\n{$indent}<ul class=\"dmd-submenu invisible absolute left-0 top-full z-40 min-w-[220px] translate-y-1 rounded-md border border-line bg-white p-2 opacity-0 shadow-card transition-all duration-200 group-hover:visible group-hover:translate-y-0 group-hover:opacity-100 group-focus-within:visible group-focus-within:translate-y-0 group-focus-within:opacity-100\">\n";
	}

	/**
	 * End a sub level.
	 *
	 * @param string   $output Markup.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Arguments.
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$output .= str_repeat( "\t", $depth ) . "</ul>\n";
	}

	/**
	 * Start an element.
	 *
	 * @param string   $output Markup.
	 * @param WP_Post  $item   Menu item.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Arguments.
	 * @param int      $id     Item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$has_children = in_array( 'menu-item-has-children', (array) $item->classes, true );

		$li_classes = array( 'dmd-menu-item', 'relative', 'list-none' );
		if ( 0 === $depth ) {
			$li_classes[] = 'group';
		}
		if ( $has_children ) {
			$li_classes[] = 'dmd-menu-item--parent';
		}
		$li_classes = array_merge( $li_classes, array_filter( (array) $item->classes ) );

		$link_classes = 0 === $depth
			? 'dmd-menu-link inline-flex items-center gap-1 py-3 text-[15px] font-medium hover:text-green'
			: 'dmd-submenu-link block rounded px-3 py-2 text-sm hover:bg-green-soft hover:text-green';

		$current = in_array( 'current-menu-item', (array) $item->classes, true ) || in_array( 'current-menu-ancestor', (array) $item->classes, true );
		if ( $current ) {
			$link_classes .= ' text-green';
		}

		$atts = array(
			'href'         => $item->url ? $item->url : '#',
			'title'        => $item->attr_title,
			'target'       => $item->target,
			'rel'          => $item->xfn,
			'class'        => $link_classes,
			'aria-current' => $current ? 'page' : '',
		);

		$attributes = '';
		foreach ( $atts as $key => $value ) {
			if ( '' === $value || is_null( $value ) ) {
				continue;
			}
			$value       = ( 'href' === $key ) ? esc_url( $value ) : esc_attr( $value );
			$attributes .= ' ' . $key . '="' . $value . '"';
		}

		$title  = apply_filters( 'the_title', $item->title, $item->ID );
		$arrow  = $has_children ? dmd_icon( 'chevron-d', 14 ) : '';

		$output .= '<li class="' . esc_attr( implode( ' ', array_unique( $li_classes ) ) ) . '">';
		$output .= '<a' . $attributes . '><span>' . esc_html( $title ) . '</span>' . $arrow . '</a>';
	}

	/**
	 * End an element.
	 *
	 * @param string   $output Markup.
	 * @param WP_Post  $item   Menu item.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Arguments.
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= "</li>\n";
	}
}
