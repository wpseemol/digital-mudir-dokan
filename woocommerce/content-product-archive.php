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
<li <?php wc_product_class( 'product-card-container', $product ); ?>>
    
    <!-- Grid View Card -->
    <div class="dmd-card-grid bg-white rounded-2xl border border-gray-100 p-4 shadow-sm hover:shadow-md transition flex-col justify-between h-full relative group">
        <!-- Image -->
        <div class="w-full aspect-square rounded-xl overflow-hidden bg-gray-50 flex items-center justify-center mb-3">
            <a href="<?php the_permalink(); ?>" class="block w-full h-full">
                <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-contain p-2 group-hover:scale-105 transition duration-300" loading="lazy" />
            </a>
            <?php if ( $is_on_sale && $percentage > 0 ) : ?>
                <span class="absolute top-3 left-3 z-10 bg-emerald-700 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow">-<?php echo esc_html( $percentage ); ?>%</span>
            <?php endif; ?>
        </div>
        
        <!-- Content -->
        <div class="flex-1">
            <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 mb-2">
                <a href="<?php the_permalink(); ?>" class="hover:text-emerald-700 transition"><?php the_title(); ?></a>
            </h3>
            <div class="flex items-baseline gap-2 mb-3">
                <?php if ( $is_on_sale && $regular_price ) : ?>
                    <span class="text-gray-400 line-through text-xs font-normal"><?php echo wc_price( $regular_price ); ?></span>
                <?php endif; ?>
                <span class="text-emerald-700 font-bold text-base"><?php echo wc_price( $product->get_price() ); ?></span>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-auto space-y-2">
            <?php dmd_order_now_button( $product, 'w-full py-2 px-4 rounded-lg bg-emerald-800 hover:bg-emerald-900 text-white text-sm font-semibold transition flex items-center justify-center' ); ?>
        </div>
    </div>

    <!-- List View Card -->
    <div class="dmd-card-list bg-white rounded-2xl border border-gray-200 p-5 shadow-sm hover:shadow-md transition flex-col sm:flex-row items-center justify-between gap-6 w-full relative">
        <!-- Left: Image -->
        <div class="w-full sm:w-44 h-40 shrink-0 aspect-square rounded-xl bg-gray-50 flex items-center justify-center overflow-hidden p-2">
            <a href="<?php the_permalink(); ?>" class="block w-full h-full">
                <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-contain hover:scale-105 transition duration-300" loading="lazy" />
            </a>
        </div>

        <!-- Middle: Content -->
        <div class="flex-1 text-left space-y-2">
            <h3 class="text-lg font-semibold text-gray-800">
                <a href="<?php the_permalink(); ?>" class="hover:text-emerald-700 transition"><?php the_title(); ?></a>
            </h3>
            <div class="flex items-baseline gap-3">
                <?php if ( $is_on_sale && $regular_price ) : ?>
                    <span class="text-gray-400 line-through font-normal"><?php echo wc_price( $regular_price ); ?></span>
                <?php endif; ?>
                <span class="text-emerald-700 font-bold text-xl"><?php echo wc_price( $product->get_price() ); ?></span>
            </div>
            <p class="text-sm text-gray-600 line-clamp-2"><?php echo esc_html( wp_strip_all_tags( $product->get_short_description() ) ); ?></p>
        </div>

        <!-- Right: Actions -->
        <div class="w-full sm:w-48 shrink-0 flex flex-col gap-2.5 justify-center">
             <?php dmd_order_now_button( $product, 'w-full py-3 px-4 rounded-lg bg-emerald-800 hover:bg-emerald-900 text-white text-sm font-semibold transition flex items-center justify-center' ); ?>
        </div>
    </div>
</li>
