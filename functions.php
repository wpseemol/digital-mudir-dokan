<?php
/**
 * Digital Mudir Dokan — theme bootstrap.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

define( 'DMD_VERSION', '1.0.0' );
define( 'DMD_DIR', get_template_directory() );
define( 'DMD_URI', get_template_directory_uri() );

/**
 * Theme setup.
 */
function dmd_theme_setup() {
	load_theme_textdomain( 'digital-mudir-dokan', DMD_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );

	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);

	add_theme_support(
		'custom-logo',
		array(
			'height'      => 60,
			'width'       => 180,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	// WooCommerce.
	add_theme_support(
		'woocommerce',
		array(
			'thumbnail_image_width' => 450,
			'single_image_width'    => 900,
			'product_grid'          => array(
				'default_rows'    => 3,
				'min_rows'        => 1,
				'default_columns' => 4,
				'min_columns'     => 2,
				'max_columns'     => 5,
			),
		)
	);
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	add_image_size( 'dmd-product-card', 450, 450, true );
	add_image_size( 'dmd-hero', 1600, 620, true );
	add_image_size( 'dmd-blog-card', 600, 420, true );

	register_nav_menus(
		array(
			'primary' => __( 'Primary menu', 'digital-mudir-dokan' ),
			'footer'  => __( 'Footer menu', 'digital-mudir-dokan' ),
			'mobile'  => __( 'Mobile menu', 'digital-mudir-dokan' ),
		)
	);

	// Editor palette mirrors the Tailwind tokens.
	add_theme_support(
		'editor-color-palette',
		array(
			array(
				'name'  => __( 'Brand green', 'digital-mudir-dokan' ),
				'slug'  => 'brand-green',
				'color' => '#113D21',
			),
			array(
				'name'  => __( 'Deep green', 'digital-mudir-dokan' ),
				'slug'  => 'brand-green-dark',
				'color' => '#058a36',
			),
			array(
				'name'  => __( 'Ink', 'digital-mudir-dokan' ),
				'slug'  => 'ink',
				'color' => '#1b1b1b',
			),
			array(
				'name'  => __( 'Surface', 'digital-mudir-dokan' ),
				'slug'  => 'surface',
				'color' => '#f3f5f7',
			),
		)
	);
}
add_action( 'after_setup_theme', 'dmd_theme_setup' );

/**
 * Content width.
 */
function dmd_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'dmd_content_width', 1200 );
}
add_action( 'after_setup_theme', 'dmd_content_width', 0 );

/**
 * Front-end assets.
 */
function dmd_assets() {
	// Google Fonts: Poppins for Latin display, Hind Siliguri for Bangla body copy.
	wp_enqueue_style(
		'dmd-fonts',
		'https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap',
		array(),
		null
	);

	// Swiper.js
	wp_enqueue_style( 'swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '11.0.0' );
	wp_enqueue_script( 'swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11.0.0', true );

	wp_enqueue_style( 'dmd-theme', DMD_URI . '/assets/css/theme.css', array(), time() );
	wp_enqueue_style( 'dmd-overrides', DMD_URI . '/assets/css/woocommerce-overrides.css', array( 'dmd-theme' ), time() );
	wp_enqueue_style( 'dmd-style', get_stylesheet_uri(), array( 'dmd-theme' ), time() );

	wp_enqueue_script( 'dmd-theme', DMD_URI . '/assets/js/theme.js', array( 'swiper-js', 'jquery' ), DMD_VERSION, true );
	wp_enqueue_script( 'dmd-live-search', DMD_URI . '/assets/js/live-search.js', array( 'jquery' ), DMD_VERSION, true );

	wp_localize_script(
		'dmd-theme',
		'dmd_theme_object',
		array(
			'selectVariation' => __( 'অনুগ্রহ করে একটি ভেরিয়েন্ট নির্বাচন করুন', 'digital-mudir-dokan' ),
			'chooseVariation' => __( 'দয়া করে প্রথমে একটি ভেরিয়েন্ট বা সাইজ নির্বাচন করুন, তারপর অর্ডার সম্পন্ন করুন।', 'digital-mudir-dokan' ),
		)
	);

	wp_localize_script(
		'dmd-live-search',
		'dmd_ajax_object',
		array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'dmd_search_nonce' ),
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'dmd_assets', 20 );

/**
 * Preconnect to the font CDN so the first paint is not blocked.
 *
 * @param array  $urls           URLs to print.
 * @param string $relation_type  Relation type.
 * @return array
 */
function dmd_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type && wp_style_is( 'dmd-fonts', 'queue' ) ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'dmd_resource_hints', 10, 2 );

