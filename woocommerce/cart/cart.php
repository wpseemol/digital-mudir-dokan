<?php
/**
 * Cart page.
 *
 * @package Digital_Mudir_Dokan
 * @version 7.9.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' );
?>
<div class="dmd-cart grid gap-8 grid-cols-1 lg:grid-cols-12 lg:items-start">

	<form class="woocommerce-cart-form lg:col-span-8" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
		<?php do_action( 'woocommerce_before_cart_table' ); ?>

		<div class="overflow-x-auto rounded-lg border border-line bg-white">
			<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
				<caption class="screen-reader-text"><?php esc_html_e( 'Products in your cart', 'digital-mudir-dokan' ); ?></caption>
				<thead>
					<tr>
						<th class="product-remove"><span class="screen-reader-text"><?php esc_html_e( 'Remove item', 'digital-mudir-dokan' ); ?></span></th>
						<th class="product-thumbnail"><span class="screen-reader-text"><?php esc_html_e( 'Image', 'digital-mudir-dokan' ); ?></span></th>
						<th class="product-name" scope="col"><?php esc_html_e( 'Product', 'digital-mudir-dokan' ); ?></th>
						<th class="product-price" scope="col"><?php esc_html_e( 'Price', 'digital-mudir-dokan' ); ?></th>
						<th class="product-quantity" scope="col"><?php esc_html_e( 'Quantity', 'digital-mudir-dokan' ); ?></th>
						<th class="product-subtotal" scope="col"><?php esc_html_e( 'Subtotal', 'digital-mudir-dokan' ); ?></th>
					</tr>
				</thead>

				<tbody>
					<?php do_action( 'woocommerce_before_cart_contents' ); ?>

					<?php
					foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
						$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
						$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

						if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
							$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
							?>
							<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

								<td class="product-remove w-10">
									<?php
									echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										'woocommerce_cart_item_remove_link',
										sprintf(
											'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
											esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
											/* translators: %s: product name. */
											esc_attr( sprintf( __( 'Remove %s from cart', 'digital-mudir-dokan' ), wp_strip_all_tags( $_product->get_name() ) ) ),
											esc_attr( $product_id ),
											esc_attr( $_product->get_sku() )
										),
										$cart_item_key
									);
									?>
								</td>

								<td class="product-thumbnail w-20">
									<?php
									$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'woocommerce_gallery_thumbnail', array( 'class' => 'rounded-md' ) ), $cart_item, $cart_item_key );

									if ( ! $product_permalink ) {
										echo wp_kses_post( $thumbnail );
									} else {
										printf( '<a href="%s" tabindex="-1" aria-hidden="true">%s</a>', esc_url( $product_permalink ), wp_kses_post( $thumbnail ) );
									}
									?>
								</td>

								<td class="product-name" data-title="<?php esc_attr_e( 'Product', 'digital-mudir-dokan' ); ?>">
									<?php
									if ( ! $product_permalink ) {
										echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
									} else {
										echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a class="font-medium" href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
									}

									do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

									echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

									if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
										echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification text-xs text-muted">' . esc_html__( 'Available on backorder', 'digital-mudir-dokan' ) . '</p>', $product_id ) );
									}
									?>
								</td>

								<td class="product-price" data-title="<?php esc_attr_e( 'Price', 'digital-mudir-dokan' ); ?>">
									<?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</td>

								<td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'digital-mudir-dokan' ); ?>">
									<?php
									if ( $_product->is_sold_individually() ) {
										$min_quantity = 1;
										$max_quantity = 1;
									} else {
										$min_quantity = 0;
										$max_quantity = $_product->get_max_purchase_quantity();
									}

									$product_quantity = woocommerce_quantity_input(
										array(
											'input_name'   => "cart[{$cart_item_key}][qty]",
											'input_value'  => $cart_item['quantity'],
											'max_value'    => $max_quantity,
											'min_value'    => $min_quantity,
											'product_name' => $_product->get_name(),
										),
										$_product,
										false
									);

									echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									?>
								</td>

								<td class="product-subtotal font-semibold" data-title="<?php esc_attr_e( 'Subtotal', 'digital-mudir-dokan' ); ?>">
									<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</td>
							</tr>
							<?php
						}
					}
					?>

					<?php do_action( 'woocommerce_cart_contents' ); ?>

					<tr>
						<td colspan="6" class="actions">
							<div class="flex flex-wrap items-center justify-between gap-3">
								<?php if ( wc_coupons_enabled() ) : ?>
									<div class="coupon flex flex-wrap items-center gap-2">
										<label class="screen-reader-text" for="coupon_code"><?php esc_html_e( 'Coupon code', 'digital-mudir-dokan' ); ?></label>
										<input type="text" name="coupon_code" class="dmd-field input-text w-44" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'digital-mudir-dokan' ); ?>" />
										<button type="submit" class="dmd-btn dmd-btn--outline" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'digital-mudir-dokan' ); ?>"><?php esc_html_e( 'Apply coupon', 'digital-mudir-dokan' ); ?></button>
										<?php do_action( 'woocommerce_cart_coupon' ); ?>
									</div>
								<?php endif; ?>

								<button type="submit" class="dmd-btn dmd-btn--ghost" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'digital-mudir-dokan' ); ?>"><?php esc_html_e( 'Update cart', 'digital-mudir-dokan' ); ?></button>

								<?php do_action( 'woocommerce_cart_actions' ); ?>
								<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
							</div>
						</td>
					</tr>

					<?php do_action( 'woocommerce_after_cart_contents' ); ?>
				</tbody>
			</table>
		</div>

		<?php do_action( 'woocommerce_after_cart_table' ); ?>
	</form>

	<aside class="cart-collaterals lg:col-span-4 lg:sticky lg:top-6" aria-label="<?php esc_attr_e( 'Order summary', 'digital-mudir-dokan' ); ?>">
		<?php do_action( 'woocommerce_cart_collaterals' ); ?>
	</aside>
</div>

<?php
if ( function_exists( 'woocommerce_cross_sell_display' ) ) {
	woocommerce_cross_sell_display();
}

do_action( 'woocommerce_after_cart' );

