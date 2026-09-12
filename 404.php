<?php
/**
 * Page not found.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="dmd-container py-16">
	<section class="mx-auto max-w-lg text-center">
		<p class="m-0 font-display text-6xl font-bold text-green">404</p>

		<h1 class="mt-4 text-2xl font-bold"><?php esc_html_e( 'That page has moved or never existed', 'digital-mudir-dokan' ); ?></h1>
		<p class="mt-2 text-muted"><?php esc_html_e( 'Search for a product, or head back to the shop.', 'digital-mudir-dokan' ); ?></p>

		<div class="mx-auto mt-6 max-w-sm"><?php get_search_form(); ?></div>

		<p class="mt-6 m-0">
			<a class="dmd-btn dmd-btn--primary px-7" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php esc_html_e( 'Back to home', 'digital-mudir-dokan' ); ?>
			</a>
		</p>
	</section>
</div>
<?php
get_footer();
