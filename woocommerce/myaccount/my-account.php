<?php
/**
 * My account page.
 *
 * @package Digital_Mudir_Dokan
 * @version 9.6.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="dmd-account grid gap-6 lg:grid-cols-[240px_minmax(0,1fr)] lg:items-start">

	<nav class="woocommerce-MyAccount-navigation rounded-lg border border-line bg-white p-3"
		aria-label="<?php esc_attr_e( 'Account', 'digital-mudir-dokan' ); ?>">
		<?php do_action( 'woocommerce_account_navigation' ); ?>
	</nav>

	<div class="woocommerce-MyAccount-content rounded-lg border border-line bg-white p-6">
		<?php do_action( 'woocommerce_account_content' ); ?>
	</div>
</div>
