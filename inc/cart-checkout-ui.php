<?php
/**
 * Cart & checkout UI: page themes and editable labels.
 *
 * Options live under Mudir Dokan → Shop & checkout. Labels apply to both the
 * WooCommerce Blocks cart/checkout and the classic PHP templates.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

/**
 * Available page themes (slug => label).
 *
 * @return array
 */
function dmd_cart_checkout_themes() {
	return array(
		'classic' => __( 'Classic — clean table & white cards', 'digital-mudir-dokan' ),
		'compact' => __( 'Compact — tight list, soft panels', 'digital-mudir-dokan' ),
		'market'  => __( 'Market — grocery style, green accents', 'digital-mudir-dokan' ),
	);
}

/**
 * Editable cart/checkout strings: option key => [ admin label, Bangla default, English source for JS i18n ].
 *
 * The third value is the WooCommerce English string replaced via setLocaleData
 * when the option is non-empty. Empty source means attribute-only (block attrs).
 *
 * @return array
 */
function dmd_cart_checkout_label_defs() {
	return array(
		/* Cart */
		'cart_label_items_heading'     => array( __( 'Cart — items heading', 'digital-mudir-dokan' ), __( 'কার্টের পণ্য', 'digital-mudir-dokan' ), 'Products in cart' ),
		'cart_label_product'           => array( __( 'Cart — product column', 'digital-mudir-dokan' ), __( 'পণ্য', 'digital-mudir-dokan' ), 'Product' ),
		'cart_label_details'           => array( __( 'Cart — details column', 'digital-mudir-dokan' ), __( 'বিবরণ', 'digital-mudir-dokan' ), 'Details' ),
		'cart_label_total_col'         => array( __( 'Cart — total column', 'digital-mudir-dokan' ), __( 'মোট', 'digital-mudir-dokan' ), 'Total' ),
		'cart_label_price'             => array( __( 'Cart — price column', 'digital-mudir-dokan' ), __( 'দাম', 'digital-mudir-dokan' ), 'Price' ),
		'cart_label_quantity'          => array( __( 'Cart — quantity column', 'digital-mudir-dokan' ), __( 'পরিমাণ', 'digital-mudir-dokan' ), 'Quantity' ),
		'cart_label_subtotal'          => array( __( 'Cart — subtotal', 'digital-mudir-dokan' ), __( 'সাবটোটাল', 'digital-mudir-dokan' ), 'Subtotal' ),
		'cart_label_totals_heading'    => array( __( 'Cart — order summary heading', 'digital-mudir-dokan' ), __( 'কার্ট সারাংশ', 'digital-mudir-dokan' ), 'Cart totals' ),
		'cart_label_shipping'          => array( __( 'Cart — shipping row', 'digital-mudir-dokan' ), __( 'শিপিং', 'digital-mudir-dokan' ), 'Shipping' ),
		'cart_label_estimated_total'   => array( __( 'Cart — estimated total', 'digital-mudir-dokan' ), __( 'আনুমানিক মোট', 'digital-mudir-dokan' ), 'Estimated total' ),
		'cart_label_coupon'            => array( __( 'Cart — coupons heading', 'digital-mudir-dokan' ), __( 'কুপন যোগ করুন', 'digital-mudir-dokan' ), 'Add coupons' ),
		'cart_label_coupon_placeholder'=> array( __( 'Cart — coupon placeholder', 'digital-mudir-dokan' ), __( 'কুপন কোড', 'digital-mudir-dokan' ), 'Enter code' ),
		'cart_label_apply_coupon'      => array( __( 'Cart — apply coupon button', 'digital-mudir-dokan' ), __( 'প্রয়োগ করুন', 'digital-mudir-dokan' ), 'Apply' ),
		'cart_label_update'            => array( __( 'Cart — update cart button', 'digital-mudir-dokan' ), __( 'কার্ট আপডেট করুন', 'digital-mudir-dokan' ), 'Update cart' ),
		'cart_label_proceed'           => array( __( 'Cart — proceed to checkout', 'digital-mudir-dokan' ), __( 'চেকআউটে যান', 'digital-mudir-dokan' ), 'Proceed to Checkout' ),
		'cart_empty_title'             => array( __( 'Empty cart — title', 'digital-mudir-dokan' ), __( 'আপনার কার্ট খালি', 'digital-mudir-dokan' ), 'Your cart is currently empty!' ),
		'cart_empty_text'              => array( __( 'Empty cart — message', 'digital-mudir-dokan' ), __( 'একটি পণ্য যোগ করলে এখানে দেখা যাবে।', 'digital-mudir-dokan' ), '' ),
		'cart_empty_button'            => array( __( 'Empty cart — shop button', 'digital-mudir-dokan' ), __( 'পণ্য দেখুন', 'digital-mudir-dokan' ), 'Browse store' ),

		/* Checkout sections */
		'checkout_label_contact'           => array( __( 'Checkout — contact information', 'digital-mudir-dokan' ), __( 'যোগাযোগের তথ্য', 'digital-mudir-dokan' ), 'Contact information' ),
		'checkout_label_shipping_address'  => array( __( 'Checkout — shipping address', 'digital-mudir-dokan' ), __( 'শিপিং ঠিকানা', 'digital-mudir-dokan' ), 'Shipping address' ),
		'checkout_label_billing_address'   => array( __( 'Checkout — billing address', 'digital-mudir-dokan' ), __( 'বিলিং ঠিকানা', 'digital-mudir-dokan' ), 'Billing address' ),
		'checkout_label_shipping_options'  => array( __( 'Checkout — shipping options', 'digital-mudir-dokan' ), __( 'শিপিং অপশন', 'digital-mudir-dokan' ), 'Shipping options' ),
		'checkout_label_payment'           => array( __( 'Checkout — payment options', 'digital-mudir-dokan' ), __( 'পেমেন্ট অপশন', 'digital-mudir-dokan' ), 'Payment options' ),
		'checkout_label_order_summary'     => array( __( 'Checkout — order summary', 'digital-mudir-dokan' ), __( 'অর্ডার সারাংশ', 'digital-mudir-dokan' ), 'Order summary' ),
		'checkout_label_coupon'            => array( __( 'Checkout — coupons heading', 'digital-mudir-dokan' ), __( 'কুপন যোগ করুন', 'digital-mudir-dokan' ), 'Add coupons' ),
		'checkout_label_order_note'        => array( __( 'Checkout — order note checkbox', 'digital-mudir-dokan' ), __( 'অর্ডারে নোট যোগ করুন', 'digital-mudir-dokan' ), 'Add a note to your order' ),
		'checkout_label_place_order'       => array( __( 'Checkout — place order button', 'digital-mudir-dokan' ), __( 'অর্ডার করুন', 'digital-mudir-dokan' ), 'Place Order' ),
		'checkout_label_same_as_shipping'  => array( __( 'Checkout — use same address', 'digital-mudir-dokan' ), __( 'বিলিংয়ের জন্য একই ঠিকানা ব্যবহার করুন', 'digital-mudir-dokan' ), 'Use same address for billing' ),
		'checkout_guest_notice'            => array( __( 'Checkout — guest notice', 'digital-mudir-dokan' ), __( 'আপনি অতিথি হিসেবে চেকআউট করছেন।', 'digital-mudir-dokan' ), 'You are currently checking out as a guest.' ),

		/* Checkout fields */
		'checkout_label_email'       => array( __( 'Field — email label', 'digital-mudir-dokan' ), __( 'ইমেইল ঠিকানা', 'digital-mudir-dokan' ), 'Email address' ),
		'checkout_ph_email'          => array( __( 'Field — email placeholder', 'digital-mudir-dokan' ), __( 'name@example.com', 'digital-mudir-dokan' ), '' ),
		'checkout_label_first_name'  => array( __( 'Field — first name', 'digital-mudir-dokan' ), __( 'নামের প্রথম অংশ', 'digital-mudir-dokan' ), 'First name' ),
		'checkout_label_last_name'   => array( __( 'Field — last name', 'digital-mudir-dokan' ), __( 'নামের শেষ অংশ', 'digital-mudir-dokan' ), 'Last name' ),
		'checkout_label_company'     => array( __( 'Field — company', 'digital-mudir-dokan' ), __( 'কোম্পানি (ঐচ্ছিক)', 'digital-mudir-dokan' ), 'Company (optional)' ),
		'checkout_label_country'     => array( __( 'Field — country', 'digital-mudir-dokan' ), __( 'দেশ/অঞ্চল', 'digital-mudir-dokan' ), 'Country/Region' ),
		'checkout_label_address_1'   => array( __( 'Field — address', 'digital-mudir-dokan' ), __( 'ঠিকানা', 'digital-mudir-dokan' ), 'Address' ),
		'checkout_ph_address_1'      => array( __( 'Field — address placeholder', 'digital-mudir-dokan' ), __( 'বাড়ি নং ও রাস্তার নাম', 'digital-mudir-dokan' ), '' ),
		'checkout_label_address_2'   => array( __( 'Field — apartment button', 'digital-mudir-dokan' ), __( '+ ফ্ল্যাট, স্যুট ইত্যাদি যোগ করুন', 'digital-mudir-dokan' ), '+ Add apartment, suite, etc.' ),
		'checkout_label_city'        => array( __( 'Field — city', 'digital-mudir-dokan' ), __( 'শহর', 'digital-mudir-dokan' ), 'City' ),
		'checkout_label_state'       => array( __( 'Field — district / state', 'digital-mudir-dokan' ), __( 'জেলা', 'digital-mudir-dokan' ), 'District' ),
		'checkout_label_postcode'    => array( __( 'Field — postcode', 'digital-mudir-dokan' ), __( 'পোস্টকোড (ঐচ্ছিক)', 'digital-mudir-dokan' ), 'Postal code (optional)' ),
		'checkout_label_phone'       => array( __( 'Field — phone', 'digital-mudir-dokan' ), __( 'মোবাইল নম্বর', 'digital-mudir-dokan' ), 'Phone (optional)' ),
		'checkout_ph_phone'          => array( __( 'Field — phone placeholder', 'digital-mudir-dokan' ), __( '01XXXXXXXXX', 'digital-mudir-dokan' ), '' ),
	);
}

