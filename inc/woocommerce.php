<?php
/**
 * WooCommerce integration.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get product placeholder URL.
 *
 * @return string
 */
function dmd_get_product_placeholder_url() {
	$url = get_theme_mod( 'dmd_default_product_placeholder' );

	if ( $url ) {
		return esc_url( $url );
	}

	return wc_placeholder_img_src();
}

/**
 * Replace the plugin stylesheets with the theme build.
 *
 * @param array $styles Registered WooCommerce styles.
 * @return array
 */
function dmd_wc_styles( $styles ) {
	unset( $styles['woocommerce-general'], $styles['woocommerce-layout'], $styles['woocommerce-smallscreen'] );
	return $styles;
}
add_filter( 'woocommerce_enqueue_styles', 'dmd_wc_styles' );

/**
 * Rewire the default WooCommerce hooks to match the theme layout.
 */
function dmd_wc_hooks() {
	// Wrappers — header.php / footer.php already open and close the page shell.
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

	// The toolbar renders the count and sorting itself, but the hook still
	// fires in the template so notices and third-party additions survive.
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

	// Loop card is rebuilt in woocommerce/content-product.php.
	remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
	remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

	// Cross-sells move below the cart grid so they get full width.
	remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );

	// The single product summary is laid out by hand in
	// woocommerce/content-single-product.php. Only the pieces the template
	// prints itself are unhooked — `woocommerce_single_product_summary` is
	// still fired there so plugins that attach to it keep working.
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
}
add_action( 'init', 'dmd_wc_hooks' );

/**
 * Products per row on archives.
 *
 * @return int
 */
function dmd_loop_columns() {
	return 4;
}
add_filter( 'loop_shop_columns', 'dmd_loop_columns', 20 );

/**
 * Default products per page.
 *
 * @return int
 */
function dmd_products_per_page() {
	$allowed = array( 9, 12, 18, 24 );
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only display preference.
	$chosen = isset( $_GET['per_page'] ) ? absint( wp_unslash( $_GET['per_page'] ) ) : 0;

	if ( in_array( $chosen, $allowed, true ) ) {
		return $chosen;
	}

	return 12;
}
add_filter( 'loop_shop_per_page', 'dmd_products_per_page', 20 );

/**
 * Icon arrows on shop pagination.
 *
 * @param array $args Pagination args.
 * @return array
 */
function dmd_pagination_args( $args ) {
	$args['prev_text'] = dmd_icon( 'chevron-l', 16 );
	$args['next_text'] = dmd_icon( 'chevron-r', 16 );
	return $args;
}
add_filter( 'woocommerce_pagination_args', 'dmd_pagination_args' );

/**
 * Product thumbnail placeholder size.
 *
 * @return string
 */
function dmd_placeholder_size() {
	return 'dmd-product-card';
}
add_filter( 'woocommerce_placeholder_img_size', 'dmd_placeholder_size' );

/**
 * Show the sale percentage instead of the word “Sale”, matching the design.
 *
 * @param string     $html    Default markup.
 * @param WP_Post    $post    Post object.
 * @param WC_Product $product Product object.
 * @return string
 */
function dmd_sale_percentage( $html, $post, $product ) {
	if ( ! $product->is_on_sale() ) {
		return '';
	}

	$percentage = dmd_get_sale_percentage( $product );

	if ( ! $percentage ) {
		return $html;
	}

	return sprintf(
		'<span class="dmd-badge dmd-badge--sale absolute left-3 top-3 z-10">-%d%%</span>',
		$percentage
	);
}
add_filter( 'woocommerce_sale_flash', 'dmd_sale_percentage', 10, 3 );

/**
 * Largest discount across a product (handles variable products).
 *
 * @param WC_Product $product Product.
 * @return int
 */
function dmd_get_sale_percentage( $product ) {
	$percentages = array();

	if ( $product->is_type( 'variable' ) ) {
		foreach ( $product->get_children() as $child_id ) {
			$variation = wc_get_product( $child_id );
			if ( ! $variation ) {
				continue;
			}
			$regular = (float) $variation->get_regular_price();
			$sale    = (float) $variation->get_sale_price();
			if ( $regular > 0 && $sale > 0 ) {
				$percentages[] = round( 100 - ( $sale / $regular * 100 ) );
			}
		}
	} else {
		$regular = (float) $product->get_regular_price();
		$sale    = (float) $product->get_sale_price();
		if ( $regular > 0 && $sale > 0 ) {
			$percentages[] = round( 100 - ( $sale / $regular * 100 ) );
		}
	}

	return $percentages ? (int) max( $percentages ) : 0;
}

