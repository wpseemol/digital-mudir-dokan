<?php
/**
 * Product card used on homepage.
 *
 * @package Digital_Mudir_Dokan
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

$dmd_percentage = dmd_get_sale_percentage( $product );
?>

<div class="bg-white p-3 flex flex-col justify-between h-full relative">
    
    <?php if ( $dmd_percentage ) : ?>
        <span class="absolute top-2 left-2 z-10 w-8 h-8 rounded-full bg-[#113D21] text-white text-[10px] font-bold flex items-center justify-center shadow-sm">
            -<?php echo esc_html( $dmd_percentage ); ?>%
        </span>
    <?php endif; ?>

    <div class="w-full aspect-square rounded-lg overflow-hidden bg-gray-50 flex items-center justify-center mb-3">
        <a href="<?php the_permalink(); ?>" class="w-full h-full">
            <?php
            if ( has_post_thumbnail() ) {
                echo wp_kses_post(
                    $product->get_image(
                        'dmd-product-card',
                        array(
                            'class'    => 'w-full h-full object-contain p-1',
                            'loading'  => 'lazy',
                            'decoding' => 'async',
                        )
                    )
                );
            } else {
                printf(
                    '<img src="%1$s" alt="%2$s" class="w-full h-full object-contain p-1" loading="lazy" decoding="async">',
                    esc_url( dmd_get_product_placeholder_url() ),
                    esc_attr( $product->get_name() )
                );
            }
            ?>
        </a>
    </div>

    <div class="flex flex-col flex-grow">
        <h3 class="text-xs md:text-sm font-semibold text-gray-800 line-clamp-1 mb-1">
            <a href="<?php the_permalink(); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
        </h3>

        <?php if ( $product->get_price_html() ) : ?>
            <div class="flex items-baseline gap-1 mb-2 text-xs font-medium">
                <?php echo wp_kses_post( $product->get_price_html() ); ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="flex flex-col gap-1.5">
        <?php 
        // Add to cart
        echo apply_filters( 'woocommerce_loop_add_to_cart_link',
            sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="%s">%s</a>',
                esc_url( $product->add_to_cart_url() ),
                esc_attr( $product->get_id() ),
                esc_attr( $product->get_sku() ),
                'w-full py-2 px-3 rounded-lg bg-[#113D21] hover:bg-[#058a36] text-white text-xs font-semibold transition text-center flex items-center justify-center',
                esc_html( $product->add_to_cart_text() )
            ),
            $product
        );
        ?>
        
        <?php dmd_order_now_button( $product, 'w-full py-2 px-3 rounded-lg bg-white border border-[#113D21] text-[#113D21] hover:bg-emerald-50 text-xs font-semibold transition text-center flex items-center justify-center' ); ?>
    </div>
</div>
