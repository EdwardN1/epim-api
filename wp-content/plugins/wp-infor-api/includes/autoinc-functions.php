<?php
if ( ! defined( 'ABSPATH' ) )
	exit;

global $logErrors;

$logErrors = true;

function _er($msg) {
	global $logErrors;
	if($logErrors) error_log($msg);
}

function wpiai_get_access_token() {
	$url = get_option('wpiai_token_url');
	$username = get_option( 'wpiai_username' );
	$password = get_option( 'wpiai_password' );
	$client_id = get_option( 'wpiai_client_id' );
	$client_secret = get_option( 'wpiai_client_secret' );
	$args = array(
		'method' => 'POST',
		'headers' => array(
			'Content-type: application/x-www-form-urlencoded'
		),
		'body' => array(
			'grant_type' => 'password',
			'username' => $username,
			'password' => $password,
			'client_id' => $client_id,
			'client_secret' => $client_secret
		),
	);
	$response = wp_remote_post($url,$args);
	$apiCall = 'Get Access Token Failed';
	if ( is_array( $response ) && ! is_wp_error( $response ) ) {
		$apiCall = $response['body'];
	} else {
		if(is_wp_error( $response )) {
			$apiCall = $response->get_error_message();
		}
	}
	return $apiCall;
}

function wpiai_get_access_token_value() {
	$api_response = wpiai_get_access_token();
	$json = json_decode($api_response);
	if(is_null($json)) {
		return $api_response;
	};
	$access_token = $json['access_token'];
	if(is_null($access_token)) {
		return $json;
	}
	$parameters = get_option( 'wpiai_message_test_parameters' );
	$xml = get_option('wpiai_message_test_xml');
}