<?php
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 ); 

remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );

add_filter( 'woocommerce_subcategory_count_html', '__return_false' );

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );

remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );

remove_action( 'woocommerce_thankyou', 'woocommerce_order_details_table', 10 );

function cod_delivery_icon() {
	return get_template_directory_uri() . '/images/cod.png';
}
add_filter( 'woocommerce_cod_icon', 'cod_delivery_icon' );

function custom_single_product_data_tabs() {
	get_template_part('template-parts/wc/custom-tabs');
}
add_action( 'woocommerce_after_single_product_summary', 'custom_single_product_data_tabs', 10 );

/**
 * Custom Gallery
 */
function show_single_product_images() {
	get_template_part('template-parts/wc/gallery');
}
add_action( 'woocommerce_before_single_product_summary', 'show_single_product_images', 20 );

/**
 * Add QTY LABEL
 */
function add_qty_label() {
	echo '<span class="qty-label">Qty:</span>';
}
add_action( 'woocommerce_before_add_to_cart_quantity', 'add_qty_label' );

/**
 * Display Brand Logo
 */
function content_loop_brand_logo() {
	global $product;
	$product = wc_get_product( get_the_id() );
	$brand = $product->get_attribute( 'pa_brand' );

	if( $brand ):
		$term = get_term_by( 'name', $brand, 'pa_brand' );
		$img = get_option( '_brand_' . $term->slug );
		if( $img ):
			echo sprintf( '<img class="brand" src="%s">', $img );
		else:
			echo '<div class="brand-placeholder"></div>';
		endif;
	else:
		echo '<div class="brand-placeholder"></div>';
	endif;
}
add_action( 'woocommerce_before_shop_loop_item', 'content_loop_brand_logo', 15 );

/** 
 * Display SKU
 */
function erf_content_sku(){
	global $product;

	if( is_archive() ):
		$html = '<span class="archive-sku">' . $product->get_sku() . '</span>';
	else:
		$html = '<span class="sku">SKU: ' . $product->get_sku() . '</span>';
	endif;

	echo $html;
}
add_action( 'woocommerce_single_product_summary', 'erf_content_sku', 23 );

/**
 * Prepopulate Region Select
 */
function prepopulate_store_regions( $field ) {
    
    $field['choices'] = array();
    
    $choices = get_field( 'regions', 'options', false);
    
    if( is_array($choices) ) {
        foreach( $choices as $key => $choice ) {
            $field['choices'][ $choice['field_60783cbe09b6a'] ] = $choice['field_60783cbe09b6a'];
        }
    }
   
    return $field;
    
}
add_filter('acf/load_field/name=store_region', 'prepopulate_store_regions');

/**
 * Display Pricing with VAT
 */
function add_tax_to_price( $html ) {

	global $product;
	if($product){
		$html = '';
		$vat_enabled	= $_SESSION['show_vat'];
		$price 			= $product->get_price();
		$incTax 		= wc_price( $product->get_price_including_tax() );
		$exTax 			= wc_price( $product->get_price_excluding_tax() );

		if( $price ) {
			if( $vat_enabled == 'true' ) {
				$html = $incTax;
				$html .= '<small>inc. VAT</small>';
				$html .= '<span class="alternate">' . $exTax . '<small>ex. VAT</small></span>';
			} else {
				$html = $exTax;
				$html .= '<small>ex. VAT</small>';
				$html .= '<span class="alternate">' . $incTax . '<small>inc. VAT</small></span>';
			}
		} else {
			$html = '<div class="price-placeholder"></div>';
		}
	}

	
	return $html;
}
add_filter( 'woocommerce_get_price_html', 'add_tax_to_price', 10 );

/**
 * Display - before QTY
 */
function display_minus_qty() {
	echo '<button class="minus-qty">-</button>';
}
add_action( 'woocommerce_before_add_to_cart_quantity', 'display_minus_qty' );

/**
 * Display + after QTY
 */
