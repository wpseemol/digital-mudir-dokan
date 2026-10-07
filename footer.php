<?php
/**
 * Site footer and document close.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

$dmd_contact     = dmd_contact();
$dmd_social      = dmd_social_links();
$dmd_has_widgets = is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' );
$dmd_has_wc      = function_exists( 'wc_get_page_permalink' );
$dmd_footer_cats = $dmd_has_wc ? dmd_get_top_categories( 6 ) : array();
$dmd_policy      = dmd_option( 'footer_policy_links' ) ? dmd_policy_links() : array();

$dmd_quick_links = array();
if ( $dmd_has_wc ) {
	$dmd_quick_links = array(
		__( 'Shop', 'digital-mudir-dokan' )           => wc_get_page_permalink( 'shop' ),
		__( 'All categories', 'digital-mudir-dokan' ) => dmd_get_all_categories_url(),
		__( 'My account', 'digital-mudir-dokan' )     => wc_get_page_permalink( 'myaccount' ),
		__( 'Cart', 'digital-mudir-dokan' )           => wc_get_cart_url(),
		__( 'Checkout', 'digital-mudir-dokan' )       => wc_get_checkout_url(),
	);
}

$dmd_link_class = 'text-sm text-white/75 transition-colors hover:text-white';
?>
	</main><!-- #dmd-main -->

	<footer id="dmd-footer" class="dmd-footer mt-auto bg-green text-white/75" role="contentinfo">
		<div class="dmd-container grid gap-10 py-12 sm:grid-cols-2 lg:grid-cols-[1.4fr_1fr_1fr_1.3fr] lg:gap-12 lg:py-14">

			<div class="dmd-footer-brand">
				<?php if ( has_custom_logo() ) : ?>
					<div class="inline-flex rounded-xl bg-white px-4 py-3 [&_a]:block [&_img]:!h-14 [&_img]:!w-auto [&_img]:!max-w-[180px] [&_img]:object-contain"><?php the_custom_logo(); ?></div>
				<?php else : ?>
					<a class="font-display text-xl font-bold text-white hover:text-white" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
				<?php endif; ?>

				<?php if ( get_bloginfo( 'description' ) ) : ?>
					<p class="dmd-site-description mt-4 max-w-xs text-sm leading-relaxed"><?php bloginfo( 'description' ); ?></p>
				<?php endif; ?>

				<?php if ( $dmd_social ) : ?>
					<ul class="m-0 mt-5 flex list-none items-center gap-2 p-0">
						<?php foreach ( $dmd_social as $dmd_key => $dmd_item ) : ?>
							<li class="list-none">
								<a class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/10 text-white transition-colors hover:bg-white hover:text-green"
									href="<?php echo esc_url( $dmd_item['url'] ); ?>"
									target="_blank"
									rel="noopener noreferrer">
									<?php echo wp_kses( dmd_social_icon( $dmd_key ), dmd_svg_allowed_html() ); ?>
									<span class="screen-reader-text"><?php echo esc_html( $dmd_item['label'] ); ?></span>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>

			<?php if ( $dmd_has_widgets ) : ?>
				<?php for ( $dmd_i = 1; $dmd_i <= 2; $dmd_i++ ) : ?>
					<div class="dmd-footer-widgets">
						<?php dynamic_sidebar( 'footer-' . $dmd_i ); ?>
					</div>
				<?php endfor; ?>
			<?php else : ?>
				<nav aria-labelledby="dmd-footer-cats">
					<h2 id="dmd-footer-cats" class="mb-4 text-base font-semibold text-white"><?php esc_html_e( 'Categories', 'digital-mudir-dokan' ); ?></h2>
					<ul class="m-0 list-none space-y-2.5 p-0">
						<?php foreach ( $dmd_footer_cats as $dmd_cat ) : ?>
							<li class="list-none"><a class="<?php echo esc_attr( $dmd_link_class ); ?>" href="<?php echo esc_url( get_term_link( $dmd_cat ) ); ?>"><?php echo esc_html( $dmd_cat->name ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</nav>

				<nav aria-labelledby="dmd-footer-links">
					<h2 id="dmd-footer-links" class="mb-4 text-base font-semibold text-white"><?php esc_html_e( 'Quick links', 'digital-mudir-dokan' ); ?></h2>
					<?php if ( has_nav_menu( 'footer' ) ) : ?>
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'footer',
								'container'      => false,
								'depth'          => 1,
								'menu_class'     => 'dmd-footer-menu m-0 list-none space-y-2.5 p-0',
							)
						);
						?>
					<?php else : ?>
						<ul class="m-0 list-none space-y-2.5 p-0">
							<?php foreach ( $dmd_quick_links as $dmd_label => $dmd_url ) : ?>
								<li class="list-none"><a class="<?php echo esc_attr( $dmd_link_class ); ?>" href="<?php echo esc_url( $dmd_url ); ?>"><?php echo esc_html( $dmd_label ); ?></a></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</nav>
			<?php endif; ?>

			<div>
				<h2 class="mb-4 text-base font-semibold text-white"><?php esc_html_e( 'Contact us', 'digital-mudir-dokan' ); ?></h2>
				<address class="space-y-3 text-sm not-italic leading-relaxed">
					<?php if ( $dmd_contact['address'] ) : ?>
						<p class="m-0 flex items-start gap-2.5">
							<span class="mt-0.5 shrink-0 text-white"><?php dmd_the_icon( 'pin', 16 ); ?></span>
							<span><?php echo esc_html( $dmd_contact['address'] ); ?></span>
						</p>
					<?php endif; ?>

					<?php foreach ( array( 'hotline', 'phone_1', 'phone_2' ) as $dmd_key ) : ?>
						<?php if ( $dmd_contact[ $dmd_key ] ) : ?>
							<p class="m-0 flex items-center gap-2.5">
								<span class="shrink-0 text-white"><?php dmd_the_icon( 'phone', 16 ); ?></span>
								<a class="<?php echo esc_attr( $dmd_link_class ); ?>" href="tel:<?php echo esc_attr( dmd_tel( $dmd_contact[ $dmd_key ] ) ); ?>"><?php echo esc_html( $dmd_contact[ $dmd_key ] ); ?></a>
							</p>
						<?php endif; ?>
					<?php endforeach; ?>

					<?php foreach ( array( 'email_1', 'email_2' ) as $dmd_key ) : ?>
						<?php if ( $dmd_contact[ $dmd_key ] ) : ?>
							<p class="m-0 flex items-center gap-2.5">
								<span class="shrink-0 text-white"><?php dmd_the_icon( 'mail', 16 ); ?></span>
								<a class="<?php echo esc_attr( $dmd_link_class ); ?> break-all" href="mailto:<?php echo esc_attr( $dmd_contact[ $dmd_key ] ); ?>"><?php echo esc_html( $dmd_contact[ $dmd_key ] ); ?></a>
							</p>
						<?php endif; ?>
					<?php endforeach; ?>
				</address>
			</div>
		</div>

		<?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
			<div class="border-t border-white/10">
				<div class="dmd-footer-widgets dmd-container py-8">
					<?php dynamic_sidebar( 'footer-3' ); ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( get_theme_mod( 'dmd_disclaimer' ) ) : ?>
			<section class="border-t border-white/10" aria-labelledby="dmd-disclaimer-title">
				<div class="dmd-container py-6">
					<h2 id="dmd-disclaimer-title" class="m-0 text-[13px] font-semibold text-white"><?php esc_html_e( 'Disclaimer', 'digital-mudir-dokan' ); ?></h2>
					<div class="mt-2 max-w-4xl text-xs leading-relaxed text-white/60">
						<?php echo wp_kses_post( wpautop( get_theme_mod( 'dmd_disclaimer' ) ) ); ?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<div class="border-t border-white/10 bg-black/15">
			<div class="dmd-container flex flex-col items-center justify-between gap-4 py-5 text-xs text-white/60 md:flex-row">
				<div class="flex flex-col items-center gap-1 md:items-start">
					<p class="m-0">
						<?php
						printf(
							/* translators: 1: year, 2: site name. */
							esc_html__( 'Copyright © %1$s %2$s. All rights reserved.', 'digital-mudir-dokan' ),
							esc_html( gmdate( 'Y' ) ),
							esc_html( get_bloginfo( 'name' ) )
						);
						?>
					</p>
					<?php if ( $dmd_policy ) : ?>
						<nav aria-label="<?php esc_attr_e( 'Policies', 'digital-mudir-dokan' ); ?>">
							<ul class="m-0 flex list-none flex-wrap justify-center gap-x-4 gap-y-1 p-0 md:justify-start">
								<?php foreach ( $dmd_policy as $dmd_label => $dmd_url ) : ?>
									<li class="list-none"><a class="text-white/60 hover:text-white" href="<?php echo esc_url( $dmd_url ); ?>"><?php echo esc_html( $dmd_label ); ?></a></li>
								<?php endforeach; ?>
							</ul>
						</nav>
					<?php endif; ?>
					<?php if ( get_theme_mod( 'dmd_trade_license' ) ) : ?>
						<p class="m-0">
							<?php
							printf(
								/* translators: %s: trade licence number. */
								esc_html__( 'Trade licence no: %s', 'digital-mudir-dokan' ),
								esc_html( get_theme_mod( 'dmd_trade_license' ) )
							);
							?>
						</p>
					<?php endif; ?>
				</div>

				<?php if ( get_theme_mod( 'dmd_payment_image' ) ) : ?>
					<div class="flex items-center gap-3">
						<span class="font-medium text-white/80"><?php esc_html_e( 'Pay with', 'digital-mudir-dokan' ); ?></span>
						<img class="max-h-8 w-auto rounded bg-white px-2 py-1"
							src="<?php echo esc_url( get_theme_mod( 'dmd_payment_image' ) ); ?>"
							alt="<?php esc_attr_e( 'Accepted cards and mobile banking options', 'digital-mudir-dokan' ); ?>"
							loading="lazy"
							decoding="async" />
					</div>
				<?php endif; ?>
			</div>
		</div>
	</footer>

	<?php if ( $dmd_contact['whatsapp'] || $dmd_contact['messenger'] ) : ?>
		<div class="dmd-float fixed bottom-5 right-4 z-40 flex flex-col gap-3">
			<?php if ( $dmd_contact['whatsapp'] ) : ?>
				<a class="flex h-12 w-12 items-center justify-center rounded-full bg-green text-white shadow-lift ring-4 ring-white transition hover:scale-105 hover:bg-green-dark hover:text-white"
					href="https://wa.me/<?php echo esc_attr( ltrim( dmd_tel( $dmd_contact['whatsapp'] ), '+' ) ); ?>"
					target="_blank"
					rel="noopener noreferrer">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.4A10 10 0 1 0 12 2Zm5.3 14c-.2.6-1.2 1.2-1.7 1.2-.5.1-1 .1-1.6-.1-.4-.1-.9-.3-1.5-.6a11 11 0 0 1-4.2-3.9c-.4-.6-.9-1.4-.9-2.3 0-.9.5-1.4.7-1.6.2-.2.4-.3.6-.3h.5c.2 0 .4 0 .6.4l.7 1.8c.1.2 0 .4-.1.5l-.3.4c-.1.1-.3.3-.1.6.2.3.7 1.1 1.4 1.8.9.8 1.6 1 1.9 1.2.2.1.4.1.5-.1l.7-.8c.2-.2.3-.2.5-.1l1.7.8c.2.1.4.2.4.3.1.2.1.6 0 .8Z"/></svg>
					<span class="screen-reader-text"><?php esc_html_e( 'Chat on WhatsApp', 'digital-mudir-dokan' ); ?></span>
				</a>
			<?php endif; ?>

			<?php if ( $dmd_contact['messenger'] ) : ?>
				<a class="flex h-12 w-12 items-center justify-center rounded-full bg-[#0084ff] text-white shadow-lift ring-4 ring-white transition hover:scale-105 hover:text-white"
					href="<?php echo esc_url( $dmd_contact['messenger'] ); ?>"
					target="_blank"
					rel="noopener noreferrer">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M12 2C6.3 2 2 6.2 2 11.8c0 3 1.4 5.7 3.7 7.5V23l3.4-1.9c.9.3 1.9.4 2.9.4 5.7 0 10-4.2 10-9.7S17.7 2 12 2Zm1 13.1-2.6-2.7L5.5 15l5.4-5.7 2.6 2.7L18.4 9Z"/></svg>
					<span class="screen-reader-text"><?php esc_html_e( 'Chat on Messenger', 'digital-mudir-dokan' ); ?></span>
				</a>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
