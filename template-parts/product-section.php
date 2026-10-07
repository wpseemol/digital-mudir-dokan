<?php
/**
 * A titled grid of products with optional category tabs, used on the front page.
 *
 * @param array $args {
 *     @type string $id        Section id prefix.
 *     @type string $title     Section heading.
 *     @type string $eyebrow   Small line above the heading.
 *     @type int    $limit     How many products to show.
 *     @type string $source    'recent' or 'best_selling'.
 *     @type bool   $show_tabs Category tabs that filter the grid.
 *     @type bool   $show_link "View all" link.
 *     @type string $class     Extra section classes (background).
 * }
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

$dmd_args = wp_parse_args(
	$args,
	array(
		'id'        => 'products',
		'title'     => __( 'Our products', 'digital-mudir-dokan' ),
		'eyebrow'   => '',
		'limit'     => 12,
		'source'    => 'recent',
		'show_tabs' => true,
		'show_link' => true,
		'class'     => '',
	)
);

$dmd_query_args = array(
	'post_type'           => 'product',
	'post_status'         => 'publish',
	'posts_per_page'      => max( 1, (int) $dmd_args['limit'] ),
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
	'tax_query'           => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		array(
			'taxonomy' => 'product_visibility',
			'field'    => 'name',
			'terms'    => array( 'exclude-from-catalog' ),
			'operator' => 'NOT IN',
		),
	),
);

if ( 'best_selling' === $dmd_args['source'] ) {
	$dmd_query_args['meta_key'] = 'total_sales'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
	$dmd_query_args['orderby']  = array(
		'meta_value_num' => 'DESC',
		'date'           => 'DESC',
	);
}

$dmd_products = new WP_Query( $dmd_query_args );

if ( ! $dmd_products->have_posts() ) {
	return;
}

// Only offer tabs for categories that actually appear in this grid.
$dmd_tabs = array();
if ( $dmd_args['show_tabs'] ) {
	$dmd_present = array();
	foreach ( $dmd_products->posts as $dmd_post ) {
		$dmd_p = wc_get_product( $dmd_post );
		if ( $dmd_p ) {
			$dmd_present = array_merge( $dmd_present, explode( ' ', dmd_get_product_cat_slugs( $dmd_p ) ) );
		}
	}
	foreach ( dmd_get_top_categories() as $dmd_cat ) {
		if ( in_array( $dmd_cat->slug, $dmd_present, true ) ) {
			$dmd_tabs[] = $dmd_cat;
		}
	}
	if ( count( $dmd_tabs ) < 2 ) {
		$dmd_tabs = array();
	}
}

$dmd_heading_id = $dmd_args['id'] . '-heading';
$dmd_shop_url   = wc_get_page_permalink( 'shop' );
if ( 'best_selling' === $dmd_args['source'] ) {
	$dmd_shop_url = add_query_arg( 'orderby', 'popularity', $dmd_shop_url );
}
?>
<section id="<?php echo esc_attr( $dmd_args['id'] ); ?>-section" class="dmd-section <?php echo esc_attr( $dmd_args['class'] ); ?>" aria-labelledby="<?php echo esc_attr( $dmd_heading_id ); ?>" <?php echo $dmd_tabs ? 'data-dmd-tabs' : ''; ?>>
	<div class="dmd-container">
		<?php
		dmd_section_heading(
			array(
				'title'    => $dmd_args['title'],
				'eyebrow'  => $dmd_args['eyebrow'],
				'id'       => $dmd_heading_id,
				'link_url' => $dmd_args['show_link'] ? $dmd_shop_url : '',
			)
		);
		?>

		<?php if ( $dmd_tabs ) : ?>
			<div class="dmd-scroll-x -mx-4 mb-6 gap-2 px-4 pb-1 sm:mx-0 sm:flex-wrap sm:px-0" role="toolbar" aria-label="<?php esc_attr_e( 'Filter by category', 'digital-mudir-dokan' ); ?>">
				<button type="button" class="dmd-chip is-active" data-dmd-tab-filter="all" aria-pressed="true"><?php esc_html_e( 'সকল পণ্য', 'digital-mudir-dokan' ); ?></button>
				<?php foreach ( $dmd_tabs as $dmd_tab ) : ?>
					<button type="button" class="dmd-chip" data-dmd-tab-filter="<?php echo esc_attr( $dmd_tab->slug ); ?>" aria-pressed="false"><?php echo esc_html( $dmd_tab->name ); ?></button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<ul class="products m-0 grid list-none grid-cols-2 gap-3 p-0 sm:gap-4 md:grid-cols-3 lg:grid-cols-4 lg:gap-5">
			<?php
			while ( $dmd_products->have_posts() ) :
				$dmd_products->the_post();
				$GLOBALS['product'] = wc_get_product( get_the_ID() ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				wc_get_template_part( 'content', 'product-homepage' );
			endwhile;
			wp_reset_postdata();
			?>
		</ul>

		<?php if ( $dmd_tabs ) : ?>
			<p class="mt-6 text-center text-sm text-muted" data-dmd-tabs-empty hidden><?php esc_html_e( 'No products in this category yet.', 'digital-mudir-dokan' ); ?></p>
		<?php endif; ?>

		<?php if ( $dmd_args['show_link'] ) : ?>
			<div class="mt-8 text-center sm:hidden">
				<a class="dmd-btn dmd-btn--outline" href="<?php echo esc_url( $dmd_shop_url ); ?>"><?php esc_html_e( 'View all', 'digital-mudir-dokan' ); ?></a>
			</div>
		<?php endif; ?>
	</div>
</section>
