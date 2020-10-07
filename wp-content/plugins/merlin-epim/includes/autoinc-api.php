<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 *
 *
 ************************************ API Calls*****************************************
 *
 */

function epmer_api_make_curl_call($url) {

	if(function_exists('curl_init')) {
		$ch = curl_init();

		curl_setopt( $ch, CURLOPT_URL, $url );

		$headers   = array();
		$headers[] = "Ocp-Apim-Subscription-Key: " . get_option( 'epim_key' );

		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

		$apiCall = curl_exec( $ch );

		curl_close( $ch );

		return $apiCall;
	} else {
		$opts = array(
			'http' => array(
				'method' => "GET",
				'header' => "Ocp-Apim-Subscription-Key: " . get_option('epim_key')
			)
		);
		$context = stream_context_create($opts);
		$apiCall = file_get_contents($url, false, $context);

		return $apiCall;
	}

}

function epmer_make_api_call( $url ) {
	$response = null;
	$method   = get_option( 'epim_api_retrieval_method' );
	$epim_url = get_option( 'epim_url' );
	if ( substr( $epim_url, - 1 != '/' ) ) {
		$epim_url .= '/';
	}
	$epim_url .= 'api/';
	if ( $method == 'curl' ) {
		return epimaapi_make_curl_call($epim_url.$url);
	} else {

		$apiCall = false;

		$args = array(
			'headers' => array(
				'Ocp-Apim-Subscription-Key' => get_option('epim_key')
			)
		);

		$response = wp_safe_remote_get($epim_url . $url,$args);

		if ( is_array( $response ) && ! is_wp_error( $response ) ) {
			$apiCall = $response['body'];
		} else {
			if(is_wp_error( $response )) {
				//error_log($response->get_error_message());
				//error_log('URL called: '.$epim_url . $url);
				$apiCall = epimaapi_make_curl_call($epim_url.$url);
			}
		}

		return $apiCall;
	}

}