<?php
/**
 * Related products.
 *
 * Replaces WooCommerce's floated `woocommerce_product_loop_start()` markup with
 * a responsive grid. The `grid-view` class keeps the shared product card
 * (woocommerce/content-product-archive.php) in its grid presentation.
 *
 * @package Digital_Mudir_Dokan
 * @version 10.3.0
 */

defined( 'ABSPATH' ) || exit;

if ( $related_products ) :

	/**
	 * Ensure all images of related products are lazy loaded by increasing the
	 * current media count to WordPress's lazy loading threshold if needed.
	 * Because wp_increase_content_media_count() is a private function, we
	 * check for its existence before use.
	 */
	if ( function_exists( 'wp_increase_content_media_count' ) ) {
		$dmd_content_media_count = wp_increase_content_media_count( 0 );
		if ( $dmd_content_media_count < wp_omit_loading_attr_threshold() ) {
			wp_increase_content_media_count( wp_omit_loading_attr_threshold() - $dmd_content_media_count );
		}
	}

	$dmd_related_heading = apply_filters( 'woocommerce_product_related_products_heading', __( 'Related products', 'digital-mudir-dokan' ) );
	?>

	<section class="related products mt-12 pt-8 border-t border-gray-100">

		<?php if ( $dmd_related_heading ) : ?>
			<h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-6"><?php echo esc_html( $dmd_related_heading ); ?></h2>
		<?php endif; ?>

		<ul class="products grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6 list-none p-0 m-0 grid-view">

			<?php foreach ( $related_products as $dmd_related_product ) : ?>

				<?php
				$dmd_post_object = get_post( $dmd_related_product->get_id() );

				setup_postdata( $GLOBALS['post'] = $dmd_post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found

				wc_get_template_part( 'content', 'product' );
				?>

			<?php endforeach; ?>

		</ul>

	</section>

	<?php
endif;

wp_reset_postdata();
