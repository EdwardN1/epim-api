<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wpiai_api_checkSecure() {
	if ( ! check_ajax_referer( 'wpiai-security-nonce', 'security' ) ) {
		wp_send_json_error( 'Invalid security token sent.' );
		wp_die();
	}
}

add_action( 'wp_ajax_wpiai_get_access_token', 'ajax_get_wpiai_get_access_token' );
add_action( 'wp_ajax_wpiai_get_message_response', 'ajax_get_wpiai_get_message_response' );
add_action( 'wp_ajax_wpiai_get_infor_ping', 'ajax_get_wpiai_get_infor_ping' );

add_action( 'wp_ajax_wpiai_get_customer_response', 'ajax_get_wpiai_get_customer_response' );
add_action( 'wp_ajax_wpiai_get_customer_xml', 'ajax_get_wpiai_get_customer_xml' );
add_action( 'wp_ajax_wpiai_get_customer_params', 'ajax_get_wpiai_get_customer_params' );

add_action( 'wp_ajax_wpiai_get_sales_order_response', 'ajax_get_wpiai_get_sales_order_response' );
add_action( 'wp_ajax_wpiai_get_sales_order_xml', 'ajax_get_wpiai_get_sales_order_xml' );
add_action('wp_ajax_wpiai_get_changed_order_XML','ajax_get_wpiai_get_changed_order_XML');

add_action( 'wp_ajax_wpiai_get_ship_to_response', 'ajax_get_wpiai_get_ship_to_response' );
add_action( 'wp_ajax_wpiai_get_ship_to_xml', 'ajax_get_wpiai_get_ship_to_xml' );

add_action( 'wp_ajax_wpiai_get_contact_response', 'ajax_get_wpiai_get_contact_response' );
add_action( 'wp_ajax_wpiai_get_contact_xml', 'ajax_get_wpiai_get_contact_xml' );

add_action( 'wp_ajax_wpiai_get_product_api_response', 'ajax_wpiai_get_product_api_response' );
add_action( 'wp_ajax_wpiai_update_default_prices', 'ajax_wpiai_update_default_prices' );
add_action( 'wp_ajax_wpiai_update_default_price_for_product', 'ajax_wpiai_update_default_price_for_product' );
add_action( 'wp_ajax_wpiai_test_get_infor_prices', 'ajax_wpiai_test_get_infor_prices' );

add_action( 'wp_ajax_wpiai_get_product_updates_api_response', 'ajax_wpiai_get_product_updates_api_response' );

add_action( 'wp_ajax_wpiai_get_accounts_customer_balances_api_response', 'ajax_wpiai_get_accounts_customer_balances_api_response' );
add_action( 'wp_ajax_wpiai_get_accounts_customer_data_credit_api_response', 'ajax_wpiai_get_accounts_customer_data_credit_api_response' );

add_action( 'wp_ajax_wpiai_get_invoices_api_response', 'ajax_wpiai_get_invoices_api_response' );
add_action( 'wp_ajax_wpiai_get_single_invoice_api_response', 'wpiai_get_single_invoice_api_response' );
add_action( 'wp_ajax_wpiai_get_single_invoice_api_response_test', 'wpiai_get_single_invoice_api_response_test' );

function ajax_wpiai_test_get_infor_prices () {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	echo json_encode(testgetInforPriceList());
	exit;
}

function ajax_wpiai_update_default_price_for_product() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	$ID = $_POST['sku'];
	//error_log('Updating price for product: '. $ID);
	_oi('Updating price for product: '. $ID,'ajax-handlers');
	if ( $ID ) {
		$productsList = array();
		$product      = wc_get_product( wc_get_product_id_by_sku($ID) );
		if ( $product ) {
			$productsList[] = $product->get_sku();;
			$defaultwhse                = get_option( 'wpiai_default_warehouse' );
			$defaultBranchStockAndPrice = getBranchStockAndPrice( '', $productsList );
			foreach ( $defaultBranchStockAndPrice as $item ) {
				if ( $item['warehouseID'] == $defaultwhse ) {
					$oldPrice = $product->get_price();
					$product->set_price( round( $item['price'], 2 ) );
					$product->set_regular_price( round( $item['price'], 2 ) ); // To be sure
					$product->set_manage_stock( true );
					$product->set_stock_quantity( $item['quantity'] );
					$product->save();
					echo 'Price for : ' . $product->get_sku() . ' updated from ' . $oldPrice . ' to ' . $product->get_price() . ' Quantity set to ' . $product->get_stock_quantity();
					//error_log('Price for : '.$product->get_sku().' updated from '.$oldPrice.' to '.$product->get_price());
					_oi('Price for : '.$product->get_sku().' updated from '.$oldPrice.' to '.$product->get_price(),'ajax-handlers');
					//break;
				}
			}
		} else {
			echo $ID . ' can not find that product';
			//error_log($ID . ' can not find that product');
			_oi($ID . ' can not find that product','ajax-handlers');
		}
	} else {
		echo 'No product ID requested';
		error_log('No product ID requested');
		_oi('No product ID requested','ajax-handlers');
	}
	exit;
}


