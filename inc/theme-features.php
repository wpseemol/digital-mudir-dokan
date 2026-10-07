<?php
/**
 * Front-end features switched on from the theme dashboard.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

/* ---------------------------------------------------------------------------
 * General
 * ------------------------------------------------------------------------- */

/**
 * Drop the emoji detection script and styles.
 */
function dmd_maybe_disable_emojis() {
	if ( ! dmd_option( 'disable_emojis' ) ) {
		return;
	}

	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'emoji_svg_url', '__return_false' );
}
add_action( 'init', 'dmd_maybe_disable_emojis' );

/**
 * Back-to-top button; theme.js reveals it after scrolling.
 */
function dmd_back_to_top() {
	if ( ! dmd_option( 'back_to_top' ) ) {
		return;
	}
	?>
	<a href="#page" class="dmd-back-to-top pointer-events-none fixed bottom-5 left-4 z-40 flex h-11 w-11 translate-y-4 items-center justify-center rounded-full bg-white text-green opacity-0 shadow-lift ring-1 ring-line transition duration-300 hover:bg-green hover:text-white focus:pointer-events-auto focus:translate-y-0 focus:opacity-100 motion-reduce:transition-none" data-dmd-back-to-top>
		<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m6 15 6-6 6 6"/></svg>
		<span class="screen-reader-text"><?php esc_html_e( 'Back to top', 'digital-mudir-dokan' ); ?></span>
	</a>
	<?php
}
add_action( 'wp_footer', 'dmd_back_to_top', 5 );

/**
 * Maintenance mode: a 503 "back soon" page for visitors who cannot edit the site.
 */
function dmd_maintenance_mode() {
	if ( ! dmd_option( 'maintenance_mode' ) || current_user_can( 'edit_posts' ) ) {
		return;
	}

	if ( wp_doing_ajax() || wp_doing_cron() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || is_robots() || is_favicon() ) {
		return;
	}

	status_header( 503 );
	header( 'Retry-After: 3600' );
	nocache_headers();

	get_template_part( 'template-parts/maintenance' );
	exit;
}
add_action( 'template_redirect', 'dmd_maintenance_mode', 1 );

/**
 * Warn logged-in staff that visitors are seeing the maintenance page.
 *
 * @param WP_Admin_Bar $bar Admin bar.
 */
function dmd_maintenance_admin_bar( $bar ) {
	if ( ! dmd_option( 'maintenance_mode' ) || ! current_user_can( 'edit_posts' ) ) {
		return;
	}

	$bar->add_node(
		array(
			'id'    => 'dmd-maintenance',
			'title' => __( 'Maintenance mode is on', 'digital-mudir-dokan' ),
			'href'  => current_user_can( 'manage_options' ) ? admin_url( 'admin.php?page=dmd-theme-general' ) : false,
			'meta'  => array( 'class' => 'dmd-maintenance-flag' ),
		)
	);
}
add_action( 'admin_bar_menu', 'dmd_maintenance_admin_bar', 100 );

/**
 * Colour the maintenance flag so it is hard to miss.
 */
function dmd_maintenance_admin_bar_style() {
	if ( dmd_option( 'maintenance_mode' ) && is_admin_bar_showing() ) {
		echo '<style>#wpadminbar .dmd-maintenance-flag > .ab-item{background:#b32d2e;color:#fff}</style>';
	}
}
add_action( 'wp_head', 'dmd_maintenance_admin_bar_style' );
add_action( 'admin_head', 'dmd_maintenance_admin_bar_style' );

/* ---------------------------------------------------------------------------
 * Integrations
 * ------------------------------------------------------------------------- */

/**
 * Whether analytics should run for this visitor.
 *
 * @return bool
 */
function dmd_should_track() {
	return ! is_admin() && ! current_user_can( 'manage_options' ) && ! is_customize_preview();
}

/**
 * GA4, Meta Pixel and custom head code.
 */
