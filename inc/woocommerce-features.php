<?php
/**
 * Shop rules switched on from Mudir Dokan → Shop & checkout.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

/* ---------------------------------------------------------------------------
 * Cart & checkout
 * ------------------------------------------------------------------------- */

/**
 * Cart subtotal the minimum-order and free-delivery rules compare against.
 *
 * @return float
 */
function dmd_cart_subtotal_for_rules() {
	if ( ! WC()->cart ) {
		return 0.0;
	}
	return (float) WC()->cart->get_displayed_subtotal() - (float) WC()->cart->get_discount_total();
}

/**
 * Block checkout while the cart is below the minimum order amount.
 */
function dmd_check_minimum_order() {
	$minimum = (float) dmd_option( 'min_order_amount' );

	if ( $minimum <= 0 || ! WC()->cart || WC()->cart->is_empty() ) {
		return;
	}

	$subtotal = dmd_cart_subtotal_for_rules();
	if ( $subtotal >= $minimum ) {
		return;
	}

	wc_add_notice(
		sprintf(
			/* translators: 1: minimum amount, 2: amount still needed. */
			__( 'The minimum order is %1$s. Please add %2$s more to place your order.', 'digital-mudir-dokan' ),
			wc_price( $minimum ),
			wc_price( $minimum - $subtotal )
		),
		'error'
	);
}
add_action( 'woocommerce_check_cart_items', 'dmd_check_minimum_order' );

/**
 * "Add ৳X more for free delivery" progress bar.
 */
function dmd_free_delivery_progress() {
	$target = (float) dmd_option( 'free_delivery_amount' );

	if ( $target <= 0 || ! WC()->cart || WC()->cart->is_empty() ) {
		return;
	}

	$subtotal = dmd_cart_subtotal_for_rules();
	$percent  = (int) min( 100, floor( $subtotal / $target * 100 ) );
	$reached  = $subtotal >= $target;
	?>
	<div class="dmd-free-delivery mb-6 rounded-xl border border-line bg-white p-4 sm:p-5">
		<p class="m-0 flex items-center gap-2 text-sm font-medium text-ink">
			<span class="text-green"><?php dmd_the_icon( $reached ? 'check' : 'truck', 18 ); ?></span>
			<?php
			if ( $reached ) {
				esc_html_e( 'অভিনন্দন! আপনার অর্ডারে ফ্রি ডেলিভারি প্রযোজ্য।', 'digital-mudir-dokan' );
			} else {
				echo wp_kses_post(
					sprintf(
						/* translators: %s: amount still needed. */
						__( 'ফ্রি ডেলিভারি পেতে আরও %s টাকার পণ্য যোগ করুন।', 'digital-mudir-dokan' ),
						'<strong class="text-green">' . wc_price( $target - $subtotal ) . '</strong>'
					)
				);
			}
			?>
		</p>
		<div class="mt-3 h-2 overflow-hidden rounded-full bg-surface" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?php echo esc_attr( $percent ); ?>" aria-label="<?php esc_attr_e( 'Progress towards free delivery', 'digital-mudir-dokan' ); ?>">
			<div class="h-full rounded-full bg-green-dark transition-[width] duration-500" style="width: <?php echo esc_attr( $percent ); ?>%;"></div>
		</div>
	</div>
	<?php
}
add_action( 'woocommerce_before_cart', 'dmd_free_delivery_progress', 5 );
add_action( 'woocommerce_before_checkout_form', 'dmd_free_delivery_progress', 5 );

/**
 * Simplified checkout and the order-notes switch.
 *
 * Runs after dmd_checkout_fields() so the theme's placeholders stay intact.
 *
 * @param array $fields Checkout fields.
 * @return array
 */
function dmd_simplify_checkout_fields( $fields ) {
	if ( dmd_option( 'simple_checkout' ) ) {
		foreach ( array( 'billing', 'shipping' ) as $group ) {
			unset( $fields[ $group ][ $group . '_company' ], $fields[ $group ][ $group . '_address_2' ], $fields[ $group ][ $group . '_postcode' ] );
		}
		if ( isset( $fields['billing']['billing_email'] ) ) {
			$fields['billing']['billing_email']['required'] = false;
		}
	}

	if ( ! dmd_option( 'order_notes' ) ) {
		unset( $fields['order']['order_comments'] );
	}

	return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'dmd_simplify_checkout_fields', 20 );

/**
 * Hide the "Additional information" heading when the notes field is off.
 *
 * @param bool $enabled Whether order notes are enabled.
 * @return bool
 */
function dmd_order_notes_enabled( $enabled ) {
	return dmd_option( 'order_notes' ) ? $enabled : false;
}
add_filter( 'woocommerce_enable_order_notes_field', 'dmd_order_notes_enabled' );

/**
 * Postcodes are optional in the simplified checkout, including for countries
 * whose locale normally requires one.
 *
 * @param array $locale Country locales.
 * @return array
 */
