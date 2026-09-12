<?php
/**
 * Latest posts strip on the front page.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

$dmd_posts = new WP_Query(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => 3,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);

if ( ! $dmd_posts->have_posts() ) {
	wp_reset_postdata();
	return;
}
?>
<section class="dmd-blog-strip bg-surface py-12" aria-labelledby="dmd-blog-title">
	<div class="dmd-container">
		<h2 id="dmd-blog-title" class="dmd-section-title mb-8">
			<?php echo esc_html( get_theme_mod( 'dmd_home_blog_title', __( 'From our blog', 'digital-mudir-dokan' ) ) ); ?>
		</h2>

		<ul class="m-0 grid list-none grid-cols-1 gap-5 p-0 md:grid-cols-3">
			<?php
			while ( $dmd_posts->have_posts() ) :
				$dmd_posts->the_post();
				?>
				<li class="list-none">
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'flex h-full flex-col overflow-hidden rounded-lg border border-line bg-white' ); ?>>

						<?php if ( has_post_thumbnail() ) : ?>
							<a class="relative block" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
								<?php
								the_post_thumbnail(
									'dmd-blog-card',
									array(
										'class'    => 'h-48 w-full object-cover',
										'loading'  => 'lazy',
										'decoding' => 'async',
										'alt'      => '',
									)
								);
								?>
								<span class="absolute left-4 top-4 flex h-12 w-12 flex-col items-center justify-center rounded-md bg-white text-center leading-none shadow-card">
									<span class="text-base font-bold"><?php echo esc_html( get_the_date( 'd' ) ); ?></span>
									<span class="text-[10px] uppercase text-muted"><?php echo esc_html( get_the_date( 'M' ) ); ?></span>
								</span>
							</a>
						<?php endif; ?>

						<div class="flex flex-1 flex-col p-5 text-center">
							<h3 class="dmd-post-title text-lg leading-snug">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h3>

							<p class="mt-2 text-sm text-muted"><?php echo esc_html( get_the_excerpt() ); ?></p>

							<p class="mt-auto pt-4 m-0">
								<a class="text-sm font-semibold text-green" href="<?php the_permalink(); ?>">
									<?php esc_html_e( 'Continue reading', 'digital-mudir-dokan' ); ?>
									<span class="screen-reader-text"><?php the_title(); ?></span>
								</a>
							</p>
						</div>
					</article>
				</li>
				<?php
			endwhile;
			wp_reset_postdata();
			?>
		</ul>
	</div>
</section>
