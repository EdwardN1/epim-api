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
	$currentToken = get_option( 'wpiai_current_token' );
	$refreshTime = get_option( 'wpiai_token_refresh_time' );
	$refreshPeriod = get_option( 'wpiai_token_refresh_period' );
	$now = time();
	$refreshAt = $refreshTime + $refreshPeriod;
	if($now > $refreshAt) {
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
            update_option('wpiai_current_token',$apiCall);
            update_option('wpiai_token_refresh_time',$now);
        } else {
            if(is_wp_error( $response )) {
                $apiCall = $response->get_error_message();
            }
        }
        return $apiCall;
    }
	return $currentToken;
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

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);

	curl_setopt($ch, CURLOPT_POST, true);

	$delimiter = '-------------' . uniqid();

	$p = 'ew0KImRvY3VtZW50TmFtZSIgOiAiUHJvY2Vzcy5Xb29DdXN0b21lciIsDQoibWVzc2FnZUlkIiA6ICIxIiwNCiJmcm9tTG9naWNhbElkIiA6ICJsaWQ6Ly9pbmZvci5pbXMud29vY29tbWVyY2VpbiIsDQoidG9Mb2dpY2FsSWQiIDogImxpZDovL2RlZmF1bHQiLA0KImVuY29kaW5nIiA6ICJOT05FIiwNCiJjaGFyYWN0ZXJTZXQiIDogIlVURi04IiwNCiJhY2NvdW50aW5nRW50aXR5IiA6ICIxIiwNCiJsb2NhdGlvbiIgOiAiMSIsDQoiZG9jdW1lbnRJZCIgOiAiMSIsDQoidmFyaWF0aW9uSWQiIDogMSwNCiJyZXZpc2lvbklkIiA6ICIxMjMiLA0KImJhdGNoSWQiIDogIjEiLA0KImJhdGNoU2VxdWVuY2UiIDogMSwNCiJiYXRjaFNpemUiIDogMSwNCiJiYXRjaFJldmlzaW9uIiA6IDEsDQoiYmF0Y2hBYm9ydEluZGljYXRvciIgOiB0cnVlLA0KImluc3RhbmNlcyIgOiAiMSIsDQoic291cmNlIiA6ICJXb29Db21tZXJjZSINCn0NCg==';
	$x = 'PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4NCjxQcm9jZXNzV29vQ3VzdG9tZXIgcmVsZWFzZUlEPSI5LjIiIHZlcnNpb25JRD0iMi4xMi4wIiB4bWxucz0iaHR0cDovL3NjaGVtYS5pbmZvci5jb20vSW5mb3JPQUdJUy8yIiB4bWxuczp4c2Q9Imh0dHA6Ly93d3cudzMub3JnLzIwMDEvWE1MU2NoZW1hIj4NCgk8QXBwbGljYXRpb25BcmVhPg0KCQk8U2VuZGVyPg0KCQkJPExvZ2ljYWxJRD5saWQ6Ly9pbmZvci53b29jb21tZXJjZS50ZXN0PC9Mb2dpY2FsSUQ+IDwhLS0gVG8gYmUgZGV0ZXJtaW5lZCB3aGVuIHRoZSBDb25uZWN0aW9uIFBvaW50IGlzIHNldCB1cCAtLT4NCgkJPC9TZW5kZXI+DQoJCTxDcmVhdGlvbkRhdGVUaW1lPjIwMjAtMDgtMDRUMTE6NDY6MzAuNDQxWjwvQ3JlYXRpb25EYXRlVGltZT4gIDwhLS0gRGF0ZS9UaW1lIHRoZSBCT0Qgd2FzIGNyZWF0ZWQgLS0+DQoJCTxCT0RJRD5BS1hXN0EwMDA2TTc6UUtYVzdBMDA4WlpSPC9CT0RJRD4gIDwhLS0gQSB1bmlxdWUgSUQgLS0+DQoJPC9BcHBsaWNhdGlvbkFyZWE+DQoJPERhdGFBcmVhPg0KCQk8UHJvY2Vzcz4NCgkJCTxUZW5hbnRJRD5FUkZFTEVDVFJJQ19UUk48L1RlbmFudElEPiA8IS0tIFRlbmFudCwgaS5lLiBTeXN0ZW0gVFJOL1BSRCBldGMuIC0tPg0KCQkJPEFjY291bnRpbmdFbnRpdHlJRD4xPC9BY2NvdW50aW5nRW50aXR5SUQ+IDwhLS0gQ29tcGFueSBudW1iZXIgLS0+DQoJCQk8QWN0aW9uQ3JpdGVyaWE+DQoJCQkJPEFjdGlvbkV4cHJlc3Npb24gYWN0aW9uQ29kZT0iQ2hhbmdlIj5DaGFuZ2U8L0FjdGlvbkV4cHJlc3Npb24+ICA8IS0tIEFkZCBvciBDaGFuZ2UgLS0+DQoJCQk8L0FjdGlvbkNyaXRlcmlhPg0KCQk8L1Byb2Nlc3M+DQoJCTxXb29DdXN0b21lcj4NCgkJCTxDdXN0b21lcklEPjwvQ3VzdG9tZXJJRD4NCgkJCTwhLS0gVGhlIFNoaXBUbyBCT0Qgd291bGQgbG9vayBhbG1vc3QgaWRlbnRpY2FsIGJ1dCBoYXZlIGEgU2hpcFRvSUQgbm9kZSBhcyB3ZWxsIC0tPg0KCQkJPE5hbWU+QUJDPC9OYW1lPg0KCQkJPEFkZHJlc3NMaW5lMT5Ib3VzZSBOYW1lPC9BZGRyZXNzTGluZTE+DQoJCQk8QWRkcmVzc0xpbmUyPlN0cmVldDwvQWRkcmVzc0xpbmUyPg0KCQkJPEFkZHJlc3NMaW5lMz5Ub3duPC9BZGRyZXNzTGluZTM+DQoJCQk8Q2l0eT5DaXR5PC9DaXR5Pg0KCQkJPENvdW50eT5Db3VudHk8L0NvdW50eT4gPCEtLSBvciBTdGF0ZSwgUmVnaW9uIGV0Yy4gLS0+DQoJCQk8Q291bnRyeT5VSzwvQ291bnRyeT4gPCEtLSBhcyBpbiBDU0QgLSB0cmFuc2xhdGlvbiBuZWVkZWQ/IC0tPg0KCQkJPFBvc3RDb2RlPkEwMSAyQkI8L1Bvc3RDb2RlPg0KCQkJPFBob25lPjU1NS0xMjM0NTY3PC9QaG9uZT4NCgkJCTxGYXg+NTU1LTEyMzQ1Njg8L0ZheD4NCgkJCTxFbWFpbD50ZXN0QGluZm9yLmNvbTwvRW1haWw+DQoJCQk8IS0tIENvbnRhY3Q/IFdvdWxkIG5lZWQgdG8gZXhpc3QgaW4gQ1NEIC0tPg0KCQkJPCEtLSBXYXJlaG91c2UgPyAtLT4NCgkJCTwhLS0gUGF5bWVudCBUZXJtcyA/IC0tPg0KCQk8L1dvb0N1c3RvbWVyPg0KCTwvRGF0YUFyZWE+DQo8L1Byb2Nlc3NXb29DdXN0b21lcj4NCg==';

	$fields = array();

	$fields['ParameterRequest'] = $paramters;
	$fields['MessagePayload'] = $xml;

	$data = '';

	foreach ($fields as $name => $content) {
		$data .= "--" . $delimiter . "\r\n";
		$data .= 'Content-Disposition: form-data; name="' . $name . '"';
		// note: double endline
		$data .= "\r\n\r\n";
		$data .= $content;
		$data .= "\r\n";
	}

	$data .= "--" . $delimiter . "--\r\n";

	$headers   = array();
	$headers[] = 'Content-Length: ' . strlen($data);
	$headers[] ='Content-Type: multipart/form-data; boundary=' . $delimiter;
	$headers[] ='accept: application/json';
	$headers[] = "Authorization: Bearer " . $access_token;



	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

	curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

	$apicall = curl_exec($ch);
	curl_close($ch);
	return $apicall;
}