function dmd_integrations_head() {
	$ga4   = dmd_option( 'ga4_id' );
	$pixel = dmd_option( 'meta_pixel_id' );

	if ( dmd_should_track() && $ga4 ) {
		?>
		<script async src="<?php echo esc_url( 'https://www.googletagmanager.com/gtag/js?id=' . rawurlencode( $ga4 ) ); ?>"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());
			gtag('config', <?php echo wp_json_encode( $ga4 ); ?>);
		</script>
		<?php
	}

	if ( dmd_should_track() && $pixel ) {
		?>
		<script>
			!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
			fbq('init', <?php echo wp_json_encode( $pixel ); ?>);
			fbq('track', 'PageView');
		</script>
		<noscript><img height="1" width="1" style="display:none" alt="" src="<?php echo esc_url( 'https://www.facebook.com/tr?id=' . rawurlencode( $pixel ) . '&ev=PageView&noscript=1' ); ?>" /></noscript>
		<?php
	}

	$code = dmd_option( 'header_code' );
	if ( $code ) {
		echo "\n" . $code . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- saved only by users with unfiltered_html.
	}
}
add_action( 'wp_head', 'dmd_integrations_head', 20 );

/**
 * Custom footer code.
 */
function dmd_integrations_footer() {
	$code = dmd_option( 'footer_code' );
	if ( $code ) {
		echo "\n" . $code . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- saved only by users with unfiltered_html.
	}
}
add_action( 'wp_footer', 'dmd_integrations_footer', 100 );

/**
 * Report a purchase to GA4 and the Meta Pixel once per order.
 *
 * @param int $order_id Order ID.
 */
function dmd_track_purchase( $order_id ) {
	$ga4   = dmd_option( 'ga4_id' );
	$pixel = dmd_option( 'meta_pixel_id' );
	$order = $order_id ? wc_get_order( $order_id ) : false;

	if ( ! $order || ( ! $ga4 && ! $pixel ) || ! dmd_should_track() || $order->get_meta( '_dmd_tracked' ) ) {
		return;
	}

	$items = array();
	foreach ( $order->get_items() as $item ) {
		$items[] = array(
			'item_id'   => (string) $item->get_product_id(),
			'item_name' => $item->get_name(),
			'quantity'  => (int) $item->get_quantity(),
			'price'     => (float) $order->get_item_total( $item ),
		);
	}

	$value    = (float) $order->get_total();
	$currency = $order->get_currency();
	?>
	<script>
		<?php if ( $ga4 ) : ?>
		if (typeof gtag === 'function') {
			gtag('event', 'purchase', <?php echo wp_json_encode( array( 'transaction_id' => (string) $order->get_order_number(), 'value' => $value, 'currency' => $currency, 'items' => $items ) ); ?>);
		}
		<?php endif; ?>
		<?php if ( $pixel ) : ?>
		if (typeof fbq === 'function') {
			fbq('track', 'Purchase', <?php echo wp_json_encode( array( 'value' => $value, 'currency' => $currency, 'content_type' => 'product', 'content_ids' => wp_list_pluck( $items, 'item_id' ) ) ); ?>);
		}
		<?php endif; ?>
	</script>
	<?php
	$order->update_meta_data( '_dmd_tracked', 1 );
	$order->save();
}
add_action( 'woocommerce_thankyou', 'dmd_track_purchase', 20 );

/* ---------------------------------------------------------------------------
 * Contact form
 * ------------------------------------------------------------------------- */

/**
 * Validate and email a contact-page message, then return to the form.
 */