function display_plus_qty() {
	echo '<button class="plus-qty">+</button>';
}
add_action( 'woocommerce_after_add_to_cart_quantity', 'display_plus_qty' );

/**
 *	Format Shipping Address with commas
 */
function format_shipping_address( $address ) {
	$newAddress = '';
	foreach( $address as $key => $item ):
		if ( $item != "" AND $key != 'CSD_ID' ):
			if( $key == 'first_name' ):
				$newAddress .= $item . ' ';
			else:
				$newAddress .= $item . ', ';
			endif;
		endif;
	endforeach;
	return rtrim( $newAddress, ', ' );
}

function ajax_get_branch_stock() {
	$products = ( isset( $_POST['products'] )) ? $_POST['products'] : array();

	$skuList = array();

	if( !empty( $products ) ):
		foreach( $products as $id ):
			$product = wc_get_product( $id );
			array_push( $skuList, $product->get_sku() ); 
		endforeach;
	endif;

	$response = getBranchStockAndPrice( '', $skuList );

	echo json_encode( $response );

	wp_die();
}
add_action( 'wp_ajax_erf_get_branch_stock', 'ajax_get_branch_stock' );
add_action( 'wp_ajax_nopriv_erf_get_branch_stock', 'ajax_get_branch_stock' );

function ajax_get_store_details() {
	$id = ( isset( $_POST['store_id'] )) ? $_POST['store_id'] : null;

	if( !is_null( $id ) ) {

		$address1 = get_post_meta( $id, 'wpsl_address', true );
		$address2 = get_post_meta( $id, 'wpsl_address2', true );
		$city = get_post_meta( $id, 'wpsl_city', true );
		$zip = get_post_meta( $id, 'wpsl_zip', true );
		$address = sprintf( '<p>%s, %s, %s, %s</p>', $address1, $address2, $city, $zip );
		
		$openingTimes = get_post_meta( $id, 'wpsl_hours', true );
		$telephone = get_post_meta( $id, 'wpsl_phone', true );
		$email = get_post_meta( $id, 'wpsl_email', true );
		
		$details['address'] = $address;
		$details['business_hours'] = $openingTimes;
		$details['telephone'] = $telephone;
		$details['email'] = $email;

		echo json_encode( $details );
	}

	wp_die();
}
add_action( 'wp_ajax_get_branch_details', 'ajax_get_store_details' );
add_action( 'wp_ajax_nopriv_get_branch_details', 'ajax_get_store_details' );

/**
 * use cash on delivery for payment on account
 * Remove it from unrecognized logged out customers
 * or users without permission
 */
function setup_payment_gateways( $available_gateways ) {

	$access = get_user_meta( get_current_user_id(), 'wpiai_selltype', true );

	if( !is_user_logged_in() || strtolower($access) != 'account' ) {
		unset( $available_gateways['cod'] );
	}

	return $available_gateways;
}
add_filter( 'woocommerce_available_payment_gateways', 'setup_payment_gateways' );

/**
 * Adjust Checkout Fields
 */
function filter_woocommerce_fields( $fields ) {

	$reorder = array();

    $order = array(
    	'shipping_first_name' => 10,
    	'shipping_address_1' => 20,
		'shipping_last_name' => 30,
		'shipping_address_2' => 40,
		'shipping_postcode' => 60,
		'shipping_company' => 70,
		'shipping_city' => 80,
		'shipping_country' => 90,
		//'shipping_state' => 100,
    );

    foreach( $order as $key => $field ):
    	$fields['shipping'][$key]['priority'] = $field;
    	$fields['shipping'][$key]['class'] = array();
    	$fields['shipping'][$key]['label_class'] = array();
  		$reorder[$key] = $fields['shipping'][$key];
    endforeach;

    $fields['shipping'] = $reorder;

    $fields['shipping']['shipping_address_1']['label'] = 'Address 1';
    $fields['shipping']['shipping_address_2']['label'] = 'Address 2';
    $fields['shipping']['shipping_address_2']['placeholder'] = '';

    $fields['billing']['billing_address_2']['label'] = 'Street Address 2';
    $fields['billing']['billing_address_2']['label_class'] = array();

    unset( $fields['billing']['billing_state'] );

    return $fields;

}
add_filter( 'woocommerce_checkout_fields', 'filter_woocommerce_fields' );

