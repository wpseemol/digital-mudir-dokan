<?php
/**
 * Checkout form.
 *
 * @package Digital_Mudir_Dokan
 * @version 9.4.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_checkout_form', $checkout );

// Registration may be required to check out.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to check out.', 'digital-mudir-dokan' ) ) );
	return;
}
?>
<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

	<div class="grid gap-8 grid-cols-1 lg:grid-cols-12 lg:items-start">

		<div class="dmd-checkout-details lg:col-span-7">
			<?php if ( $checkout->get_checkout_fields() ) : ?>

				<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

				<section class="rounded-lg border border-line bg-white p-6" aria-labelledby="dmd-billing-title">
					<h2 id="dmd-billing-title" class="mb-4 mt-0 text-lg font-semibold">
						<?php esc_html_e( 'Delivery details', 'digital-mudir-dokan' ); ?>
					</h2>
					<div class="col2-set" id="customer_details">
						<div class="col-1">
							<?php do_action( 'woocommerce_checkout_billing' ); ?>
						</div>
						<div class="col-2">
							<?php do_action( 'woocommerce_checkout_shipping' ); ?>
						</div>
					</div>
				</section>

				<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

			<?php endif; ?>
		</div>

		<aside class="dmd-order-review lg:col-span-5 lg:sticky lg:top-6" aria-labelledby="dmd-order-title">
			<h2 id="dmd-order-title" class="mb-4 mt-0 text-lg font-semibold">
				<?php esc_html_e( 'Your order', 'digital-mudir-dokan' ); ?>
			</h2>

			<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
			<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

			<div id="order_review" class="woocommerce-checkout-review-order">
				<?php do_action( 'woocommerce_checkout_order_review' ); ?>
			</div>

			<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
		</aside>
	</div>
</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
