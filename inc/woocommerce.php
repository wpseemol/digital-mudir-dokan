<?php
/**
 * WooCommerce integration.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'woocommerce_enqueue_styles', '__return_false' );

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

	// Pagination is printed explicitly in archive-product.php.
	remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );

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
 * Whether the current request is a product category/tag/attribute archive.
 *
 * @return bool
 */
function dmd_is_product_tax_archive() {
	return function_exists( 'is_product_taxonomy' ) && is_product_taxonomy();
}

/**
 * Grid column count from Customizer (3 or 4).
 *
 * @return int
 */
function dmd_get_catalog_columns() {
	$key  = dmd_is_product_tax_archive() ? 'dmd_category_grid_columns' : 'dmd_shop_grid_columns';
	$cols = (int) get_theme_mod( $key, 4 );

	return in_array( $cols, array( 3, 4 ), true ) ? $cols : 4;
}

/**
 * Products-per-page from Customizer for the current catalog context.
 *
 * @return int
 */
function dmd_get_catalog_per_page() {
	$key = dmd_is_product_tax_archive() ? 'dmd_category_products_per_page' : 'dmd_shop_products_per_page';

	return max( 1, (int) get_theme_mod( $key, 12 ) );
}

/**
 * Products per row on archives.
 *
 * @return int
 */
function dmd_loop_columns() {
	return dmd_get_catalog_columns();
}
add_filter( 'loop_shop_columns', 'dmd_loop_columns', 20 );

/**
 * Products per page on archives.
 *
 * @param int $per_page Default per page.
 * @return int
 */
function dmd_loop_per_page( $per_page ) {
	return dmd_get_catalog_per_page();
}
add_filter( 'loop_shop_per_page', 'dmd_loop_per_page', 20 );

/**
 * Force Customizer per-page on the main product query (shop + tax archives).
 *
 * @param WP_Query $q Product query.
 */
function dmd_product_query_per_page( $q ) {
	$product_taxonomies = get_object_taxonomies( 'product', 'names' );

	if ( ! empty( $product_taxonomies ) && $q->is_tax( $product_taxonomies ) ) {
		$per_page = max( 1, (int) get_theme_mod( 'dmd_category_products_per_page', 12 ) );
	} else {
		$per_page = max( 1, (int) get_theme_mod( 'dmd_shop_products_per_page', 12 ) );
	}

	$q->set( 'posts_per_page', $per_page );
}
add_action( 'woocommerce_product_query', 'dmd_product_query_per_page' );

/**
 * Icon arrows on shop pagination.
 *
 * @param array $args Pagination args.
 * @return array
 */
function dmd_pagination_args( $args ) {
	$args['prev_text'] = '&larr;';
	$args['next_text'] = '&rarr;';
	$args['type']      = 'list';
	$args['end_size']  = 2;
	$args['mid_size']  = 2;
	return $args;
}
add_filter( 'woocommerce_pagination_args', 'dmd_pagination_args' );

/**
 * Catalog pagination for shop and product taxonomies.
 */
function dmd_woo_pagination() {
	global $wp_query;

	$total = isset( $wp_query->max_num_pages ) ? (int) $wp_query->max_num_pages : 0;
	$current = max( 1, get_query_var( 'paged' ) );

	if ( $total <= 1 ) {
		return;
	}

	echo '<div class="dmd-woo-pagination mt-10 flex justify-center">';
	
	// Testing native paginate_links as a fallback
	echo paginate_links( array(
		'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
		'format'    => '?paged=%#%',
		'current'   => $current,
		'total'     => $total,
		'prev_text' => '&larr;',
		'next_text' => '&rarr;',
		'type'      => 'list',
		'end_size'  => 2,
		'mid_size'  => 2,
	) );
	
	echo '</div>';
}

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
 * Get category thumbnail URL for a product.
 *
 * @param WC_Product $product Product.
 * @return string|false
 */
function dmd_get_category_thumbnail_url( $product ) {
    $terms = get_the_terms( $product->get_id(), 'product_cat' );
    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
        $term = reset( $terms );
        $thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
        if ( $thumbnail_id ) {
            return wp_get_attachment_url( $thumbnail_id );
        }
    }
    return false;
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
		'<a href="%1$s" class="dmd-btn dmd-btn--primary dmd-direct-checkout-btn %2$s" data-dmd-order-now="%3$d" rel="nofollow">%4$s</a>',
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

	// Variable products carry the chosen variation and its attribute values in
	// the query string, mirroring WC_Form_Handler::add_to_cart_action().
	$variation_id = 0;
	$variations   = array();

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( isset( $_GET['variation_id'] ) ) {
		$variation_id = absint( wp_unslash( $_GET['variation_id'] ) );
	}

	if ( $variation_id ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		foreach ( $_GET as $dmd_key => $dmd_value ) {
			if ( 'attribute_' !== substr( $dmd_key, 0, 10 ) ) {
				continue;
			}
			$variations[ sanitize_title( wp_unslash( $dmd_key ) ) ] = wc_clean( wp_unslash( $dmd_value ) );
		}
	}

	WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variations );

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

/* ---------------------------------------------------------------------------
 * AJAX Live Search
 * ------------------------------------------------------------------------- */

/**
 * Handle live search requests.
 */
function dmd_ajax_live_search() {
	check_ajax_referer( 'dmd_search_nonce', 'nonce' );

	$search_query = sanitize_text_field( wp_unslash( $_POST['query'] ) );

	if ( empty( $search_query ) ) {
		wp_send_json_error( __( 'Please enter a search term.', 'digital-mudir-dokan' ) );
	}

	$args = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => 5,
		's'              => $search_query,
	);

	$query = new WP_Query( $args );

	if ( ! $query->have_posts() ) {
		wp_send_json_error( __( 'No products found.', 'digital-mudir-dokan' ) );
	}

	ob_start();
	while ( $query->have_posts() ) {
		$query->the_post();
		?>
		<a href="<?php echo esc_url( get_permalink() ); ?>" class="dmd-search-result flex items-center gap-3 p-2 hover:bg-surface">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'h-10 w-10 object-cover rounded' ) ); ?>
			<?php endif; ?>
			<span class="text-sm font-medium"><?php the_title(); ?></span>
		</a>
		<?php
	}
	
	// Add View all link
	$search_url = add_query_arg( array( 's' => $search_query, 'post_type' => 'product' ), home_url( '/' ) );
	echo '<a href="' . esc_url( $search_url ) . '" class="block p-2 mt-2 text-sm text-center text-green font-semibold hover:underline border-t border-line">' . esc_html__( 'View all results', 'digital-mudir-dokan' ) . '</a>';

	wp_reset_postdata();
	$output = ob_get_clean();

	wp_send_json_success( $output );
}
add_action( 'wp_ajax_dmd_live_search', 'dmd_ajax_live_search' );
