<?php
/**
 * Front page.
 *
 * Order: hero → shop by category → service highlights → top selling →
 * promo banners → all products → order band → page content → videos →
 * blog → FAQ. Each section hides itself when it has nothing to show.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<h1 class="screen-reader-text"><?php echo esc_html( get_bloginfo( 'name' ) . ' — ' . get_bloginfo( 'description' ) ); ?></h1>
<?php

get_template_part( 'template-parts/hero-slider' );

if ( class_exists( 'WooCommerce' ) && get_theme_mod( 'dmd_show_home_categories', true ) ) {
	get_template_part( 'template-parts/category-strip' );
}

if ( get_theme_mod( 'dmd_show_trust_row', true ) ) {
	get_template_part( 'template-parts/trust-row' );
}

if ( class_exists( 'WooCommerce' ) ) {
	get_template_part(
		'template-parts/product-section',
		null,
		array(
			'id'        => 'top-selling',
			'title'     => get_theme_mod( 'dmd_top_selling_title', __( 'Top selling products', 'digital-mudir-dokan' ) ),
			'eyebrow'   => __( 'Customer favourites', 'digital-mudir-dokan' ),
			'limit'     => (int) get_theme_mod( 'dmd_top_selling_count', 4 ),
			'source'    => 'best_selling',
			'show_tabs' => false,
			'show_link' => true,
			'class'     => 'mt-10 bg-surface/70 sm:mt-14',
		)
	);

	get_template_part( 'template-parts/promo-banners' );

	get_template_part(
		'template-parts/product-section',
		null,
		array(
			'id'        => 'all-products',
			'title'     => get_theme_mod( 'dmd_all_products_title', __( 'Our products', 'digital-mudir-dokan' ) ),
			'eyebrow'   => __( 'Fresh arrivals', 'digital-mudir-dokan' ),
			'limit'     => (int) get_theme_mod( 'dmd_product_count', 12 ),
			'source'    => 'recent',
			'show_tabs' => get_theme_mod( 'dmd_show_category_tabs', true ),
			'show_link' => get_theme_mod( 'dmd_show_view_all_link', true ),
		)
	);
}

if ( get_theme_mod( 'dmd_show_cta_band', true ) ) {
	get_template_part( 'template-parts/cta-band' );
}

// Content of the static front page, if one is assigned and has content.
if ( is_page() && have_posts() ) {
	while ( have_posts() ) :
		the_post();

		if ( trim( get_the_content() ) ) :
			?>
			<section class="dmd-front-content dmd-section">
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