/**
 * Style the loop add-to-cart button without dropping the classes WooCommerce
 * needs for AJAX (`ajax_add_to_cart`, `product_type_*`).
 *
 * @param array      $args    Button args.
 * @param WC_Product $product Product.
 * @return array
 */
function dmd_loop_add_to_cart_args( $args, $product ) {
	$args['class'] = trim( $args['class'] . ' dmd-btn dmd-btn--primary dmd-btn--block' );
	return $args;
}
add_filter( 'woocommerce_loop_add_to_cart_args', 'dmd_loop_add_to_cart_args', 10, 2 );

/**
 * Style the place-order button on checkout.
 *
 * @param string $html Button markup.
 * @return string
 */
function dmd_order_button_html( $html ) {
	return str_replace( 'class="button', 'class="dmd-btn dmd-btn--primary dmd-btn--block button', $html );
}
add_filter( 'woocommerce_order_button_html', 'dmd_order_button_html' );

/**
 * Number of gallery thumbnails per row.
 *
 * @return int
 */
function dmd_gallery_columns() {
	return 3;
}
add_filter( 'woocommerce_product_thumbnails_columns', 'dmd_gallery_columns' );

/* ---------------------------------------------------------------------------
 * Express order button — add to cart, then go straight to checkout.
 * ------------------------------------------------------------------------- */

/**
 * The configured label for the express order button.
 *
 * @return string
 */
function dmd_order_button_label() {
	return get_theme_mod( 'dmd_order_button_text', __( 'অর্ডার করুন', 'digital-mudir-dokan' ) );
}

/**
 * Render the express order button for a product.
 *
 * @param WC_Product $product Product. Defaults to the global product.
 * @param string     $classes Extra classes.
 */
function dmd_order_now_button( $product = null, $classes = '' ) {
	$product = $product ? $product : $GLOBALS['product'];

	if ( ! $product instanceof WC_Product || ! $product->is_purchasable() || ! $product->is_in_stock() ) {
		return;
	}

	$url = wp_nonce_url(
		add_query_arg(
			array(
				'dmd-order-now' => $product->get_id(),
			),
			wc_get_checkout_url()
		),
		'dmd_order_now',
		'dmd_nonce'
	);

	printf(
		'<a href="%1$s" class="dmd-btn dmd-btn--primary %2$s" data-dmd-order-now="%3$d" rel="nofollow">%4$s</a>',
		esc_url( $url ),
		esc_attr( $classes ),
		(int) $product->get_id(),
		esc_html( dmd_order_button_label() )
	);
}

/**
 * Handle the express order request before the checkout renders.
 */
function dmd_handle_order_now() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( empty( $_GET['dmd-order-now'] ) ) {
		return;
	}

	if ( ! isset( $_GET['dmd_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['dmd_nonce'] ) ), 'dmd_order_now' ) ) {
		return;
	}

	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return;
	}

	$product_id = absint( wp_unslash( $_GET['dmd-order-now'] ) );
	$product    = wc_get_product( $product_id );

	if ( ! $product || ! $product->is_purchasable() ) {
		return;
	}

	$quantity = 1;
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( isset( $_GET['quantity'] ) ) {
		$quantity = max( 1, absint( wp_unslash( $_GET['quantity'] ) ) );
	}

	WC()->cart->add_to_cart( $product_id, $quantity );

	wp_safe_redirect( wc_get_checkout_url() );
	exit;
}
add_action( 'template_redirect', 'dmd_handle_order_now', 5 );

/* ---------------------------------------------------------------------------
 * Mini cart fragments
 * ------------------------------------------------------------------------- */

/**
 * Refresh the header cart without a page reload.
 *
 * @param array $fragments Fragments.
 * @return array
 */
function dmd_cart_fragments( $fragments ) {
	ob_start();
	dmd_cart_link();
	$fragments['a.dmd-cart-link'] = ob_get_clean();

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'dmd_cart_fragments' );

/**
 * Header cart link with a live item count and total.
 */
