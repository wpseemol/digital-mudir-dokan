<?php
/**
 * Product card — grid and list layouts.
 *
 * Expects `$args`:
 * - `product` WC_Product
 * - `view`    `grid`, `list` or `both` (archives print both and CSS picks one)
 * - `heading` Heading tag for the product name (h2 on archives, h3 in sections).
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

$dmd_product = isset( $args['product'] ) ? $args['product'] : null;

if ( ! $dmd_product instanceof WC_Product ) {
	return;
}

$dmd_view    = isset( $args['view'] ) ? $args['view'] : 'grid';
$dmd_heading = isset( $args['heading'] ) && in_array( $args['heading'], array( 'h2', 'h3' ), true ) ? $args['heading'] : 'h3';
$dmd_link    = get_permalink( $dmd_product->get_id() );
$dmd_name    = $dmd_product->get_name();
$dmd_can_buy = $dmd_product->is_purchasable() && $dmd_product->is_in_stock();
$dmd_sale    = $dmd_product->is_on_sale() ? dmd_get_sale_percentage( $dmd_product ) : 0;

$dmd_terms = get_the_terms( $dmd_product->get_id(), 'product_cat' );
$dmd_term  = null;
if ( $dmd_terms && ! is_wp_error( $dmd_terms ) ) {
	foreach ( $dmd_terms as $dmd_candidate ) {
		if ( (int) get_option( 'default_product_cat' ) !== (int) $dmd_candidate->term_id ) {
			$dmd_term = $dmd_candidate;
			break;
		}
	}
}

$dmd_image_attrs = array(
	'class'    => 'h-full w-full object-contain p-3 mix-blend-multiply transition-transform duration-300 group-hover:scale-105',
	'alt'      => $dmd_name,
	'loading'  => 'lazy',
	'decoding' => 'async',
);

if ( $dmd_product->get_image_id() ) {
	$dmd_image = wp_get_attachment_image( $dmd_product->get_image_id(), 'woocommerce_thumbnail', false, $dmd_image_attrs );
} else {
	$dmd_placeholder = get_theme_mod( 'dmd_default_product_placeholder', '' );
	$dmd_image       = sprintf(
		'<img src="%1$s" alt="%2$s" class="%3$s" loading="lazy" decoding="async" />',
		esc_url( $dmd_placeholder ? $dmd_placeholder : wc_placeholder_img_src( 'woocommerce_thumbnail' ) ),
		esc_attr( $dmd_name ),
		esc_attr( $dmd_image_attrs['class'] )
	);
}

$dmd_badges = function () use ( $dmd_sale, $dmd_product ) {
	if ( ! $dmd_sale && $dmd_product->is_in_stock() ) {
		return;
	}
	echo '<span class="pointer-events-none absolute left-2.5 top-2.5 z-10 flex flex-col items-start gap-1.5">';
	if ( $dmd_sale ) {
		printf( '<span class="rounded-md bg-green-dark px-2 py-1 text-[11px] font-bold leading-none text-white">-%d%%</span>', (int) $dmd_sale );
	}
	if ( ! $dmd_product->is_in_stock() ) {
		echo '<span class="rounded-md bg-ink/80 px-2 py-1 text-[11px] font-semibold leading-none text-white">' . esc_html__( 'Out of stock', 'digital-mudir-dokan' ) . '</span>';
	}
	echo '</span>';
};

$dmd_meta = function () use ( $dmd_term, $dmd_product ) {
	if ( $dmd_term ) {
		printf(
			'<a class="mb-1 block truncate text-[11px] font-medium uppercase tracking-wide text-muted hover:text-green" href="%1$s">%2$s</a>',
			esc_url( get_term_link( $dmd_term ) ),
			esc_html( $dmd_term->name )
		);
	}
	if ( wc_review_ratings_enabled() && $dmd_product->get_rating_count() ) {
		echo '<div class="mb-1.5 flex items-center gap-1">' . wc_get_rating_html( $dmd_product->get_average_rating() ) . '<span class="text-[11px] text-muted">(' . esc_html( $dmd_product->get_rating_count() ) . ')</span></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce markup.
	}
};
?>

<?php if ( 'list' !== $dmd_view ) : ?>
	<article class="dmd-card dmd-card-grid group relative flex h-full flex-col overflow-hidden rounded-xl border border-line bg-white transition duration-200 hover:-translate-y-0.5 hover:border-green/25 hover:shadow-lift">
		<a href="<?php echo esc_url( $dmd_link ); ?>" class="relative block aspect-square overflow-hidden bg-surface/70" tabindex="-1" aria-hidden="true">
			<?php echo $dmd_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built above. ?>
		</a>
		<?php $dmd_badges(); ?>

		<div class="flex flex-1 flex-col p-3 sm:p-4">
			<?php $dmd_meta(); ?>
			<<?php echo esc_html( $dmd_heading ); ?> class="woocommerce-loop-product__title dmd-line-clamp-2 m-0 mb-2 min-h-[2.6em] text-sm font-semibold leading-snug sm:text-[15px]">
				<a class="text-ink hover:text-green" href="<?php echo esc_url( $dmd_link ); ?>"><?php echo esc_html( $dmd_name ); ?></a>
			</<?php echo esc_html( $dmd_heading ); ?>>
			<div class="mb-3">
				<span class="price"><?php echo wp_kses_post( $dmd_product->get_price_html() ); ?></span>
			</div>

			<div class="mt-auto flex items-center gap-2">
				<?php if ( $dmd_can_buy ) : ?>
					<?php dmd_loop_add_to_cart_button( $dmd_product, 'icon' ); ?>
					<?php dmd_order_now_button( $dmd_product, 'h-10 min-w-0 flex-1 !rounded-lg !px-3 !py-0 text-[13px]' ); ?>
				<?php else : ?>
					<?php dmd_loop_add_to_cart_button( $dmd_product, 'full' ); ?>
				<?php endif; ?>
			</div>
		</div>
	</article>
<?php endif; ?>

<?php if ( 'grid' !== $dmd_view ) : ?>
	<article class="dmd-card dmd-card-list group relative w-full flex-col gap-4 overflow-hidden rounded-xl border border-line bg-white p-3 transition duration-200 hover:border-green/25 hover:shadow-lift sm:flex-row sm:items-center sm:p-4">
		<a href="<?php echo esc_url( $dmd_link ); ?>" class="relative block aspect-square w-full shrink-0 overflow-hidden rounded-lg bg-surface/70 sm:w-40 md:w-48" tabindex="-1" aria-hidden="true">
			<?php echo $dmd_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built above. ?>
			<?php $dmd_badges(); ?>
		</a>

		<div class="min-w-0 flex-1">
			<?php $dmd_meta(); ?>
			<<?php echo esc_html( $dmd_heading ); ?> class="m-0 mb-2 text-base font-semibold leading-snug md:text-lg">
				<a class="text-ink hover:text-green" href="<?php echo esc_url( $dmd_link ); ?>"><?php echo esc_html( $dmd_name ); ?></a>
			</<?php echo esc_html( $dmd_heading ); ?>>
			<div class="mb-2">
				<span class="price"><?php echo wp_kses_post( $dmd_product->get_price_html() ); ?></span>
			</div>
			<?php if ( $dmd_product->get_short_description() ) : ?>
				<p class="dmd-line-clamp-2 m-0 text-sm text-muted"><?php echo esc_html( wp_strip_all_tags( $dmd_product->get_short_description() ) ); ?></p>
			<?php endif; ?>
		</div>

		<div class="flex w-full shrink-0 flex-col gap-2 sm:w-44">
			<?php dmd_loop_add_to_cart_button( $dmd_product, 'full' ); ?>
			<?php dmd_order_now_button( $dmd_product, 'h-10 w-full !rounded-lg !py-0 text-[13px]' ); ?>
		</div>
	</article>
<?php endif; ?>