function wpiai_get_infor_api_response($url,$data) {
    $access_token = wpiai_get_access_token_value();

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);

    if($data==='get') {
	    //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    } else {
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }



	$headers[] = "Content-Type: application/json";
	$headers[]= "TenantID: ERFELECTRIC_TRN";
    $headers[] = "Authorization: Bearer " . $access_token;



    curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

    $apicall = curl_exec($ch);
    curl_close($ch);
    return $apicall;
}

function wpiai_get_product_updates() {
	$url = get_option('wpiai_product_pricing_updates_api_url');
	$operator = get_option('wpiai_product_pricing_updates_operator');
	$restartRowID = get_option('wpiai_product_pricing_updates_restartRowId');
	$lookbackExp = get_option('wpiai_product_pricing_updates_lookbackExp');
	$ionRespStyle = get_option('wpiai_product_pricing_updates_ionapiRespStyle');
	$url .= '?operator='.$operator.'&restartRowId='.$restartRowID.'&lookbackExp='.urlencode($lookbackExp).'&ionapiRespStyle='.$ionRespStyle;
	$request = 'get';
	$api = wpiai_get_infor_api_response($url,$request);
	$response = json_decode($api);
	if(is_object($response)) {
		return $response->result;
	} else {
		return false;
	}

}

function wpiai_get_test_response() {
	$url = get_option( 'wpiai_message_test_url' );
	$parameters = get_option( 'wpiai_message_test_parameters' );
	$xml = get_option( 'wpiai_message_test_xml' );
	$xml = trim(preg_replace('/\t+/', '', $xml));
	return wpiai_get_infor_message_multipart_message($url,$parameters,$xml);
}

function wpiai_get_customer_response() {
	$url = get_option( 'wpiai_customer_url' );
	$parameters = get_option( 'wpiai_customer_parameters' );
	$xml = get_option( 'wpiai_customer_xml' );
	$xml = trim(preg_replace('/\t+/', '', $xml));
	return wpiai_get_infor_message_multipart_message($url,$parameters,$xml);
}

function wpiai_get_sales_order_response() {
	$url = get_option( 'wpiai_sales_order_url' );
	$parameters = get_option( 'wpiai_sales_order_parameters' );
	$xml = get_option( 'wpiai_sales_order_xml' );
	$xml = trim(preg_replace('/\t+/', '', $xml));
	return wpiai_get_infor_message_multipart_message($url,$parameters,$xml);
}

function wpiai_get_ship_to_response() {
	$url = get_option( 'wpiai_ship_to_url' );
	$parameters = get_option( 'wpiai_ship_to_parameters' );
	$xml = get_option( 'wpiai_ship_to_xml' );
	$xml = trim(preg_replace('/\t+/', '', $xml));
	return wpiai_get_infor_message_multipart_message($url,$parameters,$xml);
}

function wpiai_get_contact_response() {
	$url = get_option( 'wpiai_contact_url' );
	$parameters = get_option( 'wpiai_contact_parameters' );
	$xml = get_option( 'wpiai_contact_xml' );
	$xml = trim(preg_replace('/\t+/', '', $xml));
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
