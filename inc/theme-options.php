<?php
/**
 * Theme options registry.
 *
 * Everything on the Digital Mudir Dokan admin screen is stored in a single
 * `dmd_options` array. Design and homepage content stay in the Customizer;
 * these options cover pages, shop behaviour and integrations.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

/**
 * Settings tabs on the theme dashboard, keyed by slug.
 *
 * @return array
 */
function dmd_option_tabs() {
	return array(
		'general'      => array(
			'label' => __( 'General', 'digital-mudir-dokan' ),
			'icon'  => 'dashicons-admin-generic',
		),
		'pages'        => array(
			'label' => __( 'Pages', 'digital-mudir-dokan' ),
			'icon'  => 'dashicons-admin-page',
		),
		'about'        => array(
			'label' => __( 'About page', 'digital-mudir-dokan' ),
			'icon'  => 'dashicons-groups',
		),
		'contact'      => array(
			'label' => __( 'Contact page', 'digital-mudir-dokan' ),
			'icon'  => 'dashicons-email-alt',
		),
		'shop'         => array(
			'label' => __( 'Shop & checkout', 'digital-mudir-dokan' ),
			'icon'  => 'dashicons-cart',
		),
		'integrations' => array(
			'label' => __( 'Integrations', 'digital-mudir-dokan' ),
			'icon'  => 'dashicons-chart-area',
		),
	);
}

/**
 * Field definitions per tab.
 *
 * Types: heading, checkbox, text, textarea, email, number, price, page, image, map, code, ga4, pixel.
 *
 * @return array
 */
