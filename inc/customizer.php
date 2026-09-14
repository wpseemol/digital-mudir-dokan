<?php
/**
 * Customizer options.
 *
 * Everything an owner needs to launch the shop lives here: the announcement bar,
 * hero slides, homepage sections, contact details and social profiles.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register settings.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 */
function dmd_customize_register( $wp_customize ) {

	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	$wp_customize->add_panel(
		'dmd_panel',
		array(
			'title'    => __( 'Digital Mudir Dokan', 'digital-mudir-dokan' ),
			'priority' => 20,
		)
	);

	/* ---------------------------------------------------------------
	 * Shop & Category Settings
	 * --------------------------------------------------------------- */
	$wp_customize->add_section(
		'dmd_catalog_settings',
		array(
			'title' => __( 'Shop & Category Settings', 'digital-mudir-dokan' ),
			'panel' => 'dmd_panel',
		)
	);

	$wp_customize->add_setting(
		'dmd_archive_grid_columns',
		array( 'default' => '4', 'sanitize_callback' => 'sanitize_text_field' )
	);
	$wp_customize->add_control(
		'dmd_archive_grid_columns',
		array(
			'label'   => __( 'Grid Columns', 'digital-mudir-dokan' ),
			'section' => 'dmd_catalog_settings',
			'type'    => 'select',
			'choices' => array(
				'3' => __( '3 Columns Grid', 'digital-mudir-dokan' ),
				'4' => __( '4 Columns Grid', 'digital-mudir-dokan' ),
			),
		)
	);

	$wp_customize->add_setting(
		'dmd_shop_products_per_page',
		array( 'default' => 12, 'sanitize_callback' => 'absint' )
	);
	$wp_customize->add_control(
		'dmd_shop_products_per_page',
		array(
			'label'       => __( 'Shop Page Products Per Page', 'digital-mudir-dokan' ),
			'section'     => 'dmd_catalog_settings',
			'type'        => 'number',
			'input_attrs' => array( 'min' => 4, 'max' => 48, 'step' => 4 ),
		)
	);

	$wp_customize->add_setting(
		'dmd_category_products_per_page',
		array( 'default' => 12, 'sanitize_callback' => 'absint' )
	);
	$wp_customize->add_control(
		'dmd_category_products_per_page',
		array(
			'label'       => __( 'Category Archive Products Per Page', 'digital-mudir-dokan' ),
			'section'     => 'dmd_catalog_settings',
			'type'        => 'number',
			'input_attrs' => array( 'min' => 4, 'max' => 48, 'step' => 4 ),
		)
	);

	/* ---------------------------------------------------------------
	 * Shop settings
	 * --------------------------------------------------------------- */
	$wp_customize->add_section(
		'dmd_shop_settings',
		array(
			'title' => __( 'Shop settings', 'digital-mudir-dokan' ),
			'panel' => 'dmd_panel',
		)
	);

	$wp_customize->add_setting(
		'dmd_default_product_placeholder',
		array( 'sanitize_callback' => 'esc_url_raw' )
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'dmd_default_product_placeholder',
			array(
				'label'       => __( 'Default Product Card Image (যদি প্রোডাক্টের ছবি না থাকে)', 'digital-mudir-dokan' ),
				'description' => __( 'Upload a fallback image/logo to display on product cards when a product has no featured image uploaded.', 'digital-mudir-dokan' ),
				'section'     => 'dmd_shop_settings',
			)
		)
	);

	/* ---------------------------------------------------------------
	 * Header
	 * --------------------------------------------------------------- */
	$wp_customize->add_section(
		'dmd_header',
		array(
			'title' => __( 'Header', 'digital-mudir-dokan' ),
			'panel' => 'dmd_panel',
		)
	);

	$wp_customize->add_setting(
		'dmd_logo_alignment',
		array(
			'default'           => 'center',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'dmd_logo_alignment',
		array(
			'label'   => __( 'Logo alignment', 'digital-mudir-dokan' ),
			'section' => 'dmd_header',
			'type'    => 'radio',
			'choices' => array(
				'left'   => __( 'Left', 'digital-mudir-dokan' ),
				'center' => __( 'Center', 'digital-mudir-dokan' ),
				'right'  => __( 'Right', 'digital-mudir-dokan' ),
			),
		)
	);

	$wp_customize->add_setting(
		'dmd_logo_width',
		array(
			'default'           => 180,
			'sanitize_callback' => 'absint',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'dmd_logo_width',
		array(
			'label'       => __( 'Logo width (px)', 'digital-mudir-dokan' ),
			'section'     => 'dmd_header',
			'type'        => 'range',
			'input_attrs' => array(
				'min'  => 50,
				'max'  => 400,
				'step' => 5,
			),
		)
	);

	/* ---------------------------------------------------------------
	 * Announcement bar
	 * --------------------------------------------------------------- */
	$wp_customize->add_section(
		'dmd_topbar',
		array(
			'title' => __( 'Announcement bar', 'digital-mudir-dokan' ),
			'panel' => 'dmd_panel',
		)
	);

	$wp_customize->add_setting(
		'dmd_topbar_text',
		array(
			'default'           => __( 'আমাদের যে কোন পণ্য অর্ডার করতে WhatsApp করুন', 'digital-mudir-dokan' ),
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'dmd_topbar_text',
		array(
			'label'   => __( 'Message', 'digital-mudir-dokan' ),
			'section' => 'dmd_topbar',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'dmd_topbar_show',
		array(
			'default'           => true,
			'sanitize_callback' => 'dmd_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'dmd_topbar_show',
		array(
			'label'   => __( 'Show the announcement bar', 'digital-mudir-dokan' ),
			'section' => 'dmd_topbar',
			'type'    => 'checkbox',
		)
	);

	/* ---------------------------------------------------------------
	 * Hero slider
	 * --------------------------------------------------------------- */
	$wp_customize->add_section(
		'dmd_hero',
		array(
			'title'       => __( 'Hero slider / Banner', 'digital-mudir-dokan' ),
			'panel'       => 'dmd_panel',
			'description' => __( 'Add up to five slides. A slide appears once it has an image or a headline.', 'digital-mudir-dokan' ),
		)
	);

	$wp_customize->add_setting(
		'dmd_slider_width',
		array(
			'default'           => 'container',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'dmd_slider_width',
		array(
			'label'   => __( 'Container Width Mode', 'digital-mudir-dokan' ),
			'section' => 'dmd_hero',
			'type'    => 'select',
			'choices' => array(
				'container' => __( 'Boxed / Centered', 'digital-mudir-dokan' ),
				'full'      => __( 'Full width', 'digital-mudir-dokan' ),
			),
		)
	);

	$wp_customize->add_setting(
		'dmd_hero_autoplay',
		array(
			'default'           => true,
			'sanitize_callback' => 'dmd_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'dmd_hero_autoplay',
		array(
			'label'   => __( 'Enable autoplay', 'digital-mudir-dokan' ),
			'section' => 'dmd_hero',
			'type'    => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'dmd_slider_delay',
		array(
			'default'           => 4000,
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'dmd_slider_delay',
		array(
			'label'       => __( 'Autoplay Delay / Slide Duration (ms)', 'digital-mudir-dokan' ),
			'section'     => 'dmd_hero',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 1000,
				'max'  => 10000,
				'step' => 500,
			),
		)
	);

	$wp_customize->add_setting(
		'dmd_slider_speed',
		array(
			'default'           => 800,
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'dmd_slider_speed',
		array(
			'label'       => __( 'Transition Slide Speed (ms)', 'digital-mudir-dokan' ),
			'section'     => 'dmd_hero',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 300,
				'max'  => 3000,
				'step' => 100,
			),
		)
	);

	$wp_customize->add_setting( 'dmd_hero_btn_bg', array( 'default' => '#113D21', 'sanitize_callback' => 'sanitize_hex_color' ) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dmd_hero_btn_bg', array( 'label' => __( 'Hero button background', 'digital-mudir-dokan' ), 'section' => 'dmd_hero' ) ) );

	$wp_customize->add_setting( 'dmd_hero_btn_text', array( 'default' => '#ffffff', 'sanitize_callback' => 'sanitize_hex_color' ) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dmd_hero_btn_text', array( 'label' => __( 'Hero button text color', 'digital-mudir-dokan' ), 'section' => 'dmd_hero' ) ) );

	$wp_customize->add_setting( 'dmd_hero_nav_bg', array( 'default' => '#ffffff', 'sanitize_callback' => 'sanitize_hex_color' ) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dmd_hero_nav_bg', array( 'label' => __( 'Hero navigation button color', 'digital-mudir-dokan' ), 'section' => 'dmd_hero' ) ) );

	$wp_customize->add_setting( 'dmd_hero_headline_color', array( 'default' => '#ffffff', 'sanitize_callback' => 'sanitize_hex_color' ) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dmd_hero_headline_color', array( 'label' => __( 'Hero headline text color', 'digital-mudir-dokan' ), 'section' => 'dmd_hero' ) ) );

	$wp_customize->add_setting( 'dmd_hero_subtitle_color', array( 'default' => '#ffffff', 'sanitize_callback' => 'sanitize_hex_color' ) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dmd_hero_subtitle_color', array( 'label' => __( 'Hero supporting line text color', 'digital-mudir-dokan' ), 'section' => 'dmd_hero' ) ) );

	// Fallback banner
	$wp_customize->add_setting( 'dmd_hero_fallback_image', array( 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'dmd_hero_fallback_image',
			array( 'label' => __( 'Fallback Banner Image', 'digital-mudir-dokan' ), 'section' => 'dmd_hero' )
		)
	);
	$wp_customize->add_setting( 'dmd_hero_fallback_title', array( 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'dmd_hero_fallback_title', array( 'label' => __( 'Fallback Title', 'digital-mudir-dokan' ), 'section' => 'dmd_hero', 'type' => 'text' ) );
	$wp_customize->add_setting( 'dmd_hero_fallback_url', array( 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( 'dmd_hero_fallback_url', array( 'label' => __( 'Fallback Link', 'digital-mudir-dokan' ), 'section' => 'dmd_hero', 'type' => 'url' ) );

	for ( $i = 1; $i <= 5; $i++ ) {
		$wp_customize->add_setting(
			"dmd_slide_{$i}_image",
			array( 'sanitize_callback' => 'esc_url_raw' )
		);
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				"dmd_slide_{$i}_image",
				array(
					/* translators: %d: slide number. */
					'label'   => sprintf( __( 'Slide %d — image (Recommended: 1600x620px)', 'digital-mudir-dokan' ), $i ),
					'section' => 'dmd_hero',
				)
			)
		);

		$wp_customize->add_setting(
			"dmd_slide_{$i}_title",
			array( 'sanitize_callback' => 'sanitize_text_field' )
		);
		$wp_customize->add_control(
			"dmd_slide_{$i}_title",
			array(
				/* translators: %d: slide number. */
				'label'   => sprintf( __( 'Slide %d — headline', 'digital-mudir-dokan' ), $i ),
				'section' => 'dmd_hero',
				'type'    => 'text',
			)
		);

		$wp_customize->add_setting(
			"dmd_slide_{$i}_subtitle",
			array( 'sanitize_callback' => 'sanitize_text_field' )
		);
		$wp_customize->add_control(
			"dmd_slide_{$i}_subtitle",
			array(
				/* translators: %d: slide number. */
				'label'   => sprintf( __( 'Slide %d — supporting line', 'digital-mudir-dokan' ), $i ),
				'section' => 'dmd_hero',
				'type'    => 'text',
			)
		);

		$wp_customize->add_setting(
			"dmd_slide_{$i}_cta_text",
			array( 'sanitize_callback' => 'sanitize_text_field' )
		);
		$wp_customize->add_control(
			"dmd_slide_{$i}_cta_text",
			array(
				/* translators: %d: slide number. */
				'label'   => sprintf( __( 'Slide %d — button label', 'digital-mudir-dokan' ), $i ),
				'section' => 'dmd_hero',
				'type'    => 'text',
			)
		);

		$wp_customize->add_setting(
			"dmd_slide_{$i}_cta_url",
			array( 'sanitize_callback' => 'esc_url_raw' )
		);
		$wp_customize->add_control(
			"dmd_slide_{$i}_cta_url",
			array(
				/* translators: %d: slide number. */
				'label'   => sprintf( __( 'Slide %d — button link', 'digital-mudir-dokan' ), $i ),
				'section' => 'dmd_hero',
				'type'    => 'url',
			)
		);
	}

	/* ---------------------------------------------------------------
	 * Homepage sections
	 * --------------------------------------------------------------- */
	$wp_customize->add_section(
		'dmd_homepage',
		array(
			'title' => __( 'Homepage sections', 'digital-mudir-dokan' ),
			'panel' => 'dmd_panel',
		)
	);

	// Settings for Product Sections
	$product_sections = [
		'dmd_top_selling_title' => 'Top Selling / Featured Title',
		'dmd_all_products_title' => 'All Products Title',
	];

	foreach ($product_sections as $id => $label) {
		$wp_customize->add_setting($id, array('default' => 'Our Products', 'sanitize_callback' => 'sanitize_text_field'));
		$wp_customize->add_control($id, array('label' => __($label, 'digital-mudir-dokan'), 'section' => 'dmd_homepage', 'type' => 'text'));
	}

	$wp_customize->add_setting('dmd_show_category_tabs', array('default' => true, 'sanitize_callback' => 'dmd_sanitize_checkbox'));
	$wp_customize->add_control('dmd_show_category_tabs', array('label' => __('Show Category Tabs', 'digital-mudir-dokan'), 'section' => 'dmd_homepage', 'type' => 'checkbox'));

	$wp_customize->add_setting('dmd_show_view_all_link', array('default' => true, 'sanitize_callback' => 'dmd_sanitize_checkbox'));
	$wp_customize->add_control('dmd_show_view_all_link', array('label' => __('Show View All link', 'digital-mudir-dokan'), 'section' => 'dmd_homepage', 'type' => 'checkbox'));

	$wp_customize->add_setting('dmd_top_selling_count', array('default' => 4, 'sanitize_callback' => 'absint'));
	$wp_customize->add_control('dmd_top_selling_count', array('label' => __('Top Selling Product Count Limit', 'digital-mudir-dokan'), 'section' => 'dmd_homepage', 'type' => 'number'));

	$wp_customize->add_setting('dmd_product_count', array('default' => 12, 'sanitize_callback' => 'absint'));
	$wp_customize->add_control('dmd_product_count', array('label' => __('Product Count Limit', 'digital-mudir-dokan'), 'section' => 'dmd_homepage', 'type' => 'number'));

	$wp_customize->add_section(
		'dmd_faq',
		array(
			'title' => __( 'FAQ Section', 'digital-mudir-dokan' ),
			'panel' => 'dmd_panel',
		)
	);

	for ( $i = 1; $i <= 5; $i++ ) {
		$wp_customize->add_setting( "dmd_faq_{$i}_q", array( 'sanitize_callback' => 'sanitize_text_field' ) );
		$wp_customize->add_control( "dmd_faq_{$i}_q", array( 'label' => "FAQ {$i} — Question", 'section' => 'dmd_faq', 'type' => 'text' ) );
		
		$wp_customize->add_setting( "dmd_faq_{$i}_a", array( 'sanitize_callback' => 'sanitize_textarea_field' ) );
		$wp_customize->add_control( "dmd_faq_{$i}_a", array( 'label' => "FAQ {$i} — Answer", 'section' => 'dmd_faq', 'type' => 'textarea' ) );
	}


	/* ---------------------------------------------------------------
	 * Shop details
	 * --------------------------------------------------------------- */
	$wp_customize->add_section(
		'dmd_contact',
		array(
			'title' => __( 'Shop details', 'digital-mudir-dokan' ),
			'panel' => 'dmd_panel',
		)
	);

	$contact_fields = array(
		'dmd_whatsapp'      => array( __( 'WhatsApp number', 'digital-mudir-dokan' ), '+8801621611589' ),
		'dmd_hotline'       => array( __( 'Hotline', 'digital-mudir-dokan' ), '09647460074' ),
		'dmd_phone_1'       => array( __( 'Phone 1', 'digital-mudir-dokan' ), '+880 1621 611 589' ),
		'dmd_phone_2'       => array( __( 'Phone 2', 'digital-mudir-dokan' ), '+880 1788 871 247' ),
		'dmd_email_1'       => array( __( 'Email 1', 'digital-mudir-dokan' ), '' ),
		'dmd_email_2'       => array( __( 'Email 2', 'digital-mudir-dokan' ), '' ),
		'dmd_trade_license' => array( __( 'Trade licence number', 'digital-mudir-dokan' ), '' ),
		'dmd_messenger_url' => array( __( 'Messenger link', 'digital-mudir-dokan' ), '' ),
	);

	foreach ( $contact_fields as $key => $conf ) {
		$wp_customize->add_setting(
			$key,
			array(
				'default'           => $conf[1],
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			$key,
			array(
				'label'   => $conf[0],
				'section' => 'dmd_contact',
				'type'    => 'text',
			)
		);
	}

	$wp_customize->add_setting(
		'dmd_address',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'dmd_address',
		array(
			'label'   => __( 'Shop address', 'digital-mudir-dokan' ),
			'section' => 'dmd_contact',
			'type'    => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'dmd_disclaimer',
		array(
			'default'           => '',
			'sanitize_callback' => 'wp_kses_post',
		)
	);
	$wp_customize->add_control(
		'dmd_disclaimer',
		array(
			'label'       => __( 'Footer disclaimer', 'digital-mudir-dokan' ),
			'description' => __( 'Shown above the copyright line.', 'digital-mudir-dokan' ),
			'section'     => 'dmd_contact',
			'type'        => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'dmd_payment_image',
		array( 'sanitize_callback' => 'esc_url_raw' )
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'dmd_payment_image',
			array(
				'label'       => __( 'Payment methods strip', 'digital-mudir-dokan' ),
				'description' => __( 'An image of the card and mobile banking logos you accept.', 'digital-mudir-dokan' ),
				'section'     => 'dmd_contact',
			)
		)
	);

	/* ---------------------------------------------------------------
	 * Delivery promises (single product page)
	 * --------------------------------------------------------------- */
	$wp_customize->add_section(
		'dmd_delivery',
		array(
			'title' => __( 'Delivery', 'digital-mudir-dokan' ),
			'panel' => 'dmd_panel',
		)
	);

	$delivery = array(
		'dmd_delivery_title'   => array( __( 'Heading', 'digital-mudir-dokan' ), __( 'ডেলিভারী টাইম:', 'digital-mudir-dokan' ) ),
		'dmd_delivery_inside'  => array( __( 'Inside the city', 'digital-mudir-dokan' ), __( 'ঢাকার ভেতরে: ১-২ দিন', 'digital-mudir-dokan' ) ),
		'dmd_delivery_outside' => array( __( 'Outside the city', 'digital-mudir-dokan' ), __( 'ঢাকার বাইরে: ২-৩ দিন', 'digital-mudir-dokan' ) ),
	);

	foreach ( $delivery as $key => $conf ) {
		$wp_customize->add_setting(
			$key,
			array(
				'default'           => $conf[1],
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			$key,
			array(
				'label'   => $conf[0],
				'section' => 'dmd_delivery',
				'type'    => 'text',
			)
		);
	}

	$wp_customize->add_setting(
		'dmd_order_button_text',
		array(
			'default'           => __( 'অর্ডার করুন', 'digital-mudir-dokan' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'dmd_order_button_text',
		array(
			'label'       => __( 'Express order button label', 'digital-mudir-dokan' ),
			'description' => __( 'Adds the product to the cart and goes straight to checkout.', 'digital-mudir-dokan' ),
			'section'     => 'dmd_delivery',
			'type'        => 'text',
		)
	);

	/* ---------------------------------------------------------------
	 * Social profiles
	 * --------------------------------------------------------------- */
	$wp_customize->add_section(
		'dmd_social',
		array(
			'title' => __( 'Social profiles', 'digital-mudir-dokan' ),
			'panel' => 'dmd_panel',
		)
	);

	foreach ( array( 'facebook', 'tiktok', 'instagram', 'twitter', 'youtube' ) as $network ) {
		$wp_customize->add_setting(
			'dmd_social_' . $network,
			array( 'sanitize_callback' => 'esc_url_raw' )
		);
		$wp_customize->add_control(
			'dmd_social_' . $network,
			array(
				'label'   => ucfirst( $network ),
				'section' => 'dmd_social',
				'type'    => 'url',
			)
		);
	}
}
add_action( 'customize_register', 'dmd_customize_register' );

/**
 * Checkbox sanitiser.
 *
 * @param mixed $checked Raw value.
 * @return bool
 */
function dmd_sanitize_checkbox( $checked ) {
	return ( isset( $checked ) && true === (bool) $checked );
}

/**
 * Live preview script.
 */
function dmd_customize_preview_js() {
	wp_enqueue_script(
		'dmd-customizer',
		DMD_URI . '/assets/js/customizer.js',
		array( 'customize-preview' ),
		DMD_VERSION,
		true
	);
}
add_action( 'customize_preview_init', 'dmd_customize_preview_js' );
