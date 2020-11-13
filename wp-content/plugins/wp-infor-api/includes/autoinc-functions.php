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

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);

	curl_setopt($ch, CURLOPT_POST, true);

	$delimiter = '-------------' . uniqid();

	$p = 'ew0KImRvY3VtZW50TmFtZSIgOiAiUHJvY2Vzcy5Xb29DdXN0b21lciIsDQoibWVzc2FnZUlkIiA6ICIxIiwNCiJmcm9tTG9naWNhbElkIiA6ICJsaWQ6Ly9pbmZvci5pbXMud29vY29tbWVyY2VpbiIsDQoidG9Mb2dpY2FsSWQiIDogImxpZDovL2RlZmF1bHQiLA0KImVuY29kaW5nIiA6ICJOT05FIiwNCiJjaGFyYWN0ZXJTZXQiIDogIlVURi04IiwNCiJhY2NvdW50aW5nRW50aXR5IiA6ICIxIiwNCiJsb2NhdGlvbiIgOiAiMSIsDQoiZG9jdW1lbnRJZCIgOiAiMSIsDQoidmFyaWF0aW9uSWQiIDogMSwNCiJyZXZpc2lvbklkIiA6ICIxMjMiLA0KImJhdGNoSWQiIDogIjEiLA0KImJhdGNoU2VxdWVuY2UiIDogMSwNCiJiYXRjaFNpemUiIDogMSwNCiJiYXRjaFJldmlzaW9uIiA6IDEsDQoiYmF0Y2hBYm9ydEluZGljYXRvciIgOiB0cnVlLA0KImluc3RhbmNlcyIgOiAiMSIsDQoic291cmNlIiA6ICJXb29Db21tZXJjZSINCn0NCg==';
	$x = 'PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4NCjxQcm9jZXNzV29vQ3VzdG9tZXIgcmVsZWFzZUlEPSI5LjIiIHZlcnNpb25JRD0iMi4xMi4wIiB4bWxucz0iaHR0cDovL3NjaGVtYS5pbmZvci5jb20vSW5mb3JPQUdJUy8yIiB4bWxuczp4c2Q9Imh0dHA6Ly93d3cudzMub3JnLzIwMDEvWE1MU2NoZW1hIj4NCgk8QXBwbGljYXRpb25BcmVhPg0KCQk8U2VuZGVyPg0KCQkJPExvZ2ljYWxJRD5saWQ6Ly9pbmZvci53b29jb21tZXJjZS50ZXN0PC9Mb2dpY2FsSUQ+IDwhLS0gVG8gYmUgZGV0ZXJtaW5lZCB3aGVuIHRoZSBDb25uZWN0aW9uIFBvaW50IGlzIHNldCB1cCAtLT4NCgkJPC9TZW5kZXI+DQoJCTxDcmVhdGlvbkRhdGVUaW1lPjIwMjAtMDgtMDRUMTE6NDY6MzAuNDQxWjwvQ3JlYXRpb25EYXRlVGltZT4gIDwhLS0gRGF0ZS9UaW1lIHRoZSBCT0Qgd2FzIGNyZWF0ZWQgLS0+DQoJCTxCT0RJRD5BS1hXN0EwMDA2TTc6UUtYVzdBMDA4WlpSPC9CT0RJRD4gIDwhLS0gQSB1bmlxdWUgSUQgLS0+DQoJPC9BcHBsaWNhdGlvbkFyZWE+DQoJPERhdGFBcmVhPg0KCQk8UHJvY2Vzcz4NCgkJCTxUZW5hbnRJRD5FUkZFTEVDVFJJQ19UUk48L1RlbmFudElEPiA8IS0tIFRlbmFudCwgaS5lLiBTeXN0ZW0gVFJOL1BSRCBldGMuIC0tPg0KCQkJPEFjY291bnRpbmdFbnRpdHlJRD4xPC9BY2NvdW50aW5nRW50aXR5SUQ+IDwhLS0gQ29tcGFueSBudW1iZXIgLS0+DQoJCQk8QWN0aW9uQ3JpdGVyaWE+DQoJCQkJPEFjdGlvbkV4cHJlc3Npb24gYWN0aW9uQ29kZT0iQ2hhbmdlIj5DaGFuZ2U8L0FjdGlvbkV4cHJlc3Npb24+ICA8IS0tIEFkZCBvciBDaGFuZ2UgLS0+DQoJCQk8L0FjdGlvbkNyaXRlcmlhPg0KCQk8L1Byb2Nlc3M+DQoJCTxXb29DdXN0b21lcj4NCgkJCTxDdXN0b21lcklEPjwvQ3VzdG9tZXJJRD4NCgkJCTwhLS0gVGhlIFNoaXBUbyBCT0Qgd291bGQgbG9vayBhbG1vc3QgaWRlbnRpY2FsIGJ1dCBoYXZlIGEgU2hpcFRvSUQgbm9kZSBhcyB3ZWxsIC0tPg0KCQkJPE5hbWU+QUJDPC9OYW1lPg0KCQkJPEFkZHJlc3NMaW5lMT5Ib3VzZSBOYW1lPC9BZGRyZXNzTGluZTE+DQoJCQk8QWRkcmVzc0xpbmUyPlN0cmVldDwvQWRkcmVzc0xpbmUyPg0KCQkJPEFkZHJlc3NMaW5lMz5Ub3duPC9BZGRyZXNzTGluZTM+DQoJCQk8Q2l0eT5DaXR5PC9DaXR5Pg0KCQkJPENvdW50eT5Db3VudHk8L0NvdW50eT4gPCEtLSBvciBTdGF0ZSwgUmVnaW9uIGV0Yy4gLS0+DQoJCQk8Q291bnRyeT5VSzwvQ291bnRyeT4gPCEtLSBhcyBpbiBDU0QgLSB0cmFuc2xhdGlvbiBuZWVkZWQ/IC0tPg0KCQkJPFBvc3RDb2RlPkEwMSAyQkI8L1Bvc3RDb2RlPg0KCQkJPFBob25lPjU1NS0xMjM0NTY3PC9QaG9uZT4NCgkJCTxGYXg+NTU1LTEyMzQ1Njg8L0ZheD4NCgkJCTxFbWFpbD50ZXN0QGluZm9yLmNvbTwvRW1haWw+DQoJCQk8IS0tIENvbnRhY3Q/IFdvdWxkIG5lZWQgdG8gZXhpc3QgaW4gQ1NEIC0tPg0KCQkJPCEtLSBXYXJlaG91c2UgPyAtLT4NCgkJCTwhLS0gUGF5bWVudCBUZXJtcyA/IC0tPg0KCQk8L1dvb0N1c3RvbWVyPg0KCTwvRGF0YUFyZWE+DQo8L1Byb2Nlc3NXb29DdXN0b21lcj4NCg==';

	/*$files = array();
	$files['parameters.json'] = array(
		'type' => 'application/octet-stream;base64',
		'content' => $paramters,
		//'content' => base64_encode($paramters),
	);
	$files['xml.bin'] = array(
		'type' => 'application/octet-stream;base64',
		'content' => $xml,
		//'content' => base64_encode(),
	);*/

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

	/*foreach ($files as $name => $file) {
		$data .= "--" . $delimiter . "\r\n";
		$data .= 'Content-Disposition: form-data; name="' . $name . '";' .
		         ' filename="' . $name . '"' . "\r\n";
		$data .= 'Content-Type: ' . $file['type'] . "\r\n";
		$data .= "\r\n";
		$data .= $file['content'] . "\r\n";
	}*/

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