function ajax_wpiai_update_default_prices() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	$wpiai_guest_customer_number = get_option( 'wpiai_guest_customer_number' );
	if ( $wpiai_guest_customer_number ) {
		$timeStart = microtime( true );
		$all_ids   = get_posts( array(
			'post_type'   => 'product',
			'numberposts' => - 1,
			'post_status' => 'publish',
			'fields'      => 'ids',
		) );
		$skus      = array();
		$skuBlocks = array();
		error_log( 'Number of products to update: ' . count( $all_ids ) );
		$i = 0;
		foreach ( $all_ids as $product_id ) {
			$product = wc_get_product( $product_id );
			$skus[]  = $product->get_sku();
			/*$i++;
			if($i>=100) {
				$skuBlocks[] = $skus;
				$skus = array();
				$i = 0;
			}*/
		}
		/*if(!empty($skus)) {
			$skuBlocks[] = $skus;
		}*/
		if ( ! empty( $skus ) ) {
			//echo json_encode(getBranchStockAndPrice($wpiai_guest_customer_number,$skus));
			//echo json_encode(createProductsRequest($wpiai_guest_customer_number,$skuBlocks[0]));
			echo json_encode( $skus );
		} else {
			echo 'No Products Found';
		}
		$timeEnd = microtime( true );
		$time    = $timeEnd - $timeStart;
		error_log( 'ajax_wpiai_update_default_prices took ' . $time . ' seconds' );
	} else {
		echo 'No Default Customer';
	}
	exit;
}

function wpiai_get_single_invoice_api_response_test() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	$url   = get_option( 'wpiai_single_invoice_url' );
	$order = $_POST['order'];
	//error_log($order);
	if ( $order ) {
		$response = getSingleInvoice( $order );
		//error_log(print_r($response,true));
		echo json_encode( $response );
	} else {
		echo 'Something went wrong.';
	}

	exit;
}

function wpiai_get_single_invoice_api_response() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	$url      = get_option( 'wpiai_single_invoice_url' );
	$request  = get_option( 'wpiai_single_invoice_request' );
	$response = wpiai_get_infor_api_response( $url, $request );
	echo $response;
	exit;
}

function ajax_wpiai_get_invoices_api_response() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	$url      = get_option( 'wpiai_invoices_url' );
	$request  = get_option( 'wpiai_invoices_request' );
	$response = wpiai_get_infor_api_response( $url, $request );
	echo $response;
	exit;
}

function ajax_get_wpiai_get_access_token() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	echo json_encode( wpiai_get_access_token() );
	exit;
}

function ajax_get_wpiai_get_message_response() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	echo json_encode( wpiai_get_test_response() );
	exit;
}

function ajax_get_wpiai_get_customer_response() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	echo json_encode( wpiai_get_customer_response() );
	exit;
}

function ajax_get_wpiai_get_customer_xml() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	echo json_encode( get_customer_XML_record( 4 ) );
	exit;
}

function ajax_wpiai_get_accounts_customer_data_credit_api_response() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	$url      = get_option( 'wpiai_accounts_customer_data_credit_url' );
	$request  = get_option( 'wpiai_accounts_request' );
	$response = wpiai_get_infor_api_response( $url, $request );
	echo $response;
	exit;
}

function ajax_wpiai_get_accounts_customer_balances_api_response() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	$url      = get_option( 'wpiai_accounts_customer_balance_url' );
	$request  = get_option( 'wpiai_accounts_request' );
	$response = wpiai_get_infor_api_response( $url, $request );
	echo $response;
	exit;
}