function dmd_handle_contact_form() {
	$back = wp_get_referer();
	$back = $back ? remove_query_arg( array( 'dmd_contact', 'dmd_error' ), $back ) : home_url( '/' );

	$redirect = static function ( $status, $error = '' ) use ( $back ) {
		$args = array( 'dmd_contact' => $status );
		if ( $error ) {
			$args['dmd_error'] = $error;
		}
		wp_safe_redirect( add_query_arg( $args, $back ) . '#dmd-contact-form' );
		exit;
	};

	if ( ! dmd_option( 'contact_form' ) || ! isset( $_POST['dmd_contact_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['dmd_contact_nonce'] ) ), 'dmd_contact' ) ) {
		$redirect( 'error', 'expired' );
	}

	// Bots fill every field, people never see this one.
	if ( ! empty( $_POST['dmd_website'] ) ) {
		$redirect( 'sent' );
	}

	$name    = isset( $_POST['dmd_name'] ) ? sanitize_text_field( wp_unslash( $_POST['dmd_name'] ) ) : '';
	$phone   = isset( $_POST['dmd_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['dmd_phone'] ) ) : '';
	$email   = isset( $_POST['dmd_email'] ) ? sanitize_email( wp_unslash( $_POST['dmd_email'] ) ) : '';
	$subject = isset( $_POST['dmd_subject'] ) ? sanitize_text_field( wp_unslash( $_POST['dmd_subject'] ) ) : '';
	$message = isset( $_POST['dmd_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['dmd_message'] ) ) : '';

	if ( '' === $name || '' === $message || ( '' === $phone && '' === $email ) ) {
		$redirect( 'error', 'missing' );
	}

	if ( '' !== $phone && ! preg_match( '/^[0-9+()\-\s]{6,20}$/', $phone ) ) {
		$redirect( 'error', 'phone' );
	}

	$ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$key = 'dmd_contact_' . md5( $ip );
	if ( get_transient( $key ) ) {
		$redirect( 'error', 'wait' );
	}

	$to = dmd_option( 'contact_recipient' );
	$to = is_email( $to ) ? $to : get_option( 'admin_email' );

	$body = implode(
		"\n",
		array(
			/* translators: %s: sender name. */
			sprintf( __( 'Name: %s', 'digital-mudir-dokan' ), $name ),
			/* translators: %s: sender phone. */
			sprintf( __( 'Phone: %s', 'digital-mudir-dokan' ), $phone ? $phone : '—' ),
			/* translators: %s: sender email. */
			sprintf( __( 'Email: %s', 'digital-mudir-dokan' ), $email ? $email : '—' ),
			'',
			$message,
			'',
			/* translators: %s: page URL. */
			sprintf( __( 'Sent from: %s', 'digital-mudir-dokan' ), $back ),
		)
	);

	$headers = array();
	if ( $email ) {
		$headers[] = 'Reply-To: ' . str_replace( array( "\r", "\n", '"' ), '', $name ) . ' <' . $email . '>';
	}

	$title = sprintf(
		/* translators: 1: site name, 2: subject or sender name. */
		__( '[%1$s] New message: %2$s', 'digital-mudir-dokan' ),
		wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ),
		$subject ? $subject : $name
	);

	if ( ! wp_mail( $to, $title, $body, $headers ) ) {
		$redirect( 'error', 'mail' );
	}

	set_transient( $key, 1, MINUTE_IN_SECONDS );
	$redirect( 'sent' );
}
add_action( 'admin_post_dmd_contact', 'dmd_handle_contact_form' );
add_action( 'admin_post_nopriv_dmd_contact', 'dmd_handle_contact_form' );

/**
 * Message shown above the contact form after a submission.
 *
 * @return array{type:string,text:string}|null
 */
function dmd_contact_form_status() {
	// phpcs:disable WordPress.Security.NonceVerification.Recommended -- display-only flags set by our own redirect.
	$status = isset( $_GET['dmd_contact'] ) ? sanitize_key( wp_unslash( $_GET['dmd_contact'] ) ) : '';

	if ( '' === $status ) {
		return null;
	}

	if ( 'sent' === $status ) {
		return array(
			'type' => 'success',
			'text' => dmd_option( 'contact_success' ),
		);
	}

	$errors = array(
		'missing' => __( 'Please enter your name, a message, and a phone number or email address.', 'digital-mudir-dokan' ),
		'phone'   => __( 'Please enter a valid phone number.', 'digital-mudir-dokan' ),
		'wait'    => __( 'You just sent a message. Please wait a minute before sending another.', 'digital-mudir-dokan' ),
		'mail'    => __( 'Sorry, the message could not be sent. Please call or WhatsApp us instead.', 'digital-mudir-dokan' ),
		'expired' => __( 'The form expired. Please try again.', 'digital-mudir-dokan' ),
	);
	$code   = isset( $_GET['dmd_error'] ) ? sanitize_key( wp_unslash( $_GET['dmd_error'] ) ) : 'expired';
	// phpcs:enable

	return array(
		'type' => 'error',
		'text' => isset( $errors[ $code ] ) ? $errors[ $code ] : $errors['expired'],
	);
}