function dmd_simplify_checkout_locale( $locale ) {
	if ( ! dmd_option( 'simple_checkout' ) ) {
		return $locale;
	}
	foreach ( $locale as $country => $fields ) {
		$locale[ $country ]['postcode']['required'] = false;
		$locale[ $country ]['postcode']['hidden']   = true;
	}
	return $locale;
}
add_filter( 'woocommerce_get_country_locale', 'dmd_simplify_checkout_locale' );

/* ---------------------------------------------------------------------------
 * Product page
 * ------------------------------------------------------------------------- */

/**
 * Stock quantity when it is at or under the low-stock warning level, else 0.
 *
 * @param WC_Product $product Product.
 * @return int
 */
function dmd_low_stock_qty( $product ) {
	$limit = absint( dmd_option( 'low_stock_amount' ) );

	if ( ! $limit || ! $product || ! $product->managing_stock() || ! $product->is_in_stock() ) {
		return 0;
	}

	$qty = (int) $product->get_stock_quantity();
	return ( $qty > 0 && $qty <= $limit ) ? $qty : 0;
}

/**
 * "Only X left" availability text (used by variations and plugins).
 *
 * @param string     $text    Availability text.
 * @param WC_Product $product Product.
 * @return string
 */
function dmd_low_stock_text( $text, $product ) {
	$qty = dmd_low_stock_qty( $product );
	if ( $qty ) {
		/* translators: %s: units left. */
		return sprintf( __( 'মাত্র %s টি বাকি আছে — দ্রুত অর্ডার করুন!', 'digital-mudir-dokan' ), number_format_i18n( $qty ) );
	}
	return $text;
}
add_filter( 'woocommerce_get_availability_text', 'dmd_low_stock_text', 10, 2 );

/**
 * "Order on WhatsApp" link for a product.
 *
 * @param WC_Product $product Product.
 * @return string Empty when the feature or the number is missing.
 */
function dmd_whatsapp_order_url( $product ) {
	$number = ltrim( dmd_tel( dmd_contact()['whatsapp'] ), '+' );

	if ( ! dmd_option( 'whatsapp_order' ) || '' === $number || ! $product ) {
		return '';
	}

	$lines = array(
		dmd_option( 'whatsapp_message' ),
		$product->get_name(),
		/* translators: %s: product price. */
		sprintf( __( 'দাম: %s', 'digital-mudir-dokan' ), wp_strip_all_tags( html_entity_decode( wc_price( wc_get_price_to_display( $product ) ) ) ) ),
		get_permalink( $product->get_id() ),
	);

	return 'https://wa.me/' . $number . '?text=' . rawurlencode( implode( "\n", array_filter( $lines ) ) );
}

/**
 * Print the WhatsApp order button under the add-to-cart form.
 */
function dmd_whatsapp_order_button() {
	global $product;

	if ( ! $product instanceof WC_Product || ! $product->is_in_stock() ) {
		return;
	}

	$url = dmd_whatsapp_order_url( $product );
	if ( ! $url ) {
		return;
	}
	?>
	<a class="dmd-whatsapp-order mt-3 inline-flex w-full items-center justify-center gap-2 rounded-lg border border-[#25D366] bg-[#25D366]/10 px-5 py-3 text-sm font-semibold text-[#128C7E] transition hover:bg-[#25D366] hover:text-white" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer">
		<?php dmd_the_icon( 'whatsapp', 18 ); ?>
		<?php esc_html_e( 'WhatsApp-এ অর্ডার করুন', 'digital-mudir-dokan' ); ?>
	</a>
	<?php
}
add_action( 'woocommerce_after_add_to_cart_form', 'dmd_whatsapp_order_button' );

/* ---------------------------------------------------------------------------
 * Catalog
 * ------------------------------------------------------------------------- */

/**
 * Sort out-of-stock products after everything else on the main product query.
 *
 * @param array    $clauses SQL clauses.
 * @param WP_Query $query   Query.
 * @return array
 */
function dmd_out_of_stock_last( $clauses, $query ) {
	if ( is_admin() || 'product_query' !== $query->get( 'wc_query' ) || ! dmd_option( 'out_of_stock_last' ) ) {
		return $clauses;
	}

	global $wpdb;

	$clauses['join']   .= " LEFT JOIN {$wpdb->postmeta} AS dmd_stock ON ( {$wpdb->posts}.ID = dmd_stock.post_id AND dmd_stock.meta_key = '_stock_status' ) ";
	$order              = "CASE WHEN dmd_stock.meta_value = 'outofstock' THEN 1 ELSE 0 END ASC";
	$clauses['orderby'] = $clauses['orderby'] ? $order . ', ' . $clauses['orderby'] : $order;

	return $clauses;
}
add_filter( 'posts_clauses', 'dmd_out_of_stock_last', 20, 2 );
