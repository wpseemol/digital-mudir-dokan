<?php
/**
 * A titled grid of products with tabbed category filtering used on the front page.
 *
 * @param array $args {
 *     @type string $title   Section heading.
 *     @type int    $limit   How many products to show per category.
 *     @type string $source  'recent' or 'featured'.
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
		'title'  => __( 'সকল পণ্য / আমাদের পণ্যসমূহ', 'digital-mudir-dokan' ),
		'limit'  => 12,
		'source' => 'recent',
		'id'     => 'all-products',
	)
);

// Fetch categories
$categories = get_terms( array(
    'taxonomy' => 'product_cat',
    'hide_empty' => true,
    'parent' => 0,
) );

$dmd_heading_id = 'dmd-section-' . sanitize_html_class( $dmd_args['id'] );
?>
<section class="dmd-product-section bg-white py-12" aria-labelledby="<?php echo esc_attr( $dmd_heading_id ); ?>">
    <div class="dmd-container">
        <div class="flex flex-wrap justify-between items-center mb-8 gap-4">
            <h2 id="<?php echo esc_attr( $dmd_heading_id ); ?>" class="dmd-section-title text-left m-0">
                <?php echo esc_html( $dmd_args['title'] ); ?>
            </h2>

            <div class="flex flex-wrap gap-2 product-tabs">
                <button class="px-5 py-2 text-sm font-semibold border border-gray-300 rounded-md transition-all bg-[#1B6A3B] text-white border-[#1B6A3B]" data-category="all">
                    সকল পণ্য (All)
                </button>
                <?php foreach ( $categories as $category ) : ?>
                    <button class="px-5 py-2 text-sm font-semibold border border-gray-300 rounded-md transition-all" data-category="<?php echo esc_attr( $category->slug ); ?>">
                        <?php echo esc_html( $category->name ); ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <a class="text-green-600 font-semibold flex items-center gap-1" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
                সব দেখুন (VIEW ALL) ->
            </a>
        </div>

        <div class="product-grid-container">
            <?php 
            // For simplicity, we can load all products and filter with JS, 
            // or use AJAX. Here we load all relevant products and add category tags.
            $dmd_products = wc_get_products( [
                'limit' => 20, 
                'status' => 'publish'
            ] );
            ?>
            <ul class="dmd-product-grid grid grid-cols-2 lg:grid-cols-4 gap-4 list-none p-0 m-0">
                <?php foreach ( $dmd_products as $product ) : 
                    $cat_slugs = [];
                    foreach( get_the_terms( $product->get_id(), 'product_cat' ) as $term ) $cat_slugs[] = $term->slug;
                ?>
                    <li class="product-item" data-categories="<?php echo esc_attr( implode(' ', $cat_slugs) ); ?>">
                        <?php 
                        global $product;
                        $product = $product;
                        wc_get_template_part( 'content', 'product' ); 
                        ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</section>
