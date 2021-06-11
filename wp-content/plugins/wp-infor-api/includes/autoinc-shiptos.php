<?php
function createShipToAPIRequest($organizationID,$customerID,$company,$address_1,$address_2,$address_3,$city,$post_code,$email = '') {
	$unique = uniqidReal(8);//uniqid();
	$request = '{"request": {"companyNumber": 1,"operatorInit": "WOO","tMntTt": {"t-mnt-tt": [';
	$request .= '{"setNo": 1,"seqNo": 1,"key1": "'.$organizationID.'","key2": "'.$unique.'","updateMode": "add","fieldName": "addr1","fieldValue": "'.$address_1.'"},';
	$request .= '{"setNo": 1,"seqNo": 2,"key1": "'.$organizationID.'","key2": "'.$unique.'","updateMode": "add","fieldName": "addr2","fieldValue": "'.$address_2.'"},';
	$request .= '{"setNo": 1,"seqNo": 3,"key1": "'.$organizationID.'","key2": "'.$unique.'","updateMode": "add","fieldName": "addr3","fieldValue": "'.$address_3.'"},';
	$request .= '{"setNo": 1,"seqNo": 4,"key1": "'.$organizationID.'","key2": "'.$unique.'","updateMode": "add","fieldName": "name","fieldValue": "'.$company.'"},';
	$request .= '{"setNo": 1,"seqNo": 5,"key1": "'.$organizationID.'","key2": "'.$unique.'","updateMode": "add","fieldName": "city","fieldValue": "'.$city.'"},';
	$request .= '{"setNo": 1,"seqNo": 6,"key1": "'.$organizationID.'","key2": "'.$unique.'","updateMode": "add","fieldName": "zipcd","fieldValue": "'.$post_code.'"},';
	$request .= '{"setNo": 1,"seqNo": 7,"key1": "'.$organizationID.'","key2": "'.$unique.'","updateMode": "add","fieldName": "user1","fieldValue": "'.$customerID.'"},';
	if($email!='') {
		$request .= '{"setNo": 1,"seqNo": 8,"key1": "'.$organizationID.'","key2": "'.$unique.'","updateMode": "add","fieldName": "email","fieldValue": "'.$email.'"},';
	}
	$request .= '{"setNo": 1,"seqNo": 9,"key1": "'.$organizationID.'","key2": "'.$unique.'","updateMode": "add","fieldName": "user2","fieldValue": "'.$unique.'"}';
	$request .= ' ]},"extraData": "string"}}';
	return $request;
}

function returnCSDShipToID($returnData) {
	$pieces = explode(',',$returnData);
	if(is_array($pieces)) {
		if(count($pieces>=3)) {
			$id1 = substr($pieces[1], strpos($pieces[1], 'Customer #:') + 11);
			if($id1) {
				$id2 = substr($pieces[2], strpos($pieces[2], 'Ship To:') + 8);
				if($id2) {
					return trim($id1).'-'.trim($id2);
				}
			}
		}
	}
	return false;
}

function createCSDShipTo($organizationID,$customerID,$company,$address_1,$address_2,$address_3,$city,$post_code, $email='') {
	$request = createShipToAPIRequest($organizationID,$customerID,$company,$address_1,$address_2,$address_3,$city,$post_code,$email);
	$url = get_option('wpiai_shipto_api_url');
	$response = wpiai_get_infor_api_response($url,$request);
	$allArray = json_decode($response,true);
	if(is_array($allArray)) {
		if(is_array($allArray['response'])) {
			if ( array_key_exists( 'cErrorMessage', $allArray['response'] ) ) {
				if ( $allArray['response']['cErrorMessage'] == '' ) {
					if ( array_key_exists( 'returnData', $allArray['response'] ) ) {
						$id = returnCSDShipToID( $allArray['response']['returnData'] );
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