function dmd_option_fields() {
	$fields = array(
		'general'      => array(
			'h_features'          => array(
				'type'  => 'heading',
				'label' => __( 'Site features', 'digital-mudir-dokan' ),
			),
			'back_to_top'         => array(
				'type'    => 'checkbox',
				'label'   => __( 'Back-to-top button', 'digital-mudir-dokan' ),
				'desc'    => __( 'Shows a round button in the bottom-left corner after scrolling down.', 'digital-mudir-dokan' ),
				'default' => 1,
			),
			'footer_policy_links' => array(
				'type'    => 'checkbox',
				'label'   => __( 'Policy links in the footer', 'digital-mudir-dokan' ),
				'desc'    => __( 'Lists the privacy, terms, return and delivery pages chosen on the Pages tab in the footer bottom bar.', 'digital-mudir-dokan' ),
				'default' => 1,
			),
			'disable_emojis'      => array(
				'type'    => 'checkbox',
				'label'   => __( 'Remove the WordPress emoji script', 'digital-mudir-dokan' ),
				'desc'    => __( 'Saves one script and one stylesheet on every page. Emojis still show using the visitor\'s own fonts.', 'digital-mudir-dokan' ),
				'default' => 1,
			),
			'h_maintenance'       => array(
				'type'  => 'heading',
				'label' => __( 'Maintenance mode', 'digital-mudir-dokan' ),
			),
			'maintenance_mode'    => array(
				'type'    => 'checkbox',
				'label'   => __( 'Turn on maintenance mode', 'digital-mudir-dokan' ),
				'desc'    => __( 'Visitors see a "back soon" page with your hotline. Logged-in administrators and editors still see the full site.', 'digital-mudir-dokan' ),
				'default' => 0,
			),
			'maintenance_title'   => array(
				'type'    => 'text',
				'label'   => __( 'Heading', 'digital-mudir-dokan' ),
				'default' => __( 'আমরা শীঘ্রই ফিরছি', 'digital-mudir-dokan' ),
			),
			'maintenance_message' => array(
				'type'    => 'textarea',
				'label'   => __( 'Message', 'digital-mudir-dokan' ),
				'default' => __( 'ওয়েবসাইটে কিছু উন্নয়নের কাজ চলছে। অর্ডার করতে আমাদের হটলাইনে কল করুন অথবা WhatsApp করুন।', 'digital-mudir-dokan' ),
			),
		),
		'pages'        => array(
			'page_about'      => array(
				'type'  => 'page',
				'label' => __( 'About page', 'digital-mudir-dokan' ),
			),
			'page_contact'    => array(
				'type'  => 'page',
				'label' => __( 'Contact page', 'digital-mudir-dokan' ),
			),
			'page_categories' => array(
				'type'  => 'page',
				'label' => __( 'All categories page', 'digital-mudir-dokan' ),
				'desc'  => __( 'Used by the "All categories" links on the homepage and in the footer.', 'digital-mudir-dokan' ),
			),
			'page_privacy'    => array(
				'type'  => 'page',
				'label' => __( 'Privacy policy', 'digital-mudir-dokan' ),
			),
			'page_terms'      => array(
				'type'  => 'page',
				'label' => __( 'Terms & conditions', 'digital-mudir-dokan' ),
			),
			'page_returns'    => array(
				'type'  => 'page',
				'label' => __( 'Return & refund policy', 'digital-mudir-dokan' ),
			),
			'page_delivery'   => array(
				'type'  => 'page',
				'label' => __( 'Delivery information', 'digital-mudir-dokan' ),
			),
		),
		'about'        => array(
			'h_intro'        => array(
				'type'  => 'heading',
				'label' => __( 'Introduction', 'digital-mudir-dokan' ),
			),
			'about_eyebrow'  => array(
				'type'    => 'text',
				'label'   => __( 'Small label above the title', 'digital-mudir-dokan' ),
				'default' => __( 'আমাদের সম্পর্কে', 'digital-mudir-dokan' ),
			),
			'about_title'    => array(
				'type'    => 'text',
				'label'   => __( 'Title', 'digital-mudir-dokan' ),
				'default' => __( 'বিশ্বস্ত মুদি দোকান, এখন আপনার হাতের মুঠোয়', 'digital-mudir-dokan' ),
			),
			'about_intro'    => array(
				'type'    => 'textarea',
				'label'   => __( 'Introduction', 'digital-mudir-dokan' ),
				'default' => __( 'চাল, ডাল, তেল থেকে শুরু করে প্রতিদিনের প্রয়োজনীয় সব মুদি পণ্য আমরা সরাসরি বাছাই করে আপনার দরজায় পৌঁছে দিই। খাঁটি পণ্য, ন্যায্য দাম এবং দ্রুত ডেলিভারি — এটাই আমাদের প্রতিশ্রুতি।', 'digital-mudir-dokan' ),
			),
			'about_image'    => array(
				'type'  => 'image',
				'label' => __( 'Image', 'digital-mudir-dokan' ),
				'desc'  => __( 'Landscape photo of your shop or team, at least 1200 × 900 px. Falls back to the page\'s featured image.', 'digital-mudir-dokan' ),
			),
			'h_stats'        => array(
				'type'  => 'heading',
				'label' => __( 'Numbers', 'digital-mudir-dokan' ),
				'desc'  => __( 'Leave a value empty to hide that card.', 'digital-mudir-dokan' ),
			),
			'h_mission'      => array(
				'type'  => 'heading',
				'label' => __( 'Mission & vision', 'digital-mudir-dokan' ),
			),
			'about_mission'  => array(
				'type'    => 'textarea',
				'label'   => __( 'Our mission', 'digital-mudir-dokan' ),
				'default' => __( 'প্রতিটি পরিবারের কাছে খাঁটি ও মানসম্মত মুদি পণ্য সাশ্রয়ী দামে সহজে পৌঁছে দেওয়া।', 'digital-mudir-dokan' ),
			),
			'about_vision'   => array(
				'type'    => 'textarea',
				'label'   => __( 'Our vision', 'digital-mudir-dokan' ),
				'default' => __( 'বাংলাদেশের সবচেয়ে বিশ্বস্ত অনলাইন মুদি দোকান হয়ে ওঠা, যেখানে কেনাকাটা হবে নিশ্চিন্ত ও আনন্দময়।', 'digital-mudir-dokan' ),
			),
			'h_sections'     => array(
				'type'  => 'heading',
				'label' => __( 'Extra sections', 'digital-mudir-dokan' ),
			),
			'about_show_trust' => array(
				'type'    => 'checkbox',
				'label'   => __( 'Show the service highlights row', 'digital-mudir-dokan' ),
				'desc'    => __( 'The same four cards as the homepage (edit them in the Customizer).', 'digital-mudir-dokan' ),
				'default' => 1,
			),
			'about_show_cta' => array(
				'type'    => 'checkbox',
				'label'   => __( 'Show the "order by phone" band', 'digital-mudir-dokan' ),
				'default' => 1,
			),
		),
		'contact'      => array(
			'contact_title'     => array(
				'type'    => 'text',
				'label'   => __( 'Title', 'digital-mudir-dokan' ),
				'default' => __( 'যোগাযোগ করুন', 'digital-mudir-dokan' ),
			),
			'contact_intro'     => array(
				'type'    => 'textarea',
				'label'   => __( 'Introduction', 'digital-mudir-dokan' ),
				'default' => __( 'অর্ডার, ডেলিভারি বা যেকোনো প্রশ্নে আমাদের সাথে যোগাযোগ করুন। আমরা দ্রুত উত্তর দেওয়ার চেষ্টা করি।', 'digital-mudir-dokan' ),
			),
			'contact_hours'     => array(
				'type'    => 'textarea',
				'label'   => __( 'Opening hours', 'digital-mudir-dokan' ),
				'desc'    => __( 'One line per row, e.g. "Sat – Thu: 9am – 10pm".', 'digital-mudir-dokan' ),
				'default' => __( "শনি – বৃহস্পতি: সকাল ৯টা – রাত ১০টা\nশুক্রবার: বিকাল ৩টা – রাত ১০টা", 'digital-mudir-dokan' ),
			),
			'contact_map'       => array(
				'type'  => 'map',
				'label' => __( 'Google Maps embed', 'digital-mudir-dokan' ),
				'desc'  => __( 'In Google Maps choose Share → Embed a map, then paste the whole <iframe> code or just its src link.', 'digital-mudir-dokan' ),
			),
			'h_form'            => array(
				'type'  => 'heading',
				'label' => __( 'Contact form', 'digital-mudir-dokan' ),
			),
			'contact_form'      => array(
				'type'    => 'checkbox',
				'label'   => __( 'Show the contact form', 'digital-mudir-dokan' ),
				'default' => 1,
			),
			'contact_recipient' => array(
				'type'  => 'email',
				'label' => __( 'Send messages to', 'digital-mudir-dokan' ),
				'desc'  => __( 'Leave empty to use the site administration email.', 'digital-mudir-dokan' ),
			),
			'contact_success'   => array(
				'type'    => 'text',
				'label'   => __( 'Thank-you message', 'digital-mudir-dokan' ),
				'default' => __( 'ধন্যবাদ! আপনার বার্তা পাঠানো হয়েছে। আমরা শীঘ্রই যোগাযোগ করব।', 'digital-mudir-dokan' ),
			),
		),
		'shop'         => array(
			'h_cart'               => array(
				'type'  => 'heading',
				'label' => __( 'Cart & checkout', 'digital-mudir-dokan' ),
			),
			'min_order_amount'     => array(
				'type'    => 'price',
				'label'   => __( 'Minimum order amount', 'digital-mudir-dokan' ),
				'desc'    => __( 'Checkout is blocked until the cart subtotal reaches this amount. 0 turns it off.', 'digital-mudir-dokan' ),
				'default' => 0,
			),
			'free_delivery_amount' => array(
				'type'    => 'price',
				'label'   => __( 'Free delivery from', 'digital-mudir-dokan' ),
				'desc'    => __( 'Shows a progress bar on the cart and checkout. Match it to the minimum amount of your WooCommerce "Free shipping" method. 0 turns it off.', 'digital-mudir-dokan' ),
				'default' => 0,
			),
			'simple_checkout'      => array(
				'type'    => 'checkbox',
				'label'   => __( 'Simplified checkout', 'digital-mudir-dokan' ),
				'desc'    => __( 'Removes company, address line 2 and postcode, and makes email optional — only name, phone and address are required.', 'digital-mudir-dokan' ),
				'default' => 0,
			),
			'order_notes'          => array(
				'type'    => 'checkbox',
				'label'   => __( 'Order notes field', 'digital-mudir-dokan' ),
				'default' => 1,
			),
			'h_page_themes'        => array(
				'type'  => 'heading',
				'label' => __( 'Cart & checkout page themes', 'digital-mudir-dokan' ),
				'desc'  => __( 'Pick a look for each page. Labels below stay editable in Bangla or English.', 'digital-mudir-dokan' ),
			),
			'cart_theme'           => array(
				'type'    => 'select',
				'label'   => __( 'Cart page theme', 'digital-mudir-dokan' ),
				'default' => 'classic',
				'choices' => function_exists( 'dmd_cart_checkout_themes' ) ? dmd_cart_checkout_themes() : array( 'classic' => __( 'Classic', 'digital-mudir-dokan' ) ),
			),
			'checkout_theme'       => array(
				'type'    => 'select',
				'label'   => __( 'Checkout page theme', 'digital-mudir-dokan' ),
				'default' => 'classic',
				'choices' => function_exists( 'dmd_cart_checkout_themes' ) ? dmd_cart_checkout_themes() : array( 'classic' => __( 'Classic', 'digital-mudir-dokan' ) ),
			),
			'h_product'            => array(
				'type'  => 'heading',
				'label' => __( 'Product page', 'digital-mudir-dokan' ),
			),
			'whatsapp_order'       => array(
				'type'    => 'checkbox',
				'label'   => __( '"Order on WhatsApp" button', 'digital-mudir-dokan' ),
				'desc'    => __( 'Opens a WhatsApp chat with the product name, price and link filled in. Uses the WhatsApp number from Customizer → Shop details.', 'digital-mudir-dokan' ),
				'default' => 1,
			),
			'whatsapp_message'     => array(
				'type'    => 'text',
				'label'   => __( 'WhatsApp message start', 'digital-mudir-dokan' ),
				'default' => __( 'আসসালামু আলাইকুম, আমি এই পণ্যটি অর্ডার করতে চাই:', 'digital-mudir-dokan' ),
			),
			'low_stock_amount'     => array(
				'type'    => 'number',
				'label'   => __( 'Low-stock warning at', 'digital-mudir-dokan' ),
				'desc'    => __( 'Shows "Only X left" when a stock-managed product has this many or fewer. 0 turns it off.', 'digital-mudir-dokan' ),
				'default' => 5,
			),
			'h_catalog'            => array(
				'type'  => 'heading',
				'label' => __( 'Catalog', 'digital-mudir-dokan' ),
			),
			'out_of_stock_last'    => array(
				'type'    => 'checkbox',
				'label'   => __( 'Show out-of-stock products last', 'digital-mudir-dokan' ),
				'desc'    => __( 'Applies to the shop, category and search pages, whatever sorting the visitor picks.', 'digital-mudir-dokan' ),
				'default' => 1,
			),
		),
		'integrations' => array(
			'h_tracking'    => array(
				'type'  => 'heading',
				'label' => __( 'Analytics', 'digital-mudir-dokan' ),
				'desc'  => __( 'Tracking is skipped for logged-in administrators so your own visits are not counted. Purchases are reported automatically on the order-received page.', 'digital-mudir-dokan' ),
			),
			'ga4_id'        => array(
				'type'        => 'ga4',
				'label'       => __( 'Google Analytics 4 measurement ID', 'digital-mudir-dokan' ),
				'placeholder' => 'G-XXXXXXXXXX',
			),
			'meta_pixel_id' => array(
				'type'        => 'pixel',
				'label'       => __( 'Meta (Facebook) Pixel ID', 'digital-mudir-dokan' ),
				'placeholder' => '123456789012345',
			),
			'h_code'        => array(
				'type'  => 'heading',
				'label' => __( 'Custom code', 'digital-mudir-dokan' ),
				'desc'  => __( 'For verification tags or chat widgets. Only administrators allowed to post unfiltered HTML can change these.', 'digital-mudir-dokan' ),
			),
			'header_code'   => array(
				'type'  => 'code',
				'label' => __( 'Code before </head>', 'digital-mudir-dokan' ),
			),
			'footer_code'   => array(
				'type'  => 'code',
				'label' => __( 'Code before </body>', 'digital-mudir-dokan' ),
			),
		),
	);

	$stats = array(
		1 => array( __( '১০,০০০+', 'digital-mudir-dokan' ), __( 'সন্তুষ্ট গ্রাহক', 'digital-mudir-dokan' ) ),
		2 => array( __( '৫০০+', 'digital-mudir-dokan' ), __( 'বাছাই করা পণ্য', 'digital-mudir-dokan' ) ),
		3 => array( __( '৩০ মিনিট', 'digital-mudir-dokan' ), __( 'দ্রুত ডেলিভারি', 'digital-mudir-dokan' ) ),
		4 => array( __( '১০০%', 'digital-mudir-dokan' ), __( 'খাঁটি পণ্যের নিশ্চয়তা', 'digital-mudir-dokan' ) ),
	);

	$stat_fields = array();
	foreach ( $stats as $i => $stat ) {
		$stat_fields[ 'about_stat_' . $i . '_value' ] = array(
			'type'    => 'text',
			/* translators: %d: card number. */
			'label'   => sprintf( __( 'Card %d — number', 'digital-mudir-dokan' ), $i ),
			'default' => $stat[0],
			'half'    => true,
		);
		$stat_fields[ 'about_stat_' . $i . '_label' ] = array(
			'type'    => 'text',
			/* translators: %d: card number. */
			'label'   => sprintf( __( 'Card %d — label', 'digital-mudir-dokan' ), $i ),
			'default' => $stat[1],
			'half'    => true,
		);
	}

	$about           = $fields['about'];
	$position        = array_search( 'h_stats', array_keys( $about ), true ) + 1;
	$fields['about'] = array_slice( $about, 0, $position, true ) + $stat_fields + array_slice( $about, $position, null, true );

	// Cart & checkout editable labels (defined in inc/cart-checkout-ui.php).
	if ( function_exists( 'dmd_cart_checkout_label_defs' ) ) {
		$label_fields = array(
			'h_cart_labels' => array(
				'type'  => 'heading',
				'label' => __( 'Cart page labels', 'digital-mudir-dokan' ),
				'desc'  => __( 'Every heading and button on the cart page. Leave empty to use the Bangla default.', 'digital-mudir-dokan' ),
			),
		);
		$checkout_heading_added = false;
		foreach ( dmd_cart_checkout_label_defs() as $id => $def ) {
			if ( ! $checkout_heading_added && 0 === strpos( $id, 'checkout_' ) ) {
				$label_fields['h_checkout_labels'] = array(
					'type'  => 'heading',
					'label' => __( 'Checkout page labels', 'digital-mudir-dokan' ),
					'desc'  => __( 'Section titles, order summary, buttons and form fields on checkout.', 'digital-mudir-dokan' ),
				);
				$checkout_heading_added = true;
			}
			$label_fields[ $id ] = array(
				'type'    => 'text',
				'label'   => $def[0],
				'default' => $def[1],
				'half'    => true,
			);
		}

		$shop     = $fields['shop'];
		$insert   = array_search( 'h_product', array_keys( $shop ), true );
		if ( false === $insert ) {
			$fields['shop'] = $shop + $label_fields;
		} else {
			$fields['shop'] = array_slice( $shop, 0, $insert, true ) + $label_fields + array_slice( $shop, $insert, null, true );
		}
	}

	return apply_filters( 'dmd_option_fields', $fields );
}

