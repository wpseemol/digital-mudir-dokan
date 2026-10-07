<?php
/**
 * Homepage hero slider.
 *
 * Banners sit in a fixed 13:5 frame (4:3 on phones when a mobile image is
 * set), matching the sizes the Customizer crops to, so nothing is cut off.
 * Slide headlines are h2: the page h1 lives in front-page.php.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

$dmd_slides  = dmd_get_hero_slides();
$dmd_is_full = 'full' === dmd_get_hero_mode();

$dmd_hero_btn_bg        = get_theme_mod( 'dmd_hero_btn_bg', '#113D21' );
$dmd_hero_btn_text      = get_theme_mod( 'dmd_hero_btn_text', '#ffffff' );
$dmd_hero_nav_bg        = get_theme_mod( 'dmd_hero_nav_bg', '#ffffff' );
$dmd_hero_title_color   = get_theme_mod( 'dmd_hero_headline_color', '#ffffff' );
$dmd_hero_subtitle_color = get_theme_mod( 'dmd_hero_subtitle_color', '#ffffff' );

$dmd_shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );

if ( ! $dmd_slides ) : ?>
	<section class="dmd-hero <?php echo esc_attr( $dmd_is_full ? '' : 'pt-4 sm:pt-6' ); ?>" aria-label="<?php esc_attr_e( 'Featured', 'digital-mudir-dokan' ); ?>">
		<div class="<?php echo esc_attr( $dmd_is_full ? '' : 'dmd-container' ); ?>">
			<div class="relative overflow-hidden bg-green text-white <?php echo esc_attr( $dmd_is_full ? '' : 'rounded-2xl' ); ?>">
				<div class="pointer-events-none absolute -right-24 -top-24 h-80 w-80 rounded-full bg-green-dark/40 blur-2xl" aria-hidden="true"></div>
				<div class="pointer-events-none absolute -bottom-32 left-1/3 h-72 w-72 rounded-full bg-white/5" aria-hidden="true"></div>
				<div class="dmd-container relative flex min-h-[320px] flex-col items-start justify-center gap-4 py-14 md:min-h-[420px]">
					<p class="m-0 inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-medium tracking-wide">
						<?php echo dmd_icon( 'truck', 14 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
						<?php esc_html_e( 'সারা দেশে হোম ডেলিভারি', 'digital-mudir-dokan' ); ?>
					</p>
					<h2 class="m-0 max-w-2xl font-display text-3xl font-bold leading-tight md:text-5xl" style="color: <?php echo esc_attr( $dmd_hero_title_color ); ?>;">
						<?php esc_html_e( 'সেরা ও খাঁটি পণ্যের ডিজিটাল মুদির দোকান', 'digital-mudir-dokan' ); ?>
					</h2>
					<p class="m-0 max-w-xl text-base opacity-90 md:text-lg" style="color: <?php echo esc_attr( $dmd_hero_subtitle_color ); ?>;">
						<?php esc_html_e( 'আপনার নিত্যপ্রয়োজনীয় পণ্য এখন আপনার হাতের মুঠোয়।', 'digital-mudir-dokan' ); ?>
					</p>
					<a href="<?php echo esc_url( $dmd_shop_url ); ?>" class="mt-2 inline-flex items-center gap-2 rounded-lg bg-white px-6 py-3 text-sm font-semibold text-green shadow-lift transition hover:bg-green-soft hover:text-green">
						<?php esc_html_e( 'পণ্য কিনুন', 'digital-mudir-dokan' ); ?>
						<?php echo dmd_icon( 'chevron-r', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
					</a>
				</div>
			</div>
		</div>
	</section>
	<?php
	return;
endif;

$dmd_has_mobile = false;
foreach ( $dmd_slides as $dmd_slide ) {
	if ( ! empty( $dmd_slide['image_mobile'] ) ) {
		$dmd_has_mobile = true;
		break;
	}
}

$dmd_frame_class = $dmd_has_mobile ? 'aspect-[4/3] md:aspect-[13/5]' : 'aspect-[13/5]';
$dmd_sizes       = $dmd_is_full ? '100vw' : '(min-width: 1280px) 1240px, 100vw';
$dmd_multi       = count( $dmd_slides ) > 1;
?>
<section class="dmd-hero <?php echo esc_attr( $dmd_is_full ? '' : 'pt-4 sm:pt-6' ); ?>" aria-roledescription="carousel" aria-label="<?php esc_attr_e( 'Featured offers', 'digital-mudir-dokan' ); ?>">
	<div class="<?php echo esc_attr( $dmd_is_full ? '' : 'dmd-container' ); ?>">
		<div class="swiper hero-swiper relative overflow-hidden bg-surface <?php echo esc_attr( $dmd_is_full ? '' : 'rounded-2xl' ); ?>"
			data-autoplay="<?php echo esc_attr( get_theme_mod( 'dmd_hero_autoplay', true ) ? 'true' : 'false' ); ?>"
			data-delay="<?php echo esc_attr( absint( get_theme_mod( 'dmd_slider_delay', 4000 ) ) ); ?>"
			data-speed="<?php echo esc_attr( absint( get_theme_mod( 'dmd_slider_speed', 800 ) ) ); ?>">
			<div class="swiper-wrapper">
				<?php
				foreach ( $dmd_slides as $dmd_index => $dmd_slide ) :
					$dmd_alt      = $dmd_slide['title'] ? $dmd_slide['title'] : get_bloginfo( 'name' );
					$dmd_has_text = $dmd_slide['title'] || $dmd_slide['subtitle'] || ( $dmd_slide['cta_text'] && $dmd_slide['cta_url'] );
					$dmd_whole    = ! $dmd_slide['cta_text'] && $dmd_slide['cta_url'];
					$dmd_tag      = $dmd_whole ? 'a' : 'div';
					?>
					<<?php echo esc_html( $dmd_tag ); ?>
						class="swiper-slide relative block w-full overflow-hidden <?php echo esc_attr( $dmd_frame_class ); ?>"
						<?php if ( $dmd_whole ) : ?>
							href="<?php echo esc_url( $dmd_slide['cta_url'] ); ?>"
						<?php endif; ?>
						<?php if ( $dmd_multi ) : ?>
							role="group" aria-roledescription="slide"
							aria-label="<?php echo esc_attr( sprintf( /* translators: 1: slide number, 2: total. */ __( '%1$d of %2$d', 'digital-mudir-dokan' ), $dmd_index + 1, count( $dmd_slides ) ) ); ?>"
						<?php endif; ?>>

						<?php if ( $dmd_slide['image'] ) : ?>
							<picture>
								<?php if ( $dmd_slide['image_mobile'] ) : ?>
									<source media="(max-width: 767px)" srcset="<?php echo esc_attr( $dmd_slide['image_mobile']['srcset'] ? $dmd_slide['image_mobile']['srcset'] : $dmd_slide['image_mobile']['url'] ); ?>" sizes="100vw" />
								<?php endif; ?>
								<img src="<?php echo esc_url( $dmd_slide['image']['url'] ); ?>"
									<?php if ( $dmd_slide['image']['srcset'] ) : ?>
										srcset="<?php echo esc_attr( $dmd_slide['image']['srcset'] ); ?>"
										sizes="<?php echo esc_attr( $dmd_sizes ); ?>"
									<?php endif; ?>
									<?php if ( $dmd_slide['image']['width'] ) : ?>
										width="<?php echo esc_attr( $dmd_slide['image']['width'] ); ?>"
										height="<?php echo esc_attr( $dmd_slide['image']['height'] ); ?>"
									<?php endif; ?>
									alt="<?php echo esc_attr( $dmd_alt ); ?>"
									<?php echo 0 === $dmd_index ? 'fetchpriority="high"' : 'loading="lazy"'; ?>
									decoding="async"
									class="absolute inset-0 h-full w-full select-none object-cover object-center" />
							</picture>
						<?php else : ?>
							<span class="absolute inset-0 bg-gradient-to-br from-green to-green-dark" aria-hidden="true"></span>
						<?php endif; ?>

						<?php if ( $dmd_has_text ) : ?>
							<?php if ( $dmd_slide['image'] ) : ?>
								<span class="absolute inset-0 bg-gradient-to-r from-black/55 via-black/25 to-transparent" aria-hidden="true"></span>
							<?php endif; ?>
							<div class="absolute inset-0 z-10 flex items-center">
								<div class="<?php echo esc_attr( $dmd_is_full ? 'dmd-container' : 'w-full px-6 sm:px-10 lg:px-16' ); ?>">
									<div class="max-w-xl space-y-2 sm:space-y-4">
										<?php if ( $dmd_slide['title'] ) : ?>
											<h2 class="m-0 font-display text-xl font-bold leading-tight drop-shadow-md sm:text-3xl lg:text-5xl" style="color: <?php echo esc_attr( $dmd_hero_title_color ); ?>;">
												<?php echo esc_html( $dmd_slide['title'] ); ?>
											</h2>
										<?php endif; ?>
										<?php if ( $dmd_slide['subtitle'] ) : ?>
											<p class="m-0 hidden text-base opacity-90 drop-shadow-sm sm:block md:text-lg" style="color: <?php echo esc_attr( $dmd_hero_subtitle_color ); ?>;">
												<?php echo esc_html( $dmd_slide['subtitle'] ); ?>
											</p>
										<?php endif; ?>
										<?php if ( $dmd_slide['cta_text'] && $dmd_slide['cta_url'] ) : ?>
											<a href="<?php echo esc_url( $dmd_slide['cta_url'] ); ?>"
												class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-xs font-semibold shadow-lg transition hover:opacity-90 sm:px-7 sm:py-3 sm:text-sm"
												style="background-color: <?php echo esc_attr( $dmd_hero_btn_bg ); ?>; color: <?php echo esc_attr( $dmd_hero_btn_text ); ?>;">
												<?php echo esc_html( $dmd_slide['cta_text'] ); ?>
												<?php echo dmd_icon( 'chevron-r', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
											</a>
										<?php endif; ?>
									</div>
								</div>
							</div>
						<?php elseif ( $dmd_whole ) : ?>
							<span class="screen-reader-text"><?php echo esc_html( $dmd_alt ); ?></span>
						<?php endif; ?>
					</<?php echo esc_html( $dmd_tag ); ?>>
				<?php endforeach; ?>
			</div>

			<?php if ( $dmd_multi ) : ?>
				<button type="button" class="dmd-hero-nav swiper-button-prev-custom left-4 lg:left-6" style="background-color: <?php echo esc_attr( $dmd_hero_nav_bg ); ?>;" aria-label="<?php esc_attr_e( 'Previous slide', 'digital-mudir-dokan' ); ?>">
					<?php echo dmd_icon( 'chevron-l', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
				</button>
				<button type="button" class="dmd-hero-nav swiper-button-next-custom right-4 lg:right-6" style="background-color: <?php echo esc_attr( $dmd_hero_nav_bg ); ?>;" aria-label="<?php esc_attr_e( 'Next slide', 'digital-mudir-dokan' ); ?>">
					<?php echo dmd_icon( 'chevron-r', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
				</button>
				<div class="swiper-pagination-custom absolute inset-x-0 bottom-3 z-20 flex items-center justify-center gap-1.5 sm:bottom-5"></div>
			<?php endif; ?>
		</div>
	</div>
</section>