function ajax_remove_unavailable_items() {
	$items = $_POST['data'];

	foreach( $items as $item ):
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			if ( $cart_item['product_id'] == $item['product_id'] ) {

				if( $item['stock_level'] == 0 ):
					WC()->cart->remove_cart_item( $cart_item_key );
				else:
					WC()->cart->set_quantity( $cart_item_key, $item['stock_level'] );
				endif;

			}
		}
	endforeach;

	wp_die();
}
add_action( 'wp_ajax_remove_unavailable_items', 'ajax_remove_unavailable_items' );
add_action( 'wp_ajax_nopriv_remove_unavailable_items', 'ajax_remove_unavailable_items' );

/**
 * Display Custom Version of Mini cart
 */
function custom_wc_mini_cart() {

	ob_start();

	get_template_part('template-parts/wc/mini-cart');

	$mini_cart = ob_get_clean();

	echo $mini_cart;

	wp_die();
}
add_action( 'wp_ajax_display_updated_cart', 'custom_wc_mini_cart' );
add_action( 'wp_ajax_nopriv_display_updated_cart', 'custom_wc_mini_cart' );

/**
 * Display WC Mini Cart
 */
function display_mini_cart() {

	$method = $_POST['method'];

	ob_start();
	
	if( $method == 'pickup' ):
		get_template_part('template-parts/wc/review-order-override');
		$review = ob_get_clean();
	else:

		$min_cost = 50;
		$cart_value = WC()->cart->get_cart_contents_total();

		if( $cart_value > $min_cost ):
			echo 'FREE';
			get_template_part('template-parts/wc/review-order-override');
		else:
			echo 'FLAT RATE';
			woocommerce_order_review();
		endif;

		$review = ob_get_clean();
	endif;

	wp_send_json( $review );

}
add_action( 'wp_ajax_get_mini_cart', 'display_mini_cart' );
add_action( 'wp_ajax_nopriv_get_mini_cart', 'display_mini_cart' );

/**
 * Disable Persistant WC
 */
add_filter('woocommerce_persistent_cart_enabled', function () {
    return false;
});

/**
 * Returns Cart Total via AJAX
 */
function get_cart_total_ajax() {
	$total = ( WC()->cart->get_cart_contents_tax() + WC()->cart->get_cart_contents_total() );
	wp_send_json( $total );
}
add_action( 'wp_ajax_get_cart_total', 'get_cart_total_ajax' );
add_action( 'wp_ajax_nopriv_get_cart_total', 'get_cart_total_ajax' );

/**
 * VAT TOGGLER VIA AJAX
 */
function toggle_vat() {

	$vat_enabled = $_POST['vat'];
	$_SESSION['show_vat'] = $vat_enabled;

	wp_die();
}
add_action( 'wp_ajax_toggle_vat', 'toggle_vat' );
add_action( 'wp_ajax_nopriv_toggle_vat', 'toggle_vat' );

function display_loop_add_to_cart() {
	get_template_part('template-parts/wc/add-to-cart-loop');
}
add_action( 'woocommerce_after_shop_loop_item', 'display_loop_add_to_cart', 15 );

function remove_orders_pagination( $args ) {
	$args['limit'] = -1;
	return $args;
}
add_filter( 'woocommerce_my_account_my_orders_query', 'remove_orders_pagination' );

function return_csd_order_id( $order ) {
	$order_id = $order->get_id();
	$csd_id = get_csd_order_id( $order_id );

	if( $csd_id ) {
		echo $csd_id;
	} else {
		echo '#' . $order_id;
	}
}
add_action( 'woocommerce_my_account_my_orders_column_order-number', 'return_csd_order_id' );

/**
 * Change amount of columns displayed on homepage
 * and retain the amount on archive pages
 */
