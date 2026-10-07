<?php
/**
 * Product card used in homepage sections and related-product grids.
 *
 * `data-dmd-cats` lets the homepage category tabs filter the grid.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php wc_product_class( 'dmd-product', $product ); ?> data-dmd-cats="<?php echo esc_attr( dmd_get_product_cat_slugs( $product ) ); ?>">
	<?php
	get_template_part(
		'template-parts/product-card',
		null,
		array(
			'product' => $product,
			'view'    => 'grid',
			'heading' => 'h3',
		)
	);
	?>
</li>
