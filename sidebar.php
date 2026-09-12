<?php
/**
 * Blog sidebar.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>
<aside id="secondary" class="dmd-sidebar" aria-label="<?php esc_attr_e( 'Sidebar', 'digital-mudir-dokan' ); ?>">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside>
