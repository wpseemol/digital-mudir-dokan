<?php
/**
 * Site footer and document close.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

$dmd_contact = dmd_contact();
$dmd_social  = dmd_social_links();
?>
	</main><!-- #dmd-main -->

	<footer id="dmd-footer" class="dmd-footer border-t-2 border-green/20 bg-white" role="contentinfo">

		<?php if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) ) : ?>
			<div class="border-b border-line">
				<div class="dmd-container grid gap-8 py-10 sm:grid-cols-2 lg:grid-cols-3">
					<?php for ( $dmd_i = 1; $dmd_i <= 3; $dmd_i++ ) : ?>
						<?php if ( is_active_sidebar( 'footer-' . $dmd_i ) ) : ?>
							<div class="dmd-footer-col">
								<?php dynamic_sidebar( 'footer-' . $dmd_i ); ?>
							</div>
						<?php endif; ?>
					<?php endfor; ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="dmd-container py-10 text-center">

			<div class="dmd-footer-brand flex flex-col items-center justify-center">
				<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
					<p class="m-0 font-display text-xl font-bold">
						<a class="text-green" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
					</p>
				<?php endif; ?>
			</div>

			<?php if ( $dmd_social ) : ?>
				<ul class="mt-4 flex list-none items-center justify-center gap-2 p-0">
					<?php foreach ( $dmd_social as $dmd_key => $dmd_item ) : ?>
						<li class="list-none">
							<a class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-green text-white hover:bg-green-dark hover:text-white"
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

			<address class="mt-5 not-italic text-[13px] leading-relaxed text-ink">
				<?php if ( $dmd_contact['address'] ) : ?>
					<p class="m-0 flex items-center justify-center gap-1.5">
						<span class="text-green"><?php dmd_the_icon( 'pin', 16 ); ?></span>
						<?php echo esc_html( $dmd_contact['address'] ); ?>
					</p>
				<?php endif; ?>

				<p class="mt-3 flex flex-wrap items-center justify-center gap-x-5 gap-y-2">
					<?php if ( $dmd_contact['phone_1'] ) : ?>
						<a class="inline-flex items-center gap-1.5" href="tel:<?php echo esc_attr( dmd_tel( $dmd_contact['phone_1'] ) ); ?>">
							<span class="text-green"><?php dmd_the_icon( 'phone', 15 ); ?></span><?php echo esc_html( $dmd_contact['phone_1'] ); ?>
						</a>
					<?php endif; ?>

					<?php if ( $dmd_contact['phone_2'] ) : ?>
						<a class="inline-flex items-center gap-1.5" href="tel:<?php echo esc_attr( dmd_tel( $dmd_contact['phone_2'] ) ); ?>">
							<span class="text-green"><?php dmd_the_icon( 'phone', 15 ); ?></span><?php echo esc_html( $dmd_contact['phone_2'] ); ?>
						</a>
					<?php endif; ?>

					<?php if ( $dmd_contact['email_1'] ) : ?>
						<a class="inline-flex items-center gap-1.5" href="mailto:<?php echo esc_attr( $dmd_contact['email_1'] ); ?>">
							<span class="text-green"><?php dmd_the_icon( 'mail', 15 ); ?></span><?php echo esc_html( $dmd_contact['email_1'] ); ?>
						</a>
					<?php endif; ?>

					<?php if ( $dmd_contact['email_2'] ) : ?>
						<a class="inline-flex items-center gap-1.5" href="mailto:<?php echo esc_attr( $dmd_contact['email_2'] ); ?>">
							<span class="text-green"><?php dmd_the_icon( 'mail', 15 ); ?></span><?php echo esc_html( $dmd_contact['email_2'] ); ?>
						</a>
					<?php endif; ?>
				</p>
			</address>

			<?php if ( has_nav_menu( 'footer' ) ) : ?>
				<nav class="dmd-footer-nav mt-5" aria-label="<?php esc_attr_e( 'Footer', 'digital-mudir-dokan' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'container'      => false,
							'depth'          => 1,
							'menu_class'     => 'm-0 flex flex-wrap items-center justify-center gap-x-4 gap-y-2 p-0 list-none text-[13px] font-medium uppercase tracking-wide',
						)
					);
					?>
				</nav>
			<?php endif; ?>

			<?php if ( get_theme_mod( 'dmd_disclaimer' ) ) : ?>
				<section class="mt-8 border-y border-line py-5" aria-labelledby="dmd-disclaimer-title">
					<h2 id="dmd-disclaimer-title" class="text-[13px] font-semibold"><?php esc_html_e( 'Disclaimer', 'digital-mudir-dokan' ); ?></h2>
					<div class="mx-auto mt-2 max-w-[860px] text-[12px] leading-relaxed text-muted">
						<?php echo wp_kses_post( wpautop( get_theme_mod( 'dmd_disclaimer' ) ) ); ?>
					</div>
				</section>
			<?php endif; ?>

			<div class="mt-6 flex flex-wrap items-center justify-between gap-3 text-[11px] uppercase tracking-wide text-muted">
				<p class="m-0">
					<?php
					printf(
						/* translators: 1: year, 2: site name. */
						esc_html__( 'Copyright © %1$s | All rights reserved by %2$s', 'digital-mudir-dokan' ),
						esc_html( gmdate( 'Y' ) ),
						esc_html( get_bloginfo( 'name' ) )
					);
					?>
				</p>

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

				<p class="m-0"><?php esc_html_e( 'Designed & developed in Bangladesh', 'digital-mudir-dokan' ); ?></p>
			</div>

			<?php if ( get_theme_mod( 'dmd_payment_image' ) ) : ?>
				<div class="mt-5 flex flex-wrap items-center justify-center gap-3 border-t border-line pt-5">
					<span class="text-sm font-semibold"><?php esc_html_e( 'Pay with', 'digital-mudir-dokan' ); ?></span>
					<img src="<?php echo esc_url( get_theme_mod( 'dmd_payment_image' ) ); ?>"
						alt="<?php esc_attr_e( 'Accepted cards and mobile banking options', 'digital-mudir-dokan' ); ?>"
						loading="lazy"
						decoding="async" />
				</div>
			<?php endif; ?>
		</div>
	</footer>

	<?php if ( $dmd_contact['whatsapp'] || $dmd_contact['messenger'] ) : ?>
		<div class="dmd-float fixed bottom-5 right-4 z-40 flex flex-col gap-3">
			<?php if ( $dmd_contact['whatsapp'] ) : ?>
				<a class="flex h-12 w-12 items-center justify-center rounded-full bg-green text-white shadow-card hover:bg-green-dark hover:text-white"
					href="https://wa.me/<?php echo esc_attr( ltrim( dmd_tel( $dmd_contact['whatsapp'] ), '+' ) ); ?>"
					target="_blank"
					rel="noopener noreferrer">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.4A10 10 0 1 0 12 2Zm5.3 14c-.2.6-1.2 1.2-1.7 1.2-.5.1-1 .1-1.6-.1-.4-.1-.9-.3-1.5-.6a11 11 0 0 1-4.2-3.9c-.4-.6-.9-1.4-.9-2.3 0-.9.5-1.4.7-1.6.2-.2.4-.3.6-.3h.5c.2 0 .4 0 .6.4l.7 1.8c.1.2 0 .4-.1.5l-.3.4c-.1.1-.3.3-.1.6.2.3.7 1.1 1.4 1.8.9.8 1.6 1 1.9 1.2.2.1.4.1.5-.1l.7-.8c.2-.2.3-.2.5-.1l1.7.8c.2.1.4.2.4.3.1.2.1.6 0 .8Z"/></svg>
					<span class="screen-reader-text"><?php esc_html_e( 'Chat on WhatsApp', 'digital-mudir-dokan' ); ?></span>
				</a>
			<?php endif; ?>

			<?php if ( $dmd_contact['messenger'] ) : ?>
				<a class="flex h-12 w-12 items-center justify-center rounded-full bg-[#0084ff] text-white shadow-card hover:text-white"
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
