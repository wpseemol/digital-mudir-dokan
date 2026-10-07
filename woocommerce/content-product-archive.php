<?php
/**
 * Product card used on the shop and category archives (grid + list).
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php wc_product_class( 'dmd-product', $product ); ?>>
	<?php
	get_template_part(
		'template-parts/product-card',
		null,
		array(
			'product' => $product,
			'view'    => 'both',
			'heading' => 'h2',
		)
	);
	?>
</li>
