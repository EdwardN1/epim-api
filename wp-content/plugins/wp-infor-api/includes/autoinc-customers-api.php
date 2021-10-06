<?php
function createCustomersAPIRequest($user_id,$company_name,$email,$phone,$address_1,$address_2,$city,$post_code,$credit_check=false) {
	$cc = '0';
	if($credit_check) {
		$cc = '1';
	}
	$request = '{"request": {"companyNumber": 1,"operatorInit": "JR2","tMntTt": {"t-mnt-tt": [';
	$request .= '{"setNo": 1,"seqNo": 1,"updateMode": "add","fieldName": "addr1","fieldValue": "'.$address_1.'"},';
	$request .= '{"setNo": 1,"seqNo": 2,"updateMode": "add","fieldName": "addr2","fieldValue": "'.$address_2.'"},';
	$request .= '{"setNo": 1,"seqNo": 3,"updateMode": "add","fieldName": "city","fieldValue": "'.$city.'"},';
	$request .= '{"setNo": 1,"seqNo": 4,"updateMode": "add","fieldName": "zipcd","fieldValue": "'.$post_code.'"},';
	$request .= '{"setNo": 1,"seqNo": 5,"updateMode": "add","fieldName": "name","fieldValue": "'.$company_name.'"},';
	$request .= '{"setNo": 1,"seqNo": 6,"updateMode": "add","fieldName": "custtype","fieldValue": "DI"},';
	$request .= '{"setNo": 1,"seqNo": 7,"updateMode": "add","fieldName": "phoneno","fieldValue": "'.$phone.'"},';
	$request .= '{"setNo": 1,"seqNo": 8,"updateMode": "add","fieldName": "email","fieldValue": "'.$email.'"},';
	$request .= '{"setNo": 1,"seqNo": 9,"updateMode": "add","fieldName": "user4","fieldValue": "'.$user_id.'"},';
	$request .= '{"setNo": 1,"seqNo": 10,"updateMode": "add","fieldName": "whse","fieldValue": "FCC"},';
	$request .= '{"setNo": 1,"seqNo": 11,"updateMode": "add","fieldName": "statecd","fieldValue": "gb"},';
	$request .= '{"setNo": 1,"seqNo": 12,"updateMode": "add","fieldName": "divno","fieldValue": "09"},';
	$request .= '{"setNo": 1,"seqNo": 13,"updateMode": "add","fieldName": "selltype","fieldValue": "c"},';
	$request .= '{"setNo": 1,"seqNo": 14,"updateMode": "add","fieldName": "slsrepin","fieldValue": "MA99"},';
	$request .= '{"setNo": 1,"seqNo": 15,"updateMode": "add","fieldName": "slsrepout","fieldValue": "MA99"},';
	$request .= '{"setNo": 1,"seqNo": 16,"updateMode": "add","fieldName": "taxablety","fieldValue": "y"},';
	$request .= '{"setNo": 1,"seqNo": 17,"updateMode": "add","fieldName": "bankno","fieldValue": "1"},';
	$request .= '{"setNo": 1,"seqNo": 18,"updateMode": "add","fieldName": "termstype","fieldValue": "01"},';
	$request .= '{"setNo": 1,"seqNo": 19,"updateMode": "add","fieldName": "tendqtyfl","fieldValue": "yes"},';
	$request .= '{"setNo": 1,"seqNo": 20,"updateMode": "add","fieldName": "pricecd","fieldValue": "1"},';
	$request .= '{"setNo": 1,"seqNo": 21,"updateMode": "add","fieldName": "disccd","fieldValue": "1"},';
	$request .= '{"setNo": 1,"seqNo": 22,"updateMode": "add","fieldName": "wodisccd","fieldValue": "1"},';
	$request .= '{"setNo": 1,"seqNo": 23,"updateMode": "add","fieldName": "einvtype","fieldValue": "M"},';
	$request .= '{"setNo": 1,"seqNo": 24,"updateMode": "add","fieldName": "einvto","fieldValue": "'.$email.'"},';
	$request .= '{"setNo": 1,"seqNo": 25,"updateMode": "add","fieldName": "user6","fieldValue": "'.$cc.'"}';
	$request .= ' ]},"extraData": "string"}}';
	wp_mail('edward@technicks.com','Infor Customer Request',$request,array('Content-Type: text/html; charset=UTF-8'));
	return $request;
}

function returnCSDCustomerID($returnData) {
	$id = substr($returnData, strpos($returnData, 'Customer #:') + 11);
	if($id) {
		if($id!='') {
			return trim($id);
		}
	}
	return false;
}

function createCSDCustomer($user_id,$company_name,$email,$phone,$address_1,$address_2,$city,$post_code,$credit_check=false) {
	$submitted = '$user_id='.$user_id.',$company_name='.$company_name.',$email='.$email.',$phone='.$phone.',$address_1='.$address_1.',$address_2='.$address_2.',$city='.$city.',$post_code='.$post_code.',$credit_check='.$credit_check;
	wp_mail('edward@technicks.com','createCSDCustomer',$submitted,array('Content-Type: text/html; charset=UTF-8'));
	$request = createCustomersAPIRequest($user_id,$company_name,$email,$phone,$address_1,$address_2,$city,$post_code);
	$url = get_option('wpiai_customer_api_url');
	$response = wpiai_get_infor_api_response($url,$request);
	$allArray = json_decode($response,true);
	if(is_array($allArray)) {
		if(is_array($allArray['response'])) {
			if ( array_key_exists( 'cErrorMessage', $allArray['response'] ) ) {
				if ( $allArray['response']['cErrorMessage'] == '' ) {
					if ( array_key_exists( 'returnData', $allArray['response'] ) ) {
						$id = returnCSDCustomerID( $allArray['response']['returnData'] );
						if ( $id ) {
							//wp_mail('edward@technicks.com','Infor Customer Request',$id,array('Content-Type: text/html; charset=UTF-8'));
							return $id;
						}
					}
				} else {
					//wp_mail('edward@technicks.com','Infor Customer Request',$allArray['cErrorMessage'],array('Content-Type: text/html; charset=UTF-8'));
					error_log( $allArray['cErrorMessage'] );
				}
			} else {
				//wp_mail('edward@technicks.com','Infor Customer Request',$id,array('Content-Type: text/html; charset=UTF-8'));
				error_log( 'No cErrorMessage' );
			}
		}
	}
	error_log('Failed');
	error_log(print_r($allArray,true));
	return false;
}