/**
 * Widget areas.
 */
function dmd_widgets() {
	register_sidebar(
		array(
			'name'          => __( 'Blog sidebar', 'digital-mudir-dokan' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Shows beside posts and pages.', 'digital-mudir-dokan' ),
			'before_widget' => '<section id="%1$s" class="dmd-widget %2$s mb-6 rounded-lg border border-line p-5">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="dmd-widget__title mb-3 text-base font-semibold">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Shop sidebar', 'digital-mudir-dokan' ),
			'id'            => 'shop-sidebar',
			'description'   => __( 'Shows on shop and product archive pages.', 'digital-mudir-dokan' ),
			'before_widget' => '<section id="%1$s" class="dmd-widget %2$s mb-5 rounded-lg border border-line bg-white p-5">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="dmd-widget__title mb-3 text-base font-semibold">',
			'after_title'   => '</h2>',
		)
	);

	for ( $i = 1; $i <= 3; $i++ ) {
		register_sidebar(
			array(
				/* translators: %d: footer column number. */
				'name'          => sprintf( __( 'Footer column %d', 'digital-mudir-dokan' ), $i ),
				'id'            => 'footer-' . $i,
				'before_widget' => '<section id="%1$s" class="dmd-widget %2$s mb-6">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="mb-3 text-sm font-semibold uppercase tracking-wide">',
				'after_title'   => '</h2>',
			)
		);
	}
}
add_action( 'widgets_init', 'dmd_widgets' );

/* ---------------------------------------------------------------------------
 * My account — registration
 * ------------------------------------------------------------------------- */

/**
 * Shared class list for the account login/register inputs.
 *
 * @return string
 */
function dmd_auth_input_class() {
	return 'w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none text-sm transition';
}

/**
 * Class list for the account password inputs.
 *
 * WooCommerce's script wraps `.woocommerce-Input[type="password"]` — inside a
 * `.woocommerce` container — in `.password-input` and appends its show/hide
 * toggle. The marker class therefore has to be present or no toggle appears.
 * The My Account shortcode already renders that `.woocommerce` container, so
 * only the input needs the extra class.
 *
 * @return string
 */
function dmd_auth_password_class() {
	return dmd_auth_input_class() . ' woocommerce-Input woocommerce-Input--password';
}

/**
 * Turn the My Account registration form on and make customers type a password.
 *
 * Each option is only written when it differs from the wanted value, so a normal
 * request never hits the database. Return false from the
 * `dmd_force_account_registration` filter to leave the choice to the shop owner.
 */
function dmd_enable_account_registration() {
	if ( ! apply_filters( 'dmd_force_account_registration', true ) ) {
		return;
	}

	if ( 'yes' !== get_option( 'woocommerce_enable_myaccount_registration' ) ) {
		update_option( 'woocommerce_enable_myaccount_registration', 'yes' );
	}

	if ( 'no' !== get_option( 'woocommerce_registration_generate_password' ) ) {
		update_option( 'woocommerce_registration_generate_password', 'no' );
	}
}
add_action( 'after_setup_theme', 'dmd_enable_account_registration', 20 );

/**
 * Name and phone rows appended to the registration form.
 *
 * `woocommerce_register_form` prints after the core email and password rows, so
 * they are validated through the registration hooks rather than re-ordered.
 */
