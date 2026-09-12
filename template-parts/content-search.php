<?php
/**
 * Post card used in loops.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'dmd-post-card flex h-full flex-col overflow-hidden rounded-lg border border-line bg-white' ); ?>>

	<?php if ( has_post_thumbnail() ) : ?>
		<a class="relative block" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
			<?php
			the_post_thumbnail(
				'dmd-blog-card',
				array(
					'class'    => 'h-52 w-full object-cover',
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

	<div class="flex flex-1 flex-col p-5">
		<h2 class="dmd-post-title m-0 text-lg leading-snug">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h2>

		<p class="mt-2 text-sm text-muted"><?php echo esc_html( get_the_excerpt() ); ?></p>

		<footer class="mt-auto flex items-center justify-between pt-4">
			<?php dmd_posted_on(); ?>
			<a class="text-sm font-semibold text-green" href="<?php the_permalink(); ?>">
				<?php esc_html_e( 'Continue reading', 'digital-mudir-dokan' ); ?>
				<span class="screen-reader-text"><?php the_title(); ?></span>
			</a>
		</footer>
	</div>
</article>
