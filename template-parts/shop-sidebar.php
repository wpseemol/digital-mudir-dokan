<?php
/**
 * Category archive sidebar: price filter + product categories.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

$min_price = isset( $_GET['min_price'] ) ? wc_clean( wp_unslash( $_GET['min_price'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$max_price = isset( $_GET['max_price'] ) ? wc_clean( wp_unslash( $_GET['max_price'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

$form_action = '';
if ( is_product_category() || is_product_tag() || is_product_taxonomy() ) {
	$term = get_queried_object();
	if ( $term instanceof WP_Term ) {
		$form_action = get_term_link( $term );
		if ( is_wp_error( $form_action ) ) {
			$form_action = '';
		}
	}
} elseif ( function_exists( 'wc_get_page_id' ) ) {
	$form_action = get_permalink( wc_get_page_id( 'shop' ) );
}

$currency = function_exists( 'get_woocommerce_currency_symbol' ) ? get_woocommerce_currency_symbol() : '';
?>
<aside class="dmd-shop-sidebar space-y-5" aria-label="<?php esc_attr_e( 'Shop filters', 'digital-mudir-dokan' ); ?>">

	<section class="dmd-filter-card rounded-lg border border-line bg-white p-5">
		<h2 class="mb-3 text-base font-semibold"><?php esc_html_e( 'Filter by price', 'digital-mudir-dokan' ); ?></h2>
		<form class="dmd-price-filter" method="get" action="<?php echo esc_url( $form_action ); ?>">
			<?php
			// Keep catalog order / search args when applying a price filter.
			if ( ! empty( $_GET ) && is_array( $_GET ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				foreach ( wp_unslash( $_GET ) as $key => $value ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					if ( in_array( $key, array( 'min_price', 'max_price', 'paged', 'page' ), true ) || is_array( $value ) ) {
						continue;
					}
					printf(
						'<input type="hidden" name="%1$s" value="%2$s" />',
						esc_attr( sanitize_key( $key ) ),
						esc_attr( wc_clean( $value ) )
					);
				}
			}
			?>
			<div class="grid grid-cols-2 gap-2">
				<label class="block">
					<span class="mb-1 block text-xs text-muted"><?php esc_html_e( 'Min', 'digital-mudir-dokan' ); ?><?php echo $currency ? ' (' . esc_html( $currency ) . ')' : ''; ?></span>
					<input
						type="number"
						name="min_price"
						inputmode="decimal"
						min="0"
						step="any"
						value="<?php echo esc_attr( $min_price ); ?>"
						placeholder="0"
						class="dmd-price-filter__input w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600"
					/>
				</label>
				<label class="block">
					<span class="mb-1 block text-xs text-muted"><?php esc_html_e( 'Max', 'digital-mudir-dokan' ); ?><?php echo $currency ? ' (' . esc_html( $currency ) . ')' : ''; ?></span>
					<input
						type="number"
						name="max_price"
						inputmode="decimal"
						min="0"
						step="any"
						value="<?php echo esc_attr( $max_price ); ?>"
						placeholder="<?php esc_attr_e( 'Any', 'digital-mudir-dokan' ); ?>"
						class="dmd-price-filter__input w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600"
					/>
				</label>
			</div>
			<button type="submit" class="mt-3 w-full rounded-lg bg-emerald-800 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-900">
				<?php esc_html_e( 'Apply', 'digital-mudir-dokan' ); ?>
			</button>
			<?php if ( '' !== $min_price || '' !== $max_price ) : ?>
				<a href="<?php echo esc_url( $form_action ); ?>" class="mt-2 block text-center text-xs font-medium text-emerald-800 hover:underline">
					<?php esc_html_e( 'Clear price filter', 'digital-mudir-dokan' ); ?>
				</a>
			<?php endif; ?>
		</form>
	</section>

	<section class="dmd-filter-card rounded-lg border border-line bg-white p-5">
		<h2 class="mb-3 text-base font-semibold"><?php esc_html_e( 'Product Categories', 'digital-mudir-dokan' ); ?></h2>
		<ul class="dmd-product-categories product-categories m-0 list-none space-y-1.5 p-0 text-sm">
			<?php
			wp_list_categories(
				array(
					'taxonomy'     => 'product_cat',
					'title_li'     => '',
					'show_count'   => true,
					'hide_empty'   => false,
					'hierarchical' => true,
					'current_category' => is_product_category() ? get_queried_object_id() : 0,
				)
			);
			?>
		</ul>
	</section>

</aside>
