<?php
/**
 * Maintenance mode page, printed with a 503 status.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

$dmd_contact = dmd_contact();
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="robots" content="noindex" />
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'dmd-maintenance' ); ?>>
	<main class="flex min-h-screen items-center justify-center bg-gradient-to-b from-green-soft to-white px-4 py-16">
		<div class="w-full max-w-lg rounded-2xl border border-line bg-white p-8 text-center shadow-card sm:p-10">
			<?php if ( has_custom_logo() ) : ?>
				<div class="mb-6 flex justify-center [&_img]:!h-16 [&_img]:!w-auto"><?php the_custom_logo(); ?></div>
			<?php endif; ?>

			<span class="mx-auto mb-5 flex h-14 w-14 items-center justify-center rounded-full bg-green-soft text-green"><?php dmd_the_icon( 'leaf', 26 ); ?></span>

			<h1 class="m-0 font-display text-2xl font-bold text-ink sm:text-3xl"><?php echo esc_html( dmd_option( 'maintenance_title' ) ); ?></h1>
			<p class="m-0 mt-3 leading-relaxed text-muted"><?php echo esc_html( dmd_option( 'maintenance_message' ) ); ?></p>

			<div class="mt-7 flex flex-wrap justify-center gap-3">
				<?php if ( $dmd_contact['hotline'] ) : ?>
					<a class="dmd-btn dmd-btn--primary" href="tel:<?php echo esc_attr( dmd_tel( $dmd_contact['hotline'] ) ); ?>">
						<?php dmd_the_icon( 'phone', 18 ); ?>
						<?php echo esc_html( $dmd_contact['hotline'] ); ?>
					</a>
				<?php endif; ?>
				<?php if ( $dmd_contact['whatsapp'] ) : ?>
					<a class="dmd-btn dmd-btn--outline" href="https://wa.me/<?php echo esc_attr( ltrim( dmd_tel( $dmd_contact['whatsapp'] ), '+' ) ); ?>" target="_blank" rel="noopener noreferrer">
						<?php dmd_the_icon( 'whatsapp', 18 ); ?>
						<?php esc_html_e( 'WhatsApp', 'digital-mudir-dokan' ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</main>
	<?php wp_footer(); ?>
</body>
</html>
