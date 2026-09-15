<?php
/**
 * Homepage hero slider (Swiper.js version).
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

$dmd_slides      = dmd_get_hero_slides();
$dmd_slide_count = count( $dmd_slides );
$dmd_width_mode  = get_theme_mod( 'dmd_slider_width', 'container' );
$dmd_container_class = ( 'full' === $dmd_width_mode ) ? 'w-full' : 'max-w-7xl mx-auto px-4 md:px-8';

// Customizer Colors
$hero_btn_bg   = get_theme_mod( 'dmd_hero_btn_bg', '#113D21' );
$hero_btn_text = get_theme_mod( 'dmd_hero_btn_text', '#ffffff' );
$hero_nav_bg   = get_theme_mod( 'dmd_hero_nav_bg', '#ffffff' );
$hero_title_color = get_theme_mod( 'dmd_hero_headline_color', '#ffffff' );
$hero_subtitle_color = get_theme_mod( 'dmd_hero_subtitle_color', '#ffffff' );

// Fallback logic
$fallback_image = get_theme_mod( 'dmd_hero_fallback_image' );
$fallback_title = get_theme_mod( 'dmd_hero_fallback_title' );
$fallback_url   = get_theme_mod( 'dmd_hero_fallback_url' );

// If no slides, try to render fallback. If no fallback, render default static hero.
if ( 0 === $dmd_slide_count ) {
    if ( $fallback_image || $fallback_title ) : ?>
        <section class="relative w-full overflow-hidden h-[300px] md:h-[480px] lg:h-[680px] <?php echo esc_attr( 'full' === $dmd_width_mode ? '' : 'my-4' ); ?>">
            <div class="<?php echo esc_attr( $dmd_container_class ); ?> h-full">
                <div class="relative w-full h-full rounded-2xl overflow-hidden">
                    <?php if ( $fallback_image ) : ?>
                        <img src="<?php echo esc_url( $fallback_image ); ?>" alt="<?php echo esc_attr( $fallback_title ? $fallback_title : get_bloginfo( 'name' ) ); ?>" class="absolute inset-0 w-full h-full object-cover" />
                    <?php endif; ?>
                    <div class="absolute inset-0 bg-black/30 flex items-center p-12">
                        <div class="max-w-xl">
                            <?php if ( $fallback_title ) : ?>
                                <h1 style="color: <?php echo esc_attr( $hero_title_color ); ?>;" class="text-4xl md:text-6xl font-bold"><?php echo esc_html( $fallback_title ); ?></h1>
                            <?php endif; ?>
                            <?php if ( $fallback_url ) : ?>
                                <a href="<?php echo esc_url( $fallback_url ); ?>" 
                                   style="background-color: <?php echo esc_attr( $hero_btn_bg ); ?>; color: <?php echo esc_attr( $hero_btn_text ); ?>;"
                                   class="mt-6 inline-block px-7 py-3 rounded-lg font-semibold transition">
                                    <?php esc_html_e( 'Shop Now', 'digital-mudir-dokan' ); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php else : ?>
        <section class="relative w-full overflow-hidden h-[300px] md:h-[480px] lg:h-[680px] bg-gradient-to-br from-green-600 to-emerald-800 flex items-center justify-center text-white px-6">
            <div class="max-w-xl text-center space-y-4">
                <h1 style="color: <?php echo esc_attr( $hero_title_color ); ?>;" class="text-3xl md:text-5xl font-bold leading-tight">
                    <?php esc_html_e( 'সেরা ও খাঁটি পণ্যের ডিজিটাল মুদির দোকান', 'digital-mudir-dokan' ); ?>
                </h1>
                <p style="color: <?php echo esc_attr( $hero_subtitle_color ); ?>;" class="text-base md:text-lg opacity-90">
                    <?php esc_html_e( 'আপনার নিত্যপ্রয়োজনীয় পণ্য এখন আপনার হাতের মুঠোয়।', 'digital-mudir-dokan' ); ?>
                </p>
                <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"
                   style="background-color: <?php echo esc_attr( $hero_btn_bg ); ?>; color: <?php echo esc_attr( $hero_btn_text ); ?>;"
                    class="inline-block px-7 py-3 rounded-lg font-semibold transition shadow-lg">
                    <?php esc_html_e( 'পণ্য কিনুন (Shop Now)', 'digital-mudir-dokan' ); ?>
                </a>
            </div>
        </section>
    <?php endif;
    return;
}

// Single Slide Handling
if ( 1 === $dmd_slide_count ) :
    $dmd_slide = $dmd_slides[0]; ?>
    <section class="relative w-full overflow-hidden h-[300px] md:h-[480px] lg:h-[680px] <?php echo esc_attr( 'full' === $dmd_width_mode ? '' : 'my-4' ); ?>">
        <div class="relative w-screen left-1/2 -translate-x-1/2 h-full overflow-hidden rounded-2xl">
            <?php if ( $dmd_slide['image'] ) : ?>
                <img src="<?php echo esc_url( $dmd_slide['image'] ); ?>" alt="<?php echo esc_attr( $dmd_slide['title'] ); ?>" class="absolute inset-0 w-full h-full object-cover" />
            <?php endif; ?>
            
            <div class="max-w-7xl mx-auto px-4 md:px-8 h-full relative z-10 flex items-center">
                <div class="max-w-xl space-y-4">
                    <?php if ( $dmd_slide['title'] ) : ?>
                        <h1 style="color: <?php echo esc_attr( $hero_title_color ); ?>;" class="text-3xl md:text-5xl font-bold leading-tight drop-shadow-md"><?php echo esc_html( $dmd_slide['title'] ); ?></h1>
                    <?php endif; ?>
                    <?php if ( $dmd_slide['subtitle'] ) : ?>
                        <p style="color: <?php echo esc_attr( $hero_subtitle_color ); ?>;" class="text-base md:text-lg opacity-90 drop-shadow-sm"><?php echo esc_html( $dmd_slide['subtitle'] ); ?></p>
                    <?php endif; ?>
                    <?php if ( $dmd_slide['cta_text'] && $dmd_slide['cta_url'] ) : ?>
                        <a href="<?php echo esc_url( $dmd_slide['cta_url'] ); ?>" 
                           style="background-color: <?php echo esc_attr( $hero_btn_bg ); ?>; color: <?php echo esc_attr( $hero_btn_text ); ?>;"
                           class="inline-block px-7 py-3 rounded-lg font-semibold transition shadow-lg">
                            <?php echo esc_html( $dmd_slide['cta_text'] ); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
<?php return; endif; ?>

<!-- Swiper for Multiple Slides -->
<section class="relative w-full overflow-hidden bg-gray-50 <?php echo esc_attr( 'full' === $dmd_width_mode ? '' : 'my-4' ); ?>">
    <div class="swiper hero-swiper w-full rounded-2xl overflow-hidden"
            data-autoplay="<?php echo esc_attr( get_theme_mod( 'dmd_hero_autoplay', true ) ? 'true' : 'false' ); ?>"
            data-delay="<?php echo esc_attr( get_theme_mod( 'dmd_slider_delay', 4000 ) ); ?>"
            data-speed="<?php echo esc_attr( get_theme_mod( 'dmd_slider_speed', 800 ) ); ?>">
        <div class="swiper-wrapper">
            <?php foreach ( $dmd_slides as $dmd_slide ) : ?>
            <div class="swiper-slide relative w-full h-[300px] md:h-[480px] lg:h-[680px]">
                <?php if ( $dmd_slide['image'] ) : ?>
                <img src="<?php echo esc_url( $dmd_slide['image'] ); ?>"
                    alt="<?php echo esc_attr( $dmd_slide['title'] ? $dmd_slide['title'] : get_bloginfo( 'name' ) ); ?>"
                    class="absolute inset-0 w-full h-full object-cover object-center pointer-events-none" />
                <?php endif; ?>

                <!-- Slide Content Overlay -->
                <div class="relative z-10 max-w-7xl mx-auto h-full flex items-center px-6 lg:px-12">
                    <div class="max-w-xl space-y-4">
                        <?php if ( $dmd_slide['title'] ) : ?>
                        <h1 style="color: <?php echo esc_attr( $hero_title_color ); ?>;" class="text-3xl md:text-5xl font-bold leading-tight drop-shadow-md">
                            <?php echo esc_html( $dmd_slide['title'] ); ?>
                        </h1>
                        <?php endif; ?>

                        <?php if ( $dmd_slide['subtitle'] ) : ?>
                        <p style="color: <?php echo esc_attr( $hero_subtitle_color ); ?>;" class="text-base md:text-lg opacity-90 drop-shadow-sm">
                            <?php echo esc_html( $dmd_slide['subtitle'] ); ?>
                        </p>
                        <?php endif; ?>

                        <?php if ( $dmd_slide['cta_text'] && $dmd_slide['cta_url'] ) : ?>
                        <a href="<?php echo esc_url( $dmd_slide['cta_url'] ); ?>"
                           style="background-color: <?php echo esc_attr( $hero_btn_bg ); ?>; color: <?php echo esc_attr( $hero_btn_text ); ?>;"
                            class="inline-block px-7 py-3 rounded-lg font-semibold transition shadow-lg">
                            <?php echo esc_html( $dmd_slide['cta_text'] ); ?>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Custom Styled Navigation Buttons -->
        <button type="button"
            style="background-color: <?php echo esc_attr( $hero_nav_bg ); ?>;"
            class="swiper-button-prev-custom absolute left-8 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full shadow-md flex items-center justify-center hover:bg-emerald-600 hover:text-white transition duration-200 cursor-pointer">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        <button type="button"
            style="background-color: <?php echo esc_attr( $hero_nav_bg ); ?>;"
            class="swiper-button-next-custom absolute right-8 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full shadow-md flex items-center justify-center hover:bg-emerald-600 hover:text-white transition duration-200 cursor-pointer">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>

        <!-- Custom Centered Pagination -->
        <div class="swiper-pagination-custom absolute bottom-4 inset-x-0 flex items-center justify-center gap-2 z-20">
        </div>
    </div>
</section>
<?php