function get_customer_XML_record($user_id) {
	$res = false;
	$user = get_userdata($user_id);
	if ($user) {
		$xmld = get_option( 'wpiai_customer_xml' );
		$xml = simplexml_load_string($xmld);
		//error_log(print_r($xml,true));
		$BODID = uniqid();
		$nowDT = new DateTime();
		$CreationDateTime = $nowDT->format(DateTime::ATOM);
		$ActionExpression = 'Change';
		$Name = $user->first_name.' '.$user->last_name;
		$CSD_ID = get_user_meta($user_id,'CSD_ID',true);
		if($CSD_ID == '') {
			$ActionExpression = 'Add';
		}
		$AddressLine1 = get_user_meta($user_id,'billing_address_1',true);
		$AddressLine2 = get_user_meta($user_id,'billing_address_2',true);
		$CityName = get_user_meta($user_id,'billing_city',true);
		$PostalCode = get_user_meta($user_id,'billing_postcode',true);
		$DialNumber = get_user_meta($user_id,'billing_phone',true);
		$URI = $user->user_email;
		$SXe_user4 = $user_id;
		$xml->registerXPathNamespace('x','http://schema.infor.com/InforOAGIS/2');
		//error_log(print_r($xml->xpath('//x:ApplicationArea')[0]->CreationDateTime[0],true));
		//$xml->xpath('//x:ApplicationArea')[0]->CreationDateTime[0] = $CreationDateTime;
		if($xml->xpath('//x:ApplicationArea')[0]->CreationDateTime[0]) {
			$xml->xpath('//x:ApplicationArea')[0]->CreationDateTime[0] = $CreationDateTime;
		} else {
			//error_log('Cant find path');
		}
		if($xml->xpath('//x:ApplicationArea')[0]->BODID[0]) {
			$xml->xpath('//x:ApplicationArea')[0]->BODID[0] = $BODID;
		}
		if($xml->xpath('//x:DataArea')[0]->Process[0]->ActionCriteria[0]->ActionExpression[0]) {
			$xml->xpath('//x:DataArea')[0]->Process[0]->ActionCriteria[0]->ActionExpression[0] = $ActionExpression;
			$xml->xpath('//x:DataArea')[0]->Process[0]->ActionCriteria[0]->ActionExpression[0]->attributes()['actionCode'] = $ActionExpression;
		}
		if($xml->xpath('//x:DataArea')[0]->CustomerPartyMaster[0]->PartyIDs[0]->ID[0]) {
			$xml->xpath('//x:DataArea')[0]->CustomerPartyMaster[0]->PartyIDs[0]->ID[0] = $CSD_ID;
		}
		if($xml->xpath('//x:DataArea')[0]->CustomerPartyMaster[0]->Name[0]) {
			$xml->xpath('//x:DataArea')[0]->CustomerPartyMaster[0]->Name[0] = $Name;
		}
		if($xml->xpath('//x:DataArea')[0]->CustomerPartyMaster[0]->Location[0]->Address[0]->AddressLine[0]) {
			$xml->xpath('//x:DataArea')[0]->CustomerPartyMaster[0]->Location[0]->Address[0]->AddressLine[0] = $AddressLine1;
		}
		if($xml->xpath('//x:DataArea')[0]->CustomerPartyMaster[0]->Location[0]->Address[0]->AddressLine[1]) {
			$xml->xpath('//x:DataArea')[0]->CustomerPartyMaster[0]->Location[0]->Address[0]->AddressLine[1] = $AddressLine2;
		}
		if($xml->xpath('//x:DataArea')[0]->CustomerPartyMaster[0]->Location[0]->Address[0]->AddressLine[2]) {
			$xml->xpath('//x:DataArea')[0]->CustomerPartyMaster[0]->Location[0]->Address[0]->AddressLine[2] = '';
		}
		if($xml->xpath('//x:DataArea')[0]->CustomerPartyMaster[0]->Location[0]->Address[0]->CityName[0]) {
			$xml->xpath('//x:DataArea')[0]->CustomerPartyMaster[0]->Location[0]->Address[0]->CityName[0] = $CityName;
		}
		if($xml->xpath('//x:DataArea')[0]->CustomerPartyMaster[0]->Location[0]->Address[0]->PostalCode[0]) {
			$xml->xpath('//x:DataArea')[0]->CustomerPartyMaster[0]->Location[0]->Address[0]->PostalCode[0] = $PostalCode;
		}
		if($xml->xpath('//x:DataArea')[0]->CustomerPartyMaster[0]->Communication[0]->DialNumber[0]) {
			$xml->xpath('//x:DataArea')[0]->CustomerPartyMaster[0]->Communication[0]->DialNumber[0] = $DialNumber;
		}
		if($xml->xpath('//x:DataArea')[0]->CustomerPartyMaster[0]->Communication[1]->URI[0]) {
			$xml->xpath('//x:DataArea')[0]->CustomerPartyMaster[0]->Communication[1]->URI[0] = $URI;
		}
		/*if($xml->xpath('//x:DataArea')[0]->CustomerPartyMaster[0]->UserArea[0]->Property[0]->NameValue[0]) {
			$xml->xpath('//x:DataArea')[0]->CustomerPartyMaster[0]->UserArea[0]->Property[0]->Property[0]->NameValue[0]-> = $SXe_user4;
			error_log(print_r($xml->xpath('//x:DataArea')[0]->CustomerPartyMaster[0]->UserArea[0]->Property[0]->NameValue,true));
		}*/
		$xmld = $xml->asXML();
		$propertyStart = strpos($xmld,'<NameValue type="String" name="SXe_user4">');
		if($propertyStart) {
			$propertyEnd = strpos($xmld,'</NameValue>',$propertyStart);
			$xmld = substr($xmld,0,$propertyStart).'<NameValue type="String" name="SXe_user4">'.$SXe_user4.substr($xmld,$propertyEnd);
		}
		return $xmld;
	} else {
		return $res;
	}
}