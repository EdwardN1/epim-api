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

function ajax_get_wpiai_get_infor_ping() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	echo json_encode(wpiai_infor_ping());
	exit;
}