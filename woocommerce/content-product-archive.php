<?php
/**
 * Product card used in Archive pages (Grid/List View).
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
    return;
}

$product_id    = $product->get_id();
$image_id      = $product->get_image_id();
$custom_ph     = get_theme_mod( 'dmd_default_product_placeholder', '' );

if ( $image_id ) {
    $img_url = wp_get_attachment_image_url( $image_id, 'woocommerce_thumbnail' );
} elseif ( ! empty( $custom_ph ) ) {
    $img_url = $custom_ph;
} else {
    $img_url = wc_placeholder_img_src( 'woocommerce_thumbnail' );
}

$regular_price = $product->get_regular_price();
$is_on_sale    = $product->is_on_sale();
$percentage    = 0;

if ( $is_on_sale && $regular_price && $product->get_sale_price() ) {
    $percentage = round( ( ( (float)$regular_price - (float)$product->get_sale_price() ) / (float)$regular_price ) * 100 );
}
?>
<li <?php wc_product_class( 'product-card bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md transition flex flex-col group-[.list-view]:flex-row group-[.list-view]:gap-4', $product ); ?>>

    <!-- Discount Badge -->
    <?php if ( $is_on_sale && $percentage > 0 ) : ?>
        <span class="absolute top-3 left-3 z-10 w-8 h-8 rounded-full bg-emerald-700 text-white text-xs font-bold flex items-center justify-center shadow">-<?php echo esc_html( $percentage ); ?>%</span>
    <?php endif; ?>

    <!-- Image Container (Grid: Top, List: Left) -->
    <div class="product-card-image-box w-full aspect-[4/3] rounded-lg overflow-hidden bg-gray-50 flex items-center justify-center mb-3 relative group-[.list-view]:w-40 group-[.list-view]:aspect-square group-[.list-view]:mb-0 flex-shrink-0">
        <a href="<?php the_permalink(); ?>" class="block w-full h-full">
            <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-contain p-2 hover:scale-105 transition duration-300" loading="lazy" />
        </a>
    </div>

    <!-- Content + Actions (Grid: Stacked, List: Row) -->
    <div class="flex-1 flex flex-col group-[.list-view]:flex-row group-[.list-view]:justify-between group-[.list-view]:items-center group-[.list-view]:gap-4">
        
        <!-- Content -->
        <div class="product-card-content flex-1">
            <h3 class="text-sm md:text-base font-semibold text-gray-800 line-clamp-2 mb-2">
                <a href="<?php the_permalink(); ?>" class="hover:text-emerald-700 transition"><?php the_title(); ?></a>
            </h3>
            <div class="flex items-baseline gap-2 mb-3">
                <?php if ( $is_on_sale && $regular_price ) : ?>
                    <span class="text-gray-400 line-through text-xs font-normal"><?php echo wc_price( $regular_price ); ?></span>
                <?php endif; ?>
                <span class="text-emerald-700 font-bold text-base"><?php echo wc_price( $product->get_price() ); ?></span>
            </div>
        </div>

        <!-- Action Buttons (Grid: Stacked Bottom, List: Stacked Right) -->
        <div class="product-card-actions space-y-2 mt-auto group-[.list-view]:mt-0 group-[.list-view]:space-y-0 group-[.list-view]:flex group-[.list-view]:gap-2 group-[.list-view]:w-1/3">
            <?php 
            echo apply_filters( 'woocommerce_loop_add_to_cart_link',
                sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" class="%s">%s</a>',
                    esc_url( $product->add_to_cart_url() ),
                    esc_attr( $product_id ),
                    'button add_to_cart_button ajax_add_to_cart w-full py-2.5 px-4 rounded-xl bg-emerald-800 hover:bg-emerald-900 text-white text-sm font-semibold transition flex items-center justify-center gap-1',
                    esc_html__( 'Add to cart', 'digital-mudir-dokan' )
                ),
                $product
            );
            ?>
            <a href="<?php echo esc_url( wc_get_checkout_url() . '?add-to-cart=' . $product_id ); ?>" class="w-full py-2 px-4 rounded-xl bg-white border border-emerald-800 text-emerald-800 hover:bg-emerald-50 text-sm font-semibold transition flex items-center justify-center">
                <?php esc_html_e( 'অর্ডার করুন', 'digital-mudir-dokan' ); ?>
            </a>
        </div>
    </div>
</li>
