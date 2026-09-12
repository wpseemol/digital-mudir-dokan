<?php
/**
 * Fallback template — also the blog index.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="bg-surface py-6">
	<div class="dmd-container flex flex-wrap items-center justify-between gap-3">
		<h1 class="m-0 text-xl font-bold sm:text-2xl">
			<?php
			if ( is_home() && ! is_front_page() ) {
				echo esc_html( get_the_title( get_option( 'page_for_posts' ) ) );
			} else {
				esc_html_e( 'Blog', 'digital-mudir-dokan' );
			}
			?>
		</h1>
		<?php dmd_breadcrumbs(); ?>
	</div>
</div>

<div class="dmd-container py-10">
	<div class="grid gap-8 <?php echo is_active_sidebar( 'sidebar-1' ) ? 'lg:grid-cols-[minmax(0,1fr)_300px]' : ''; ?>">
		<div>
			<?php if ( have_posts() ) : ?>
				<div class="grid gap-6 sm:grid-cols-2">
					<?php
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/content', get_post_type() );
					endwhile;
					?>
				</div>

				<?php dmd_pagination(); ?>
			<?php else : ?>
				<?php get_template_part( 'template-parts/content', 'none' ); ?>
			<?php endif; ?>
		</div>

		<?php get_sidebar(); ?>
	</div>
</div>
<?php
get_footer();