function dmd_cart_link() {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return;
	}

	$count = WC()->cart->get_cart_contents_count();
	?>
	<a class="dmd-cart-link relative inline-flex items-center gap-2 text-sm"
		href="<?php echo esc_url( wc_get_cart_url() ); ?>"
		title="<?php esc_attr_e( 'View your shopping cart', 'digital-mudir-dokan' ); ?>">
		<span class="relative inline-flex">
			<?php dmd_the_icon( 'cart', 22 ); ?>
			<span class="dmd-cart-count absolute -right-2 -top-2 inline-flex h-[18px] min-w-[18px] items-center justify-center rounded-full bg-green px-1 text-[10px] font-semibold text-white">
				<?php echo esc_html( $count ); ?>
			</span>
		</span>
		<span class="dmd-cart-total hidden font-semibold sm:inline">
			<?php echo wp_kses_post( WC()->cart->get_cart_subtotal() ); ?>
		</span>
		<span class="screen-reader-text">
			<?php
			/* translators: %d: number of items in the cart. */
			printf( esc_html( _n( '%d item in cart', '%d items in cart', $count, 'digital-mudir-dokan' ) ), (int) $count );
			?>
		</span>
	</a>
	<?php
}

/**
 * Checkout field polish — placeholders and a two-column grid.
 *
 * @param array $fields Checkout fields.
 * @return array
 */
function dmd_checkout_fields( $fields ) {
	if ( isset( $fields['billing']['billing_phone'] ) ) {
		$fields['billing']['billing_phone']['placeholder'] = __( '01XXXXXXXXX', 'digital-mudir-dokan' );
		$fields['billing']['billing_phone']['priority']    = 25;
		$fields['billing']['billing_phone']['required']    = true;
	}

	if ( isset( $fields['billing']['billing_email'] ) ) {
		$fields['billing']['billing_email']['placeholder'] = __( 'name@example.com', 'digital-mudir-dokan' );
	}

	foreach ( array( 'billing', 'shipping' ) as $group ) {
		if ( empty( $fields[ $group ] ) ) {
			continue;
		}
		foreach ( $fields[ $group ] as $key => $field ) {
			$fields[ $group ][ $key ]['class'][] = 'dmd-form-row';
		}
	}

	return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'dmd_checkout_fields' );

/**
 * Show four cross-sells on the cart page so the row matches the product grid.
 *
 * @param int $count Cross-sell count.
 * @return int
 */
function dmd_cross_sells_total( $count ) {
	return 4;
}
add_filter( 'woocommerce_cross_sells_total', 'dmd_cross_sells_total' );

/**
 * Related products count.
 *
 * @param array $args Query args.
 * @return array
 */
function dmd_related_products_args( $args ) {
	$args['posts_per_page'] = 4;
	$args['columns']        = 4;
	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'dmd_related_products_args', 20 );

/**
 * Track recently viewed products so the single product page can show them.
 */
function dmd_track_recently_viewed() {
	if ( ! is_singular( 'product' ) ) {
		return;
	}

	// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$viewed = empty( $_COOKIE['dmd_recently_viewed'] ) ? array() : (array) explode( '|', sanitize_text_field( wp_unslash( $_COOKIE['dmd_recently_viewed'] ) ) );
	$viewed = array_filter( array_map( 'absint', $viewed ) );

	$id     = get_the_ID();
	$viewed = array_diff( $viewed, array( $id ) );
	array_unshift( $viewed, $id );
	$viewed = array_slice( $viewed, 0, 12 );

	wc_setcookie( 'dmd_recently_viewed', implode( '|', $viewed ) );
}
add_action( 'template_redirect', 'dmd_track_recently_viewed', 20 );

/**
 * IDs of recently viewed products, excluding the one on screen.
 *
 * @param int $exclude Product ID to skip.
 * @return array
 */
function dmd_get_recently_viewed( $exclude = 0 ) {
	// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$viewed = empty( $_COOKIE['dmd_recently_viewed'] ) ? array() : (array) explode( '|', sanitize_text_field( wp_unslash( $_COOKIE['dmd_recently_viewed'] ) ) );
	$viewed = array_filter( array_map( 'absint', $viewed ) );

	if ( $exclude ) {
		$viewed = array_diff( $viewed, array( $exclude ) );
	}

	return array_slice( $viewed, 0, 6 );
}

/**
 * Add the express order button under the single product add-to-cart form.
 */
function dmd_single_order_now() {
	global $product;

	if ( ! $product instanceof WC_Product ) {
		return;
	}

	echo '<div class="dmd-single-order mt-3">';
	dmd_order_now_button( $product, 'dmd-btn--block' );
	echo '</div>';
}
add_action( 'woocommerce_after_add_to_cart_form', 'dmd_single_order_now', 5 );
