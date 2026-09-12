<?php
/**
 * Product card used in every loop.
 *
 * @package Digital_Mudir_Dokan
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

$dmd_percentage = dmd_get_sale_percentage( $product );
$dmd_is_hot     = $product->is_featured() || (int) $product->get_total_sales() > 0;
?>
<li <?php wc_product_class( 'dmd-card', $product ); ?>>

	<div class="dmd-card__media">
		<a class="block h-full w-full" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
			<?php
			if ( has_post_thumbnail() ) {
				echo wp_kses_post(
					$product->get_image(
						'dmd-product-card',
						array(
							'class'    => 'h-full w-full object-cover',
							'loading'  => 'lazy',
							'decoding' => 'async',
						)
					)
				);
			} else {
				printf(
					'<img src="%1$s" alt="%2$s" class="h-full w-full object-contain p-4" loading="lazy" decoding="async">',
					esc_url( dmd_get_product_placeholder_url() ),
					esc_attr( $product->get_name() )
				);
			}
			?>
		</a>

		<div class="absolute left-3 top-3 z-10 flex flex-col items-start gap-1.5">
			<?php if ( $dmd_percentage ) : ?>
				<span class="dmd-badge dmd-badge--sale">
					-<?php echo esc_html( $dmd_percentage ); ?>%
					<span class="screen-reader-text"><?php esc_html_e( 'discount', 'digital-mudir-dokan' ); ?></span>
				</span>
			<?php endif; ?>

			<?php if ( ! $product->is_in_stock() ) : ?>
				<span class="dmd-badge dmd-badge--out"><?php esc_html_e( 'Sold out', 'digital-mudir-dokan' ); ?></span>
			<?php elseif ( $dmd_is_hot ) : ?>
				<span class="dmd-badge dmd-badge--hot"><?php esc_html_e( 'Hot', 'digital-mudir-dokan' ); ?></span>
			<?php endif; ?>
		</div>
	</div>

	<div class="flex flex-col flex-grow">
		<h3 class="dmd-card__title">
			<a class="hover:text-green" href="<?php the_permalink(); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
		</h3>

		<?php if ( wc_review_ratings_enabled() && $product->get_average_rating() > 0 ) : ?>
			<div class="mt-1">
				<?php echo wp_kses_post( wc_get_rating_html( $product->get_average_rating(), $product->get_rating_count() ) ); ?>
			</div>
		<?php endif; ?>

		<?php if ( $product->get_price_html() ) : ?>
			<p class="dmd-card__price m-0"><?php echo wp_kses_post( $product->get_price_html() ); ?></p>
		<?php endif; ?>
	</div>

	<div class="dmd-card__actions">
		<?php woocommerce_template_loop_add_to_cart(); ?>

		<?php dmd_order_now_button( $product, 'dmd-btn--outline dmd-btn--block' ); ?>
	</div>
</li>
