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

<div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-200 p-4 flex flex-col justify-between h-full relative">
    
    <?php if ( $dmd_percentage ) : ?>
        <span class="absolute top-3 left-3 z-10 w-9 h-9 rounded-full bg-emerald-600 text-white text-xs font-bold flex items-center justify-center shadow-sm">
            -<?php echo esc_html( $dmd_percentage ); ?>%
        </span>
    <?php endif; ?>

    <div class="w-full aspect-[4/3] rounded-lg overflow-hidden bg-gray-50 flex items-center justify-center mb-4">
        <a href="<?php the_permalink(); ?>">
            <?php
            if ( has_post_thumbnail() ) {
                echo wp_kses_post(
                    $product->get_image(
                        'dmd-product-card',
                        array(
                            'class'    => 'w-full h-full object-contain p-2 hover:scale-105 transition-transform duration-300',
                            'loading'  => 'lazy',
                            'decoding' => 'async',
                        )
                    )
                );
            } else {
                printf(
                    '<img src="%1$s" alt="%2$s" class="w-full h-full object-contain p-2 hover:scale-105 transition-transform duration-300" loading="lazy" decoding="async">',
                    esc_url( dmd_get_product_placeholder_url() ),
                    esc_attr( $product->get_name() )
                );
            }
            ?>
        </a>
    </div>

    <div class="flex flex-col flex-grow">
        <h3 class="text-sm md:text-base font-semibold text-gray-800 line-clamp-1 mb-2">
            <a href="<?php the_permalink(); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
        </h3>

        <?php if ( $product->get_price_html() ) : ?>
            <div class="flex items-baseline gap-2 mb-4 text-sm font-medium">
                <?php echo wp_kses_post( $product->get_price_html() ); ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="flex flex-col gap-2">
        <?php 
        // Add to cart
        echo apply_filters( 'woocommerce_loop_add_to_cart_link',
            sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="%s">%s</a>',
                esc_url( $product->add_to_cart_url() ),
                esc_attr( $product->get_id() ),
                esc_attr( $product->get_sku() ),
                'w-full py-2.5 px-4 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-sm font-semibold transition text-center flex items-center justify-center',
                esc_html( $product->add_to_cart_text() )
            ),
            $product
        );
        ?>
        
        <?php dmd_order_now_button( $product, 'w-full py-2 px-4 rounded-lg bg-white border border-emerald-700 text-emerald-700 hover:bg-emerald-50 text-sm font-semibold transition text-center flex items-center justify-center' ); ?>
    </div>
</div>