function dmd_register_form_fields() {
	// phpcs:disable WordPress.Security.NonceVerification.Missing -- values are echoed back for convenience only; the nonce is verified by WC_Form_Handler::process_registration().
	$dmd_first_name = isset( $_POST['first_name'] ) && is_string( $_POST['first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) : '';
	$dmd_phone      = isset( $_POST['billing_phone'] ) && is_string( $_POST['billing_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_phone'] ) ) : '';
	// phpcs:enable
	?>
	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-4">
		<label for="reg_first_name" class="mb-1.5 block text-sm font-semibold text-gray-700">
			<?php esc_html_e( 'Full name', 'digital-mudir-dokan' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span>
		</label>
		<input type="text"
			class="<?php echo esc_attr( dmd_auth_input_class() ); ?>"
			name="first_name"
			id="reg_first_name"
			autocomplete="name"
			required
			value="<?php echo esc_attr( $dmd_first_name ); ?>" />
	</p>

	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-4">
		<label for="reg_phone" class="mb-1.5 block text-sm font-semibold text-gray-700">
			<?php esc_html_e( 'Phone number', 'digital-mudir-dokan' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span>
		</label>
		<input type="tel"
			class="<?php echo esc_attr( dmd_auth_input_class() ); ?>"
			name="billing_phone"
			id="reg_phone"
			autocomplete="tel"
			required
			value="<?php echo esc_attr( $dmd_phone ); ?>" />
	</p>
	<?php
}
add_action( 'woocommerce_register_form', 'dmd_register_form_fields' );

/**
 * Reject empty name, phone, email or password before the account is created.
 *
 * WooCommerce already fails an invalid/empty email and a missing password before
 * this hook runs; those checks are repeated so the rules hold even if the shop
 * later flips `woocommerce_registration_generate_password` back on.
 *
 * @param string   $username Submitted username.
 * @param string   $email    Submitted email address.
 * @param WP_Error $errors   Error collector.
 */
function dmd_validate_registration( $username, $email, $errors ) {
	// phpcs:disable WordPress.Security.NonceVerification.Missing -- verified by WC_Form_Handler::process_registration().
	$first_name = isset( $_POST['first_name'] ) && is_string( $_POST['first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) : '';
	$phone      = isset( $_POST['billing_phone'] ) && is_string( $_POST['billing_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_phone'] ) ) : '';
	// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash -- passwords must never be sanitized.
	$password = isset( $_POST['password'] ) && is_string( $_POST['password'] ) ? $_POST['password'] : '';
	// phpcs:enable

	if ( '' === trim( $first_name ) ) {
		$errors->add( 'dmd_first_name_error', __( 'Please enter your name.', 'digital-mudir-dokan' ) );
	}

	if ( '' === trim( $phone ) ) {
		$errors->add( 'dmd_phone_error', __( 'Please enter your phone number.', 'digital-mudir-dokan' ) );
	} elseif ( ! preg_match( '/^[0-9+()\-\s]{6,20}$/', $phone ) ) {
		$errors->add( 'dmd_phone_error', __( 'Please enter a valid phone number.', 'digital-mudir-dokan' ) );
	}

	if ( '' === $email || ! is_email( $email ) ) {
		$errors->add( 'dmd_email_error', __( 'Please enter a valid email address.', 'digital-mudir-dokan' ) );
	}

	if ( '' === $password ) {
		$errors->add( 'dmd_password_error', __( 'Please create a password for your account.', 'digital-mudir-dokan' ) );
	}
}
add_action( 'woocommerce_register_post', 'dmd_validate_registration', 10, 3 );

/**
 * Copy the extra registration fields onto the new customer.
 *
 * The phone number is stored as `billing_phone` so checkout is pre-filled.
 *
 * @param int $customer_id New customer user ID.
 */
function dmd_save_registration_fields( $customer_id ) {
	// phpcs:disable WordPress.Security.NonceVerification.Missing -- verified by WC_Form_Handler::process_registration().
	$first_name = isset( $_POST['first_name'] ) && is_string( $_POST['first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) : '';
	$phone      = isset( $_POST['billing_phone'] ) && is_string( $_POST['billing_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_phone'] ) ) : '';
	// phpcs:enable

	if ( '' !== $first_name ) {
		update_user_meta( $customer_id, 'first_name', $first_name );
		update_user_meta( $customer_id, 'billing_first_name', $first_name );
	}

	if ( '' !== $phone ) {
		update_user_meta( $customer_id, 'billing_phone', $phone );
	}
}
add_action( 'woocommerce_created_customer', 'dmd_save_registration_fields', 10, 1 );

require DMD_DIR . '/inc/template-functions.php';
require DMD_DIR . '/inc/template-tags.php';
require DMD_DIR . '/inc/class-dmd-nav-walker.php';
require DMD_DIR . '/inc/customizer.php';
require DMD_DIR . '/inc/seo-schema.php';

if ( class_exists( 'WooCommerce' ) ) {
	require DMD_DIR . '/inc/woocommerce.php';
}
