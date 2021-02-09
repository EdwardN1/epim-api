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

add_action( 'wp_ajax_wpiai_get_ship_to_response', 'ajax_get_wpiai_get_ship_to_response' );

add_action( 'wp_ajax_wpiai_get_contact_response', 'ajax_get_wpiai_get_contact_response' );
add_action( 'wp_ajax_wpiai_get_contact_xml', 'ajax_get_wpiai_get_contact_xml' );

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
	header( "Content-Type: application/json" );
	echo json_encode(get_contact_XML_record(4, 'change', $record));
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