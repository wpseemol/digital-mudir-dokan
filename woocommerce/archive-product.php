<?php
/**
 * Shop and product archives.
 *
 * @package Digital_Mudir_Dokan
 * @version 8.6.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

global $wp_query;

$dmd_cols  = function_exists( 'dmd_get_catalog_columns' ) ? dmd_get_catalog_columns() : 4;
$dmd_term  = is_product_taxonomy() ? get_queried_object() : null;
$dmd_term  = $dmd_term instanceof WP_Term ? $dmd_term : null;
$dmd_total = (int) $wp_query->found_posts;

$dmd_term_image = '';
if ( $dmd_term && 'product_cat' === $dmd_term->taxonomy ) {
	$dmd_thumb_id = (int) get_term_meta( $dmd_term->term_id, 'thumbnail_id', true );
	if ( $dmd_thumb_id ) {
		$dmd_term_image = wp_get_attachment_image_url( $dmd_thumb_id, 'thumbnail' );
	}
}

// Sub-category chips: children of the current category, or top-level ones on the shop page.
$dmd_chips = array();
if ( $dmd_term && 'product_cat' === $dmd_term->taxonomy ) {
	$dmd_chips = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'parent'     => $dmd_term->term_id,
			'hide_empty' => true,
		)
	);
} elseif ( is_shop() && ! is_search() ) {
	$dmd_chips = dmd_get_top_categories( 12, true );
}
$dmd_chips = is_wp_error( $dmd_chips ) ? array() : $dmd_chips;

// phpcs:disable WordPress.Security.NonceVerification.Recommended
$dmd_min = isset( $_GET['min_price'] ) ? wc_clean( wp_unslash( $_GET['min_price'] ) ) : '';
$dmd_max = isset( $_GET['max_price'] ) ? wc_clean( wp_unslash( $_GET['max_price'] ) ) : '';
// phpcs:enable
?>
<section class="dmd-archive-header border-b border-line bg-gradient-to-b from-green-soft/70 to-white">
	<div class="dmd-container py-6 sm:py-8">
		<?php dmd_breadcrumbs(); ?>

		<div class="mt-4 flex items-center gap-4">
			<?php if ( $dmd_term_image ) : ?>
				<img src="<?php echo esc_url( $dmd_term_image ); ?>" alt="" class="h-14 w-14 shrink-0 rounded-full border border-line bg-white object-cover sm:h-16 sm:w-16" />
			<?php endif; ?>
			<div class="min-w-0">
				<h1 class="dmd-heading !text-2xl sm:!text-3xl"><?php woocommerce_page_title(); ?></h1>
				<p class="m-0 mt-1 text-sm text-muted">
					<?php
					/* translators: %s: number of products. */
					echo esc_html( sprintf( _n( '%s product', '%s products', $dmd_total, 'digital-mudir-dokan' ), number_format_i18n( $dmd_total ) ) );
					?>
				</p>
			</div>
		</div>

		<?php
		ob_start();
		do_action( 'woocommerce_archive_description' );
		$dmd_description = trim( ob_get_clean() );
		?>
		<?php if ( $dmd_description ) : ?>
			<div class="dmd-archive-description dmd-prose mt-3 max-w-3xl !text-sm text-muted">
				<?php echo $dmd_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- printed by WooCommerce, already filtered. ?>
			</div>
		<?php endif; ?>

		<?php if ( $dmd_chips ) : ?>
			<nav class="dmd-scroll-x -mx-4 mt-5 gap-2 px-4 pb-1 sm:mx-0 sm:flex-wrap sm:px-0" aria-label="<?php esc_attr_e( 'Categories', 'digital-mudir-dokan' ); ?>">
				<?php foreach ( $dmd_chips as $dmd_chip ) : ?>
					<a class="dmd-chip" href="<?php echo esc_url( get_term_link( $dmd_chip ) ); ?>">
						<?php echo esc_html( $dmd_chip->name ); ?>
						<span class="text-xs text-muted">(<?php echo esc_html( number_format_i18n( $dmd_chip->count ) ); ?>)</span>
					</a>
				<?php endforeach; ?>
			</nav>
		<?php endif; ?>
	</div>
</section>

