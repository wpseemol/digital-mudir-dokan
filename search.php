<?php
/**
 * Search results.
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
			printf(
				/* translators: %s: search query. */
				esc_html__( 'Results for “%s”', 'digital-mudir-dokan' ),
				esc_html( get_search_query() )
			);
			?>
		</h1>
		<?php dmd_breadcrumbs(); ?>
	</div>
</div>

<div class="dmd-container py-10">
	<div class="mb-8 max-w-md"><?php get_search_form(); ?></div>

	<?php if ( have_posts() ) : ?>
		<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/content', 'search' );
			endwhile;
			?>
		</div>
		<?php dmd_pagination(); ?>
	<?php else : ?>
		<?php get_template_part( 'template-parts/content', 'none' ); ?>
	<?php endif; ?>
</div>
<?php
get_footer();