/**
 * Read one cart/checkout label, falling back to its Bangla default.
 *
 * @param string $key Option key.
 * @return string
 */
function dmd_checkout_label( $key ) {
	$defs = dmd_cart_checkout_label_defs();
	$default = isset( $defs[ $key ] ) ? $defs[ $key ][1] : '';
	$value   = dmd_option( $key, $default );
	return is_string( $value ) ? $value : $default;
}

/**
 * Body classes for the selected cart / checkout themes.
 *
 * @param array $classes Body classes.
 * @return array
 */
function dmd_cart_checkout_body_class( $classes ) {
	$themes = array_keys( dmd_cart_checkout_themes() );

	if ( function_exists( 'is_cart' ) && is_cart() ) {
		$theme = dmd_option( 'cart_theme', 'classic' );
		if ( ! in_array( $theme, $themes, true ) ) {
			$theme = 'classic';
		}
		$classes[] = 'dmd-cart-theme-' . $theme;
	}

	if ( function_exists( 'is_checkout' ) && is_checkout() && ! is_wc_endpoint_url() ) {
		$theme = dmd_option( 'checkout_theme', 'classic' );
		if ( ! in_array( $theme, $themes, true ) ) {
			$theme = 'classic';
		}
		$classes[] = 'dmd-checkout-theme-' . $theme;
	}

	return $classes;
}
add_filter( 'body_class', 'dmd_cart_checkout_body_class', 20 );

