<?php
/**
 * Homepage hero slider (Swiper.js version).
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

$dmd_slides = dmd_get_hero_slides();

if ( ! $dmd_slides ) : ?>
    <section class="relative w-full h-[400px] md:h-[480px] lg:h-[680px] bg-gradient-to-br from-green-600 to-emerald-800 flex items-center justify-center text-white px-6">
        <div class="max-w-xl text-center space-y-4">
            <h1 class="text-3xl md:text-5xl font-bold leading-tight">
                <?php esc_html_e( 'সেরা ও খাঁটি পণ্যের ডিজিটাল মুদির দোকান', 'digital-mudir-dokan' ); ?>
            </h1>
            <p class="text-base md:text-lg opacity-90">
                <?php esc_html_e( 'আপনার নিত্যপ্রয়োজনীয় পণ্য এখন আপনার হাতের মুঠোয়।', 'digital-mudir-dokan' ); ?>
            </p>
            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"
                class="inline-block px-7 py-3 rounded-lg bg-white text-emerald-800 font-semibold hover:bg-gray-100 transition shadow-lg">
                <?php esc_html_e( 'পণ্য কিনুন (Shop Now)', 'digital-mudir-dokan' ); ?>
            </a>
        </div>
    </section>
<?php return; endif; ?>

<section class=" relative w-full overflow-hidden bg-gray-50">
    <div class="swiper hero-swiper w-full"
        data-autoplay="<?php echo esc_attr( get_theme_mod( 'dmd_hero_autoplay', true ) ? 'true' : 'false' ); ?>"
        data-speed="<?php echo esc_attr( get_theme_mod( 'dmd_hero_speed', 800 ) ); ?>"
        data-delay="<?php echo esc_attr( get_theme_mod( 'dmd_hero_delay', 4000 ) ); ?>">
        <div class="swiper-wrapper">
            <?php foreach ( $dmd_slides as $dmd_slide ) : ?>
            <div class="swiper-slide relative w-full h-[400px] md:h-[480px] lg:h-[680px]">
                <?php if ( $dmd_slide['image'] ) : ?>
                <img src="<?php echo esc_url( $dmd_slide['image'] ); ?>"
                    alt="<?php echo esc_attr( $dmd_slide['title'] ? $dmd_slide['title'] : get_bloginfo( 'name' ) ); ?>"
                    class="absolute inset-0 w-full h-full object-cover object-center pointer-events-none" />
                <?php endif; ?>

                <!-- Slide Content Overlay -->
                <div class="relative z-10 max-w-7xl mx-auto h-full flex items-center px-6 lg:px-12">
                    <div class="max-w-xl text-white space-y-4">
                        <?php if ( $dmd_slide['title'] ) : ?>
                        <h1 class="text-3xl md:text-5xl font-bold leading-tight drop-shadow-md">
                            <?php echo esc_html( $dmd_slide['title'] ); ?>
                        </h1>
                        <?php endif; ?>

                        <?php if ( $dmd_slide['subtitle'] ) : ?>
                        <p class="text-base md:text-lg opacity-90 drop-shadow-sm">
                            <?php echo esc_html( $dmd_slide['subtitle'] ); ?>
                        </p>
                        <?php endif; ?>

                        <?php if ( $dmd_slide['cta_text'] && $dmd_slide['cta_url'] ) : ?>
                        <a href="<?php echo esc_url( $dmd_slide['cta_url'] ); ?>"
                            class="inline-block px-7 py-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-semibold transition shadow-lg">
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
            class="swiper-button-prev-custom absolute left-8 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-white/90 text-gray-800 shadow-md flex items-center justify-center hover:bg-emerald-600 hover:text-white transition duration-200 cursor-pointer">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        <button type="button"
            class="swiper-button-next-custom absolute right-8 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-white/90 text-gray-800 shadow-md flex items-center justify-center hover:bg-emerald-600 hover:text-white transition duration-200 cursor-pointer">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>

        <!-- Custom Centered Pagination -->
        <div class="swiper-pagination-custom absolute bottom-4 inset-x-0 flex items-center justify-center gap-2 z-20">
        </div>
    </div>
</section>