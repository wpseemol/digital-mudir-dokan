<?php
/**
 * Theme dashboard: the "Mudir Dokan" admin menu.
 *
 * A setup overview plus tabbed settings saved through the Settings API into
 * the `dmd_options` option (see inc/theme-options.php).
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the top-level menu and one submenu per tab.
 */
function dmd_admin_menu() {
	add_menu_page(
		__( 'Digital Mudir Dokan', 'digital-mudir-dokan' ),
		__( 'Mudir Dokan', 'digital-mudir-dokan' ),
		'manage_options',
		'dmd-theme',
		'dmd_render_admin_page',
		'dashicons-store',
		3
	);

	add_submenu_page( 'dmd-theme', __( 'Dashboard', 'digital-mudir-dokan' ), __( 'Dashboard', 'digital-mudir-dokan' ), 'manage_options', 'dmd-theme', 'dmd_render_admin_page' );

	foreach ( dmd_option_tabs() as $slug => $tab ) {
		add_submenu_page( 'dmd-theme', $tab['label'], $tab['label'], 'manage_options', 'dmd-theme-' . $slug, 'dmd_render_admin_page' );
	}

	add_submenu_page( 'dmd-theme', __( 'Customize design', 'digital-mudir-dokan' ), __( 'Customize design', 'digital-mudir-dokan' ), 'customize', 'customize.php' );
}
add_action( 'admin_menu', 'dmd_admin_menu' );

/**
 * Register the option with its sanitizer.
 */
function dmd_admin_register_setting() {
	register_setting(
		'dmd_options_group',
		'dmd_options',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'dmd_sanitize_options',
			'default'           => array(),
		)
	);
}
add_action( 'admin_init', 'dmd_admin_register_setting' );

/**
 * Sanitize the fields of the tab that was submitted and keep every other tab.
 *
 * Only form submissions carry `_tab`; arrays saved from code (and WordPress
 * re-running the filter when the option is first added) pass through.
 *
 * @param mixed $input Submitted values.
 * @return array
 */
function dmd_sanitize_options( $input ) {
	if ( ! is_array( $input ) ) {
		return array();
	}

	$tabs = dmd_option_fields();

	if ( empty( $input['_tab'] ) || ! isset( $tabs[ $input['_tab'] ] ) ) {
		unset( $input['_tab'] );
		return $input;
	}

	$saved = get_option( 'dmd_options', array() );
	$clean = is_array( $saved ) ? $saved : array();

	foreach ( $tabs[ $input['_tab'] ] as $id => $field ) {
		if ( 'heading' === $field['type'] ) {
			continue;
		}

		$value = isset( $input[ $id ] ) ? $input[ $id ] : '';

		switch ( $field['type'] ) {
			case 'checkbox':
				$clean[ $id ] = empty( $value ) ? 0 : 1;
				break;
			case 'number':
			case 'page':
			case 'image':
				$clean[ $id ] = absint( $value );
				break;
			case 'price':
				$clean[ $id ] = max( 0, round( (float) $value, 2 ) );
				break;
			case 'email':
				$clean[ $id ] = sanitize_email( $value );
				break;
			case 'textarea':
				$clean[ $id ] = sanitize_textarea_field( $value );
				break;
			case 'map':
				$clean[ $id ] = dmd_sanitize_map_embed( $value );
				break;
			case 'ga4':
				$clean[ $id ] = preg_replace( '/[^A-Z0-9-]/', '', strtoupper( (string) $value ) );
				break;
			case 'pixel':
				$clean[ $id ] = preg_replace( '/\D/', '', (string) $value );
				break;
			case 'code':
				if ( current_user_can( 'unfiltered_html' ) ) {
					$clean[ $id ] = trim( (string) $value );
				}
				break;
			case 'select':
				$choices = isset( $field['choices'] ) && is_array( $field['choices'] ) ? $field['choices'] : array();
				$value   = sanitize_key( (string) $value );
				$clean[ $id ] = array_key_exists( $value, $choices ) ? $value : ( isset( $field['default'] ) ? $field['default'] : '' );
				break;
			default:
				$clean[ $id ] = sanitize_text_field( $value );
		}
	}

	return $clean;
}

