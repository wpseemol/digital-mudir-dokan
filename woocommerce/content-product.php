<?php
/**
 * Product card used in loops.
 *
 * Archives print the grid and list layouts and let the view switcher pick
 * one; everywhere else (homepage, related products) only the grid card.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

if ( is_shop() || is_product_taxonomy() || is_search() ) {
	wc_get_template_part( 'content', 'product-archive' );
} else {
	wc_get_template_part( 'content', 'product-homepage' );
}
