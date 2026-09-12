<?php
/**
 * Page shell — opens the document and renders the site header.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

$dmd_contact = dmd_contact();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#dmd-main"><?php esc_html_e( 'Skip to content', 'digital-mudir-dokan' ); ?></a>

<div id="page" class="dmd-site flex min-h-screen flex-col">

	<?php if ( get_theme_mod( 'dmd_topbar_show', true ) ) : ?>
		<aside class="dmd-topbar bg-green text-white" aria-label="<?php esc_attr_e( 'Store announcement', 'digital-mudir-dokan' ); ?>">
			<div class="dmd-container py-2 text-center text-[13px] leading-snug">
				<span><?php echo wp_kses_post( get_theme_mod( 'dmd_topbar_text', __( 'আমাদের যে কোন পণ্য অর্ডার করতে WhatsApp করুন', 'digital-mudir-dokan' ) ) ); ?></span>

				<?php if ( $dmd_contact['whatsapp'] ) : ?>
					<a class="font-semibold text-white underline-offset-2 hover:underline"
						href="https://wa.me/<?php echo esc_attr( ltrim( dmd_tel( $dmd_contact['whatsapp'] ), '+' ) ); ?>"
						rel="noopener"
						target="_blank">
						<?php echo esc_html( $dmd_contact['whatsapp'] ); ?>
					</a>
				<?php endif; ?>

				<?php if ( $dmd_contact['hotline'] ) : ?>
					<span class="mx-1 opacity-60" aria-hidden="true">|</span>
					<?php esc_html_e( 'বা কল করুন:', 'digital-mudir-dokan' ); ?>
					<a class="font-semibold text-white underline-offset-2 hover:underline" href="tel:<?php echo esc_attr( dmd_tel( $dmd_contact['hotline'] ) ); ?>">
						<?php echo esc_html( $dmd_contact['hotline'] ); ?>
					</a>
				<?php endif; ?>
			</div>
		</aside>
	<?php endif; ?>

	<header id="dmd-header" class="dmd-header border-b border-line bg-white" role="banner">

		<div class="dmd-container">
			<div class="grid grid-cols-[auto_1fr_auto] items-center gap-4 py-4">

				<!-- Left: search + mobile menu -->
				<div class="flex items-center gap-1">
					<button type="button"
						class="dmd-menu-toggle inline-flex h-10 w-10 items-center justify-center rounded-md lg:hidden"
						aria-expanded="false"
						aria-controls="dmd-mobile-nav">
						<?php dmd_the_icon( 'menu', 22 ); ?>
						<span class="screen-reader-text"><?php esc_html_e( 'Open menu', 'digital-mudir-dokan' ); ?></span>
					</button>

					<button type="button"
						class="dmd-search-toggle inline-flex h-10 w-10 items-center justify-center rounded-md"
						aria-expanded="false"
						aria-controls="dmd-search-panel">
						<?php dmd_the_icon( 'search', 20 ); ?>
						<span class="screen-reader-text"><?php esc_html_e( 'Search products', 'digital-mudir-dokan' ); ?></span>
					</button>
				</div>

				<!-- Centre: logo -->
				<div class="dmd-branding text-center">
					<?php if ( has_custom_logo() ) : ?>
						<?php the_custom_logo(); ?>
					<?php else : ?>
						<?php if ( is_front_page() ) : ?>
							<p class="m-0 font-display text-xl font-bold text-green"><?php bloginfo( 'name' ); ?></p>
						<?php else : ?>
							<p class="m-0 font-display text-xl font-bold">
								<a class="text-green" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
							</p>
						<?php endif; ?>
					<?php endif; ?>
				</div>

				<!-- Right: account, wishlist, cart -->
				<div class="flex items-center justify-end gap-3 sm:gap-4">
					<?php if ( function_exists( 'wc_get_page_permalink' ) ) : ?>
						<a class="hidden text-sm hover:text-green sm:inline" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">
							<?php
							if ( is_user_logged_in() ) {
								esc_html_e( 'My account', 'digital-mudir-dokan' );
							} else {
								esc_html_e( 'Login / Register', 'digital-mudir-dokan' );
							}
							?>
						</a>
						<a class="inline-flex sm:hidden" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">
							<?php dmd_the_icon( 'user', 21 ); ?>
							<span class="screen-reader-text"><?php esc_html_e( 'My account', 'digital-mudir-dokan' ); ?></span>
						</a>
					<?php endif; ?>

					<?php if ( defined( 'YITH_WCWL' ) || shortcode_exists( 'yith_wcwl_wishlist' ) ) : ?>
						<a class="hover:text-green" href="<?php echo esc_url( home_url( '/wishlist/' ) ); ?>">
							<?php dmd_the_icon( 'heart', 21 ); ?>
							<span class="screen-reader-text"><?php esc_html_e( 'Wishlist', 'digital-mudir-dokan' ); ?></span>
						</a>
					<?php endif; ?>

					<?php if ( function_exists( 'WC' ) ) : ?>
						<?php dmd_cart_link(); ?>
					<?php endif; ?>
				</div>
			</div>

			<!-- Search panel -->
			<div id="dmd-search-panel" class="dmd-search-panel hidden pb-4" hidden>
				<?php get_search_form(); ?>
			</div>
		</div>

		<!-- Primary navigation -->
		<nav id="dmd-primary-nav" class="dmd-nav hidden border-t border-line lg:block" aria-label="<?php esc_attr_e( 'Primary', 'digital-mudir-dokan' ); ?>">
			<div class="dmd-container">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'dmd-menu m-0 flex flex-wrap items-center justify-center gap-x-7 p-0 list-none',
						'depth'          => 2,
						'walker'         => new DMD_Nav_Walker(),
						'fallback_cb'    => 'dmd_primary_menu_fallback',
					)
				);
				?>
			</div>
		</nav>

		<!-- Mobile navigation -->
		<nav id="dmd-mobile-nav" class="dmd-mobile-nav hidden border-t border-line lg:hidden" aria-label="<?php esc_attr_e( 'Mobile', 'digital-mudir-dokan' ); ?>" hidden>
			<div class="dmd-container py-3">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => has_nav_menu( 'mobile' ) ? 'mobile' : 'primary',
						'container'      => false,
						'menu_class'     => 'dmd-menu--mobile m-0 flex flex-col p-0 list-none divide-y divide-line',
						'depth'          => 2,
						'fallback_cb'    => 'dmd_primary_menu_fallback',
					)
				);
				?>
			</div>
		</nav>
	</header>

	<main id="dmd-main" class="dmd-main flex-1" role="main">
