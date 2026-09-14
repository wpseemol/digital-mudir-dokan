<?php
/**
 * Front page.
 *
 * Order of the page: hero slider → top selling → all products → trust row →
 * customer videos → blog. Any content written on the assigned static front page
 * is printed between the product grids and the video wall.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

get_header();

get_template_part( 'template-parts/hero-slider' );

if ( class_exists( 'WooCommerce' ) ) {

	get_template_part(
		'template-parts/product-section',
		null,
		array(
			'id'        => 'top-selling',
			'title'     => get_theme_mod( 'dmd_top_selling_title', __( 'Top selling products', 'digital-mudir-dokan' ) ),
			'limit'     => (int) get_theme_mod( 'dmd_top_selling_count', 4 ),
			'source'    => 'best_selling',
			'show_tabs' => false,
			'show_link' => false,
		)
	);

	get_template_part(
		'template-parts/product-section',
		null,
		array(
			'id'        => 'all-products',
			'title'     => get_theme_mod( 'dmd_all_products_title', __( 'All products', 'digital-mudir-dokan' ) ),
			'limit'     => (int) get_theme_mod( 'dmd_product_count', 12 ),
			'source'    => 'recent',
			'show_tabs' => get_theme_mod( 'dmd_show_category_tabs', true ),
			'show_link' => get_theme_mod( 'dmd_show_view_all_link', true ),
		)
	);
}

get_template_part( 'template-parts/trust-row' );

// Content of the static front page, if one is assigned and has content.
if ( is_page() && have_posts() ) {
	while ( have_posts() ) :
		the_post();

		$dmd_content = trim( get_the_content() );

		if ( $dmd_content ) :
			?>
<section class="dmd-front-content bg-white py-12">
    <div class="dmd-container">
        <div class="dmd-prose mx-auto max-w-[70ch]">
            <?php the_content(); ?>
        </div>
    </div>
</section>
<?php
		endif;
	endwhile;
}

get_template_part( 'template-parts/video-wall' );
get_template_part( 'template-parts/blog-strip' );
get_template_part( 'template-parts/faq-section' );

get_footer();