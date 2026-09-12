<?php
/**
 * Empty cart.
 *
 * @package Digital_Mudir_Dokan
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_cart_is_empty' );
?>
<div class="dmd-cart-empty mx-auto max-w-md rounded-lg border border-line bg-white px-6 py-12 text-center">
	<span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-green-soft text-green">
		<?php dmd_the_icon( 'cart', 26 ); ?>
	</span>

	<h2 class="mt-4 text-lg font-semibold"><?php esc_html_e( 'Your cart is empty', 'digital-mudir-dokan' ); ?></h2>
	<p class="mt-1 text-sm text-muted"><?php esc_html_e( 'Add a product and it will show up here.', 'digital-mudir-dokan' ); ?></p>

	<?php if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
		<p class="mt-6 m-0">
			<a class="dmd-btn dmd-btn--primary px-7" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
				<?php esc_html_e( 'Browse products', 'digital-mudir-dokan' ); ?>
			</a>
		</p>
	<?php endif; ?>
</div>
<?php
if ( function_exists( 'woocommerce_cross_sell_display' ) ) {
	woocommerce_cross_sell_display();
}
