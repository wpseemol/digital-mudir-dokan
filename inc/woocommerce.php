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
 * Archives always list products; categories are shown as chips and in the
 * sidebar. A "Show categories" display setting would otherwise make
 * WooCommerce hide sorting and treat the page as having no products.
 *
 * @param string $value Stored display mode.
 * @return string
 */
function dmd_force_products_display( $value ) {
	return 'subcategories' === $value ? '' : $value;
}
add_filter( 'option_woocommerce_shop_page_display', 'dmd_force_products_display' );
add_filter( 'option_woocommerce_category_archive_display', 'dmd_force_products_display' );

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

	$links = paginate_links(
		array(
			'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
			'format'    => '?paged=%#%',
			'current'   => $current,
			'total'     => $total,
			'prev_text' => dmd_icon( 'chevron-l', 16 ) . '<span class="screen-reader-text">' . esc_html__( 'Previous page', 'digital-mudir-dokan' ) . '</span>',
			'next_text' => '<span class="screen-reader-text">' . esc_html__( 'Next page', 'digital-mudir-dokan' ) . '</span>' . dmd_icon( 'chevron-r', 16 ),
			'type'      => 'list',
			'end_size'  => 1,
			'mid_size'  => 2,
		)
	);

	if ( ! $links ) {
		return;
	}

	echo '<nav class="dmd-woo-pagination" aria-label="' . esc_attr__( 'Products pagination', 'digital-mudir-dokan' ) . '">';
	echo wp_kses( $links, dmd_svg_allowed_html() );
	echo '</nav>';
}

/**
 * Recursive category list for the shop sidebar.
 *
 * @param int   $parent     Parent term ID.
 * @param int   $current_id Current category ID.
 * @param int[] $open_ids   IDs whose children are shown.
 */
function dmd_sidebar_category_tree( $parent, $current_id, $open_ids ) {
	$terms = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'parent'     => $parent,
			'hide_empty' => false,
			'exclude'    => array( (int) get_option( 'default_product_cat' ) ),
		)
	);

	if ( ! $terms || is_wp_error( $terms ) ) {
		return;
	}

	echo 0 === $parent ? '<ul class="dmd-product-categories">' : '<ul class="children">';

	foreach ( $terms as $term ) {
		$is_current = $term->term_id === $current_id;
		printf(
			'<li class="%1$s"><a href="%2$s"%3$s><span class="truncate">%4$s</span><span class="ml-2 shrink-0 rounded-full bg-surface px-2 py-0.5 text-[11px] text-muted">%5$s</span></a>',
			esc_attr( $is_current ? 'current-cat' : '' ),
			esc_url( get_term_link( $term ) ),
			$is_current ? ' aria-current="page"' : '',
			esc_html( $term->name ),
			esc_html( number_format_i18n( $term->count ) )
		);

		if ( in_array( $term->term_id, $open_ids, true ) ) {
			dmd_sidebar_category_tree( $term->term_id, $current_id, $open_ids );
		}

		echo '</li>';
	}

	echo '</ul>';
}

/**
 * "Showing 1–12 of 40 products" for the catalog toolbar.
 *
 * WooCommerce's own result count relies on loop props that the theme's
 * archive template doesn't set, so this reads the main query directly.
 */
