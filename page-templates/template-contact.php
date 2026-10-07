<?php
/**
 * Template Name: Contact
 *
 * Contact details from Customizer → Shop details, opening hours, a message
 * form and a map. Text comes from Mudir Dokan → Contact page.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

get_header();

$dmd_contact = dmd_contact();
$dmd_status  = dmd_contact_form_status();
$dmd_hours   = array_filter( array_map( 'trim', explode( "\n", (string) dmd_option( 'contact_hours' ) ) ) );
$dmd_map     = dmd_option( 'contact_map' );
$dmd_title   = dmd_option( 'contact_title' );

$dmd_cards = array();
if ( $dmd_contact['hotline'] ) {
	$dmd_cards[] = array( 'phone', __( 'Hotline', 'digital-mudir-dokan' ), $dmd_contact['hotline'], 'tel:' . dmd_tel( $dmd_contact['hotline'] ) );
}
if ( $dmd_contact['whatsapp'] ) {
	$dmd_cards[] = array( 'whatsapp', __( 'WhatsApp', 'digital-mudir-dokan' ), $dmd_contact['whatsapp'], 'https://wa.me/' . ltrim( dmd_tel( $dmd_contact['whatsapp'] ), '+' ) );
}
foreach ( array( 'phone_1', 'phone_2' ) as $dmd_key ) {
	if ( $dmd_contact[ $dmd_key ] && $dmd_contact[ $dmd_key ] !== $dmd_contact['hotline'] ) {
		$dmd_cards[] = array( 'phone', __( 'Phone', 'digital-mudir-dokan' ), $dmd_contact[ $dmd_key ], 'tel:' . dmd_tel( $dmd_contact[ $dmd_key ] ) );
	}
}
foreach ( array( 'email_1', 'email_2' ) as $dmd_key ) {
	if ( $dmd_contact[ $dmd_key ] ) {
		$dmd_cards[] = array( 'mail', __( 'Email', 'digital-mudir-dokan' ), $dmd_contact[ $dmd_key ], 'mailto:' . $dmd_contact[ $dmd_key ] );
	}
}

while ( have_posts() ) :
	the_post();
	?>
	<section class="dmd-archive-header border-b border-line bg-gradient-to-b from-green-soft/70 to-white">
		<div class="dmd-container py-6 sm:py-10">
			<?php dmd_breadcrumbs(); ?>
			<h1 class="dmd-heading mt-4 !text-3xl sm:!text-4xl"><?php echo esc_html( $dmd_title ? $dmd_title : get_the_title() ); ?></h1>
			<?php if ( dmd_option( 'contact_intro' ) ) : ?>
				<p class="m-0 mt-3 max-w-2xl text-base leading-relaxed text-muted"><?php echo esc_html( dmd_option( 'contact_intro' ) ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<div class="dmd-container py-8 sm:py-12">
		<div class="grid gap-6 lg:grid-cols-[1fr_1.35fr] lg:gap-8">

			<aside class="space-y-4" aria-label="<?php esc_attr_e( 'Contact details', 'digital-mudir-dokan' ); ?>">
				<?php if ( $dmd_cards ) : ?>
					<ul class="m-0 grid list-none gap-3 p-0 sm:grid-cols-2 lg:grid-cols-1">
						<?php foreach ( $dmd_cards as $dmd_card ) : ?>
							<li class="list-none">
								<a class="group flex items-center gap-4 rounded-xl border border-line bg-white p-4 text-ink transition hover:border-green/25 hover:shadow-lift hover:text-ink"
									href="<?php echo esc_url( $dmd_card[3] ); ?>"
									<?php echo 0 === strpos( $dmd_card[3], 'https://' ) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
									<span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-green-soft text-green transition group-hover:bg-green group-hover:text-white"><?php dmd_the_icon( $dmd_card[0], 20 ); ?></span>
									<span class="min-w-0">
										<span class="block text-xs text-muted"><?php echo esc_html( $dmd_card[1] ); ?></span>
										<span class="block break-all font-semibold"><?php echo esc_html( $dmd_card[2] ); ?></span>
									</span>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<?php if ( $dmd_contact['address'] || $dmd_hours ) : ?>
					<div class="rounded-xl border border-line bg-white p-5">
						<?php if ( $dmd_contact['address'] ) : ?>
							<h2 class="m-0 flex items-center gap-2 text-sm font-semibold text-ink"><span class="text-green"><?php dmd_the_icon( 'pin', 18 ); ?></span><?php esc_html_e( 'Address', 'digital-mudir-dokan' ); ?></h2>
							<address class="m-0 mt-2 text-sm not-italic leading-relaxed text-muted"><?php echo esc_html( $dmd_contact['address'] ); ?></address>
						<?php endif; ?>

						<?php if ( $dmd_hours ) : ?>
							<h2 class="m-0 flex items-center gap-2 text-sm font-semibold text-ink <?php echo $dmd_contact['address'] ? 'mt-5 border-t border-line pt-5' : ''; ?>"><span class="text-green"><?php dmd_the_icon( 'clock', 18 ); ?></span><?php esc_html_e( 'Opening hours', 'digital-mudir-dokan' ); ?></h2>
							<ul class="m-0 mt-2 list-none space-y-1 p-0 text-sm text-muted">
								<?php foreach ( $dmd_hours as $dmd_line ) : ?>
									<li class="list-none"><?php echo esc_html( $dmd_line ); ?></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</aside>

			<div>
				<?php if ( '' !== trim( get_the_content() ) ) : ?>
					<div class="dmd-prose mb-6"><?php the_content(); ?></div>
				<?php endif; ?>

				<?php if ( dmd_option( 'contact_form' ) ) : ?>
					<section id="dmd-contact-form" class="scroll-mt-32 rounded-2xl border border-line bg-white p-5 shadow-soft sm:p-8" aria-labelledby="dmd-contact-form-title">
						<h2 id="dmd-contact-form-title" class="m-0 font-display text-xl font-bold text-ink"><?php esc_html_e( 'Send us a message', 'digital-mudir-dokan' ); ?></h2>
						<p class="m-0 mt-1 text-sm text-muted"><?php esc_html_e( 'Fields marked * are required. Give us a phone number or an email so we can reply.', 'digital-mudir-dokan' ); ?></p>

						<?php if ( $dmd_status ) : ?>
							<div class="mt-5 flex items-start gap-2 rounded-lg px-4 py-3 text-sm <?php echo 'success' === $dmd_status['type'] ? 'bg-green-soft text-green-dark' : 'bg-red-50 text-red-700'; ?>" role="<?php echo 'success' === $dmd_status['type'] ? 'status' : 'alert'; ?>">
								<?php if ( 'success' === $dmd_status['type'] ) : ?>
									<span class="mt-0.5 shrink-0"><?php dmd_the_icon( 'check', 16 ); ?></span>
								<?php endif; ?>
								<span><?php echo esc_html( $dmd_status['text'] ); ?></span>
							</div>
						<?php endif; ?>

						<form class="mt-6 grid gap-4 sm:grid-cols-2" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
							<input type="hidden" name="action" value="dmd_contact" />
							<?php wp_nonce_field( 'dmd_contact', 'dmd_contact_nonce' ); ?>

							<div class="absolute -left-[9999px] h-px w-px overflow-hidden" aria-hidden="true">
								<label for="dmd-website"><?php esc_html_e( 'Leave this field empty', 'digital-mudir-dokan' ); ?></label>
								<input type="text" id="dmd-website" name="dmd_website" tabindex="-1" autocomplete="off" />
							</div>

							<p class="m-0">
								<label class="mb-1.5 block text-sm font-semibold text-ink" for="dmd-name"><?php esc_html_e( 'Your name', 'digital-mudir-dokan' ); ?> <span class="text-red-600" aria-hidden="true">*</span></label>
								<input class="dmd-field" type="text" id="dmd-name" name="dmd_name" autocomplete="name" required maxlength="100" />
							</p>
							<p class="m-0">
								<label class="mb-1.5 block text-sm font-semibold text-ink" for="dmd-phone"><?php esc_html_e( 'Phone number', 'digital-mudir-dokan' ); ?></label>
								<input class="dmd-field" type="tel" id="dmd-phone" name="dmd_phone" autocomplete="tel" placeholder="01XXXXXXXXX" maxlength="20" />
							</p>
							<p class="m-0">
								<label class="mb-1.5 block text-sm font-semibold text-ink" for="dmd-email"><?php esc_html_e( 'Email', 'digital-mudir-dokan' ); ?></label>
								<input class="dmd-field" type="email" id="dmd-email" name="dmd_email" autocomplete="email" maxlength="120" />
							</p>
							<p class="m-0">
								<label class="mb-1.5 block text-sm font-semibold text-ink" for="dmd-subject"><?php esc_html_e( 'Subject', 'digital-mudir-dokan' ); ?></label>
								<input class="dmd-field" type="text" id="dmd-subject" name="dmd_subject" maxlength="150" />
							</p>
							<p class="m-0 sm:col-span-2">
								<label class="mb-1.5 block text-sm font-semibold text-ink" for="dmd-message"><?php esc_html_e( 'Message', 'digital-mudir-dokan' ); ?> <span class="text-red-600" aria-hidden="true">*</span></label>
								<textarea class="dmd-field min-h-[140px] resize-y" id="dmd-message" name="dmd_message" rows="5" required maxlength="3000"></textarea>
							</p>
							<p class="m-0 sm:col-span-2">
								<button type="submit" class="dmd-btn dmd-btn--primary w-full sm:w-auto"><?php dmd_the_icon( 'mail', 18 ); ?><?php esc_html_e( 'Send message', 'digital-mudir-dokan' ); ?></button>
							</p>
						</form>
					</section>
				<?php endif; ?>
			</div>
		</div>

		<?php if ( $dmd_map ) : ?>
			<div class="mt-8 overflow-hidden rounded-2xl border border-line bg-surface sm:mt-12">
				<iframe class="block h-[320px] w-full sm:h-[420px]" src="<?php echo esc_url( $dmd_map ); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen title="<?php esc_attr_e( 'Our location on Google Maps', 'digital-mudir-dokan' ); ?>"></iframe>
			</div>
		<?php endif; ?>
	</div>
	<?php
endwhile;

get_footer();