/**
 * Inject editable titles/buttons into cart & checkout blocks.
 *
 * @param array $parsed_block Block being rendered.
 * @return array
 */
function dmd_cart_checkout_block_attrs( $parsed_block ) {
	static $map = null;

	if ( null === $map ) {
		$map = array(
			'woocommerce/checkout-contact-information-block' => array( 'title' => 'checkout_label_contact' ),
			'woocommerce/checkout-shipping-address-block'    => array( 'title' => 'checkout_label_shipping_address' ),
			'woocommerce/checkout-billing-address-block'     => array( 'title' => 'checkout_label_billing_address' ),
			'woocommerce/checkout-shipping-methods-block'    => array( 'title' => 'checkout_label_shipping_options' ),
			'woocommerce/checkout-payment-block'             => array( 'title' => 'checkout_label_payment' ),
			'woocommerce/checkout-actions-block'             => array( 'placeOrderButtonLabel' => 'checkout_label_place_order' ),
			'woocommerce/cart-order-summary-heading-block'   => array( 'content' => 'cart_label_totals_heading' ),
			'woocommerce/proceed-to-checkout-block'          => array( 'buttonLabel' => 'cart_label_proceed' ),
		);
	}

	$name = isset( $parsed_block['blockName'] ) ? $parsed_block['blockName'] : '';
	if ( ! $name || ! isset( $map[ $name ] ) ) {
		return $parsed_block;
	}

	foreach ( $map[ $name ] as $attr => $option_key ) {
		$value = dmd_checkout_label( $option_key );
		if ( '' !== $value ) {
			$parsed_block['attrs'][ $attr ] = $value;
		}
	}

	return $parsed_block;
}
add_filter( 'render_block_data', 'dmd_cart_checkout_block_attrs' );

