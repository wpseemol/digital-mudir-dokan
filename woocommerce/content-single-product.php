<?php
/**
 * Single product layout.
 *
 * @package Digital_Mudir_Dokan
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( post_password_required() ) {
	echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return;
}

$dmd_permalink = get_permalink();
$dmd_title     = get_the_title();
?>
<div class="bg-surface py-4">
	<div class="dmd-container">
		<?php dmd_breadcrumbs(); ?>
	</div>
</div>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'dmd-single-product', $product ); ?>>
	<div class="dmd-container py-8">

		<?php do_action( 'woocommerce_before_single_product' ); ?>

		<div class="grid gap-8 lg:grid-cols-2">

			<!-- Gallery -->
			<div class="dmd-single-product__gallery relative rounded-lg border border-line bg-white p-4">
				<?php do_action( 'woocommerce_before_single_product_summary' ); ?>
			</div>

			<!-- Summary -->
			<div class="dmd-single-product__summary summary entry-summary rounded-lg border border-line bg-white p-6">

				<h1 class="product_title entry-title m-0 text-2xl font-bold leading-snug">
					<?php echo esc_html( $dmd_title ); ?>
				</h1>

				<?php if ( wc_review_ratings_enabled() ) : ?>
					<div class="mt-2"><?php woocommerce_template_single_rating(); ?></div>
				<?php endif; ?>

				<p class="price mt-3 text-xl m-0">
					<?php echo wp_kses_post( $product->get_price_html() ); ?>
				</p>

				<?php if ( $product->get_short_description() ) : ?>
					<div class="dmd-prose mt-4 border-t border-line pt-4 text-sm">
						<?php echo wp_kses_post( wpautop( $product->get_short_description() ) ); ?>
					</div>
				<?php endif; ?>

				<div class="mt-5">
					<?php woocommerce_template_single_add_to_cart(); ?>
				</div>

				<!-- Delivery promise -->
				<section class="mt-6 rounded-md bg-surface p-4" aria-labelledby="dmd-delivery-heading">
					<h2 id="dmd-delivery-heading" class="m-0 text-base font-semibold text-green">
						<?php echo esc_html( get_theme_mod( 'dmd_delivery_title', __( 'ডেলিভারী টাইম:', 'digital-mudir-dokan' ) ) ); ?>
					</h2>
					<ul class="m-0 mt-2 list-none space-y-1 p-0 text-[13px]">
						<li class="flex list-none items-center gap-2">
							<span class="text-green"><?php dmd_the_icon( 'truck', 16 ); ?></span>
							<?php echo esc_html( get_theme_mod( 'dmd_delivery_inside', __( 'ঢাকার ভেতরে: ১-২ দিন', 'digital-mudir-dokan' ) ) ); ?>
						</li>
						<li class="flex list-none items-center gap-2">
							<span class="text-green"><?php dmd_the_icon( 'truck', 16 ); ?></span>
							<?php echo esc_html( get_theme_mod( 'dmd_delivery_outside', __( 'ঢাকার বাইরে: ২-৩ দিন', 'digital-mudir-dokan' ) ) ); ?>
						</li>
					</ul>
				</section>

				<!-- Stock -->
				<p class="mt-4 m-0">
					<?php if ( $product->is_in_stock() ) : ?>
						<span class="inline-flex items-center gap-1.5 rounded-md bg-green-soft px-3 py-1.5 text-[13px] font-medium text-green-dark">
							<?php dmd_the_icon( 'check', 15 ); ?>
							<?php
							$dmd_qty = $product->get_stock_quantity();
							if ( $product->managing_stock() && $dmd_qty ) {
								/* translators: %d: number of units in stock. */
								printf( esc_html__( '%d in stock', 'digital-mudir-dokan' ), (int) $dmd_qty );
							} else {
								esc_html_e( 'In stock', 'digital-mudir-dokan' );
							}
							?>
						</span>
					<?php else : ?>
						<span class="inline-flex items-center gap-1.5 rounded-md bg-surface px-3 py-1.5 text-[13px] font-medium text-muted">
							<?php esc_html_e( 'Out of stock', 'digital-mudir-dokan' ); ?>
						</span>
					<?php endif; ?>
				</p>

				<!-- Meta -->
				<?php if ( $product->get_sku() || has_term( '', 'product_cat', $product->get_id() ) ) : ?>
					<dl class="mt-4 border-t border-line pt-4 text-[13px] text-muted">
						<?php if ( $product->get_sku() ) : ?>
							<div class="flex gap-2">
								<dt class="font-medium text-ink"><?php esc_html_e( 'SKU', 'digital-mudir-dokan' ); ?></dt>
								<dd class="m-0"><?php echo esc_html( $product->get_sku() ); ?></dd>
							</div>
						<?php endif; ?>

						<?php
						$dmd_cats = wc_get_product_category_list( $product->get_id(), ', ' );
						if ( $dmd_cats ) :
							?>
							<div class="mt-1 flex gap-2">
								<dt class="font-medium text-ink"><?php esc_html_e( 'Category', 'digital-mudir-dokan' ); ?></dt>
								<dd class="m-0"><?php echo wp_kses_post( $dmd_cats ); ?></dd>
							</div>
						<?php endif; ?>
					</dl>
				<?php endif; ?>

				<?php
				/**
				 * Everything the template does not print itself — variation
				 * swatches, wishlist buttons, and other plugin additions.
				 */
				do_action( 'woocommerce_single_product_summary' );
				?>

				<!-- Share -->
				<div class="mt-4 flex flex-wrap items-center gap-2 border-t border-line pt-4">
					<span class="text-[13px] font-medium"><?php esc_html_e( 'Share:', 'digital-mudir-dokan' ); ?></span>

					<?php
					$dmd_share = array(
						'facebook' => array(
							'label' => __( 'Share on Facebook', 'digital-mudir-dokan' ),
							'url'   => 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode( $dmd_permalink ),
						),
						'twitter'  => array(
							'label' => __( 'Share on X', 'digital-mudir-dokan' ),
							'url'   => 'https://twitter.com/intent/tweet?url=' . rawurlencode( $dmd_permalink ) . '&text=' . rawurlencode( $dmd_title ),
						),
					);

					foreach ( $dmd_share as $dmd_network => $dmd_data ) :
						?>
						<a class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-line hover:border-green hover:bg-green hover:text-white"
							href="<?php echo esc_url( $dmd_data['url'] ); ?>"
							target="_blank"
							rel="noopener noreferrer">
							<?php echo wp_kses( dmd_social_icon( $dmd_network ), dmd_svg_allowed_html() ); ?>
							<span class="screen-reader-text"><?php echo esc_html( $dmd_data['label'] ); ?></span>
						</a>
					<?php endforeach; ?>

					<a class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-line hover:border-green hover:bg-green hover:text-white"
						href="https://wa.me/?text=<?php echo rawurlencode( $dmd_title . ' ' . $dmd_permalink ); ?>"
						target="_blank"
						rel="noopener noreferrer">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.4A10 10 0 1 0 12 2Zm5.3 14c-.2.6-1.2 1.2-1.7 1.2-.5.1-1 .1-1.6-.1-.4-.1-.9-.3-1.5-.6a11 11 0 0 1-4.2-3.9c-.4-.6-.9-1.4-.9-2.3 0-.9.5-1.4.7-1.6.2-.2.4-.3.6-.3h.5c.2 0 .4 0 .6.4l.7 1.8c.1.2 0 .4-.1.5l-.3.4c-.1.1-.3.3-.1.6.2.3.7 1.1 1.4 1.8.9.8 1.6 1 1.9 1.2.2.1.4.1.5-.1l.7-.8c.2-.2.3-.2.5-.1l1.7.8c.2.1.4.2.4.3.1.2.1.6 0 .8Z"/></svg>
						<span class="screen-reader-text"><?php esc_html_e( 'Share on WhatsApp', 'digital-mudir-dokan' ); ?></span>
					</a>
				</div>
			</div>
		</div>

		<?php
		/**
		 * Description, additional information and reviews tabs, plus upsells
		 * and related products.
		 */
		do_action( 'woocommerce_after_single_product_summary' );
		?>

		<?php
		$dmd_recent = dmd_get_recently_viewed( get_the_ID() );

		if ( $dmd_recent ) :
			$dmd_recent_products = wc_get_products(
				array(
					'include' => $dmd_recent,
					'limit'   => 6,
					'status'  => 'publish',
				)
			);

			if ( $dmd_recent_products ) :
				?>
				<section class="dmd-recently-viewed mt-12" aria-labelledby="dmd-recent-title">
					<h2 id="dmd-recent-title" class="mb-5 text-lg font-semibold">
						<?php esc_html_e( 'Recently viewed', 'digital-mudir-dokan' ); ?>
					</h2>

					<ul class="m-0 grid list-none grid-cols-2 gap-4 p-0 sm:grid-cols-3 lg:grid-cols-6">
						<?php foreach ( $dmd_recent_products as $dmd_item ) : ?>
							<li class="list-none">
								<a class="flex h-full flex-col gap-2 rounded-lg border border-line p-3 hover:border-green"
									href="<?php echo esc_url( $dmd_item->get_permalink() ); ?>">
									<span class="block overflow-hidden rounded bg-surface">
										<?php echo wp_kses_post( $dmd_item->get_image( 'woocommerce_thumbnail', array( 'class' => 'h-full w-full object-cover', 'loading' => 'lazy' ) ) ); ?>
									</span>
									<span class="text-[13px] leading-snug dmd-line-clamp-2"><?php echo esc_html( $dmd_item->get_name() ); ?></span>
									<span class="mt-auto text-[13px] font-semibold text-green"><?php echo wp_kses_post( $dmd_item->get_price_html() ); ?></span>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</section>
				<?php
			endif;
		endif;
		?>

		<?php do_action( 'woocommerce_after_single_product' ); ?>
	</div>
</div>
