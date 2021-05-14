<?php
if ( ! defined( 'ABSPATH' ) )
	exit;

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

add_action( 'wp_ajax_wpiai_get_ship_to_response', 'ajax_get_wpiai_get_ship_to_response' );
add_action( 'wp_ajax_wpiai_get_ship_to_xml', 'ajax_get_wpiai_get_ship_to_xml' );

add_action( 'wp_ajax_wpiai_get_contact_response', 'ajax_get_wpiai_get_contact_response' );
add_action( 'wp_ajax_wpiai_get_contact_xml', 'ajax_get_wpiai_get_contact_xml' );

add_action( 'wp_ajax_wpiai_get_product_api_response', 'ajax_wpiai_get_product_api_response' );

add_action( 'wp_ajax_wpiai_get_product_updates_api_response', 'ajax_wpiai_get_product_updates_api_response' );

add_action( 'wp_ajax_wpiai_get_accounts_customer_balances_api_response', 'ajax_wpiai_get_accounts_customer_balances_api_response' );
add_action( 'wp_ajax_wpiai_get_accounts_customer_data_credit_api_response', 'ajax_wpiai_get_accounts_customer_data_credit_api_response' );

add_action( 'wp_ajax_wpiai_get_invoices_api_response', 'ajax_wpiai_get_invoices_api_response' );
add_action( 'wp_ajax_wpiai_get_single_invoice_api_response', 'wpiai_get_single_invoice_api_response' );
add_action( 'wp_ajax_wpiai_get_single_invoice_api_response_test', 'wpiai_get_single_invoice_api_response_test' );

function wpiai_get_single_invoice_api_response_test() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	$url = get_option('wpiai_single_invoice_url');
	$order = $_POST['order'];
	//error_log($order);
	if($order) {
		$response = getSingleInvoice($order);
		//error_log(print_r($response,true));
		echo json_encode($response);
	} else {
		echo 'Something went wrong.';
	}

	exit;
}

function wpiai_get_single_invoice_api_response() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	$url = get_option('wpiai_single_invoice_url');
	$request = get_option('wpiai_single_invoice_request');
	$response = wpiai_get_infor_api_response($url,$request);
	echo $response;
	exit;
}

function ajax_wpiai_get_invoices_api_response() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	$url = get_option('wpiai_invoices_url');
	$request = get_option('wpiai_invoices_request');
	$response = wpiai_get_infor_api_response($url,$request);
	echo $response;
	exit;
}

function ajax_get_wpiai_get_access_token() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	echo json_encode(wpiai_get_access_token());
	exit;
}

function ajax_get_wpiai_get_message_response() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	echo json_encode(wpiai_get_test_response());
	exit;
}

function ajax_get_wpiai_get_customer_response() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	echo json_encode(wpiai_get_customer_response());
	exit;
}

function ajax_get_wpiai_get_customer_xml() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	echo json_encode(get_customer_XML_record(4));
	exit;
}

function ajax_wpiai_get_accounts_customer_data_credit_api_response() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	$url = get_option('wpiai_accounts_customer_data_credit_url');
	$request = get_option('wpiai_accounts_request');
	$response = wpiai_get_infor_api_response($url,$request);
	echo $response;
	exit;
}

function ajax_wpiai_get_accounts_customer_balances_api_response() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	$url = get_option('wpiai_accounts_customer_balance_url');
	$request = get_option('wpiai_accounts_request');
	$response = wpiai_get_infor_api_response($url,$request);
	echo $response;
	exit;
}

function ajax_wpiai_get_product_api_response() {
    wpiai_api_checkSecure();
    header( "Content-Type: application/json" );
    $url = get_option('wpiai_product_api_url');
    $request = get_option('wpiai_product_api_request');
    $response = wpiai_get_infor_api_response($url,$request);
    $allArray = json_decode($response,true);
    $stkArray = $allArray['response']['tOemultprcoutV2']['t-oemultprcoutV2'];
    $responseArray = getPricesQuantities($stkArray);
    echo json_encode($responseArray);
	//echo $response;
    exit;
}

function ajax_wpiai_get_product_updates_api_response() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	echo json_encode(wpiai_get_product_updates());
	exit;
}


function ajax_get_wpiai_get_sales_order_xml() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	echo json_encode(wpiai_get_order_XML(49049, 'Add'));
	exit;
}

function ajax_get_wpiai_get_contact_xml() {
	wpiai_api_checkSecure();
	$record = array();
	$record['contact_status_code'] = 'Status Code';
	$record['contact_first_name'] = get_user_meta(4,'first_name',true);;
	$record['contact_last_name'] = get_user_meta(4,'last_name',true);;;
	$name = $record['contact_first_name'];
	if($record['contact_first_name'] != '') {
		if($record['contact_last_name'] !='') $name .= ' ';
		$name .= $record['contact_first_name'];
	}
	$record['constact_job_title'] = 'Job Title';
	$record['contact_addr_1'] = 'Address 1';
	$record['contact_addr_2'] = 'Address 2';
	$record['contact_addr_3'] = 'City Name';
	$record['contact_postcode'] = 'Post Code';
	$record['contact_phone'] = '+44 1522 542520';
	$record['contact_email'] = 'test.email@address.com';
	$record['contact_phone_channel'] = '1';
	$record['contact_fax_channel'] = '0';
	$record['contact_email_channel'] = '0';
	$record['contact_CONTACT_ID'] = '601bff8899753';
	$record['contact_CSD_ID'] = '720055663';
    $record['contact_status_code'] = 'A Status Code';
    $record['contact_job_title'] = 'Job Title';
    $record['contact_type'] = 'Account';

	header( "Content-Type: application/json" );
	echo json_encode(get_contact_XML_record(4, 'change', $record));
	exit;
}

function ajax_get_wpiai_get_ship_to_xml() {
    wpiai_api_checkSecure();
    $record = array();
    $record['delivery_UNIQUE_ID'] = '601ad2ba83d0a';
    $record['delivery-first-name'] = 'Joe';
    $record['delivery-last-name'] = 'Bloggs';
    $record['delivery-company-name'] = 'Company Name';
    $record['delivery-street-address-1'] = 'Street Address';
    $record['delivery-street-address-2'] = 'Address 2';
    $record['delivery-town-city'] = 'Town /City';
    $record['delivery-county'] = 'County';
    $record['delivery-postcode'] = 'Postcode';
    $record['delivery-phone'] = 'Phone';
    $record['delivery-email'] = 'edward@address.com';
    $record['delivery-CSD-ID'] = 'CSDID';

    header( "Content-Type: application/json" );
    echo json_encode(get_shipTo_XML_record(4, 'change', $record));
    exit;
}

function ajax_get_wpiai_get_customer_params() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	$p = get_option( 'wpiai_customer_parameters' );
	//error_log($p);
	echo get_customer_param_record_x($p);
	exit;
}

function ajax_get_wpiai_get_sales_order_response() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	echo json_encode(wpiai_get_sales_order_response());
	exit;
}

function ajax_get_wpiai_get_ship_to_response() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	echo json_encode(wpiai_get_ship_to_response());
	exit;
}

function ajax_get_wpiai_get_contact_response() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	echo json_encode(wpiai_get_contact_response());
	exit;
}

function ajax_get_wpiai_get_infor_ping() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	echo json_encode(wpiai_infor_ping());
	exit;
}