<?php
/**
 * Empty state for loops with no results.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="dmd-no-results mx-auto max-w-md rounded-lg border border-line bg-white px-6 py-12 text-center">
	<h2 class="m-0 text-lg font-semibold"><?php esc_html_e( 'Nothing found here', 'digital-mudir-dokan' ); ?></h2>

	<?php if ( is_search() ) : ?>
		<p class="mt-2 text-sm text-muted"><?php esc_html_e( 'Try a different word, or a shorter one.', 'digital-mudir-dokan' ); ?></p>
		<div class="mx-auto mt-5 max-w-sm"><?php get_search_form(); ?></div>
	<?php else : ?>
		<p class="mt-2 text-sm text-muted"><?php esc_html_e( 'Check back soon, or browse the shop in the meantime.', 'digital-mudir-dokan' ); ?></p>
		<?php if ( function_exists( 'wc_get_page_permalink' ) ) : ?>
			<p class="mt-5 m-0">
				<a class="dmd-btn dmd-btn--primary px-7" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
					<?php esc_html_e( 'Browse products', 'digital-mudir-dokan' ); ?>
				</a>
			</p>
		<?php endif; ?>
	<?php endif; ?>
</section>