/**
 * Accept a Google Maps embed link or a pasted <iframe> and keep only its https src.
 *
 * @param string $value Raw input.
 * @return string
 */
function dmd_sanitize_map_embed( $value ) {
	$value = trim( (string) $value );

	if ( preg_match( '/src=["\']([^"\']+)["\']/i', $value, $match ) ) {
		$value = html_entity_decode( $match[1] );
	}

	$url  = esc_url_raw( $value, array( 'https' ) );
	$host = wp_parse_url( $url, PHP_URL_HOST );

	return ( $host && preg_match( '/(^|\.)google\.[a-z.]+$/', $host ) ) ? $url : '';
}

/**
 * Tab slug of the screen being viewed.
 *
 * @return string
 */
function dmd_admin_current_tab() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only screen routing.
	$page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : 'dmd-theme';
	$tab  = substr( $page, strlen( 'dmd-theme-' ) );

	return array_key_exists( $tab, dmd_option_tabs() ) ? $tab : 'dashboard';
}

/**
 * Admin URL of a dashboard tab.
 *
 * @param string $tab Tab slug, or 'dashboard'.
 * @return string
 */
function dmd_admin_tab_url( $tab = 'dashboard' ) {
	return admin_url( 'admin.php?page=' . ( 'dashboard' === $tab ? 'dmd-theme' : 'dmd-theme-' . $tab ) );
}

/**
 * Styles, the media modal and the image picker script on the dashboard screens only.
 *
 * @param string $hook Current admin page hook.
 */
function dmd_admin_assets( $hook ) {
	if ( false === strpos( $hook, 'dmd-theme' ) ) {
		return;
	}

	wp_enqueue_style( 'dmd-admin', DMD_URI . '/assets/css/admin.css', array(), dmd_asset_version( 'assets/css/admin.css' ) );

	if ( 'about' === dmd_admin_current_tab() ) {
		wp_enqueue_media();
	}

	wp_enqueue_script( 'dmd-admin', DMD_URI . '/assets/js/admin.js', array( 'jquery' ), dmd_asset_version( 'assets/js/admin.js' ), true );
	wp_localize_script(
		'dmd-admin',
		'dmdAdmin',
		array(
			'chooseImage' => __( 'Choose image', 'digital-mudir-dokan' ),
			'useImage'    => __( 'Use this image', 'digital-mudir-dokan' ),
		)
	);
}
add_action( 'admin_enqueue_scripts', 'dmd_admin_assets' );

/**
 * Render any dashboard screen.
 */
function dmd_render_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$current = dmd_admin_current_tab();
	$tabs    = array_merge(
		array(
			'dashboard' => array(
				'label' => __( 'Dashboard', 'digital-mudir-dokan' ),
				'icon'  => 'dashicons-dashboard',
			),
		),
		dmd_option_tabs()
	);
	$theme   = wp_get_theme( get_template() );
	?>
	<div class="wrap dmd-admin">
		<header class="dmd-admin__header">
			<div class="dmd-admin__brand">
				<span class="dmd-admin__logo dashicons dashicons-store" aria-hidden="true"></span>
				<div>
					<h1><?php esc_html_e( 'Digital Mudir Dokan', 'digital-mudir-dokan' ); ?></h1>
					<p>
						<?php
						/* translators: %s: theme version. */
						echo esc_html( sprintf( __( 'Theme settings · version %s', 'digital-mudir-dokan' ), $theme->get( 'Version' ) ) );
						?>
					</p>
				</div>
			</div>
			<div class="dmd-admin__actions">
				<a class="button" href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Visit site', 'digital-mudir-dokan' ); ?></a>
				<a class="button button-primary" href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>"><?php esc_html_e( 'Customize design', 'digital-mudir-dokan' ); ?></a>
			</div>
		</header>

		<nav class="dmd-admin__tabs" aria-label="<?php esc_attr_e( 'Theme settings sections', 'digital-mudir-dokan' ); ?>">
			<?php foreach ( $tabs as $slug => $tab ) : ?>
				<a class="<?php echo esc_attr( $slug === $current ? 'is-active' : '' ); ?>" href="<?php echo esc_url( dmd_admin_tab_url( $slug ) ); ?>" <?php echo $slug === $current ? 'aria-current="page"' : ''; ?>>
					<span class="dashicons <?php echo esc_attr( $tab['icon'] ); ?>" aria-hidden="true"></span>
					<?php echo esc_html( $tab['label'] ); ?>
				</a>
			<?php endforeach; ?>
		</nav>

		<?php dmd_admin_notices(); ?>

		<div class="dmd-admin__body">
			<?php
			if ( 'dashboard' === $current ) {
				dmd_admin_dashboard_tab();
			} else {
				dmd_admin_settings_tab( $current );
			}
			?>
		</div>
	</div>
	<?php
}

