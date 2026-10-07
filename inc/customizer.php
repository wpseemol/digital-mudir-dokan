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
		'dmd_shop_grid_columns',
		array( 'default' => '4', 'sanitize_callback' => 'sanitize_text_field' )
	);
	$wp_customize->add_control(
		'dmd_shop_grid_columns',
		array(
			'label'   => __( 'Shop Page Grid Columns', 'digital-mudir-dokan' ),
			'section' => 'dmd_catalog_settings',
			'type'    => 'select',
			'choices' => array(
				'3' => __( '3 Columns Grid', 'digital-mudir-dokan' ),
				'4' => __( '4 Columns Grid', 'digital-mudir-dokan' ),
			),
		)
	);

	$wp_customize->add_setting(
		'dmd_category_grid_columns',
		array( 'default' => '4', 'sanitize_callback' => 'sanitize_text_field' )
	);
	$wp_customize->add_control(
		'dmd_category_grid_columns',
		array(
			'label'   => __( 'Category Page Grid Columns', 'digital-mudir-dokan' ),
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
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 96,
				'step' => 1,
			),
		)
	);

	$wp_customize->add_setting(
		'dmd_category_products_per_page',
		array( 'default' => 12, 'sanitize_callback' => 'absint' )
	);
	$wp_customize->add_control(
		'dmd_category_products_per_page',
		array(
			'label'       => __( 'Category Page Products Per Page', 'digital-mudir-dokan' ),
			'section'     => 'dmd_catalog_settings',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 96,
				'step' => 1,
			),
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
	$hero_specs = dmd_hero_image_specs();
	$hero_mode  = dmd_get_hero_mode();
	$hero_crop  = $hero_specs[ $hero_mode ];

	$wp_customize->add_section(
		'dmd_hero',
		array(
			'title'       => __( 'Hero slider / Banner', 'digital-mudir-dokan' ),
			'panel'       => 'dmd_panel',
			'description' => sprintf(
				/* translators: 1: boxed width, 2: boxed height, 3: full width, 4: full height, 5: mobile width, 6: mobile height. */
				__( 'Add up to five slides. A slide appears once it has an image or a headline. Banner images use a fixed 13:5 ratio — Boxed: %1$d × %2$d px, Full width: %3$d × %4$d px. The optional mobile image uses 4:3 (%5$d × %6$d px). After choosing an image you will be asked to crop it to the right ratio; images with the wrong ratio or too small are rejected when you publish.', 'digital-mudir-dokan' ),
				$hero_specs['container']['width'],
				$hero_specs['container']['height'],
				$hero_specs['full']['width'],
				$hero_specs['full']['height'],
				$hero_specs['mobile']['width'],
				$hero_specs['mobile']['height']
			),
		)
	);

	$wp_customize->add_setting(
		'dmd_slider_width',
		array(
			'default'           => 'container',
			'sanitize_callback' => 'dmd_sanitize_hero_mode',
		)
	);
	$wp_customize->add_control(
		'dmd_slider_width',
		array(
			'label'       => __( 'Container Width Mode', 'digital-mudir-dokan' ),
			'description' => __( 'Publish and reload the Customizer after switching, so the crop tool uses the size for the new mode.', 'digital-mudir-dokan' ),
			'section'     => 'dmd_hero',
			'type'        => 'select',
			'choices'     => array(
				/* translators: 1: width, 2: height in px. */
				'container' => sprintf( __( 'Boxed / Centered — images %1$d × %2$d px', 'digital-mudir-dokan' ), $hero_specs['container']['width'], $hero_specs['container']['height'] ),
				/* translators: 1: width, 2: height in px. */
				'full'      => sprintf( __( 'Full width — images %1$d × %2$d px', 'digital-mudir-dokan' ), $hero_specs['full']['width'], $hero_specs['full']['height'] ),
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

	$hero_image_description = sprintf(
		/* translators: 1: boxed width, 2: boxed height, 3: boxed min width, 4: full width, 5: full height, 6: full min width, 7: current mode name. */
		__( 'Ratio 13:5. Boxed: %1$d × %2$d px (min. %3$d px wide). Full width: %4$d × %5$d px (min. %6$d px wide). Crop size follows the current mode: %7$s.', 'digital-mudir-dokan' ),
		$hero_specs['container']['width'],
		$hero_specs['container']['height'],
		$hero_specs['container']['min_width'],
		$hero_specs['full']['width'],
		$hero_specs['full']['height'],
		$hero_specs['full']['min_width'],
		'full' === $hero_mode ? __( 'Full width', 'digital-mudir-dokan' ) : __( 'Boxed', 'digital-mudir-dokan' )
	);
	$hero_mobile_description = sprintf(
		/* translators: 1: width, 2: height, 3: min width. */
		__( 'Optional, shown on phones. Ratio 4:3 — %1$d × %2$d px (min. %3$d px wide). Leave empty to use the main image.', 'digital-mudir-dokan' ),
		$hero_specs['mobile']['width'],
		$hero_specs['mobile']['height'],
		$hero_specs['mobile']['min_width']
	);

	// Fallback banner
	$wp_customize->add_setting(
		'dmd_hero_fallback_image',
		array(
			'sanitize_callback' => 'dmd_sanitize_image_value',
			'validate_callback' => 'dmd_validate_hero_image',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Cropped_Image_Control(
			$wp_customize,
			'dmd_hero_fallback_image',
			array(
				'label'       => __( 'Fallback Banner Image', 'digital-mudir-dokan' ),
				'description' => $hero_image_description,
				'section'     => 'dmd_hero',
				'width'       => $hero_crop['width'],
				'height'      => $hero_crop['height'],
			)
		)
	);
	$wp_customize->add_setting( 'dmd_hero_fallback_title', array( 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'dmd_hero_fallback_title', array( 'label' => __( 'Fallback Title', 'digital-mudir-dokan' ), 'section' => 'dmd_hero', 'type' => 'text' ) );
	$wp_customize->add_setting( 'dmd_hero_fallback_url', array( 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( 'dmd_hero_fallback_url', array( 'label' => __( 'Fallback Link', 'digital-mudir-dokan' ), 'section' => 'dmd_hero', 'type' => 'url' ) );

	for ( $i = 1; $i <= 5; $i++ ) {
		$wp_customize->add_setting(
			"dmd_slide_{$i}_image",
			array(
				'sanitize_callback' => 'dmd_sanitize_image_value',
				'validate_callback' => 'dmd_validate_hero_image',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Cropped_Image_Control(
				$wp_customize,
				"dmd_slide_{$i}_image",
				array(
					'label'       => sprintf(
						/* translators: 1: slide number, 2: width, 3: height in px. */
						__( 'Slide %1$d — image (%2$d × %3$d px)', 'digital-mudir-dokan' ),
						$i,
						$hero_crop['width'],
						$hero_crop['height']
					),
					'description' => $hero_image_description,
					'section'     => 'dmd_hero',
					'width'       => $hero_crop['width'],
					'height'      => $hero_crop['height'],
				)
			)
		);

		$wp_customize->add_setting(
			"dmd_slide_{$i}_image_mobile",
			array(
				'sanitize_callback' => 'dmd_sanitize_image_value',
				'validate_callback' => 'dmd_validate_hero_mobile_image',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Cropped_Image_Control(
				$wp_customize,
				"dmd_slide_{$i}_image_mobile",
				array(
					'label'       => sprintf(
						/* translators: 1: slide number, 2: width, 3: height in px. */
						__( 'Slide %1$d — mobile image (%2$d × %3$d px)', 'digital-mudir-dokan' ),
						$i,
						$hero_specs['mobile']['width'],
						$hero_specs['mobile']['height']
					),
					'description' => $hero_mobile_description,
					'section'     => 'dmd_hero',
					'width'       => $hero_specs['mobile']['width'],
					'height'      => $hero_specs['mobile']['height'],
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

	dmd_customize_homepage( $wp_customize );


	/* ---------------------------------------------------------------
	 * Promo banners (homepage)
	 * --------------------------------------------------------------- */
	$promo_spec = $hero_specs['promo'];

	$wp_customize->add_section(
		'dmd_promos',
		array(
			'title'       => __( 'Homepage — Promo banners', 'digital-mudir-dokan' ),
			'panel'       => 'dmd_panel',
			'description' => sprintf(
				/* translators: 1: width, 2: height, 3: min width. */
				__( 'Two side-by-side banners shown between the homepage product sections. Ratio 2:1 — %1$d × %2$d px (min. %3$d px wide). The section is hidden until a banner has an image.', 'digital-mudir-dokan' ),
				$promo_spec['width'],
				$promo_spec['height'],
				$promo_spec['min_width']
			),
		)
	);

	for ( $i = 1; $i <= 2; $i++ ) {
		$wp_customize->add_setting(
			"dmd_promo_{$i}_image",
			array(
				'sanitize_callback' => 'dmd_sanitize_image_value',
				'validate_callback' => 'dmd_validate_promo_image',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Cropped_Image_Control(
				$wp_customize,
				"dmd_promo_{$i}_image",
				array(
					'label'   => sprintf(
						/* translators: 1: banner number, 2: width, 3: height in px. */
						__( 'Banner %1$d — image (%2$d × %3$d px)', 'digital-mudir-dokan' ),
						$i,
						$promo_spec['width'],
						$promo_spec['height']
					),
					'section' => 'dmd_promos',
					'width'   => $promo_spec['width'],
					'height'  => $promo_spec['height'],
				)
			)
		);

		$wp_customize->add_setting( "dmd_promo_{$i}_url", array( 'sanitize_callback' => 'esc_url_raw' ) );
		$wp_customize->add_control(
			"dmd_promo_{$i}_url",
			array(
				/* translators: %d: banner number. */
				'label'   => sprintf( __( 'Banner %d — link', 'digital-mudir-dokan' ), $i ),
				'section' => 'dmd_promos',
				'type'    => 'url',
			)
		);
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

	// List sections in the order they appear on the site: header, homepage top to bottom, then shop and site-wide details.
	$order = array(
		'dmd_topbar',
		'dmd_header',
		'dmd_hero',
		'dmd_home_categories',
		'dmd_home_trust',
		'dmd_home_top_selling',
		'dmd_promos',
		'dmd_home_products',
		'dmd_home_cta',
		'dmd_home_videos',
		'dmd_home_blog',
		'dmd_faq',
		'dmd_catalog_settings',
		'dmd_shop_settings',
		'dmd_delivery',
		'dmd_contact',
		'dmd_social',
	);
	foreach ( $order as $index => $section_id ) {
		$section = $wp_customize->get_section( $section_id );
		if ( $section ) {
			$section->priority = 10 + ( $index * 5 );
		}
	}
}
add_action( 'customize_register', 'dmd_customize_register' );

/**
 * Register a text-like setting and its control in one call.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 * @param string               $id           Setting ID.
 * @param string               $section      Section ID.
 * @param string               $label        Control label.
 * @param mixed                $default      Default value.
 * @param string               $type         Control type: text, textarea, number, checkbox, url.
 * @param array                $extra        Extra control args (description, input_attrs).
 */
function dmd_customize_field( $wp_customize, $id, $section, $label, $default = '', $type = 'text', $extra = array() ) {
	$sanitizers = array(
		'checkbox' => 'dmd_sanitize_checkbox',
		'number'   => 'absint',
		'textarea' => 'sanitize_textarea_field',
		'url'      => 'esc_url_raw',
	);

	$wp_customize->add_setting(
		$id,
		array(
			'default'           => $default,
			'sanitize_callback' => isset( $sanitizers[ $type ] ) ? $sanitizers[ $type ] : 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		$id,
		array_merge(
			array(
				'label'   => $label,
				'section' => $section,
				'type'    => $type,
			),
			$extra
		)
	);
}

/**
 * Homepage: one section per block, each with a visibility switch and its own text.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 */
function dmd_customize_homepage( $wp_customize ) {
	$sections = array(
		'dmd_home_categories'  => __( 'Homepage — Shop by category', 'digital-mudir-dokan' ),
		'dmd_home_trust'       => __( 'Homepage — Service highlights', 'digital-mudir-dokan' ),
		'dmd_home_top_selling' => __( 'Homepage — Top selling', 'digital-mudir-dokan' ),
		'dmd_home_products'    => __( 'Homepage — All products', 'digital-mudir-dokan' ),
		'dmd_home_cta'         => __( 'Homepage — Order band', 'digital-mudir-dokan' ),
		'dmd_home_videos'      => __( 'Homepage — Customer videos', 'digital-mudir-dokan' ),
		'dmd_home_blog'        => __( 'Homepage — Blog', 'digital-mudir-dokan' ),
		'dmd_faq'              => __( 'Homepage — FAQ', 'digital-mudir-dokan' ),
	);
	foreach ( $sections as $section_id => $title ) {
		$wp_customize->add_section(
			$section_id,
			array(
				'title' => $title,
				'panel' => 'dmd_panel',
			)
		);
	}

	// Shop by category.
	dmd_customize_field( $wp_customize, 'dmd_show_home_categories', 'dmd_home_categories', __( 'Show this section', 'digital-mudir-dokan' ), true, 'checkbox' );
	dmd_customize_field( $wp_customize, 'dmd_home_categories_title', 'dmd_home_categories', __( 'Title', 'digital-mudir-dokan' ), __( 'Shop by category', 'digital-mudir-dokan' ) );
	dmd_customize_field(
		$wp_customize,
		'dmd_home_categories_count',
		'dmd_home_categories',
		__( 'Number of categories', 'digital-mudir-dokan' ),
		12,
		'number',
		array(
			'input_attrs' => array(
				'min' => 4,
				'max' => 24,
			),
		)
	);

	// Service highlights.
	dmd_customize_field( $wp_customize, 'dmd_show_trust_row', 'dmd_home_trust', __( 'Show this section', 'digital-mudir-dokan' ), true, 'checkbox' );
	foreach ( dmd_trust_defaults() as $i => $card ) {
		/* translators: %d: card number. */
		dmd_customize_field( $wp_customize, "dmd_trust_{$i}_title", 'dmd_home_trust', sprintf( __( 'Card %d — title', 'digital-mudir-dokan' ), $i ), $card['title'] );
		/* translators: %d: card number. */
		dmd_customize_field( $wp_customize, "dmd_trust_{$i}_text", 'dmd_home_trust', sprintf( __( 'Card %d — text', 'digital-mudir-dokan' ), $i ), $card['text'], 'text', array( 'description' => 4 === $i ? __( 'Leave empty to show the hotline number.', 'digital-mudir-dokan' ) : '' ) );
	}

	// Top selling.
	dmd_customize_field( $wp_customize, 'dmd_show_top_selling', 'dmd_home_top_selling', __( 'Show this section', 'digital-mudir-dokan' ), true, 'checkbox' );
	dmd_customize_field( $wp_customize, 'dmd_top_selling_eyebrow', 'dmd_home_top_selling', __( 'Small label above the title', 'digital-mudir-dokan' ), __( 'Customer favourites', 'digital-mudir-dokan' ) );
	dmd_customize_field( $wp_customize, 'dmd_top_selling_title', 'dmd_home_top_selling', __( 'Title', 'digital-mudir-dokan' ), __( 'Top selling products', 'digital-mudir-dokan' ) );
	dmd_customize_field( $wp_customize, 'dmd_top_selling_count', 'dmd_home_top_selling', __( 'Number of products', 'digital-mudir-dokan' ), 4, 'number', array( 'input_attrs' => array( 'min' => 1, 'max' => 24 ) ) );

	// All products.
	dmd_customize_field( $wp_customize, 'dmd_show_all_products', 'dmd_home_products', __( 'Show this section', 'digital-mudir-dokan' ), true, 'checkbox' );
	dmd_customize_field( $wp_customize, 'dmd_all_products_eyebrow', 'dmd_home_products', __( 'Small label above the title', 'digital-mudir-dokan' ), __( 'Fresh arrivals', 'digital-mudir-dokan' ) );
	dmd_customize_field( $wp_customize, 'dmd_all_products_title', 'dmd_home_products', __( 'Title', 'digital-mudir-dokan' ), __( 'Our products', 'digital-mudir-dokan' ) );
	dmd_customize_field( $wp_customize, 'dmd_product_count', 'dmd_home_products', __( 'Number of products', 'digital-mudir-dokan' ), 12, 'number', array( 'input_attrs' => array( 'min' => 1, 'max' => 48 ) ) );
	dmd_customize_field( $wp_customize, 'dmd_show_category_tabs', 'dmd_home_products', __( 'Show category filter tabs', 'digital-mudir-dokan' ), true, 'checkbox', array( 'description' => __( 'Tabs appear when the products belong to at least two top-level categories.', 'digital-mudir-dokan' ) ) );
	dmd_customize_field( $wp_customize, 'dmd_show_view_all_link', 'dmd_home_products', __( 'Show "View all" link', 'digital-mudir-dokan' ), true, 'checkbox' );

	// Order band.
	dmd_customize_field( $wp_customize, 'dmd_show_cta_band', 'dmd_home_cta', __( 'Show this section', 'digital-mudir-dokan' ), true, 'checkbox' );
	dmd_customize_field( $wp_customize, 'dmd_cta_title', 'dmd_home_cta', __( 'Headline', 'digital-mudir-dokan' ), __( 'ফোনে বা WhatsApp-এ সহজেই অর্ডার করুন', 'digital-mudir-dokan' ) );
	dmd_customize_field( $wp_customize, 'dmd_cta_text', 'dmd_home_cta', __( 'Supporting line', 'digital-mudir-dokan' ), __( 'আমাদের প্রতিনিধি আপনার অর্ডার নিশ্চিত করে দ্রুত ডেলিভারির ব্যবস্থা করবেন।', 'digital-mudir-dokan' ) );

	// Customer videos.
	dmd_customize_field( $wp_customize, 'dmd_show_videos', 'dmd_home_videos', __( 'Show this section', 'digital-mudir-dokan' ), true, 'checkbox' );
	dmd_customize_field( $wp_customize, 'dmd_home_video_title', 'dmd_home_videos', __( 'Title', 'digital-mudir-dokan' ), __( 'See what they say about us', 'digital-mudir-dokan' ) );
	dmd_customize_field( $wp_customize, 'dmd_home_videos', 'dmd_home_videos', __( 'YouTube links', 'digital-mudir-dokan' ), '', 'textarea', array( 'description' => __( 'One YouTube link per line (watch, youtu.be or Shorts links). Up to 8 videos are shown.', 'digital-mudir-dokan' ) ) );

	// Blog.
	dmd_customize_field( $wp_customize, 'dmd_show_blog', 'dmd_home_blog', __( 'Show this section', 'digital-mudir-dokan' ), true, 'checkbox' );
	dmd_customize_field( $wp_customize, 'dmd_home_blog_title', 'dmd_home_blog', __( 'Title', 'digital-mudir-dokan' ), __( 'From our blog', 'digital-mudir-dokan' ) );
	dmd_customize_field( $wp_customize, 'dmd_home_blog_count', 'dmd_home_blog', __( 'Number of posts', 'digital-mudir-dokan' ), 3, 'number', array( 'input_attrs' => array( 'min' => 1, 'max' => 12 ) ) );

	// FAQ.
	dmd_customize_field( $wp_customize, 'dmd_show_faq', 'dmd_faq', __( 'Show this section', 'digital-mudir-dokan' ), true, 'checkbox' );
	dmd_customize_field( $wp_customize, 'dmd_faq_eyebrow', 'dmd_faq', __( 'Small label above the title', 'digital-mudir-dokan' ), __( 'সচরাচর জিজ্ঞাসিত প্রশ্নাবলী', 'digital-mudir-dokan' ) );
	dmd_customize_field( $wp_customize, 'dmd_faq_title', 'dmd_faq', __( 'Title', 'digital-mudir-dokan' ), __( 'সাধারণ প্রশ্ন ও উত্তর', 'digital-mudir-dokan' ) );
	dmd_customize_field( $wp_customize, 'dmd_faq_text', 'dmd_faq', __( 'Intro text', 'digital-mudir-dokan' ), __( 'আপনার প্রশ্নের উত্তর এখানে না পেলে আমাদের সাথে সরাসরি যোগাযোগ করুন।', 'digital-mudir-dokan' ), 'textarea' );
	for ( $i = 1; $i <= 5; $i++ ) {
		/* translators: %d: question number. */
		dmd_customize_field( $wp_customize, "dmd_faq_{$i}_q", 'dmd_faq', sprintf( __( 'Question %d', 'digital-mudir-dokan' ), $i ) );
		/* translators: %d: question number. */
		dmd_customize_field( $wp_customize, "dmd_faq_{$i}_a", 'dmd_faq', sprintf( __( 'Answer %d', 'digital-mudir-dokan' ), $i ), '', 'textarea' );
	}
}

/**
 * Default copy for the four service highlight cards.
 *
 * @return array<int, array{icon: string, title: string, text: string}>
 */
function dmd_trust_defaults() {
	return array(
		1 => array(
			'icon'  => 'truck',
			'title' => __( 'সারা দেশে হোম ডেলিভারি', 'digital-mudir-dokan' ),
			'text'  => __( 'ঢাকার ভেতরে: ১-২ দিন', 'digital-mudir-dokan' ),
		),
		2 => array(
			'icon'  => 'leaf',
			'title' => __( '১০০% খাঁটি ও প্রাকৃতিক', 'digital-mudir-dokan' ),
			'text'  => __( 'সরাসরি উৎপাদকের কাছ থেকে সংগ্রহ', 'digital-mudir-dokan' ),
		),
		3 => array(
			'icon'  => 'wallet',
			'title' => __( 'ক্যাশ অন ডেলিভারি', 'digital-mudir-dokan' ),
			'text'  => __( 'পণ্য হাতে পেয়ে মূল্য পরিশোধ', 'digital-mudir-dokan' ),
		),
		4 => array(
			'icon'  => 'headset',
			'title' => __( 'গ্রাহক সহায়তা', 'digital-mudir-dokan' ),
			'text'  => '',
		),
	);
}

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
 * Hero width mode sanitiser.
 *
 * @param string $mode Raw value.
 * @return string
 */
function dmd_sanitize_hero_mode( $mode ) {
	return 'full' === $mode ? 'full' : 'container';
}

/**
 * Image setting sanitiser.
 *
 * Cropped image controls store an attachment ID; slides saved before the
 * switch still hold a URL, so both are accepted.
 *
 * @param mixed $value Raw value.
 * @return int|string
 */
function dmd_sanitize_image_value( $value ) {
	if ( is_numeric( $value ) ) {
		return absint( $value );
	}
	return esc_url_raw( (string) $value );
}

/**
 * Check an image setting against a size spec.
 *
 * @param mixed $value Attachment ID or URL.
 * @param array $spec  `width`, `height` and `min_width` in px.
 * @return true|WP_Error
 */
function dmd_check_image_spec( $value, $spec ) {
	if ( empty( $value ) ) {
		return true;
	}

	$attachment_id = is_numeric( $value ) ? absint( $value ) : attachment_url_to_postid( $value );

	// External URLs can't be measured; keep them rather than block the save.
	if ( ! $attachment_id ) {
		return true;
	}

	if ( ! wp_attachment_is_image( $attachment_id ) ) {
		return new WP_Error( 'dmd_not_image', __( 'Please choose an image file (JPG, PNG or WebP).', 'digital-mudir-dokan' ) );
	}

	$meta   = wp_get_attachment_metadata( $attachment_id );
	$width  = isset( $meta['width'] ) ? (int) $meta['width'] : 0;
	$height = isset( $meta['height'] ) ? (int) $meta['height'] : 0;

	if ( ! $width || ! $height ) {
		return true;
	}

	$target_ratio = $spec['width'] / $spec['height'];
	$ratio        = $width / $height;

	if ( abs( $ratio - $target_ratio ) / $target_ratio > 0.03 ) {
		return new WP_Error(
			'dmd_image_ratio',
			sprintf(
				/* translators: 1: image width, 2: image height, 3: required width, 4: required height. */
				__( 'This image is %1$d × %2$d px, which is the wrong shape. Use %3$d × %4$d px, or click "Change image" and crop it.', 'digital-mudir-dokan' ),
				$width,
				$height,
				$spec['width'],
				$spec['height']
			)
		);
	}

	if ( $width < $spec['min_width'] ) {
		return new WP_Error(
			'dmd_image_small',
			sprintf(
				/* translators: 1: image width, 2: minimum width, 3: recommended width, 4: recommended height. */
				__( 'This image is only %1$d px wide and will look blurry. Upload at least %2$d px wide (recommended %3$d × %4$d px).', 'digital-mudir-dokan' ),
				$width,
				$spec['min_width'],
				$spec['width'],
				$spec['height']
			)
		);
	}

	return true;
}

/**
 * Hero image validator — the minimum size follows the width mode being saved.
 *
 * @param WP_Error             $validity Validity object.
 * @param mixed                $value    Attachment ID or URL.
 * @param WP_Customize_Setting $setting  Setting instance.
 * @return WP_Error
 */
function dmd_validate_hero_image( $validity, $value, $setting ) {
	$mode         = dmd_get_hero_mode();
	$mode_setting = $setting->manager->get_setting( 'dmd_slider_width' );

	if ( $mode_setting ) {
		$mode = dmd_sanitize_hero_mode( $mode_setting->post_value( $mode ) );
	}

	$specs  = dmd_hero_image_specs();
	$result = dmd_check_image_spec( $value, $specs[ $mode ] );

	if ( is_wp_error( $result ) ) {
		$validity->add( $result->get_error_code(), $result->get_error_message() );
	}
	return $validity;
}

/**
 * Hero mobile image validator.
 *
 * @param WP_Error $validity Validity object.
 * @param mixed    $value    Attachment ID or URL.
 * @return WP_Error
 */
function dmd_validate_hero_mobile_image( $validity, $value ) {
	$specs  = dmd_hero_image_specs();
	$result = dmd_check_image_spec( $value, $specs['mobile'] );

	if ( is_wp_error( $result ) ) {
		$validity->add( $result->get_error_code(), $result->get_error_message() );
	}
	return $validity;
}

/**
 * Promo banner validator.
 *
 * @param WP_Error $validity Validity object.
 * @param mixed    $value    Attachment ID or URL.
 * @return WP_Error
 */
function dmd_validate_promo_image( $validity, $value ) {
	$specs  = dmd_hero_image_specs();
	$result = dmd_check_image_spec( $value, $specs['promo'] );

	if ( is_wp_error( $result ) ) {
		$validity->add( $result->get_error_code(), $result->get_error_message() );
	}
	return $validity;
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