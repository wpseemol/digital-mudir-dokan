<?php
/**
 * A titled grid of products with optional tabbed category filtering used on the front page.
 *
 * @param array $args {
 *     @type string $title   Section heading.
 *     @type int    $limit   How many products to show.
 *     @type string $source  'recent' or 'best_selling'.
 *     @type bool   $show_tabs Enable/Disable tabbed filtering.
 *     @type bool   $show_link Enable/Disable 'View All' link.
 * }
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

$dmd_args = wp_parse_args(
	$args,
	array(
		'title'     => get_theme_mod('dmd_all_products_title', 'Our Products'),
		'limit'     => get_theme_mod('dmd_product_count', 12),
		'source'    => 'recent',
		'show_tabs' => true,
		'show_link' => true,
        'id'        => 'products'
	)
);

// Fetch top-level categories, excluding 'Uncategorized'
$categories = get_terms( array(
    'taxonomy'   => 'product_cat',
    'hide_empty' => true,
    'parent'     => 0,
    'exclude'    => get_term_by('slug', 'uncategorized', 'product_cat') ? [get_term_by('slug', 'uncategorized', 'product_cat')->term_id] : [],
) );
?>
<section id="<?php echo esc_attr($dmd_args['id']); ?>-section" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-wrap justify-between items-center mb-8 gap-4">
        <h2 class="text-2xl md:text-3xl font-bold tracking-wider text-gray-900 text-center mb-4 uppercase w-full">
            <?php echo esc_html( $dmd_args['title'] ); ?>
        </h2>

        <?php if ($dmd_args['show_tabs'] && !empty($categories)) : ?>
            <div class="flex flex-wrap gap-2 w-full justify-center mb-8">
                <button class="product-tab-btn px-5 py-2 text-sm font-semibold border border-gray-300 rounded-md transition-all bg-[#1B6A3B] text-white border-[#1B6A3B]" data-category="all">
                    সকল পণ্য (All)
                </button>
                <?php foreach ( $categories as $category ) : ?>
                    <button class="product-tab-btn px-5 py-2 text-sm font-semibold border border-gray-300 rounded-md transition-all bg-white text-gray-700" data-category="<?php echo esc_attr( $category->slug ); ?>">
                        <?php echo esc_html( $category->name ); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <ul class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6 product-grid-fade">
        <?php 
        $query_args = [ 
            'post_type' => 'product',
            'posts_per_page' => (int)$dmd_args['limit'], 
            'post_status' => 'publish' 
        ];
        if ($dmd_args['source'] === 'best_selling') {
            $query_args['orderby'] = 'meta_value_num';
            $query_args['meta_key'] = 'total_sales';
        }
        
        $products_query = new WP_Query( $query_args );

        if ( $products_query->have_posts() ) :
            while ( $products_query->have_posts() ) : $products_query->the_post();
                global $product;
                $product = wc_get_product( get_the_ID() );
                if ( ! $product || ! $product->is_visible() ) continue;
                
                wc_get_template_part( 'content', 'product' ); 
            endwhile;
            wp_reset_postdata();
        endif;
        ?>
    </ul>

    <?php if ($dmd_args['show_link']) : ?>
        <div class="text-center mt-10">
            <a class="text-emerald-700 font-semibold flex items-center justify-center gap-1" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
                সব দেখুন (VIEW ALL) <?php echo dmd_icon( 'chevron-r', 16 ); ?>
            </a>
        </div>
    <?php endif; ?>
</section>

<script>
jQuery(document).ready(function($) {
    $('.product-card-item').animate({opacity: 1}, 500);
});
</script>
