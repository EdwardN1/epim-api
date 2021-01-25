<?php

add_action( 'profile_update', 'wpiai_profile_update', 10, 2 );

function wpiai_profile_update( $user_id, $old_user_data ) {
	// Do something
	error_log('Profile update for userID: '.$user_id);
	$user = get_userdata($user_id);
	if ($user) {
		$roles = $user->roles;
		if(in_array('customer',$roles)) {
			$CSD_ID = get_user_meta($user_id,'CSD_ID',true);
			if($CSD_ID == '') {
				/**
				 * New Customer Record So Create a CSD Customer
				 */
				$url = get_option( 'wpiai_customer_url' );
				$parameters = get_option( 'wpiai_customer_parameters' );
				$pRequest = get_customer_param_record($parameters);
				$xmlRequest = get_customer_XML_record($user_id);
				$updated = wpiai_get_infor_message_multipart_message($url,$pRequest,$xmlRequest);
				error_log(print_r($updated,true));
			} else {
				/**
				 *
				 *
				 * Check for other Master Record Updates Here
				 *
				 *
				 *
				 */
			}

		} else {
			/**
			 *
			 * Not a valid User?
			 *
			 */
		}
	}
}
function get_customer_param_record_x($parameters) {
	$json = json_decode($parameters);
	if (json_last_error() == JSON_ERROR_NONE) {
		$json->messageId = uniqid();
		return json_encode($json);
	} else {
		return $parameters;
	}
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