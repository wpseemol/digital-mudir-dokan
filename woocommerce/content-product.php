<?php
/**
 * Product card used in loops.
 * This file redirects to the correct template based on context.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

if ( is_front_page() ) {
    wc_get_template_part( 'content', 'product-homepage' );
} else {
    wc_get_template_part( 'content', 'product-archive' );
}