/**
 * "Settings saved" and page-creation notices.
 */
function dmd_admin_notices() {
	settings_errors( 'general' );

	// phpcs:disable WordPress.Security.NonceVerification.Recommended -- display-only flags set by our own redirect.
	if ( isset( $_GET['dmd_pages'] ) ) {
		$count = absint( wp_unslash( $_GET['dmd_pages'] ) );
		$text  = $count
			/* translators: %d: number of pages. */
			? sprintf( _n( '%d page was created and linked.', '%d pages were created and linked.', $count, 'digital-mudir-dokan' ), $count )
			: __( 'All pages already exist — nothing was created.', 'digital-mudir-dokan' );
		printf( '<div class="notice notice-success is-dismissible"><p>%s</p></div>', esc_html( $text ) );
	}
	// phpcs:enable
}

/**
 * One settings tab as a Settings API form.
 *
 * @param string $tab Tab slug.
 */
function dmd_admin_settings_tab( $tab ) {
	$fields = dmd_option_fields();
	$fields = isset( $fields[ $tab ] ) ? $fields[ $tab ] : array();
	$open   = false;
	?>
	<form method="post" action="options.php" class="dmd-admin__form">
		<?php settings_fields( 'dmd_options_group' ); ?>
		<input type="hidden" name="dmd_options[_tab]" value="<?php echo esc_attr( $tab ); ?>" />

		<?php if ( 'pages' === $tab ) : ?>
			<div class="dmd-card dmd-card--note">
				<p><?php esc_html_e( 'Choose which page fills each role, or create every missing page in one click. New pages get the right template and starter text you can edit.', 'digital-mudir-dokan' ); ?></p>
				<button type="submit" form="dmd-create-pages" class="button"><?php esc_html_e( 'Create missing pages', 'digital-mudir-dokan' ); ?></button>
			</div>
		<?php endif; ?>

		<?php
		foreach ( $fields as $id => $field ) {
			if ( 'heading' === $field['type'] ) {
				if ( $open ) {
					echo '</div></section>';
				}
				echo '<section class="dmd-card"><h2>' . esc_html( $field['label'] ) . '</h2>';
				if ( ! empty( $field['desc'] ) ) {
					echo '<p class="dmd-card__desc">' . esc_html( $field['desc'] ) . '</p>';
				}
				echo '<div class="dmd-fields">';
				$open = true;
				continue;
			}

			if ( ! $open ) {
				echo '<section class="dmd-card"><div class="dmd-fields">';
				$open = true;
			}

			dmd_admin_field( $id, $field, dmd_option( $id ) );
		}

		if ( $open ) {
			echo '</div></section>';
		}
		?>

		<div class="dmd-admin__save">
			<?php submit_button( __( 'Save changes', 'digital-mudir-dokan' ), 'primary large', 'submit', false ); ?>
		</div>
	</form>

	<?php if ( 'pages' === $tab ) : ?>
		<form id="dmd-create-pages" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="dmd_create_pages" />
			<?php wp_nonce_field( 'dmd_create_pages' ); ?>
		</form>
	<?php endif; ?>
	<?php
}

/**
 * Render one field row.
 *
 * @param string $id    Option key.
 * @param array  $field Field definition.
 * @param mixed  $value Current value.
 */
