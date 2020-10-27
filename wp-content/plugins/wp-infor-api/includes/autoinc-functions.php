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
	$response = wp_safe_remote_post($url,$args);
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
	$access_token = $json->access_token;
	if(is_null($access_token)) {
		return $json;
	}
	return $access_token;
}

function wpiai_get_infor_message_multipart_message($url,$paramters,$xml) {
	$access_token = wpiai_get_access_token_value();
	$eol = '/r/n';
	$mime_boundary = md5(time());
	/*$data = '';


	$data .= '--'.$mime_boundary.$eol;
	$data .= 'Content-Disposition: form-data; name="ParameterRequest"; filename="parameters.json'.$eol;
	$data .= 'Content-Type: text/plain' . $eol;
	$data .= 'Content-Transfer-Encoding: base64' . $eol . $eol;
	$data .= chunk_split(base64_encode($paramters)) . $eol;
	$data .= "--" . $mime_boundary . "--" . $eol;
	$data .= 'Content-Disposition: form-data; name="MessagePayload"; filename="xml.bin'.$eol;
	$data .= 'Content-Type: text/plain' . $eol;
	$data .= 'Content-Transfer-Encoding: base64' . $eol . $eol;
	$data .= chunk_split(base64_encode($xml)) . $eol;
	$data .= "--" . $mime_boundary . "--" . $eol . $eol; // finish with two eol's!!*/

	$files = array();
	$files['parameters.json'] = 'data:application/octet-stream;base64,'.base64_encode($paramters);
	$files['xml.bin'] = 'data:application/octet-stream;base64,'.base64_encode($xml);

	$body = array(
		'args' => array(),
		'data' => array(),
		'files' => $files,
		'form' => ''
	);

	/*$boundary = wp_generate_password( 24 );

	$data = '';
	$data .= '--'.$boundary;
	$data .= $eol;
	$data .= 'Content-Disposition: form-data; name="ParameterRequest"'.$eol.$eol;
	$data .= 'parameters.json';
	$data .= $eol;

	$data .= '--'.$boundary;
	$data .= $eol;
	$data .= 'Content-Disposition: form-data; name="MessagePayload"'.$eol.$eol;
	$data .= 'xml.bin';
	$data .= $eol;

	$data .= '--'.$boundary;
	$data .= "\r\n";
	$data .= 'Content-Disposition: form-data; name="' . 'ParameterRequest' . '"; filename="parameters.json"' . "\r\n";
	//        $payload .= 'Content-Type: image/jpeg' . "\r\n";
	$data .= "\r\n";
	$data .= base64_encode($paramters);
	$data .= "\r\n";

	$data .= '--'.$boundary;
	$data .= "\r\n";
	$data .= 'Content-Disposition: form-data; name="' . 'MessagePayload' . '"; filename="xml.bin"' . "\r\n";
	//        $payload .= 'Content-Type: image/jpeg' . "\r\n";
	$data .= "\r\n";
	$data .= base64_encode($xml);
	$data .= "\r\n";

	$data .= '--' . $boundary . '--';*/

	$args = array(
		'method' => 'POST',
		'headers' => array(
			'Content-Type: multipart/form-data; boundary=' . $mime_boundary,
			'Authorization: Bearer '.$access_token,
		),
		'body' => $body,
	);

	//_er(print_r($data,true));

	$apicall = wp_safe_remote_post($url,$args);
	return $apicall;
}

function wpiai_get_test_response() {
	$url = get_option( 'wpiai_message_test_url' );
	$parameters = get_option( 'wpiai_message_test_parameters' );
	$xml = get_option( 'wpiai_message_test_xml' );
	return wpiai_get_infor_message_multipart_message($url,$parameters,$xml);
}

function wpiai_infor_ping() {
	$url = 'https://mingle-ionapi.inforcloudsuite.com/ERFELECTRIC_TRN/IONSERVICES/api/ion/messaging/service/ping';
	$access_token = 'Bearer ' . wpiai_get_access_token_value();
	$args = array(
		'headers' => array(
			'Authorization' => $access_token
		)
	);

	return wp_safe_remote_get($url,$args);
}