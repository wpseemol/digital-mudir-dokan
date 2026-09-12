<?php
/**
 * Search form.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

$dmd_id = 'dmd-search-' . wp_unique_id();
?>
<form role="search" method="get" class="dmd-search-form flex items-center gap-2" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $dmd_id ); ?>">
		<?php esc_html_e( 'Search for:', 'digital-mudir-dokan' ); ?>
	</label>

	<input type="search"
		id="<?php echo esc_attr( $dmd_id ); ?>"
		class="dmd-field"
		placeholder="<?php esc_attr_e( 'Search products…', 'digital-mudir-dokan' ); ?>"
		value="<?php echo esc_attr( get_search_query() ); ?>"
		name="s" />

	<?php if ( class_exists( 'WooCommerce' ) ) : ?>
		<input type="hidden" name="post_type" value="product" />
	<?php endif; ?>

	<button type="submit" class="dmd-btn dmd-btn--primary">
		<?php dmd_the_icon( 'search', 18 ); ?>
		<span class="screen-reader-text"><?php esc_html_e( 'Search', 'digital-mudir-dokan' ); ?></span>
	</button>
</form>