/**
 * English source => custom label map for WooCommerce JS/PHP gettext.
 *
 * @return array
 */
function dmd_cart_checkout_i18n_map() {
	static $map = null;
	if ( null !== $map ) {
		return $map;
	}

	$map = array();
	foreach ( dmd_cart_checkout_label_defs() as $key => $def ) {
		$source = isset( $def[2] ) ? $def[2] : '';
		if ( '' === $source ) {
			continue;
		}
		$label = dmd_checkout_label( $key );
		if ( '' !== $label && $label !== $source ) {
			$map[ $source ] = $label;
		}
	}

	// Extra variants WooCommerce uses interchangeably.
	$extras = array(
		'Place order'          => 'checkout_label_place_order',
		'PLACE ORDER'          => 'checkout_label_place_order',
		'Coupon code'          => 'cart_label_coupon_placeholder',
		'Apply coupon'         => 'cart_label_apply_coupon',
		'Your cart is empty'   => 'cart_empty_title',
		'Return to cart'       => 'cart_label_proceed',
		'Phone'                => 'checkout_label_phone',
		'State'                => 'checkout_label_state',
		'Postcode / ZIP'       => 'checkout_label_postcode',
		'Apartment, suite, etc. (optional)' => 'checkout_label_address_2',
	);
	foreach ( $extras as $source => $key ) {
		$label = dmd_checkout_label( $key );
		if ( '' !== $label ) {
			$map[ $source ] = $label;
		}
	}

	return $map;
}

/**
 * Swap WooCommerce PHP strings that match our editable labels.
 *
 * @param string $translation Translated text.
 * @param string $text        Original text.
 * @param string $domain      Text domain.
 * @return string
 */
function dmd_cart_checkout_gettext( $translation, $text, $domain ) {
	if ( 'woocommerce' !== $domain ) {
		return $translation;
	}
	$map = dmd_cart_checkout_i18n_map();
	return isset( $map[ $text ] ) ? $map[ $text ] : $translation;
}
add_filter( 'gettext', 'dmd_cart_checkout_gettext', 20, 3 );

