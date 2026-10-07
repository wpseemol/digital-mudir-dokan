<?php
/**
 * Single product up-sells.
 *
 * Mirrors woocommerce/single-product/related.php so both product strips share
 * the same responsive grid and card presentation.
 *
 * @package Digital_Mudir_Dokan
 * @version 9.6.0
 */

defined( 'ABSPATH' ) || exit;

if ( $upsells ) :

	$dmd_upsells_heading = apply_filters( 'woocommerce_product_upsells_products_heading', __( 'You may also like&hellip;', 'digital-mudir-dokan' ) );
	?>

	<section class="up-sells upsells products mt-12 pt-8 border-t border-gray-100">

		<?php if ( $dmd_upsells_heading ) : ?>
			<h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-6"><?php echo esc_html( $dmd_upsells_heading ); ?></h2>
		<?php endif; ?>

		<ul class="products grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6 list-none p-0 m-0 grid-view">

			<?php foreach ( $upsells as $dmd_upsell ) : ?>

				<?php
				$dmd_post_object = get_post( $dmd_upsell->get_id() );

				setup_postdata( $GLOBALS['post'] = $dmd_post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found

				wc_get_template_part( 'content', 'product' );
				?>

			<?php endforeach; ?>

		</ul>

	</section>

	<?php
endif;

wp_reset_postdata();