<div class="dmd-container py-6 sm:py-8">
	<div class="grid gap-6 lg:grid-cols-[260px_minmax(0,1fr)] lg:gap-8">

		<?php get_template_part( 'template-parts/shop', 'sidebar' ); ?>

		<div class="dmd-shop-results min-w-0">
			<?php
			/**
			 * Notices and anything a plugin attaches before the content.
			 */
			do_action( 'woocommerce_before_main_content' );

			// The toolbar renders before `woocommerce_before_shop_loop`, where WooCommerce normally sets up loop props.
			wc_setup_loop();
			?>

			<div class="dmd-shop-toolbar mb-5 flex flex-wrap items-center justify-between gap-3 rounded-xl border border-line bg-white px-3 py-2.5 sm:px-4">
				<div class="flex min-w-0 items-center gap-3">
					<button type="button" class="inline-flex items-center gap-2 rounded-lg border border-line px-3 py-2 text-sm font-medium hover:border-green hover:text-green lg:hidden" data-dmd-filter-open aria-controls="dmd-shop-sidebar" aria-expanded="false">
						<?php dmd_the_icon( 'filter', 16 ); ?>
						<?php esc_html_e( 'Filters', 'digital-mudir-dokan' ); ?>
					</button>
					<div class="hidden sm:block"><?php dmd_result_count(); ?></div>
				</div>

				<?php if ( woocommerce_product_loop() ) : ?>
					<div class="flex items-center gap-2">
						<div class="hidden items-center gap-1 rounded-lg bg-surface p-1 sm:flex" role="group" aria-label="<?php esc_attr_e( 'Layout', 'digital-mudir-dokan' ); ?>">
							<button type="button" class="dmd-view-toggle" data-view-toggle="grid" aria-pressed="true">
								<?php dmd_the_icon( 'grid', 18 ); ?>
								<span class="screen-reader-text"><?php esc_html_e( 'Grid view', 'digital-mudir-dokan' ); ?></span>
							</button>
							<button type="button" class="dmd-view-toggle" data-view-toggle="list" aria-pressed="false">
								<?php dmd_the_icon( 'list', 18 ); ?>
								<span class="screen-reader-text"><?php esc_html_e( 'List view', 'digital-mudir-dokan' ); ?></span>
							</button>
						</div>
						<?php woocommerce_catalog_ordering(); ?>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( '' !== $dmd_min || '' !== $dmd_max ) : ?>
				<div class="mb-5 flex flex-wrap items-center gap-2 text-sm">
					<span class="text-muted"><?php esc_html_e( 'Active filters:', 'digital-mudir-dokan' ); ?></span>
					<a class="dmd-chip is-active" href="<?php echo esc_url( remove_query_arg( array( 'min_price', 'max_price', 'paged' ) ) ); ?>">
						<?php
						echo esc_html(
							sprintf(
								/* translators: 1: min price, 2: max price. */
								__( 'Price: %1$s – %2$s', 'digital-mudir-dokan' ),
								'' !== $dmd_min ? wp_strip_all_tags( wc_price( (float) $dmd_min ) ) : '0',
								'' !== $dmd_max ? wp_strip_all_tags( wc_price( (float) $dmd_max ) ) : __( 'any', 'digital-mudir-dokan' )
							)
						);
						?>
						<?php dmd_the_icon( 'close', 14 ); ?>
						<span class="screen-reader-text"><?php esc_html_e( 'Remove filter', 'digital-mudir-dokan' ); ?></span>
					</a>
				</div>
			<?php endif; ?>

			<?php if ( woocommerce_product_loop() ) : ?>
				<?php
				/**
				 * Result count and sorting are unhooked from here and drawn in
				 * the toolbar above, but the hook still fires so notices and
				 * third-party additions are not lost.
				 */
				do_action( 'woocommerce_before_shop_loop' );

				if ( wc_get_loop_prop( 'total' ) ) :
					$dmd_grid_classes = array(
						'products',
						'dmd-archive-catalog',
						'grid-view',
						'grid',
						'grid-cols-2',
						'md:grid-cols-3',
						'lg:grid-cols-' . min( 4, $dmd_cols ),
						'xl:grid-cols-' . $dmd_cols,
						'columns-' . $dmd_cols,
						'list-none',
						'p-0',
						'm-0',
					);
					?>
					<ul id="dmd-archive-products" class="<?php echo esc_attr( implode( ' ', $dmd_grid_classes ) ); ?>" data-columns="<?php echo esc_attr( (string) $dmd_cols ); ?>">
						<?php
						while ( have_posts() ) {
							the_post();
							do_action( 'woocommerce_shop_loop' );
							wc_get_template_part( 'content', 'product' );
						}
						?>
					</ul>
					<?php
				endif;

				do_action( 'woocommerce_after_shop_loop' );
				dmd_woo_pagination();
				?>

			<?php else : ?>
				<div class="rounded-xl border border-dashed border-line bg-white px-6 py-12 text-center">
					<span class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-green-soft text-green"><?php dmd_the_icon( 'search', 24 ); ?></span>
					<?php do_action( 'woocommerce_no_products_found' ); ?>
					<a class="dmd-btn dmd-btn--outline mt-2" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'Browse all products', 'digital-mudir-dokan' ); ?></a>
				</div>
			<?php endif; ?>

			<?php do_action( 'woocommerce_after_main_content' ); ?>
		</div>
	</div>
</div>
<?php
get_footer( 'shop' );
