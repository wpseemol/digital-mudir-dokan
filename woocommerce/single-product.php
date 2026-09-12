<?php
/**
 * Single product wrapper.
 *
 * @package Digital_Mudir_Dokan
 * @version 1.6.4
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

while ( have_posts() ) :
	the_post();

	wc_get_template_part( 'content', 'single-product' );

endwhile;

get_footer( 'shop' );