function ajax_wpiai_get_product_api_response() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	$url           = get_option( 'wpiai_product_api_url' );
	$request       = get_option( 'wpiai_product_api_request' );
	$response      = wpiai_get_infor_api_response( $url, $request );
	$allArray      = json_decode( $response, true );
	$stkArray      = $allArray['response']['tOemultprcoutV2']['t-oemultprcoutV2'];
	$responseArray = getPricesQuantities( $stkArray );
	echo json_encode( $responseArray );
	//echo $response;
	exit;
}

function ajax_wpiai_get_product_updates_api_response() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	echo json_encode( wpiai_get_product_updates() );
	exit;
}


function ajax_get_wpiai_get_sales_order_xml() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	echo json_encode( wpiai_get_order_XML( 64123, 'Add' ) );
	exit;
}

function ajax_get_wpiai_get_changed_order_XML() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	echo json_encode( wpiai_get_changed_order_XML( 132253, 'Change' ) );
	exit;
}

function ajax_get_wpiai_get_contact_xml() {
	wpiai_api_checkSecure();
	$record                        = array();
	$record['contact_status_code'] = 'Status Code';
	$record['contact_first_name']  = get_user_meta( 4, 'first_name', true );;
	$record['contact_last_name'] = get_user_meta( 4, 'last_name', true );;;
	$name = $record['contact_first_name'];
	if ( $record['contact_first_name'] != '' ) {
		if ( $record['contact_last_name'] != '' ) {
			$name .= ' ';
		}
		$name .= $record['contact_first_name'];
	}
	$record['constact_job_title']    = 'Job Title';
	$record['contact_addr_1']        = 'Address 1';
	$record['contact_addr_2']        = 'Address 2';
	$record['contact_addr_3']        = 'City Name';
	$record['contact_postcode']      = 'Post Code';
	$record['contact_phone']         = '+44 1522 542520';
	$record['contact_email']         = 'test.email@address.com';
	$record['contact_phone_channel'] = '1';
	$record['contact_fax_channel']   = '0';
	$record['contact_email_channel'] = '0';
	$record['contact_CONTACT_ID']    = '601bff8899753';
	$record['contact_CSD_ID']        = '720055663';
	$record['contact_status_code']   = 'A Status Code';
	$record['contact_job_title']     = 'Job Title';
	$record['contact_type']          = 'Account';

	header( "Content-Type: application/json" );
	echo json_encode( get_contact_XML_record( 4, 'change', $record ) );
	exit;
}

function ajax_get_wpiai_get_ship_to_xml() {
	wpiai_api_checkSecure();
	$record                              = array();
	$record['delivery_UNIQUE_ID']        = '601ad2ba83d0a';
	$record['delivery-first-name']       = 'Joe';
	$record['delivery-last-name']        = 'Bloggs';
	$record['delivery-company-name']     = 'Company Name';
	$record['delivery-street-address-1'] = 'Street Address';
	$record['delivery-street-address-2'] = 'Address 2';
	$record['delivery-town-city']        = 'Town /City';
	$record['delivery-county']           = 'County';
	$record['delivery-postcode']         = 'Postcode';
	$record['delivery-phone']            = 'Phone';
	$record['delivery-email']            = 'edward@address.com';
	$record['delivery-CSD-ID']           = 'CSDID';

	header( "Content-Type: application/json" );
	echo json_encode( get_shipTo_XML_record( 4, 'change', $record ) );
	exit;
}

function ajax_get_wpiai_get_customer_params() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	$p = get_option( 'wpiai_customer_parameters' );
	//error_log($p);
	echo get_customer_param_record_x( $p );
	exit;
}

function ajax_get_wpiai_get_sales_order_response() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	echo json_encode( wpiai_get_sales_order_response() );
	exit;
}

function ajax_get_wpiai_get_ship_to_response() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	echo json_encode( wpiai_get_ship_to_response() );
	exit;
}

function ajax_get_wpiai_get_contact_response() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	echo json_encode( wpiai_get_contact_response() );
	exit;
}

function ajax_get_wpiai_get_infor_ping() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	echo json_encode( wpiai_infor_ping() );
	exit;
}