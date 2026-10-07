<?php
/**
 * Variable product add to cart.
 *
 * Styled dropdowns replace the default table layout. The markup WooCommerce's
 * variation script depends on is preserved: `.variations select`,
 * `.single_variation_wrap`, `.reset_variations`, `.reset_variations_alert`
 * and the `variation_id` hidden input are all rendered below.
 *
 * @package Digital_Mudir_Dokan
 * @version 11.1.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

$dmd_attribute_keys = array_keys( $attributes );
$dmd_variations_json = wp_json_encode( $available_variations );
$dmd_variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $dmd_variations_json ) : _wp_specialchars( $dmd_variations_json, ENT_QUOTES, 'UTF-8', true );

do_action( 'woocommerce_before_add_to_cart_form' );
?>

<form class="variations_form cart dmd-variations-form"
	action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>"
	method="post"
	enctype="multipart/form-data"
	data-product_id="<?php echo absint( $product->get_id() ); ?>"
	data-product_variations="<?php echo $dmd_variations_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">

	<?php do_action( 'woocommerce_before_variations_form' ); ?>

	<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>

		<p class="stock out-of-stock m-0 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
			<?php echo esc_html( apply_filters( 'woocommerce_out_of_stock_message', __( 'This product is currently out of stock and unavailable.', 'digital-mudir-dokan' ) ) ); ?>
		</p>

	<?php else : ?>

		<div class="variations dmd-variations flex flex-col gap-5" role="presentation">

			<?php foreach ( $attributes as $dmd_attribute_name => $dmd_options ) : ?>

				<div class="dmd-variation-field flex flex-col">

					<label class="block text-sm font-semibold text-gray-800 mb-2"
						for="<?php echo esc_attr( sanitize_title( $dmd_attribute_name ) ); ?>">
						<?php echo esc_html( wc_attribute_label( $dmd_attribute_name ) ); ?>
					</label>

					<?php
					wc_dropdown_variation_attribute_options(
						array(
							'options'   => $dmd_options,
							'attribute' => $dmd_attribute_name,
							'product'   => $product,
							'class'     => 'w-full max-w-xs px-4 py-2.5 rounded-xl border border-gray-300 text-gray-800 bg-white focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 outline-none text-sm transition shadow-sm',
						)
					);
					?>

					<?php
					/*
					 * Filled by assets/js/theme.js, which also hides the select it
					 * mirrors. Left empty (and with `hidden`) so the dropdown stays
					 * usable when scripting is unavailable.
					 */
					?>
					<div class="dmd-variation-chips" data-dmd-chips hidden></div>

				</div>

			<?php endforeach; ?>

			<?php if ( $dmd_attribute_keys ) : ?>
				<div class="dmd-variation-reset">
					<?php
					echo wp_kses_post(
						apply_filters(
							'woocommerce_reset_variations_link',
							'<a class="reset_variations inline-block text-xs font-medium text-muted underline underline-offset-2 hover:text-green" href="#" aria-label="' . esc_attr__( 'Clear options', 'digital-mudir-dokan' ) . '">' . esc_html__( 'Clear', 'digital-mudir-dokan' ) . '</a>'
						)
					);
					?>
				</div>
			<?php endif; ?>

		</div>

		<div class="reset_variations_alert screen-reader-text" role="alert" aria-live="polite" aria-relevant="all"></div>

		<?php if ( function_exists( 'wc_get_product_gallery_html' ) ) : ?>
			<template class="wc-product-gallery-default-template"><?php echo wc_get_product_gallery_html( $product ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></template>
		<?php endif; ?>

		<?php do_action( 'woocommerce_after_variations_table' ); ?>

		<div class="single_variation_wrap">
			<?php
			do_action( 'woocommerce_before_single_variation' );
			do_action( 'woocommerce_single_variation' );
			do_action( 'woocommerce_after_single_variation' );
			?>
		</div>

	<?php endif; ?>

	<?php do_action( 'woocommerce_after_variations_form' ); ?>

</form>

<?php
do_action( 'woocommerce_after_add_to_cart_form' );
