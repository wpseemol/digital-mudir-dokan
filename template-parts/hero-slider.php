<?php
/**
 * Homepage hero slider.
 *
 * Built as a labelled carousel: without JavaScript the track stays a horizontal
 * snap scroller, so every slide remains reachable.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

$dmd_slides = dmd_get_hero_slides();

if ( ! $dmd_slides ) {
	return;
}

$dmd_autoplay = get_theme_mod( 'dmd_hero_autoplay', true ) ? 'true' : 'false';
$dmd_speed    = max( 3, (int) get_theme_mod( 'dmd_hero_speed', 6 ) ) * 1000;
$dmd_count    = count( $dmd_slides );
?>
<section class="dmd-hero"
	aria-roledescription="carousel"
	aria-label="<?php esc_attr_e( 'Featured offers', 'digital-mudir-dokan' ); ?>">

	<div class="dmd-container pt-5">
		<div class="dmd-slider overflow-hidden rounded-lg"
			data-dmd-slider
			data-autoplay="<?php echo esc_attr( $dmd_autoplay ); ?>"
			data-interval="<?php echo esc_attr( $dmd_speed ); ?>">

			<div class="dmd-slider__track" data-dmd-track>
				<?php foreach ( $dmd_slides as $dmd_index => $dmd_slide ) : ?>
					<article class="dmd-slide relative"
						role="group"
						aria-roledescription="slide"
						aria-label="
						<?php
						/* translators: 1: current slide, 2: total slides. */
						echo esc_attr( sprintf( __( 'Slide %1$d of %2$d', 'digital-mudir-dokan' ), $dmd_index + 1, $dmd_count ) );
						?>
						">

						<?php if ( $dmd_slide['image'] ) : ?>
							<img class="h-[220px] w-full object-cover sm:h-[320px] lg:h-[420px]"
								src="<?php echo esc_url( $dmd_slide['image'] ); ?>"
								alt="<?php echo esc_attr( $dmd_slide['title'] ? $dmd_slide['title'] : get_bloginfo( 'name' ) ); ?>"
								<?php echo 0 === $dmd_index ? 'fetchpriority="high"' : 'loading="lazy"'; ?>
								decoding="async" />
						<?php endif; ?>

						<?php if ( $dmd_slide['title'] || $dmd_slide['subtitle'] || $dmd_slide['cta_text'] ) : ?>
							<div class="<?php echo $dmd_slide['image'] ? 'absolute inset-0 flex items-center bg-gradient-to-r from-black/55 via-black/20 to-transparent' : 'flex items-center bg-green-soft'; ?>">
								<div class="w-full px-6 py-10 sm:px-12 lg:py-20">
									<div class="max-w-[34rem]">
										<?php if ( $dmd_slide['title'] ) : ?>
											<h2 class="text-2xl font-bold leading-tight sm:text-3xl lg:text-[42px] <?php echo $dmd_slide['image'] ? 'text-white' : 'text-ink'; ?>">
												<?php echo esc_html( $dmd_slide['title'] ); ?>
											</h2>
										<?php endif; ?>

										<?php if ( $dmd_slide['subtitle'] ) : ?>
											<p class="mt-3 text-sm sm:text-base <?php echo $dmd_slide['image'] ? 'text-white/90' : 'text-muted'; ?>">
												<?php echo esc_html( $dmd_slide['subtitle'] ); ?>
											</p>
										<?php endif; ?>

										<?php if ( $dmd_slide['cta_text'] && $dmd_slide['cta_url'] ) : ?>
											<p class="mt-6 m-0">
												<a class="dmd-btn dmd-btn--primary px-7 py-3" href="<?php echo esc_url( $dmd_slide['cta_url'] ); ?>">
													<?php echo esc_html( $dmd_slide['cta_text'] ); ?>
												</a>
											</p>
										<?php endif; ?>
									</div>
								</div>
							</div>
						<?php endif; ?>
					</article>
				<?php endforeach; ?>
			</div>

			<?php if ( $dmd_count > 1 ) : ?>
				<button type="button" class="dmd-slider__nav left-3" data-dmd-prev>
					<?php dmd_the_icon( 'chevron-l', 18 ); ?>
					<span class="screen-reader-text"><?php esc_html_e( 'Previous slide', 'digital-mudir-dokan' ); ?></span>
				</button>

				<button type="button" class="dmd-slider__nav right-3" data-dmd-next>
					<?php dmd_the_icon( 'chevron-r', 18 ); ?>
					<span class="screen-reader-text"><?php esc_html_e( 'Next slide', 'digital-mudir-dokan' ); ?></span>
				</button>

				<div class="dmd-slider__dots" role="tablist" aria-label="<?php esc_attr_e( 'Choose a slide', 'digital-mudir-dokan' ); ?>">
					<?php foreach ( $dmd_slides as $dmd_index => $dmd_slide ) : ?>
						<button type="button"
							class="dmd-slider__dot"
							role="tab"
							data-dmd-dot="<?php echo esc_attr( $dmd_index ); ?>"
							aria-current="<?php echo 0 === $dmd_index ? 'true' : 'false'; ?>">
							<span class="screen-reader-text">
								<?php
								/* translators: %d: slide number. */
								printf( esc_html__( 'Go to slide %d', 'digital-mudir-dokan' ), (int) $dmd_index + 1 );
								?>
							</span>
						</button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
