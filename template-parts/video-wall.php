<?php
/**
 * Customer testimonial video wall.
 *
 * Thumbnails load first and the iframe is only injected when someone presses
 * play, so YouTube never slows down the first paint of the homepage.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

$dmd_raw = trim( (string) get_theme_mod( 'dmd_home_videos', '' ) );

if ( ! $dmd_raw ) {
	return;
}

$dmd_urls = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', $dmd_raw ) ) );
$dmd_ids  = array();

foreach ( $dmd_urls as $dmd_url ) {
	if ( preg_match( '%(?:youtube\.com/(?:watch\?v=|embed/|shorts/)|youtu\.be/)([A-Za-z0-9_-]{11})%', $dmd_url, $dmd_match ) ) {
		$dmd_ids[] = $dmd_match[1];
	}
}

$dmd_ids = array_slice( array_unique( $dmd_ids ), 0, 8 );

if ( ! $dmd_ids ) {
	return;
}

$dmd_title = get_theme_mod( 'dmd_home_video_title', __( 'See what they say about us', 'digital-mudir-dokan' ) );
?>
<section class="dmd-videos bg-white py-12" aria-labelledby="dmd-video-title">
	<div class="dmd-container">
		<h2 id="dmd-video-title" class="dmd-section-title mb-8"><?php echo esc_html( $dmd_title ); ?></h2>

		<ul class="m-0 grid list-none grid-cols-1 gap-4 p-0 sm:grid-cols-2 lg:grid-cols-4">
			<?php foreach ( $dmd_ids as $dmd_index => $dmd_id ) : ?>
				<li class="list-none">
					<div class="dmd-video dmd-ratio-16x9 relative overflow-hidden rounded-lg bg-surface" data-dmd-video="<?php echo esc_attr( $dmd_id ); ?>">
						<img class="h-full w-full object-cover"
							src="https://i.ytimg.com/vi/<?php echo esc_attr( $dmd_id ); ?>/hqdefault.jpg"
							alt=""
							loading="lazy"
							decoding="async" />

						<button type="button" class="dmd-video__play absolute inset-0 flex items-center justify-center bg-black/15 transition-colors hover:bg-black/30">
							<span class="flex h-12 w-[68px] items-center justify-center rounded-xl bg-red-600 text-white">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M8 5.5v13l11-6.5Z"/></svg>
							</span>
							<span class="screen-reader-text">
								<?php
								/* translators: %d: video number. */
								printf( esc_html__( 'Play customer video %d', 'digital-mudir-dokan' ), (int) $dmd_index + 1 );
								?>
							</span>
						</button>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
