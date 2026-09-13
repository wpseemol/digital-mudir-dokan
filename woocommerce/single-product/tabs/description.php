<?php
/**
 * Description tab
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/description.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package Digital_Mudir_Dokan
 * @version 2.0.0
 */

defined( 'ABSPATH' ) || exit;

global $post;

$heading = esc_html( apply_filters( 'woocommerce_product_description_heading', __( 'Description', 'digital-mudir-dokan' ) ) );

?>
<div class="dmd-description-toggle relative" data-dmd-description-wrapper>
    <?php if ( $heading ) : ?>
        <h2 class="text-lg font-semibold mb-4"><?php echo esc_html( $heading ); ?></h2>
    <?php endif; ?>

    <div class="dmd-prose max-h-64 overflow-hidden transition-all duration-300 ease-in-out" data-dmd-description-content>
        <?php the_content(); ?>
    </div>

    <div class="dmd-description-fade absolute bottom-0 inset-x-0 h-16 bg-gradient-to-t from-white to-transparent pointer-events-none" data-dmd-description-fade></div>

    <button type="button" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-emerald-600 text-emerald-700 font-medium text-sm hover:bg-emerald-50 transition mt-4" data-dmd-description-toggle>
        <span data-dmd-toggle-text><?php esc_html_e( 'বিস্তারিত দেখুন +', 'digital-mudir-dokan' ); ?></span>
    </button>
</div>
