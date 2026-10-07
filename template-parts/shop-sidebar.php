<?php
/**
 * Shop sidebar: categories + price filter (+ any "Shop sidebar" widgets).
 *
 * Sticky column from lg up; an off-canvas drawer below that, opened by the
 * "Filters" button in the archive toolbar.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

// phpcs:disable WordPress.Security.NonceVerification.Recommended
$dmd_min_price = isset( $_GET['min_price'] ) ? wc_clean( wp_unslash( $_GET['min_price'] ) ) : '';
$dmd_max_price = isset( $_GET['max_price'] ) ? wc_clean( wp_unslash( $_GET['max_price'] ) ) : '';
// phpcs:enable

$dmd_current = is_product_category() ? get_queried_object() : null;
$dmd_current = $dmd_current instanceof WP_Term ? $dmd_current : null;

$dmd_form_action = wc_get_page_permalink( 'shop' );
if ( is_product_taxonomy() ) {
	$dmd_link = get_term_link( get_queried_object() );
	if ( ! is_wp_error( $dmd_link ) ) {
		$dmd_form_action = $dmd_link;
	}
}

// The current category and its parents stay expanded.
$dmd_open_ids = array();
if ( $dmd_current ) {
	$dmd_open_ids   = get_ancestors( $dmd_current->term_id, 'product_cat', 'taxonomy' );
	$dmd_open_ids[] = $dmd_current->term_id;
}

$dmd_currency = get_woocommerce_currency_symbol();
?>
<aside id="dmd-shop-sidebar" class="dmd-shop-sidebar space-y-5" aria-label="<?php esc_attr_e( 'Shop filters', 'digital-mudir-dokan' ); ?>">

	<div class="<?php echo esc_attr( is_search() ? 'flex' : 'flex lg:hidden' ); ?> items-center justify-between border-b border-line pb-3">
		<p class="m-0 text-base font-semibold"><?php esc_html_e( 'Filters', 'digital-mudir-dokan' ); ?></p>
		<button type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-surface" data-dmd-filter-close>
			<?php dmd_the_icon( 'close', 18 ); ?>
			<span class="screen-reader-text"><?php esc_html_e( 'Close filters', 'digital-mudir-dokan' ); ?></span>
		</button>
	</div>

	<section class="dmd-widget">
		<h2 class="dmd-widget-title"><?php esc_html_e( 'Product Categories', 'digital-mudir-dokan' ); ?></h2>
		<a class="mb-1 flex items-center justify-between rounded-md px-2 py-1.5 text-sm <?php echo esc_attr( is_shop() ? 'bg-green-soft font-semibold text-green' : 'text-ink hover:bg-green-soft hover:text-green' ); ?>" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
			<?php esc_html_e( 'All products', 'digital-mudir-dokan' ); ?>
		</a>
		<?php dmd_sidebar_category_tree( 0, $dmd_current ? $dmd_current->term_id : 0, $dmd_open_ids ); ?>
	</section>

	<section class="dmd-widget">
		<h2 class="dmd-widget-title"><?php esc_html_e( 'Filter by price', 'digital-mudir-dokan' ); ?></h2>
		<form class="dmd-price-filter" method="get" action="<?php echo esc_url( $dmd_form_action ); ?>">
			<?php
			// Keep catalog order / search args when applying a price filter.
			foreach ( wp_unslash( $_GET ) as $dmd_key => $dmd_value ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				if ( in_array( $dmd_key, array( 'min_price', 'max_price', 'paged', 'page' ), true ) || is_array( $dmd_value ) ) {
					continue;
				}
				printf(
					'<input type="hidden" name="%1$s" value="%2$s" />',
					esc_attr( sanitize_key( $dmd_key ) ),
					esc_attr( wc_clean( $dmd_value ) )
				);
			}
			?>
			<div class="grid grid-cols-2 gap-2">
				<label class="block">
					<span class="mb-1 block text-xs text-muted"><?php esc_html_e( 'Min', 'digital-mudir-dokan' ); ?> (<?php echo esc_html( html_entity_decode( $dmd_currency ) ); ?>)</span>
					<input type="number" name="min_price" inputmode="decimal" min="0" step="any" value="<?php echo esc_attr( $dmd_min_price ); ?>" placeholder="0" class="dmd-field !py-2" />
				</label>
				<label class="block">
					<span class="mb-1 block text-xs text-muted"><?php esc_html_e( 'Max', 'digital-mudir-dokan' ); ?> (<?php echo esc_html( html_entity_decode( $dmd_currency ) ); ?>)</span>
					<input type="number" name="max_price" inputmode="decimal" min="0" step="any" value="<?php echo esc_attr( $dmd_max_price ); ?>" placeholder="<?php esc_attr_e( 'Any', 'digital-mudir-dokan' ); ?>" class="dmd-field !py-2" />
				</label>
			</div>
			<button type="submit" class="dmd-btn dmd-btn--primary dmd-btn--block mt-3"><?php esc_html_e( 'Apply', 'digital-mudir-dokan' ); ?></button>
			<?php if ( '' !== $dmd_min_price || '' !== $dmd_max_price ) : ?>
				<a href="<?php echo esc_url( remove_query_arg( array( 'min_price', 'max_price', 'paged' ) ) ); ?>" class="mt-2 block text-center text-xs font-medium text-green hover:underline">
					<?php esc_html_e( 'Clear price filter', 'digital-mudir-dokan' ); ?>
				</a>
			<?php endif; ?>
		</form>
	</section>

	<?php if ( is_active_sidebar( 'shop-sidebar' ) ) : ?>
		<?php dynamic_sidebar( 'shop-sidebar' ); ?>
	<?php endif; ?>
</aside>
