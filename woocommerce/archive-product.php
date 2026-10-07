<?php
/**
 * Shop and product archives.
 *
 * @package Digital_Mudir_Dokan
 * @version 8.6.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

$catalog_cols     = function_exists( 'dmd_get_catalog_columns' ) ? dmd_get_catalog_columns() : 4;
$show_shop_sidebar = is_product_taxonomy();
?>
<div class="bg-surface py-6">
	<div class="dmd-container flex flex-wrap items-center justify-between gap-3">
		<h1 class="m-0 text-xl font-bold sm:text-2xl">
			<?php woocommerce_page_title(); ?>
		</h1>
		<?php dmd_breadcrumbs(); ?>
	</div>
</div>

<div class="dmd-container py-8">
	<div class="grid gap-8 <?php echo $show_shop_sidebar ? 'lg:grid-cols-[260px_minmax(0,1fr)]' : ''; ?>">

		<?php if ( $show_shop_sidebar ) : ?>
			<?php get_template_part( 'template-parts/shop', 'sidebar' ); ?>
		<?php endif; ?>

		<!-- Results -->
		<div class="dmd-shop-results">
			<?php
			/**
			 * Notices and anything a plugin attaches before the content.
			 */
			do_action( 'woocommerce_before_main_content' );

			if ( is_product_taxonomy() || is_shop() ) {
				/**
				 * Category / shop page description.
				 */
				do_action( 'woocommerce_archive_description' );
			}
			?>

			<?php if ( woocommerce_product_loop() ) : ?>

				<div class="dmd-shop-toolbar mb-6 flex flex-wrap items-center justify-between gap-4 border-b border-line pb-4">

					<div class="flex items-center gap-4 text-sm">
						<?php woocommerce_result_count(); ?>
					</div>

					<div class="flex items-center gap-4">
						<div class="flex items-center gap-1 border-r border-line pr-4">
							<button type="button"
									class="dmd-view-toggle p-2 rounded-md transition-all duration-200 ease-in-out"
									data-view-toggle="grid"
									aria-label="<?php esc_attr_e( 'Grid view', 'digital-mudir-dokan' ); ?>"
									aria-pressed="true">
								<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="dmd-icon">
									<rect x="3" y="3" width="7" height="7" rx="1"></rect>
									<rect x="14" y="3" width="7" height="7" rx="1"></rect>
									<rect x="3" y="14" width="7" height="7" rx="1"></rect>
									<rect x="14" y="14" width="7" height="7" rx="1"></rect>
								</svg>
							</button>
							<button type="button"
									class="dmd-view-toggle p-2 rounded-md transition-all duration-200 ease-in-out"
									data-view-toggle="list"
									aria-label="<?php esc_attr_e( 'List view', 'digital-mudir-dokan' ); ?>"
									aria-pressed="false">
								<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="dmd-icon">
									<line x1="8" y1="6" x2="21" y2="6"></line>
									<line x1="8" y1="12" x2="21" y2="12"></line>
									<line x1="8" y1="18" x2="21" y2="18"></line>
									<line x1="3" y1="6" x2="3.01" y2="6"></line>
									<line x1="3" y1="12" x2="3.01" y2="12"></line>
									<line x1="3" y1="18" x2="3.01" y2="18"></line>
								</svg>
							</button>
						</div>
						<?php woocommerce_catalog_ordering(); ?>
					</div>
				</div>

				<?php
				/**
				 * Result count and sorting are unhooked from here and drawn in
				 * the toolbar above, but the hook still fires so notices and
				 * third-party additions are not lost.
				 */
				do_action( 'woocommerce_before_shop_loop' );

				if ( wc_get_loop_prop( 'total' ) ) :
					$grid_classes = array(
						'products',
						'dmd-archive-catalog',
						'grid-view',
						'grid',
						'grid-cols-2',
						'md:grid-cols-3',
						'lg:grid-cols-' . $catalog_cols,
						'columns-' . $catalog_cols,
						'gap-4',
						'md:gap-6',
						'list-none',
						'p-0',
						'm-0',
					);
					?>
				<ul id="dmd-archive-products" class="<?php echo esc_attr( implode( ' ', $grid_classes ) ); ?>" data-columns="<?php echo esc_attr( (string) $catalog_cols ); ?>">
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
				<?php do_action( 'woocommerce_no_products_found' ); ?>
			<?php endif; ?>

			<?php do_action( 'woocommerce_after_main_content' ); ?>
		</div>
	</div>
</div>
<?php
get_footer( 'shop' );