function loop_columns( $col ) {
	if( is_front_page() ):
		return 4;
	else:
    	return $col;
    endif;
}
add_filter('loop_shop_columns', 'loop_columns', 999);

/**
 * Checkout
 * Add META_VALUE for which contact placed the order
 */
function set_contact_on_order( $order_id ) {
	global $contact;
	update_post_meta( $order_id, '_contact_ordered', $contact->get_id() );
}
add_action( 'woocommerce_thankyou', 'set_contact_on_order', 5 );

/**
 * Checkout
 * Add Additional meta data to order
 */
function before_checkout_create_order( $order, $data ) {

	/**
	 * The contact we are shipping to
	 */
	$shipTo = $_POST['contact_shipto'];

	if( $shipTo == 'new_contact' ):
		$shipTo = array();
		$shipTo['first_name'] 	= sanitize_text_field( $_POST['contact_shipto_first_name'] );
		$shipTo['last_name'] 	= sanitize_text_field( $_POST['contact_shipto_last_name'] );
		$shipTo['telephone']	= sanitize_text_field( $_POST['contact_shipto_telephone'] );
	endif;

    $order->update_meta_data( '_contact_shipto', $shipTo );

    /**
     * The address we are shipping to
     */
    $checked = $_POST['use_differant_shipto'];
    $shipTo = $_POST['shipping_address'];

    if( $shipTo == 'new_shipto' ):
    	$shipTo = array();
    	if( $checked ):
    		$shipTo['type']	= 'CHECKED';
	    	$shipTo['shipping_first_name'] = $_POST['shipping_first_name'];
	    	$shipTo['shipping_last_name'] = $_POST['shipping_last_name'];
	    	$shipTo['shipping_address_1'] = $_POST['shipping_address_1'];
	    	$shipTo['shipping_address_2'] = $_POST['shipping_address_2'];
	    	$shipTo['shipping_postcode'] = $_POST['shipping_postcode'];
	    	$shipTo['shipping_city'] = $_POST['shipping_city'];
	    	$shipTo['shipping_company'] = $_POST['shipping_company'];
	    	$shipTo['shipping_country'] = $_POST['shipping_country'];
    	else:
    		$shipTo['type']	= 'UN-CHECKED';
    		$shipTo['shipping_first_name'] = $_POST['billing_first_name'];
	    	$shipTo['shipping_last_name'] = $_POST['billing_last_name'];
	    	$shipTo['shipping_address_1'] = $_POST['billing_address_1'];
	    	$shipTo['shipping_address_2'] = $_POST['billing_address_2'];
	    	$shipTo['shipping_postcode'] = $_POST['billing_postcode'];
	    	$shipTo['shipping_city'] = $_POST['billing_city'];
	    	$shipTo['shipping_company'] = $_POST['billing_company'];
	    	$shipTo['shipping_country'] = $_POST['billing_country'];
    	endif;
    endif;

    $order->update_meta_data( '_shippingto_address', $shipTo );

    /**
     * Delivery Type
     */
    $order->update_meta_data( '_delivery_type', sanitize_text_field( $_POST['delivery_type'] ) );
}
add_action('woocommerce_checkout_create_order', 'before_checkout_create_order', 20, 2);

/**
 * Set STEPS based on Product Attributes
 */
function filter_qty_steps( $args ) {
	
	$terms = get_the_terms( get_the_id(), 'pa_selling-multiple' );
	if( !is_wp_error($terms) ):
		$args['min_value'] = $terms[0]->name;
		$args['step']	= $terms[0]->name;
	endif;

	return $args;
}
add_filter( 'woocommerce_quantity_input_args', 'filter_qty_steps' );

/**
 * Set Delivery for CHECKOUT
 */
function change_shipto_posted_data( $data ) {
	$shipping_method = array ($_POST['selected_shipping_method'] );

	$data['shipping_method'] = $shipping_method;

	return $data;
}
add_filter( 'woocommerce_checkout_posted_data', 'change_shipto_posted_data' );