function dmd_result_count() {
	global $wp_query;

	$total = (int) $wp_query->found_posts;
	if ( $total < 1 ) {
		return;
	}

	$per_page = (int) $wp_query->get( 'posts_per_page' );
	$current  = max( 1, (int) get_query_var( 'paged' ) );

	echo '<p class="woocommerce-result-count m-0 text-sm text-muted">';
	if ( $per_page < 1 || $total <= $per_page ) {
		/* translators: %d: total results. */
		printf( esc_html( _n( 'Showing the single product', 'Showing all %d products', $total, 'digital-mudir-dokan' ) ), (int) $total );
	} else {
		$first = ( $per_page * $current ) - $per_page + 1;
		$last  = min( $total, $per_page * $current );
		printf(
			/* translators: 1: first result, 2: last result, 3: total results. */
			esc_html__( 'Showing %1$d–%2$d of %3$d products', 'digital-mudir-dokan' ),
			(int) $first,
			(int) $last,
			(int) $total
		);
	}
	echo '</p>';
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
 * Add-to-cart link for the theme's product cards.
 *
 * Carries WooCommerce's classes and data attributes so the core AJAX script
 * handles it, and still runs through `woocommerce_loop_add_to_cart_link`.
 *
 * @param WC_Product $product Product.
 * @param string     $variant `icon` (square cart button) or `full`.
 */
function dmd_loop_add_to_cart_button( $product, $variant = 'full' ) {
	$is_icon = 'icon' === $variant;

	$classes = array(
		'button',
		'dmd-atc',
		'product_type_' . $product->get_type(),
		$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
		$product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
		$is_icon
			? 'inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-green/20 bg-green-soft text-green transition-colors hover:border-green hover:bg-green hover:text-white'
			: 'inline-flex h-10 w-full items-center justify-center gap-2 rounded-lg border border-green bg-white px-3 text-[13px] font-semibold text-green transition-colors hover:bg-green hover:text-white',
	);

	$label = $is_icon
		? dmd_icon( 'cart', 18 ) . '<span class="screen-reader-text">' . esc_html( $product->add_to_cart_text() ) . '</span>'
		: ( $product->is_purchasable() && $product->is_in_stock() ? dmd_icon( 'cart', 16 ) : '' ) . '<span>' . esc_html( $product->add_to_cart_text() ) . '</span>';

	$html = sprintf(
		'<a href="%1$s" data-quantity="1" class="%2$s" data-product_id="%3$d" data-product_sku="%4$s" aria-label="%5$s" title="%6$s" rel="nofollow">%7$s</a>',
		esc_url( $product->add_to_cart_url() ),
		esc_attr( implode( ' ', array_filter( $classes ) ) ),
		(int) $product->get_id(),
		esc_attr( $product->get_sku() ),
		esc_attr( $product->add_to_cart_description() ),
		esc_attr( $product->add_to_cart_text() ),
		$label
	);

	$allowed         = dmd_svg_allowed_html();
	$allowed['a']    = array(
		'href'            => true,
		'class'           => true,
		'data-quantity'   => true,
		'data-product_id' => true,
		'data-product_sku' => true,
		'aria-label'      => true,
		'title'           => true,
		'rel'             => true,
	);

	echo wp_kses( apply_filters( 'woocommerce_loop_add_to_cart_link', $html, $product, array() ), $allowed );
}

/**
 * Space-separated category slugs (including parents) for card filtering.
 *
 * @param WC_Product $product Product.
 * @return string
 */
function dmd_get_product_cat_slugs( $product ) {
	$slugs = array();
	$terms = get_the_terms( $product->get_id(), 'product_cat' );

	if ( $terms && ! is_wp_error( $terms ) ) {
		foreach ( $terms as $term ) {
			$slugs[] = $term->slug;
			foreach ( get_ancestors( $term->term_id, 'product_cat', 'taxonomy' ) as $ancestor_id ) {
				$ancestor = get_term( $ancestor_id, 'product_cat' );
				if ( $ancestor instanceof WP_Term ) {
					$slugs[] = $ancestor->slug;
				}
			}
		}
	}

	return implode( ' ', array_unique( $slugs ) );
}

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
	<a class="dmd-cart-link group relative inline-flex items-center gap-2.5 rounded-lg px-2 py-1.5 text-sm text-ink hover:bg-surface hover:text-ink"
		href="<?php echo esc_url( wc_get_cart_url() ); ?>"
		title="<?php esc_attr_e( 'View your shopping cart', 'digital-mudir-dokan' ); ?>">
		<span class="relative inline-flex h-10 w-10 items-center justify-center rounded-full bg-green text-white transition-colors group-hover:bg-green-dark">
			<?php dmd_the_icon( 'cart', 20 ); ?>
			<span class="dmd-cart-count absolute -right-1 -top-1 inline-flex h-5 min-w-5 items-center justify-center rounded-full border-2 border-white bg-green-dark px-1 text-[10px] font-bold leading-none text-white">
				<?php echo esc_html( $count ); ?>
			</span>
		</span>
		<span class="hidden flex-col leading-tight sm:flex">
			<span class="text-[11px] text-muted"><?php esc_html_e( 'My cart', 'digital-mudir-dokan' ); ?></span>
			<span class="dmd-cart-total text-sm font-semibold">
				<?php echo wp_kses_post( WC()->cart->get_cart_subtotal() ); ?>
			</span>
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

	$search_query = isset( $_POST['query'] ) ? sanitize_text_field( wp_unslash( $_POST['query'] ) ) : '';

	if ( mb_strlen( $search_query ) < 2 ) {
		wp_send_json_error( __( 'Type at least 2 characters.', 'digital-mudir-dokan' ) );
	}

	$query = new WP_Query(
		array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 8,
			's'              => $search_query,
			'tax_query'      => WC()->query->get_tax_query(), // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- respects catalog visibility.
		)
	);

	$categories = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'name__like' => $search_query,
			'hide_empty' => false,
			'number'     => 6,
		)
	);
	$categories = is_wp_error( $categories ) ? array() : $categories;

	if ( ! $query->have_posts() && ! $categories ) {
		/* translators: %s: search term. */
		wp_send_json_error( sprintf( __( 'No products found for “%s”.', 'digital-mudir-dokan' ), $search_query ) );
	}

	$search_url = add_query_arg(
		array(
			's'         => $search_query,
			'post_type' => 'product',
		),
		home_url( '/' )
	);

	ob_start();
	?>
	<div class="flex flex-col gap-4 p-4 lg:flex-row lg:gap-6 lg:p-5">
		<?php if ( $categories ) : ?>
			<div class="shrink-0 lg:w-56">
				<p class="mb-2 text-xs font-semibold uppercase tracking-wider text-muted"><?php esc_html_e( 'Categories', 'digital-mudir-dokan' ); ?></p>
				<ul class="m-0 flex list-none flex-wrap gap-2 p-0 lg:flex-col lg:gap-1">
					<?php foreach ( $categories as $category ) : ?>
						<li class="list-none">
							<a class="dmd-chip lg:w-full lg:justify-between lg:rounded-lg lg:border-0 lg:bg-surface" href="<?php echo esc_url( get_term_link( $category ) ); ?>">
								<?php echo esc_html( $category->name ); ?>
								<span class="text-xs text-muted"><?php echo esc_html( number_format_i18n( $category->count ) ); ?></span>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>

		<?php if ( $query->have_posts() ) : ?>
			<div class="min-w-0 flex-1">
				<p class="mb-2 text-xs font-semibold uppercase tracking-wider text-muted">
					<?php
					printf(
						/* translators: %s: number of matching products. */
						esc_html( _n( '%s product', '%s products', $query->found_posts, 'digital-mudir-dokan' ) ),
						esc_html( number_format_i18n( $query->found_posts ) )
					);
					?>
				</p>
				<ul class="m-0 grid list-none grid-cols-1 gap-2 p-0 sm:grid-cols-2 xl:grid-cols-4">
					<?php
					while ( $query->have_posts() ) :
						$query->the_post();
						$product = wc_get_product( get_the_ID() );
						if ( ! $product ) {
							continue;
						}
						?>
						<li class="list-none">
							<a class="dmd-search-result flex items-center gap-3 rounded-lg border border-transparent p-2 text-ink hover:border-line hover:bg-surface hover:text-ink" href="<?php echo esc_url( get_permalink() ); ?>">
								<span class="flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-md bg-surface">
									<?php echo wp_kses_post( $product->get_image( 'thumbnail', array( 'class' => 'h-full w-full object-contain mix-blend-multiply' ) ) ); ?>
								</span>
								<span class="min-w-0">
									<span class="line-clamp-2 block text-sm font-medium leading-snug"><?php the_title(); ?></span>
									<span class="dmd-search-price mt-0.5 block text-sm font-semibold text-green"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
								</span>
							</a>
						</li>
					<?php endwhile; ?>
				</ul>
			</div>
		<?php endif; ?>
	</div>

	<a class="flex items-center justify-center gap-1 border-t border-line bg-surface/60 px-4 py-3 text-sm font-semibold text-green hover:bg-green-soft hover:text-green" href="<?php echo esc_url( $search_url ); ?>">
		<?php
		/* translators: %s: search term. */
		printf( esc_html__( 'View all results for “%s”', 'digital-mudir-dokan' ), esc_html( $search_query ) );
		?>
		<?php dmd_the_icon( 'chevron-r', 14 ); ?>
	</a>
	<?php
	wp_reset_postdata();

	wp_send_json_success( ob_get_clean() );
}
add_action( 'wp_ajax_dmd_live_search', 'dmd_ajax_live_search' );
add_action( 'wp_ajax_nopriv_dmd_live_search', 'dmd_ajax_live_search' );