function dmd_admin_field( $id, $field, $value ) {
	$name  = 'dmd_options[' . $id . ']';
	$input = 'dmd-' . str_replace( '_', '-', $id );
	$class = 'dmd-field dmd-field--' . $field['type'] . ( empty( $field['half'] ) ? '' : ' dmd-field--half' );
	?>
	<div class="<?php echo esc_attr( $class ); ?>">
		<?php if ( 'checkbox' === $field['type'] ) : ?>
			<label class="dmd-switch" for="<?php echo esc_attr( $input ); ?>">
				<input type="hidden" name="<?php echo esc_attr( $name ); ?>" value="0" />
				<input type="checkbox" id="<?php echo esc_attr( $input ); ?>" name="<?php echo esc_attr( $name ); ?>" value="1" <?php checked( (int) $value, 1 ); ?> />
				<span class="dmd-switch__track" aria-hidden="true"></span>
				<span class="dmd-switch__label"><?php echo esc_html( $field['label'] ); ?></span>
			</label>
		<?php else : ?>
			<label class="dmd-field__label" for="<?php echo esc_attr( $input ); ?>"><?php echo esc_html( $field['label'] ); ?></label>
			<?php
			switch ( $field['type'] ) {
				case 'textarea':
				case 'code':
					$readonly = 'code' === $field['type'] && ! current_user_can( 'unfiltered_html' );
					printf(
						'<textarea id="%1$s" name="%2$s" rows="%3$d" class="large-text%4$s"%5$s>%6$s</textarea>',
						esc_attr( $input ),
						esc_attr( $name ),
						'code' === $field['type'] ? 6 : 4,
						'code' === $field['type'] ? ' code' : '',
						$readonly ? ' readonly' : '',
						esc_textarea( $value )
					);
					break;

				case 'map':
					printf(
						'<textarea id="%1$s" name="%2$s" rows="2" class="large-text code" placeholder="https://www.google.com/maps/embed?pb=…">%3$s</textarea>',
						esc_attr( $input ),
						esc_attr( $name ),
						esc_textarea( $value )
					);
					if ( $value ) {
						printf( '<iframe class="dmd-field__map" src="%s" loading="lazy" title="%s"></iframe>', esc_url( $value ), esc_attr__( 'Map preview', 'digital-mudir-dokan' ) );
					}
					break;

				case 'page':
					$slot     = substr( $id, strlen( 'page_' ) );
					$selected = $value ? absint( $value ) : dmd_page_id( $slot );
					echo '<div class="dmd-field__row">';
					wp_dropdown_pages(
						array(
							'name'              => esc_attr( $name ),
							'id'                => esc_attr( $input ),
							'selected'          => (int) $selected,
							'show_option_none'  => esc_html__( '— Not set —', 'digital-mudir-dokan' ),
							'option_none_value' => 0,
						)
					);
					if ( $selected ) {
						printf(
							' <a class="button button-small" href="%1$s" target="_blank" rel="noopener">%2$s</a> <a class="button button-small" href="%3$s">%4$s</a>',
							esc_url( get_permalink( $selected ) ),
							esc_html__( 'View', 'digital-mudir-dokan' ),
							esc_url( get_edit_post_link( $selected ) ),
							esc_html__( 'Edit', 'digital-mudir-dokan' )
						);
					}
					echo '</div>';
					break;

				case 'image':
					$src = $value ? wp_get_attachment_image_url( (int) $value, 'medium' ) : '';
					?>
					<div class="dmd-image-field" data-dmd-image>
						<input type="hidden" id="<?php echo esc_attr( $input ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" />
						<div class="dmd-image-field__preview">
							<?php if ( $src ) : ?>
								<img src="<?php echo esc_url( $src ); ?>" alt="" />
							<?php endif; ?>
						</div>
						<button type="button" class="button" data-dmd-image-select><?php esc_html_e( 'Choose image', 'digital-mudir-dokan' ); ?></button>
						<button type="button" class="button-link button-link-delete" data-dmd-image-remove <?php echo $src ? '' : 'hidden'; ?>><?php esc_html_e( 'Remove', 'digital-mudir-dokan' ); ?></button>
					</div>
					<?php
					break;

				case 'number':
				case 'price':
					printf(
						'<input type="number" id="%1$s" name="%2$s" value="%3$s" min="0" step="%4$s" class="small-text" />%5$s',
						esc_attr( $input ),
						esc_attr( $name ),
						esc_attr( $value ),
						'price' === $field['type'] ? '0.01' : '1',
						'price' === $field['type'] && function_exists( 'get_woocommerce_currency_symbol' ) ? ' <span class="dmd-field__unit">' . esc_html( html_entity_decode( get_woocommerce_currency_symbol() ) ) . '</span>' : ''
					);
					break;

				case 'select':
					$choices = isset( $field['choices'] ) && is_array( $field['choices'] ) ? $field['choices'] : array();
					echo '<select id="' . esc_attr( $input ) . '" name="' . esc_attr( $name ) . '">';
					foreach ( $choices as $choice_value => $choice_label ) {
						printf(
							'<option value="%1$s" %2$s>%3$s</option>',
							esc_attr( $choice_value ),
							selected( (string) $value, (string) $choice_value, false ),
							esc_html( $choice_label )
						);
					}
					echo '</select>';
					break;

				default:
					printf(
						'<input type="%1$s" id="%2$s" name="%3$s" value="%4$s" class="regular-text" placeholder="%5$s" />',
						'email' === $field['type'] ? 'email' : 'text',
						esc_attr( $input ),
						esc_attr( $name ),
						esc_attr( $value ),
						esc_attr( isset( $field['placeholder'] ) ? $field['placeholder'] : '' )
					);
			}
			?>
		<?php endif; ?>

		<?php if ( ! empty( $field['desc'] ) ) : ?>
			<p class="description"><?php echo esc_html( $field['desc'] ); ?></p>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Setup checklist, store numbers and shortcuts.
 */
function dmd_admin_dashboard_tab() {
	$has_wc   = class_exists( 'WooCommerce' );
	$contact  = dmd_contact();
	$has_hero = false;
	for ( $i = 1; $i <= 5; $i++ ) {
		if ( get_theme_mod( "dmd_slide_{$i}_image" ) ) {
			$has_hero = true;
			break;
		}
	}

	$customize = static function ( $section ) {
		return add_query_arg( 'autofocus[section]', $section, admin_url( 'customize.php' ) );
	};

	$checks = array(
		array( $has_wc, __( 'WooCommerce is active', 'digital-mudir-dokan' ), admin_url( 'plugin-install.php?s=woocommerce&tab=search&type=term' ) ),
		array( $has_wc && wc_get_page_id( 'shop' ) > 0, __( 'Shop, cart, checkout and account pages exist', 'digital-mudir-dokan' ), dmd_admin_tab_url( 'pages' ) ),
		array( has_custom_logo(), __( 'Logo uploaded', 'digital-mudir-dokan' ), $customize( 'title_tagline' ) ),
		array( $has_hero, __( 'Homepage hero slides added', 'digital-mudir-dokan' ), $customize( 'dmd_hero' ) ),
		array( has_nav_menu( 'primary' ), __( 'Primary menu assigned', 'digital-mudir-dokan' ), admin_url( 'nav-menus.php' ) ),
		array( '' !== trim( (string) $contact['hotline'] ) && '' !== trim( (string) $contact['whatsapp'] ), __( 'Hotline and WhatsApp number set', 'digital-mudir-dokan' ), $customize( 'dmd_contact' ) ),
		array( dmd_page_id( 'about' ) > 0, __( 'About page linked', 'digital-mudir-dokan' ), dmd_admin_tab_url( 'pages' ) ),
		array( dmd_page_id( 'contact' ) > 0, __( 'Contact page linked', 'digital-mudir-dokan' ), dmd_admin_tab_url( 'pages' ) ),
		array( dmd_page_id( 'categories' ) > 0, __( 'All categories page linked', 'digital-mudir-dokan' ), dmd_admin_tab_url( 'pages' ) ),
		array( dmd_page_id( 'privacy' ) && dmd_page_id( 'terms' ) && dmd_page_id( 'returns' ), __( 'Privacy, terms and return policy pages published', 'digital-mudir-dokan' ), dmd_admin_tab_url( 'pages' ) ),
	);

	$done    = count( array_filter( wp_list_pluck( $checks, 0 ) ) );
	$percent = (int) round( $done / count( $checks ) * 100 );

	$stats = array();
	if ( $has_wc ) {
		$products = wp_count_posts( 'product' );
		$terms    = wp_count_terms( array( 'taxonomy' => 'product_cat' ) );
		$hpos     = class_exists( '\Automattic\WooCommerce\Utilities\OrderUtil' ) && \Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled();
		$orders   = $hpos ? admin_url( 'admin.php?page=wc-orders&status=' ) : admin_url( 'edit.php?post_type=shop_order&post_status=' );
		$stats    = array(
			array( __( 'Products', 'digital-mudir-dokan' ), (int) $products->publish, admin_url( 'edit.php?post_type=product' ), 'dashicons-products' ),
			array( __( 'Categories', 'digital-mudir-dokan' ), is_wp_error( $terms ) ? 0 : (int) $terms, admin_url( 'edit-tags.php?taxonomy=product_cat&post_type=product' ), 'dashicons-category' ),
			array( __( 'Orders to process', 'digital-mudir-dokan' ), (int) wc_orders_count( 'processing' ), $orders . 'wc-processing', 'dashicons-clipboard' ),
			array( __( 'Orders on hold', 'digital-mudir-dokan' ), (int) wc_orders_count( 'on-hold' ), $orders . 'wc-on-hold', 'dashicons-clock' ),
		);
	}

	$shortcuts = array(
		array( __( 'Header & top bar', 'digital-mudir-dokan' ), $customize( 'dmd_header' ), 'dashicons-align-wide' ),
		array( __( 'Hero slider', 'digital-mudir-dokan' ), $customize( 'dmd_hero' ), 'dashicons-images-alt2' ),
		array( __( 'Homepage sections', 'digital-mudir-dokan' ), $customize( 'dmd_home_categories' ), 'dashicons-layout' ),
		array( __( 'Promo banners', 'digital-mudir-dokan' ), $customize( 'dmd_promos' ), 'dashicons-megaphone' ),
		array( __( 'Shop grid & per page', 'digital-mudir-dokan' ), $customize( 'dmd_catalog_settings' ), 'dashicons-grid-view' ),
		array( __( 'Phone, email & address', 'digital-mudir-dokan' ), $customize( 'dmd_contact' ), 'dashicons-phone' ),
		array( __( 'Social links', 'digital-mudir-dokan' ), $customize( 'dmd_social' ), 'dashicons-share' ),
		array( __( 'Menus', 'digital-mudir-dokan' ), admin_url( 'nav-menus.php' ), 'dashicons-menu' ),
		array( __( 'Widgets', 'digital-mudir-dokan' ), admin_url( 'widgets.php' ), 'dashicons-welcome-widgets-menus' ),
	);
	if ( $has_wc ) {
		$shortcuts[] = array( __( 'Shipping zones', 'digital-mudir-dokan' ), admin_url( 'admin.php?page=wc-settings&tab=shipping' ), 'dashicons-car' );
		$shortcuts[] = array( __( 'Payment methods', 'digital-mudir-dokan' ), admin_url( 'admin.php?page=wc-settings&tab=checkout' ), 'dashicons-money-alt' );
	}
	?>
	<section class="dmd-card dmd-welcome">
		<div>
			<h2><?php esc_html_e( 'Welcome to your shop dashboard', 'digital-mudir-dokan' ); ?></h2>
			<p><?php esc_html_e( 'Design and homepage content live in the Customizer. Pages, shop rules, the About and Contact pages and tracking codes are managed from the tabs above.', 'digital-mudir-dokan' ); ?></p>
		</div>
		<div class="dmd-progress" style="--dmd-progress: <?php echo esc_attr( $percent ); ?>%;" role="img" aria-label="<?php echo esc_attr( sprintf( /* translators: %d: percentage. */ __( 'Setup %d%% complete', 'digital-mudir-dokan' ), $percent ) ); ?>">
			<span><?php echo esc_html( $percent ); ?>%</span>
		</div>
	</section>

	<?php if ( $stats ) : ?>
		<div class="dmd-stats">
			<?php foreach ( $stats as $stat ) : ?>
				<a class="dmd-stat" href="<?php echo esc_url( $stat[2] ); ?>">
					<span class="dashicons <?php echo esc_attr( $stat[3] ); ?>" aria-hidden="true"></span>
					<strong><?php echo esc_html( number_format_i18n( $stat[1] ) ); ?></strong>
					<span><?php echo esc_html( $stat[0] ); ?></span>
				</a>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<div class="dmd-grid-2">
		<section class="dmd-card">
			<h2>
				<?php
				/* translators: 1: completed steps, 2: total steps. */
				echo esc_html( sprintf( __( 'Setup checklist (%1$d of %2$d)', 'digital-mudir-dokan' ), $done, count( $checks ) ) );
				?>
			</h2>
			<ul class="dmd-checklist">
				<?php foreach ( $checks as $check ) : ?>
					<li class="<?php echo esc_attr( $check[0] ? 'is-done' : '' ); ?>">
						<span class="dashicons <?php echo esc_attr( $check[0] ? 'dashicons-yes-alt' : 'dashicons-marker' ); ?>" aria-hidden="true"></span>
						<span class="dmd-checklist__text">
							<?php echo esc_html( $check[1] ); ?>
							<span class="screen-reader-text"><?php echo $check[0] ? esc_html__( '(done)', 'digital-mudir-dokan' ) : esc_html__( '(to do)', 'digital-mudir-dokan' ); ?></span>
						</span>
						<?php if ( ! $check[0] ) : ?>
							<a href="<?php echo esc_url( $check[2] ); ?>"><?php esc_html_e( 'Fix', 'digital-mudir-dokan' ); ?></a>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="dmd-checklist__action">
				<input type="hidden" name="action" value="dmd_create_pages" />
				<?php wp_nonce_field( 'dmd_create_pages' ); ?>
				<button type="submit" class="button button-primary"><?php esc_html_e( 'Create all missing pages', 'digital-mudir-dokan' ); ?></button>
			</form>
		</section>

		<section class="dmd-card">
			<h2><?php esc_html_e( 'Shortcuts', 'digital-mudir-dokan' ); ?></h2>
			<div class="dmd-shortcuts">
				<?php foreach ( $shortcuts as $link ) : ?>
					<a href="<?php echo esc_url( $link[1] ); ?>">
						<span class="dashicons <?php echo esc_attr( $link[2] ); ?>" aria-hidden="true"></span>
						<?php echo esc_html( $link[0] ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		</section>
	</div>
	<?php
}

/**
 * Page blueprints for the one-click creator.
 *
 * @return array slot => title, slug, template, content.
 */
function dmd_page_blueprints() {
	$shop = get_bloginfo( 'name' );

	return array(
		'about'      => array(
			'title'    => __( 'About us', 'digital-mudir-dokan' ),
			'slug'     => 'about',
			'template' => 'page-templates/template-about.php',
			'content'  => '<!-- wp:paragraph --><p>' . esc_html__( 'Tell your story here: when the shop started, who runs it and what makes your groceries different.', 'digital-mudir-dokan' ) . '</p><!-- /wp:paragraph -->',
		),
		'contact'    => array(
			'title'    => __( 'Contact', 'digital-mudir-dokan' ),
			'slug'     => 'contact',
			'template' => 'page-templates/template-contact.php',
			'content'  => '',
		),
		'categories' => array(
			'title'    => __( 'All categories', 'digital-mudir-dokan' ),
			'slug'     => 'all-categories',
			'template' => 'page-templates/template-all-categories.php',
			'content'  => '',
		),
		'privacy'    => array(
			'title'   => __( 'Privacy policy', 'digital-mudir-dokan' ),
			'slug'    => 'privacy-policy',
			/* translators: %s: shop name. */
			'content' => '<!-- wp:paragraph --><p>' . esc_html( sprintf( __( 'This page explains what personal information %s collects when you order, why we need it and how we keep it safe. Replace this text with your own policy.', 'digital-mudir-dokan' ), $shop ) ) . '</p><!-- /wp:paragraph -->',
		),
		'terms'      => array(
			'title'   => __( 'Terms & conditions', 'digital-mudir-dokan' ),
			'slug'    => 'terms-and-conditions',
			'content' => '<!-- wp:paragraph --><p>' . esc_html__( 'Describe the rules for ordering, pricing, payment and delivery. Replace this text with your own terms.', 'digital-mudir-dokan' ) . '</p><!-- /wp:paragraph -->',
		),
		'returns'    => array(
			'title'   => __( 'Return & refund policy', 'digital-mudir-dokan' ),
			'slug'    => 'return-policy',
			'content' => '<!-- wp:paragraph --><p>' . esc_html__( 'Explain when customers can return a product, how to request it and how refunds are paid. Replace this text with your own policy.', 'digital-mudir-dokan' ) . '</p><!-- /wp:paragraph -->',
		),
		'delivery'   => array(
			'title'   => __( 'Delivery information', 'digital-mudir-dokan' ),
			'slug'    => 'delivery-information',
			'content' => '<!-- wp:paragraph --><p>' . esc_html__( 'List your delivery areas, charges and delivery times inside and outside the city.', 'digital-mudir-dokan' ) . '</p><!-- /wp:paragraph -->',
		),
	);
}

/**
 * Create every missing page, link it on the Pages tab and add WooCommerce's own pages.
 */
function dmd_handle_create_pages() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to do that.', 'digital-mudir-dokan' ), 403 );
	}
	check_admin_referer( 'dmd_create_pages' );

	$options = get_option( 'dmd_options', array() );
	$options = is_array( $options ) ? $options : array();
	$created = 0;

	foreach ( dmd_page_blueprints() as $slot => $page ) {
		if ( dmd_page_id( $slot ) ) {
			continue;
		}

		$existing = get_page_by_path( $page['slug'] );
		if ( $existing && 'publish' === $existing->post_status ) {
			$id = $existing->ID;
		} else {
			$id = wp_insert_post(
				array(
					'post_type'    => 'page',
					'post_status'  => 'publish',
					'post_title'   => $page['title'],
					'post_name'    => $page['slug'],
					'post_content' => $page['content'],
				),
				true
			);
			if ( is_wp_error( $id ) ) {
				continue;
			}
			++$created;
		}

		if ( ! empty( $page['template'] ) ) {
			update_post_meta( $id, '_wp_page_template', $page['template'] );
		}

		$options[ 'page_' . $slot ] = $id;

		if ( 'privacy' === $slot && ! get_option( 'wp_page_for_privacy_policy' ) ) {
			update_option( 'wp_page_for_privacy_policy', $id );
		}
		if ( 'terms' === $slot && ! get_option( 'woocommerce_terms_page_id' ) ) {
			update_option( 'woocommerce_terms_page_id', $id );
		}
	}

	update_option( 'dmd_options', $options );

	if ( class_exists( 'WC_Install' ) && method_exists( 'WC_Install', 'create_pages' ) ) {
		WC_Install::create_pages();
	}

	wp_safe_redirect( add_query_arg( 'dmd_pages', $created, dmd_admin_tab_url( 'pages' ) ) );
	exit;
}
add_action( 'admin_post_dmd_create_pages', 'dmd_handle_create_pages' );

/**
 * Shortcut to the theme dashboard under the site name in the front-end admin bar.
 *
 * @param WP_Admin_Bar $bar Admin bar.
 */
function dmd_admin_bar_link( $bar ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$bar->add_node(
		array(
			'parent' => 'site-name',
			'id'     => 'dmd-theme',
			'title'  => __( 'Mudir Dokan settings', 'digital-mudir-dokan' ),
			'href'   => dmd_admin_tab_url(),
		)
	);
}
add_action( 'admin_bar_menu', 'dmd_admin_bar_link', 40 );
