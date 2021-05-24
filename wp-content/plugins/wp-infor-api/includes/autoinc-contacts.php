<?php

function createContactsAPIRequest($organizationID,$title,$first_name,$last_name,$email,$phone,$address_1,$address_2,$address_3,$city,$post_code) {
	$request = '{"request": {"companyNumber": 1,"operatorInit": "WOO","tMntTt": {"t-mnt-tt": [';
	$request .= '{"setNo": 1,"seqNo": 1,"updateMode": "add","fieldName": "Addtiearsc","fieldValue": "'.$organizationID.'"},';
	$request .= '{"setNo": 1,"seqNo": 2,"updateMode": "add","fieldName": "firstnm","fieldValue": "'.$first_name.'"},';
	$request .= '{"setNo": 1,"seqNo": 3,"updateMode": "add","fieldName": "lastnm","fieldValue": "'.$last_name.'"},';
	$request .= '{"setNo": 1,"seqNo": 4,"updateMode": "add","fieldName": "workemailaddr","fieldValue": "'.$email.'"},';
	$request .= '{"setNo": 1,"seqNo": 5,"updateMode": "add","fieldName": "workphoneno","fieldValue": "'.$phone.'"},';
	$request .= '{"setNo": 1,"seqNo": 6,"updateMode": "add","fieldName": "faxnumber","fieldValue": "'.uniqid().'"},';
	$request .= '{"setNo": 1,"seqNo": 7,"updateMode": "add","fieldName": "addr1","fieldValue": "'.$address_1.'"},';
	$request .= '{"setNo": 1,"seqNo": 8,"updateMode": "add","fieldName": "addr2","fieldValue": "'.$address_2.'"},';
	$request .= '{"setNo": 1,"seqNo": 9,"updateMode": "add","fieldName": "addr3","fieldValue": "'.$address_3.'"},';
	$request .= '{"setNo": 1,"seqNo": 10,"updateMode": "add","fieldName": "city","fieldValue": "'.$city.'"},';
	$request .= '{"setNo": 1,"seqNo": 11,"updateMode": "add","fieldName": "zipcd","fieldValue": "'.$post_code.'"},';
	$request .= '{"setNo": 1,"seqNo": 5,"updateMode": "add","fieldName": "cotitle","fieldValue": "'.$title.'"}';
	$request .= ' ]},"extraData": "string"}}';
	return $request;
}

function returnCSDContactID($returnData) {
	$id = substr($returnData, strpos($returnData, 'Contact ID:') + 11);
	if($id) {
		if($id!='') {
			return trim($id);
		}
	}
	return false;
}

function createCSDContact($organizationID,$title,$first_name,$last_name,$email,$phone,$address_1,$address_2,$address_3,$city,$post_code) {
	$request = createContactsAPIRequest($organizationID,$title,$first_name,$last_name,$email,$phone,$address_1,$address_2,$address_3,$city,$post_code);
	$url = get_option('wpiai_contacts_api_url');
	$response = wpiai_get_infor_api_response($url,$request);
	$allArray = json_decode($response,true);
	if(is_array($allArray)) {
		if(is_array($allArray['response'])) {
			if ( array_key_exists( 'cErrorMessage', $allArray['response'] ) ) {
				if ( $allArray['response']['cErrorMessage'] == '' ) {
					if ( array_key_exists( 'returnData', $allArray['response'] ) ) {
						$id = returnCSDContactID( $allArray['response']['returnData'] );
						if ( $id ) {
							return $id;
						}
					}
				} else {
					error_log( $allArray['cErrorMessage'] );
				}
			} else {
				error_log( 'No cErrorMessage' );
			}
		}
	}
	error_log('Failed');
	error_log(print_r($allArray,true));
	return false;
}