/**
 * Default value of every option.
 *
 * @return array
 */
function dmd_option_defaults() {
	$defaults = array();
	foreach ( dmd_option_fields() as $fields ) {
		foreach ( $fields as $id => $field ) {
			if ( 'heading' !== $field['type'] ) {
				$defaults[ $id ] = isset( $field['default'] ) ? $field['default'] : '';
			}
		}
	}
	return $defaults;
}

/**
 * Read one theme option.
 *
 * @param string $key      Option key.
 * @param mixed  $fallback Value when the option was never saved; defaults to the field default.
 * @return mixed
 */
function dmd_option( $key, $fallback = null ) {
	$options = get_option( 'dmd_options', array() );

	if ( is_array( $options ) && array_key_exists( $key, $options ) ) {
		return $options[ $key ];
	}

	if ( null !== $fallback ) {
		return $fallback;
	}

	static $defaults = null;
	if ( null === $defaults ) {
		$defaults = dmd_option_defaults();
	}

	return isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
}

/**
 * Published page ID chosen for a page slot on the Pages tab, or 0.
 *
 * @param string $slot about, contact, categories, privacy, terms, returns or delivery.
 * @return int
 */
function dmd_page_id( $slot ) {
	$id = absint( dmd_option( 'page_' . $slot, 0 ) );

	if ( ! $id && 'privacy' === $slot ) {
		$id = absint( get_option( 'wp_page_for_privacy_policy' ) );
	}

	if ( ! $id && 'terms' === $slot && function_exists( 'wc_terms_and_conditions_page_id' ) ) {
		$id = absint( wc_terms_and_conditions_page_id() );
	}

	if ( ! $id && 'returns' === $slot ) {
		$id = absint( get_option( 'woocommerce_refund_returns_page_id' ) );
	}

	return ( $id && 'publish' === get_post_status( $id ) ) ? $id : 0;
}

/**
 * Policy pages for the footer, as label => URL.
 *
 * @return array
 */
function dmd_policy_links() {
	$links = array();
	foreach ( array( 'privacy', 'terms', 'returns', 'delivery' ) as $slot ) {
		$id = dmd_page_id( $slot );
		if ( $id ) {
			$links[ get_the_title( $id ) ] = get_permalink( $id );
		}
	}
	return $links;
}
