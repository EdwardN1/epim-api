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

function ajax_get_wpiai_get_access_token() {
	wpiai_api_checkSecure();
	header( "Content-Type: application/json" );
	echo json_encode(wpiai_get_access_token());
	exit;
}