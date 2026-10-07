<?php
/**
 * Template Name: About
 *
 * Introduction, key numbers, mission and vision, the page's own content as
 * "our story", plus the service highlights and order band. Text comes from
 * Mudir Dokan → About page.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$dmd_image_id = absint( dmd_option( 'about_image' ) );
	$dmd_image_id = $dmd_image_id ? $dmd_image_id : get_post_thumbnail_id();
	$dmd_title    = dmd_option( 'about_title' );
	$dmd_title    = $dmd_title ? $dmd_title : get_the_title();

	$dmd_stats = array();
	for ( $dmd_i = 1; $dmd_i <= 4; $dmd_i++ ) {
		$dmd_value = dmd_option( 'about_stat_' . $dmd_i . '_value' );
		if ( '' !== trim( (string) $dmd_value ) ) {
			$dmd_stats[] = array( $dmd_value, dmd_option( 'about_stat_' . $dmd_i . '_label' ) );
		}
	}

	$dmd_pillars = array_filter(
		array(
			array( 'leaf', __( 'Our mission', 'digital-mudir-dokan' ), dmd_option( 'about_mission' ) ),
			array( 'shield', __( 'Our vision', 'digital-mudir-dokan' ), dmd_option( 'about_vision' ) ),
		),
		static function ( $pillar ) {
			return '' !== trim( (string) $pillar[2] );
		}
	);
	?>
	<section class="dmd-archive-header border-b border-line bg-gradient-to-b from-green-soft/70 to-white">
		<div class="dmd-container py-6 sm:py-8">
			<?php dmd_breadcrumbs(); ?>
		</div>

		<div class="dmd-container grid items-center gap-8 pb-10 sm:pb-14 lg:grid-cols-2 lg:gap-14">
			<div>
				<?php if ( dmd_option( 'about_eyebrow' ) ) : ?>
					<p class="dmd-eyebrow m-0"><?php echo esc_html( dmd_option( 'about_eyebrow' ) ); ?></p>
				<?php endif; ?>
				<h1 class="dmd-heading !text-3xl sm:!text-4xl lg:!text-[42px] lg:!leading-[1.15]"><?php echo esc_html( $dmd_title ); ?></h1>
				<?php if ( dmd_option( 'about_intro' ) ) : ?>
					<div class="mt-5 space-y-3 text-base leading-relaxed text-muted sm:text-[17px]">
						<?php echo wp_kses_post( wpautop( esc_html( dmd_option( 'about_intro' ) ) ) ); ?>
					</div>
				<?php endif; ?>
				<div class="mt-7 flex flex-wrap gap-3">
					<?php if ( function_exists( 'wc_get_page_permalink' ) ) : ?>
						<a class="dmd-btn dmd-btn--primary" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'Start shopping', 'digital-mudir-dokan' ); ?></a>
					<?php endif; ?>
					<?php if ( dmd_page_id( 'contact' ) ) : ?>
						<a class="dmd-btn dmd-btn--outline" href="<?php echo esc_url( get_permalink( dmd_page_id( 'contact' ) ) ); ?>"><?php esc_html_e( 'Contact us', 'digital-mudir-dokan' ); ?></a>
					<?php endif; ?>
				</div>
			</div>

			<figure class="relative m-0">
				<div class="pointer-events-none absolute -left-4 -top-4 h-24 w-24 rounded-2xl bg-green-soft" aria-hidden="true"></div>
				<div class="pointer-events-none absolute -bottom-4 -right-4 h-32 w-32 rounded-full bg-green/10" aria-hidden="true"></div>
				<div class="relative overflow-hidden rounded-2xl bg-green-soft shadow-card">
					<?php if ( $dmd_image_id ) : ?>
						<?php echo wp_get_attachment_image( $dmd_image_id, 'large', false, array( 'class' => 'aspect-[4/3] h-auto w-full object-cover' ) ); ?>
					<?php else : ?>
						<div class="flex aspect-[4/3] items-center justify-center text-green"><?php dmd_the_icon( 'grocery', 72 ); ?></div>
					<?php endif; ?>
				</div>
			</figure>
		</div>
	</section>

	<?php if ( $dmd_stats ) : ?>
		<section class="dmd-container -mt-8 relative z-10" aria-label="<?php esc_attr_e( 'Key numbers', 'digital-mudir-dokan' ); ?>">
			<dl class="m-0 grid grid-cols-2 gap-px overflow-hidden rounded-2xl border border-line bg-line shadow-soft lg:grid-cols-4">
				<?php foreach ( $dmd_stats as $dmd_stat ) : ?>
					<div class="flex flex-col-reverse items-center bg-white px-4 py-6 text-center sm:py-8">
						<dt class="mt-1 text-sm text-muted"><?php echo esc_html( $dmd_stat[1] ); ?></dt>
						<dd class="m-0 font-display text-2xl font-bold text-green sm:text-3xl"><?php echo esc_html( $dmd_stat[0] ); ?></dd>
					</div>
				<?php endforeach; ?>
			</dl>
		</section>
	<?php endif; ?>

	<?php if ( $dmd_pillars ) : ?>
		<section class="dmd-section">
			<div class="dmd-container grid gap-5 md:grid-cols-2">
				<?php foreach ( $dmd_pillars as $dmd_pillar ) : ?>
					<article class="rounded-2xl border border-line bg-white p-6 transition hover:border-green/25 hover:shadow-lift sm:p-8">
						<span class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-soft text-green"><?php dmd_the_icon( $dmd_pillar[0], 24 ); ?></span>
						<h2 class="m-0 mt-5 font-display text-xl font-bold text-ink"><?php echo esc_html( $dmd_pillar[1] ); ?></h2>
						<p class="m-0 mt-2 leading-relaxed text-muted"><?php echo esc_html( $dmd_pillar[2] ); ?></p>
					</article>
				<?php endforeach; ?>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( '' !== trim( get_the_content() ) ) : ?>
		<section class="dmd-section bg-surface" aria-labelledby="dmd-about-story">
			<div class="dmd-container max-w-3xl">
				<h2 id="dmd-about-story" class="dmd-heading mb-5"><?php esc_html_e( 'Our story', 'digital-mudir-dokan' ); ?></h2>
				<div class="dmd-prose"><?php the_content(); ?></div>
			</div>
		</section>
	<?php endif; ?>

	<?php
	if ( dmd_option( 'about_show_trust' ) ) {
		echo '<div class="pb-6 pt-6 sm:pt-10">';
		get_template_part( 'template-parts/trust-row' );
		echo '</div>';
	}

	if ( dmd_option( 'about_show_cta' ) ) {
		echo '<div class="pb-10 sm:pb-14">';
		get_template_part( 'template-parts/cta-band' );
		echo '</div>';
	}
endwhile;

get_footer();
