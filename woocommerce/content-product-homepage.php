<?php
/**
 * Product card used in Homepage sections.
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
<li <?php wc_product_class( 'product-card-item flex flex-col justify-between bg-white rounded-2xl border border-gray-100 p-4 shadow-sm hover:shadow-md transition-all duration-300 relative group', $product ); ?>>

    <!-- Discount Badge -->
    <?php if ( $is_on_sale && $percentage > 0 ) : ?>
        <span class="absolute top-3 left-3 z-10 w-8 h-8 rounded-full bg-emerald-700 text-white text-xs font-bold flex items-center justify-center shadow">
            -<?php echo esc_html( $percentage ); ?>%
        </span>
    <?php endif; ?>

    <!-- Thumbnail -->
    <a href="<?php the_permalink(); ?>" class="block w-full aspect-square rounded-xl overflow-hidden bg-gray-50 flex items-center justify-center mb-3 group-hover:opacity-95">
        <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-contain p-2 group-hover:scale-105 transition-transform duration-300" loading="lazy" />
    </a>

    <!-- Title and Price -->
    <div class="mb-3">
        <h3 class="text-sm md:text-base font-semibold text-gray-800 line-clamp-2 mb-1 leading-snug">
            <a href="<?php the_permalink(); ?>" class="hover:text-emerald-700 transition-colors"><?php the_title(); ?></a>
        </h3>
        <div class="flex items-baseline gap-2 text-sm font-medium">
            <?php if ( $is_on_sale && $regular_price ) : ?>
                <span class="text-gray-400 line-through text-xs font-normal"><?php echo wc_price( $regular_price ); ?></span>
            <?php endif; ?>
            <span class="text-emerald-700 font-bold text-base"><?php echo wc_price( $product->get_price() ); ?></span>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="space-y-2 mt-auto">
        <?php 
        echo apply_filters( 'woocommerce_loop_add_to_cart_link',
            sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" class="%s">%s</a>',
                esc_url( $product->add_to_cart_url() ),
                esc_attr( $product_id ),
                'button product_type_simple add_to_cart_button ajax_add_to_cart w-full py-2.5 px-4 rounded-xl bg-emerald-800 hover:bg-emerald-900 text-white text-sm font-semibold transition flex items-center justify-center gap-1 shadow-sm',
                esc_html__( 'Add to cart', 'digital-mudir-dokan' )
            ),
            $product
        );
        ?>
        <a href="<?php echo esc_url( wc_get_checkout_url() . '?add-to-cart=' . $product_id ); ?>" class="w-full py-2 px-4 rounded-xl bg-white border border-emerald-800 text-emerald-800 hover:bg-emerald-50 text-sm font-semibold transition flex items-center justify-center">
            <?php esc_html_e( 'অর্ডার করুন', 'digital-mudir-dokan' ); ?>
        </a>
    </div>
</li>
