<?php
/**
 * A titled grid of products used on the front page.
 *
 * @param array $args {
 *     @type string $title   Section heading.
 *     @type int    $limit   How many products to show.
 *     @type string $orderby WooCommerce shortcode orderby value.
 *     @type string $source  'best_selling', 'recent' or 'featured'.
 *     @type string $tone    'white' or 'surface' background.
 * }
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wc_get_products' ) ) {
	return;
}

$dmd_args = wp_parse_args(
	$args,
	array(
		'title'  => '',
		'limit'  => 4,
		'source' => 'recent',
		'tone'   => 'white',
		'id'     => 'products',
	)
);

$dmd_query_args = array(
	'status'  => 'publish',
	'limit'   => (int) $dmd_args['limit'],
	'return'  => 'objects',
	'orderby' => 'date',
	'order'   => 'DESC',
);

switch ( $dmd_args['source'] ) {
	case 'best_selling':
		$dmd_query_args['orderby']  = 'meta_value_num';
		$dmd_query_args['meta_key'] = 'total_sales'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		break;

	case 'featured':
		$dmd_query_args['featured'] = true;
		break;
}

$dmd_products = wc_get_products( $dmd_query_args );

// A brand new shop has no sales history — fall back to the newest products so
// the section still has something to show.
if ( ! $dmd_products && 'recent' !== $dmd_args['source'] ) {
	$dmd_products = wc_get_products(
		array(
			'status'  => 'publish',
			'limit'   => (int) $dmd_args['limit'],
			'orderby' => 'date',
			'order'   => 'DESC',
		)
	);
}

if ( ! $dmd_products ) {
	return;
}

$dmd_heading_id = 'dmd-section-' . sanitize_html_class( $dmd_args['id'] );
?>
<section class="dmd-product-section <?php echo 'surface' === $dmd_args['tone'] ? 'bg-surface' : 'bg-white'; ?> py-12"
	aria-labelledby="<?php echo esc_attr( $dmd_heading_id ); ?>">

	<div class="dmd-container">
		<?php if ( $dmd_args['title'] ) : ?>
			<h2 id="<?php echo esc_attr( $dmd_heading_id ); ?>" class="dmd-section-title mb-8">
				<?php echo esc_html( $dmd_args['title'] ); ?>
			</h2>
		<?php endif; ?>

		<ul class="dmd-product-grid m-0 grid list-none grid-cols-2 gap-4 p-0 lg:grid-cols-4">
			<?php
			global $product, $post;
			$dmd_original_post = $post;

			foreach ( $dmd_products as $dmd_product ) :
				$post    = get_post( $dmd_product->get_id() ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				$product = $dmd_product; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				setup_postdata( $post );

				wc_get_template_part( 'content', 'product' );
			endforeach;

			$post = $dmd_original_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			wp_reset_postdata();
			?>
		</ul>

		<?php if ( 'best_selling' !== $dmd_args['source'] && function_exists( 'wc_get_page_permalink' ) ) : ?>
			<p class="mt-8 text-center m-0">
				<a class="dmd-btn dmd-btn--outline px-8" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
					<?php esc_html_e( 'Browse the full shop', 'digital-mudir-dokan' ); ?>
				</a>
			</p>
		<?php endif; ?>
	</div>
</section>