/**
 * Push the same map into WooCommerce block JS before cart/checkout scripts run.
 */
function dmd_cart_checkout_script_i18n() {
	if ( ! function_exists( 'is_cart' ) || ( ! is_cart() && ! is_checkout() ) ) {
		return;
	}

	$map = dmd_cart_checkout_i18n_map();
	if ( ! $map ) {
		return;
	}

	$locale = array( '' => array( 'domain' => 'woocommerce' ) );
	foreach ( $map as $source => $custom ) {
		$locale[ $source ] = array( $custom );
	}

	$js = 'wp.i18n.setLocaleData(' . wp_json_encode( $locale ) . ',"woocommerce");';

	foreach ( array( 'wc-cart-block-frontend', 'wc-checkout-block-frontend', 'wc-cart-checkout-base-frontend' ) as $handle ) {
		if ( wp_script_is( $handle, 'registered' ) || wp_script_is( $handle, 'enqueued' ) ) {
			wp_add_inline_script( $handle, $js, 'before' );
		}
	}

	// Fallback if handles are not yet known: print beside wp-i18n.
	if ( wp_script_is( 'wp-i18n', 'enqueued' ) || wp_script_is( 'wp-i18n', 'registered' ) ) {
		wp_add_inline_script( 'wp-i18n', $js, 'after' );
	}
}
add_action( 'wp_enqueue_scripts', 'dmd_cart_checkout_script_i18n', 100 );

/**
 * Classic + Store API address field labels and placeholders.
 *
 * @param array $fields Default address fields.
 * @return array
 */
function dmd_cart_checkout_address_fields( $fields ) {
	$pairs = array(
		'first_name' => array( 'checkout_label_first_name', '' ),
		'last_name'  => array( 'checkout_label_last_name', '' ),
		'company'    => array( 'checkout_label_company', '' ),
		'country'    => array( 'checkout_label_country', '' ),
		'address_1'  => array( 'checkout_label_address_1', 'checkout_ph_address_1' ),
		'city'       => array( 'checkout_label_city', '' ),
		'state'      => array( 'checkout_label_state', '' ),
		'postcode'   => array( 'checkout_label_postcode', '' ),
	);

	foreach ( $pairs as $key => $opts ) {
		if ( ! isset( $fields[ $key ] ) ) {
			continue;
		}
		$label = dmd_checkout_label( $opts[0] );
		if ( '' !== $label ) {
			$fields[ $key ]['label'] = $label;
		}
		if ( $opts[1] ) {
			$ph = dmd_checkout_label( $opts[1] );
			if ( '' !== $ph ) {
				$fields[ $key ]['placeholder'] = $ph;
			}
		}
	}

	return $fields;
}
add_filter( 'woocommerce_default_address_fields', 'dmd_cart_checkout_address_fields', 30 );

/**
 * Billing email / phone labels for classic checkout fields.
 *
 * @param array $fields Checkout fields.
 * @return array
 */
function dmd_cart_checkout_checkout_fields( $fields ) {
	if ( isset( $fields['billing']['billing_email'] ) ) {
		$email = dmd_checkout_label( 'checkout_label_email' );
		$ph    = dmd_checkout_label( 'checkout_ph_email' );
		if ( '' !== $email ) {
			$fields['billing']['billing_email']['label'] = $email;
		}
		if ( '' !== $ph ) {
			$fields['billing']['billing_email']['placeholder'] = $ph;
		}
	}
	if ( isset( $fields['billing']['billing_phone'] ) ) {
		$phone = dmd_checkout_label( 'checkout_label_phone' );
		$ph    = dmd_checkout_label( 'checkout_ph_phone' );
		if ( '' !== $phone ) {
			$fields['billing']['billing_phone']['label'] = $phone;
		}
		if ( '' !== $ph ) {
			$fields['billing']['billing_phone']['placeholder'] = $ph;
		}
	}
	return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'dmd_cart_checkout_checkout_fields', 40 );
