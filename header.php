<?php
/**
 * Page shell — opens the document and renders the site header.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

$dmd_contact    = dmd_contact();
$dmd_logo_width = absint( get_theme_mod( 'dmd_logo_width', 180 ) );
$dmd_has_wc     = function_exists( 'WC' );
$dmd_categories = $dmd_has_wc ? dmd_get_top_categories() : array();
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
			<div class="dmd-container flex items-center justify-center gap-x-6 py-2 text-[13px] leading-snug md:justify-between">
				<p class="m-0 text-center">
					<span class="dmd-topbar-text"><?php echo wp_kses_post( get_theme_mod( 'dmd_topbar_text', __( 'আমাদের যে কোন পণ্য অর্ডার করতে WhatsApp করুন', 'digital-mudir-dokan' ) ) ); ?></span>
					<?php if ( $dmd_contact['whatsapp'] ) : ?>
						<a class="font-semibold text-white underline-offset-2 hover:text-white hover:underline"
							href="https://wa.me/<?php echo esc_attr( ltrim( dmd_tel( $dmd_contact['whatsapp'] ), '+' ) ); ?>"
							rel="noopener"
							target="_blank"><?php echo esc_html( $dmd_contact['whatsapp'] ); ?></a>
					<?php endif; ?>
				</p>

				<?php if ( $dmd_contact['hotline'] ) : ?>
					<p class="m-0 hidden items-center gap-2 md:flex">
						<?php dmd_the_icon( 'phone', 14 ); ?>
						<?php esc_html_e( 'হটলাইন:', 'digital-mudir-dokan' ); ?>
						<a class="font-semibold text-white hover:text-white hover:underline" href="tel:<?php echo esc_attr( dmd_tel( $dmd_contact['hotline'] ) ); ?>"><?php echo esc_html( $dmd_contact['hotline'] ); ?></a>
					</p>
				<?php endif; ?>
			</div>
		</aside>
	<?php endif; ?>

	<header id="dmd-header" class="dmd-header border-b border-line" role="banner">
		<div class="dmd-container">
			<div class="relative flex flex-wrap items-center gap-x-3 gap-y-3 py-3 sm:gap-x-5 lg:flex-nowrap lg:py-4">

				<button type="button"
					class="dmd-menu-toggle order-1 -ml-2 inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-ink hover:bg-surface lg:hidden"
					aria-expanded="false"
					aria-controls="dmd-mobile-nav">
					<?php dmd_the_icon( 'menu', 22 ); ?>
					<span class="screen-reader-text"><?php esc_html_e( 'Open menu', 'digital-mudir-dokan' ); ?></span>
				</button>

				<div class="dmd-branding order-2 flex min-w-0 flex-1 items-center lg:flex-none">
					<style>.dmd-branding img.custom-logo{width:<?php echo esc_attr( $dmd_logo_width ); ?>px;max-width:min(<?php echo esc_attr( $dmd_logo_width ); ?>px,46vw);}</style>
					<?php if ( has_custom_logo() ) : ?>
						<?php the_custom_logo(); ?>
					<?php else : ?>
						<a class="font-display text-xl font-bold text-green hover:text-green" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
					<?php endif; ?>
				</div>

				<div class="dmd-header-search order-4 w-full lg:order-3 lg:mx-auto lg:max-w-2xl lg:flex-1">
					<?php get_search_form(); ?>
				</div>

				<div id="dmd-live-search-results" class="dmd-ls-results absolute inset-x-0 top-full z-50 mt-2 hidden max-h-[min(78vh,720px)] overflow-hidden rounded-2xl border border-line bg-white shadow-lift" role="region" aria-live="polite" aria-hidden="true" aria-label="<?php esc_attr_e( 'Search suggestions', 'digital-mudir-dokan' ); ?>"></div>

				<div class="order-3 ml-auto flex shrink-0 items-center gap-1 sm:gap-2 lg:order-4 lg:ml-0">
					<?php if ( function_exists( 'wc_get_page_permalink' ) ) : ?>
						<a class="group inline-flex items-center gap-2.5 rounded-lg px-2 py-1.5 text-ink hover:bg-surface hover:text-ink" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">
							<span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-surface text-ink transition-colors group-hover:bg-green-soft group-hover:text-green">
								<?php dmd_the_icon( 'user', 20 ); ?>
							</span>
							<span class="hidden flex-col leading-tight xl:flex">
								<?php if ( is_user_logged_in() ) : ?>
									<span class="text-[11px] text-muted"><?php esc_html_e( 'Welcome back', 'digital-mudir-dokan' ); ?></span>
									<span class="text-sm font-semibold"><?php esc_html_e( 'My account', 'digital-mudir-dokan' ); ?></span>
								<?php else : ?>
									<span class="text-[11px] text-muted"><?php esc_html_e( 'Sign in', 'digital-mudir-dokan' ); ?></span>
									<span class="text-sm font-semibold"><?php esc_html_e( 'Login / Register', 'digital-mudir-dokan' ); ?></span>
								<?php endif; ?>
							</span>
							<span class="screen-reader-text xl:hidden"><?php esc_html_e( 'My account', 'digital-mudir-dokan' ); ?></span>
						</a>
					<?php endif; ?>

					<?php if ( defined( 'YITH_WCWL' ) || shortcode_exists( 'yith_wcwl_wishlist' ) ) : ?>
						<a class="hidden h-10 w-10 items-center justify-center rounded-full bg-surface text-ink hover:bg-green-soft hover:text-green sm:inline-flex" href="<?php echo esc_url( home_url( '/wishlist/' ) ); ?>">
							<?php dmd_the_icon( 'heart', 20 ); ?>
							<span class="screen-reader-text"><?php esc_html_e( 'Wishlist', 'digital-mudir-dokan' ); ?></span>
						</a>
					<?php endif; ?>

					<?php
					if ( $dmd_has_wc ) {
						dmd_cart_link();
					}
					?>
				</div>
			</div>
		</div>

		<nav id="dmd-primary-nav" class="dmd-nav hidden border-t border-line bg-white lg:block" aria-label="<?php esc_attr_e( 'Primary', 'digital-mudir-dokan' ); ?>">
			<div class="dmd-container">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'dmd-menu dmd-primary-menu m-0 flex list-none flex-wrap items-center justify-center p-0',
						'depth'          => 1,
						'walker'         => new DMD_Nav_Walker(),
						'fallback_cb'    => 'dmd_primary_menu_fallback',
					)
				);
				?>
			</div>
		</nav>
	</header>

	<?php // Outside the sticky header so its stacking context cannot trap the drawer below the backdrop. ?>
	<nav id="dmd-mobile-nav" class="dmd-mobile-nav fixed inset-y-0 left-0 z-[60] w-[86vw] max-w-sm -translate-x-full transform bg-white shadow-2xl transition-transform duration-300 ease-in-out lg:hidden" data-closed-class="-translate-x-full" aria-label="<?php esc_attr_e( 'Mobile', 'digital-mudir-dokan' ); ?>">
			<div class="flex h-full flex-col">
				<div class="flex items-center justify-between border-b border-line px-4 py-3">
					<span class="font-display text-base font-bold text-green"><?php bloginfo( 'name' ); ?></span>
					<button type="button" class="dmd-menu-toggle inline-flex h-10 w-10 items-center justify-center rounded-full bg-surface" aria-expanded="false" aria-controls="dmd-mobile-nav">
						<?php dmd_the_icon( 'close', 20 ); ?>
						<span class="screen-reader-text"><?php esc_html_e( 'Close menu', 'digital-mudir-dokan' ); ?></span>
					</button>
				</div>

				<div class="flex-1 overflow-y-auto px-4 py-4">
					<?php if ( $dmd_categories ) : ?>
						<p class="mb-2 text-xs font-semibold uppercase tracking-wider text-muted"><?php esc_html_e( 'Categories', 'digital-mudir-dokan' ); ?></p>
						<ul class="m-0 mb-5 list-none divide-y divide-line p-0">
							<?php foreach ( $dmd_categories as $dmd_cat ) : ?>
								<li>
									<a class="flex items-center justify-between py-2.5 text-[15px]" href="<?php echo esc_url( get_term_link( $dmd_cat ) ); ?>">
										<?php echo esc_html( $dmd_cat->name ); ?>
										<span class="text-muted"><?php dmd_the_icon( 'chevron-r', 14 ); ?></span>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<?php if ( has_nav_menu( 'mobile' ) || ! $dmd_categories ) : ?>
						<p class="mb-2 text-xs font-semibold uppercase tracking-wider text-muted"><?php esc_html_e( 'Menu', 'digital-mudir-dokan' ); ?></p>
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
					<?php endif; ?>
				</div>

				<?php if ( $dmd_contact['hotline'] ) : ?>
					<div class="border-t border-line p-4">
						<a class="dmd-btn dmd-btn--primary dmd-btn--block" href="tel:<?php echo esc_attr( dmd_tel( $dmd_contact['hotline'] ) ); ?>">
							<?php dmd_the_icon( 'phone', 16 ); ?>
							<?php echo esc_html( $dmd_contact['hotline'] ); ?>
						</a>
					</div>
				<?php endif; ?>
			</div>
		</nav>

	<main id="dmd-main" class="dmd-main flex-1" role="main">
