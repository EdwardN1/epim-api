<?php


add_action( 'profile_update', 'wpiai_profile_update', 10, 2 );

function compare_multi_Arrays( $arrayOld, $arrayNew, $indexKey ) {
	$result = array( "added" => array(), "removed" => array(), "changed" => array() );


	//added
	foreach ( $arrayNew as $subArrayNew ) {
		$found = false;
		foreach ( $arrayOld as $subArrayOld ) {
			if ( $subArrayNew[ $indexKey ] == $subArrayOld[ $indexKey ] ) {

			    $found = true;
			}
		}
		if ( ! $found ) {
			$result['added'][] = $subArrayNew;
		}
	}

	//changed & removed
	foreach ( $arrayOld as $subArrayOld ) {
		$found = false;
		foreach ( $arrayNew as $subArrayNew ) {
			if ( $subArrayNew[ $indexKey ] == $subArrayOld[ $indexKey ] ) {
				$found = true;
				$diffs = array_diff( $subArrayNew, $subArrayOld );
				if ( count( $diffs ) > 0 ) {
					$result['changed'][] = $subArrayNew;
				}
			}
		}
		if ( ! $found ) {
			$result['removed'][] = $subArrayOld;
		}
	}

	return $result;
}

function wpiai_profile_update( $user_id, $old_user_data ) {
	// Do something
	//error_log( 'Profile update registered for userID: ' . $user_id );


	$user = get_userdata( $user_id );
	if ( $user ) {
		$roles = $user->roles;
		if ( ( $user != $old_user_data ) || ( get_user_meta( $user_id, 'wpiai_force_update', true ) != '' ) ) {
			if ( in_array( 'customer', $roles ) ) {
				$CSD_ID = get_user_meta( $user_id, 'CSD_ID', true );
				$user4  = get_user_meta( $user_id, 'wpiai_user4', true );
				if ( ( $CSD_ID == '' ) || ( $user4 != $user_id ) ) {
					/**
					 * New Customer Record So Create a CSD Customer or Sync back Woo ID to CSD required
					 */
					$url        = get_option( 'wpiai_customer_url' );
					$parameters = get_option( 'wpiai_customer_parameters' );
					$pRequest   = get_customer_param_record_x( $parameters );
					$xmlRequest = get_customer_XML_record( $user_id );
					$updated    = wpiai_get_infor_message_multipart_message( $url, $pRequest, $xmlRequest );
					if($updated) {
						update_user_meta( $user_id, 'wpiai_force_update', '' );
					}
					error_log( 'Update sent for User ID: ' . $user_id . ' , CSD_ID:' . $CSD_ID );
				}

			} else {
				/**
				 *
				 * Not a Customer
				 *
				 */
			}
		} else {
			error_log( 'No Data Change for userID ' . $user_id );
		}
		/**
		 * Add To Meta Queue Processing
		 */

		if ( in_array( 'customer', $roles ) ) {
            $users_updated = get_option('wpiai_users_updated');
            if(!is_array($users_updated)) {
                $users_updated = array();
            }
            $users_updated[] = $user_id;
            if(!update_option('wpiai_users_updated',$users_updated)) {
                error_log('UserID not saved: '.$user_id);
            } else {
                error_log($user_id . ' added to the meta update queue');
            }


		}

	}
}

function get_customer_param_record_x( $parameters ) {
	$json = json_decode( $parameters );
	if ( json_last_error() == JSON_ERROR_NONE ) {
		$json->messageId = uniqid();

		return json_encode( $json );
	} else {
		return $parameters;
	}
}

function set_messageid( $parameters ) {
    $json = json_decode( $parameters );
    if ( json_last_error() == JSON_ERROR_NONE ) {
        $json->messageId = uniqid();

        return json_encode( $json );
    } else {
        error_log('set_messageid error');
        return $parameters;
    }
}

/**
 *
 * $contact['contact_email']
 * $contact['contact_addr_1']
 * $contact['contact_addr_2']
 * $contact['contact_addr_3']
 * $contact['contact_addr_4']
 * $contact['contact_postcode']
 * $contact['contact_phone']
 * $contact['contact_name']
 *
 */

function get_shipTo_XML_record( $user_id, $action, $record )
{
    $user = get_userdata($user_id);
    if ($user) {
        $xmld = get_option('wpiai_ship_to_xml');
        $xml = simplexml_load_string($xmld);

        $nowDT = new DateTime();
        $CreationDateTime = $nowDT->format(DateTime::ATOM);
        $BODID = uniqid();

        $FirstName = '';
        if(array_key_exists('delivery-first-name',$record)) {
            $FirstName = $record['delivery-first-name'];
        }
        $LastName = '';
        if(array_key_exists('delivery-last-name',$record)) {
            $LastName = $record['delivery-last-name'];
        }
        $CompanyName = $record['delivery-company-name'];
        $AddressLine1 = $record['delivery-street-address-1'];
        $AddressLine2 = $record['delivery-street-address-2'];
        $AddressLine3 = $record['delivery-street-address-3'];
        $CityName = $record['delivery-town-city'];
        $PostalCode = $record['delivery-postcode'];
        $DialNumber = $record['delivery-phone'];
        $URI = '';
        if(array_key_exists('delivery-email',$record)) {
            $URI = $record['delivery-email'];
        }
        $delivery_UNIQUE_ID = $record['delivery_UNIQUE_ID'];
        $ShipTo_ID = $record['delivery-CSD-ID'];
        $Customer_CSD_ID = get_user_meta( $user_id, 'CSD_ID', true );

        $xml->registerXPathNamespace( 'x', 'http://schema.infor.com/InforOAGIS/2' );
        if ( $xml->xpath( '//x:ApplicationArea' )[0]->CreationDateTime[0] ) {
            $xml->xpath( '//x:ApplicationArea' )[0]->CreationDateTime[0] = $CreationDateTime;
        } else {
            //error_log('Cant find path');
        }
        if ( $xml->xpath( '//x:ApplicationArea' )[0]->BODID[0] ) {
            $xml->xpath( '//x:ApplicationArea' )[0]->BODID[0] = $BODID;
        }
        if ( $xml->xpath( '//x:DataArea' )[0]->Process[0]->ActionCriteria[0]->ActionExpression[0] ) {
            $xml->xpath( '//x:DataArea' )[0]->Process[0]->ActionCriteria[0]->ActionExpression[0]                             = $action;
            $xml->xpath( '//x:DataArea' )[0]->Process[0]->ActionCriteria[0]->ActionExpression[0]->attributes()['actionCode'] = $action;
        }
        if ( $xml->xpath( '//x:DataArea' )[0]->ShipToPartyMaster[0]->PartyIDs[0]->ID[0] ) {
            $xml->xpath( '//x:DataArea' )[0]->ShipToPartyMaster[0]->PartyIDs[0]->ID[0] = $ShipTo_ID;
        }
        if ( $xml->xpath( '//x:DataArea' )[0]->ShipToPartyMaster[0]->Name[0] ) {
            $xml->xpath( '//x:DataArea' )[0]->ShipToPartyMaster[0]->Name[0] = $CompanyName;
        }
        if ( $xml->xpath( '//x:DataArea' )[0]->ShipToPartyMaster[0]->Location[0]->Address[0]->AddressLine[0] ) {
            $xml->xpath( '//x:DataArea' )[0]->ShipToPartyMaster[0]->Location[0]->Address[0]->AddressLine[0] = $AddressLine1;
        }
        if ( $xml->xpath( '//x:DataArea' )[0]->ShipToPartyMaster[0]->Location[0]->Address[0]->AddressLine[1] ) {
            $xml->xpath( '//x:DataArea' )[0]->ShipToPartyMaster[0]->Location[0]->Address[0]->AddressLine[1] = $AddressLine2;
        }
        if ( $xml->xpath( '//x:DataArea' )[0]->ShipToPartyMaster[0]->Location[0]->Address[0]->AddressLine[2] ) {
            $xml->xpath( '//x:DataArea' )[0]->ShipToPartyMaster[0]->Location[0]->Address[0]->AddressLine[2] = $AddressLine3;
        }
        if ( $xml->xpath( '//x:DataArea' )[0]->ShipToPartyMaster[0]->Location[0]->Address[0]->CityName[0] ) {
            $xml->xpath( '//x:DataArea' )[0]->ShipToPartyMaster[0]->Location[0]->Address[0]->CityName[0] = $CityName;
        }
        if ( $xml->xpath( '//x:DataArea' )[0]->ShipToPartyMaster[0]->Location[0]->Address[0]->PostalCode[0] ) {
            $xml->xpath( '//x:DataArea' )[0]->ShipToPartyMaster[0]->Location[0]->Address[0]->PostalCode[0] = $PostalCode;
        }
        if ( $xml->xpath( '//x:DataArea' )[0]->ShipToPartyMaster[0]->Communication[0]->DialNumber[0] ) {
            $xml->xpath( '//x:DataArea' )[0]->ShipToPartyMaster[0]->Communication[0]->DialNumber[0] = $DialNumber;
        }
        if ( $xml->xpath( '//x:DataArea' )[0]->ShipToPartyMaster[0]->Communication[1]->URI[0] ) {
            $xml->xpath( '//x:DataArea' )[0]->ShipToPartyMaster[0]->Communication[1]->URI[0] = $URI;
        }
        if ( $xml->xpath( '//x:DataArea' )[0]->ShipToPartyMaster[0]->CustomerParty[0]->PartyIDs[0]->ID[0] ) {
            $xml->xpath( '//x:DataArea' )[0]->ShipToPartyMaster[0]->CustomerParty[0]->PartyIDs[0]->ID[0] = $Customer_CSD_ID;
        }
        if ( $xml->xpath( '//x:DataArea' )[0]->ShipToPartyMaster[0]->UserArea[0]->Property[0]->NameValue[0] ) {
            $xml->xpath( '//x:DataArea' )[0]->ShipToPartyMaster[0]->UserArea[0]->Property[0]->NameValue[0] = $delivery_UNIQUE_ID;
        }
        $xmld          = $xml->asXML();
        return $xmld;
    } else {
        return false;
    }
}

function get_contact_XML_record( $user_id, $action, $record ) {
	$user = get_userdata( $user_id );
	if ( $user ) {
		$xmld = get_option( 'wpiai_contact_xml' );
		if($action <> 'Add') {
			$xmld = get_option( 'wpiai_contact_xml_update' );
		}
		$xml  = simplexml_load_string( $xmld );

		$nowDT            = new DateTime();
		$CreationDateTime = $nowDT->format( DateTime::ATOM );
		$BODID            = uniqid();
		$LastModificationDateTime = $CreationDateTime;

		$StatusCode = $record['contact_status_code'];
		$GivenName =$record['contact_first_name'];
		$FamilyName  = $record['contact_last_name'];
        $name = $GivenName;
        if($FamilyName != '') {
            if($GivenName !='') $name .= ' ';
            $name .= $FamilyName;
        }
		$JobTitle = $record['contact_job_title'];
        $AddressLine = $record['contact_addr_1'];
		$AddressLine2 = $record['contact_addr_2'];
        /*if($record['contact_addr_2'] != '') {
            $AddressLine .= ', '.$record['contact_addr_2'];
        }*/
        $CityName = $record['contact_addr_3'];
        $PostalCode = $record['contact_postcode'];
        $DialNumber = $record['contact_phone'];
		$MobileNumber = $record['contact_mobile_phone'];
        $URI = $record['contact_email'];

        $commPhone = $record['contact_phone_channel'];
        $commFax = $record['contact_fax_channel'];
        $commMail = $record['contact_mail_channel'];
        $commEmail = $record['contact_email_channel'];

        $contact_CONTACT_ID = $record['contact_CONTACT_ID']; //Woo Contact ID
        $contact_CSD_ID = $record['contact_CSD_ID']; //CSD Contact ID
        $Customer_CSD_ID = get_user_meta( $user_id, 'CSD_ID', true ); //CSD Customer ID

        if($commPhone != '1') {
        	$commPhone = 'true';
        } else {
        	$commPhone = 'false';
        }

		if($commFax != '1') {
			$commFax = 'true';
		} else {
			$commFax = 'false';
		}

		if($commMail != '1') {
			$commMail = 'true';
		} else {
			$commMail = 'false';
		}

		if($commEmail != '1') {
			$commEmail = 'true';
		} else {
			$commEmail = 'false';
		}

        $xml->registerXPathNamespace( 'x', 'http://schema.infor.com/InforOAGIS/2' );
        if ( $xml->xpath( '//x:ApplicationArea' )[0]->CreationDateTime[0] ) {
            $xml->xpath( '//x:ApplicationArea' )[0]->CreationDateTime[0] = $CreationDateTime;
        } else {
            //error_log('Cant find path');
        }
        if ( $xml->xpath( '//x:ApplicationArea' )[0]->BODID[0] ) {
            $xml->xpath( '//x:ApplicationArea' )[0]->BODID[0] = $BODID;
        }
        if ( $xml->xpath( '//x:DataArea' )[0]->Process[0]->ActionCriteria[0]->ActionExpression[0] ) {
            $xml->xpath( '//x:DataArea' )[0]->Process[0]->ActionCriteria[0]->ActionExpression[0]                             = $action;
            $xml->xpath( '//x:DataArea' )[0]->Process[0]->ActionCriteria[0]->ActionExpression[0]->attributes()['actionCode'] = $action;
        }
        if ( $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->IDs[0]->ID[0] ) {
            $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->IDs[0]->ID[0] = $contact_CSD_ID;
        }
        if ( $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->Name[0] ) {
            $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->Name[0] = $name;
        }
        if ( $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->Name[0]->Code[0] ) {
            $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->Name[0]->Code[0] = $StatusCode;
        }
        if ( $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->GivenName[0] ) {
            $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->GivenName[0] = $GivenName;
        }
        if ( $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->FamilyName[0] ) {
            $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->FamilyName[0] = $FamilyName;
        }
        if ( $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->JobTitle[0] ) {
            $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->JobTitle[0] = $JobTitle;
        }
        if ( $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->CommunicationDetail[0]->Address[0]->AddressLine[0] ) {
            $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->CommunicationDetail[0]->Address[0]->AddressLine[0] = $AddressLine;
        }
		if ( $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->CommunicationDetail[0]->Address[0]->AddressLine[1] ) {
			$xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->CommunicationDetail[0]->Address[0]->AddressLine[1] = $AddressLine2;
		}
        if ( $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->CommunicationDetail[0]->Address[0]->CityName[0] ) {
            $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->CommunicationDetail[0]->Address[0]->CityName[0] = $CityName;
        }
		/*if ( $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->CommunicationDetail[0]->Address[0]->CountrySubDivisionCode[0] ) {
			$xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->CommunicationDetail[0]->Address[0]->CountrySubDivisionCode[0] = $contact_CONTACT_ID;
		}*/
        if ( $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->CommunicationDetail[0]->Address[0]->PostalCode[0] ) {
            $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->CommunicationDetail[0]->Address[0]->PostalCode[0] = $PostalCode;
        }
        if ( $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->CommunicationDetail[1]->DialNumber[0] ) {
            $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->CommunicationDetail[1]->DialNumber[0] = $DialNumber;
        }
		if ( $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->CommunicationDetail[2]->DialNumber[0] ) {
			$xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->CommunicationDetail[2]->DialNumber[0] = $contact_CONTACT_ID;
		}
        if ( $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->CommunicationDetail[4]->DialNumber[0] ) {
            $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->CommunicationDetail[4]->DialNumber[0] = $MobileNumber;
        }
        if ( $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->CommunicationDetail[5]->URI[0] ) {
            $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->CommunicationDetail[5]->URI[0] = $URI;
        }
        if($action=='Add') {
	        if ( $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->CommunicationSummary[0]->DoNotUseIndicator[0] ) {
		        $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->CommunicationSummary[0]->DoNotUseIndicator[0] = $commPhone;
	        }
	        if ( $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->CommunicationSummary[1]->DoNotUseIndicator[0] ) {
		        $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->CommunicationSummary[1]->DoNotUseIndicator[0] = $commFax;
	        }
	        if ( $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->CommunicationSummary[2]->DoNotUseIndicator[0] ) {
		        $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->CommunicationSummary[2]->DoNotUseIndicator[0] = $commMail;
	        }
	        if ( $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->CommunicationSummary[3]->DoNotUseIndicator[0] ) {
		        $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->CommunicationSummary[3]->DoNotUseIndicator[0] = $commEmail;
	        }
        }
		if ( $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->EmployerReference[0]->DocumentID[0]->ID[0] ) {
			$xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->EmployerReference[0]->DocumentID[0]->ID[0] = $Customer_CSD_ID;
		}
		/*if ( $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->UserArea[0]->Property[0]->NameValue[0] ) {
			$xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->UserArea[0]->Property[0]->NameValue[0] = $contact_CONTACT_ID;
		}*/
        $xmld          = $xml->asXML();
        return $xmld;
	} else {
	    return false;
    }
}

function get_customer_XML_record( $user_id ) {
	$res  = false;
	$user = get_userdata( $user_id );
	if ( $user ) {
		$xmld = get_option( 'wpiai_customer_xml' );
		$xml  = simplexml_load_string( $xmld );
		//error_log(print_r($xml,true));
		$BODID            = uniqid();
		$nowDT            = new DateTime();
		$CreationDateTime = $nowDT->format( DateTime::ATOM );
		$ActionExpression = 'Change';
		//$Name = $user->first_name.' '.$user->last_name;
		$Name   = get_user_meta( $user_id, 'billing_company', true );
		$CSD_ID = get_user_meta( $user_id, 'CSD_ID', true );
		if ( $CSD_ID == '' ) {
			$ActionExpression = 'Add';
		}
		$AddressLine1 = get_user_meta( $user_id, 'billing_address_1', true );
		$AddressLine2 = get_user_meta( $user_id, 'billing_address_2', true );
		$AddressLine3 = get_user_meta( $user_id, 'billing_state', true );
		$CityName     = get_user_meta( $user_id, 'billing_city', true );
		$PostalCode   = get_user_meta( $user_id, 'billing_postcode', true );
		$DialNumber   = get_user_meta( $user_id, 'billing_phone', true );
		$URI          = $user->user_email;
		$SXe_user4    = $user_id;
		$xml->registerXPathNamespace( 'x', 'http://schema.infor.com/InforOAGIS/2' );
		//error_log(print_r($xml->xpath('//x:ApplicationArea')[0]->CreationDateTime[0],true));
		//$xml->xpath('//x:ApplicationArea')[0]->CreationDateTime[0] = $CreationDateTime;
		if ( $xml->xpath( '//x:ApplicationArea' )[0]->CreationDateTime[0] ) {
			$xml->xpath( '//x:ApplicationArea' )[0]->CreationDateTime[0] = $CreationDateTime;
		} else {
			//error_log('Cant find path');
		}
		if ( $xml->xpath( '//x:ApplicationArea' )[0]->BODID[0] ) {
			$xml->xpath( '//x:ApplicationArea' )[0]->BODID[0] = $BODID;
		}
		if ( $xml->xpath( '//x:DataArea' )[0]->Process[0]->ActionCriteria[0]->ActionExpression[0] ) {
			$xml->xpath( '//x:DataArea' )[0]->Process[0]->ActionCriteria[0]->ActionExpression[0]                             = $ActionExpression;
			$xml->xpath( '//x:DataArea' )[0]->Process[0]->ActionCriteria[0]->ActionExpression[0]->attributes()['actionCode'] = $ActionExpression;
		}
		if ( $xml->xpath( '//x:DataArea' )[0]->CustomerPartyMaster[0]->PartyIDs[0]->ID[0] ) {
			$xml->xpath( '//x:DataArea' )[0]->CustomerPartyMaster[0]->PartyIDs[0]->ID[0] = $CSD_ID;
		}
		if ( $xml->xpath( '//x:DataArea' )[0]->CustomerPartyMaster[0]->Name[0] ) {
			$xml->xpath( '//x:DataArea' )[0]->CustomerPartyMaster[0]->Name[0] = $Name;
		}
		if ( $xml->xpath( '//x:DataArea' )[0]->CustomerPartyMaster[0]->Location[0]->Address[0]->AddressLine[0] ) {
			$xml->xpath( '//x:DataArea' )[0]->CustomerPartyMaster[0]->Location[0]->Address[0]->AddressLine[0] = $AddressLine1;
		}
		if ( $xml->xpath( '//x:DataArea' )[0]->CustomerPartyMaster[0]->Location[0]->Address[0]->AddressLine[1] ) {
			$xml->xpath( '//x:DataArea' )[0]->CustomerPartyMaster[0]->Location[0]->Address[0]->AddressLine[1] = $AddressLine2;
		}
		if ( $xml->xpath( '//x:DataArea' )[0]->CustomerPartyMaster[0]->Location[0]->Address[0]->AddressLine[2] ) {
			$xml->xpath( '//x:DataArea' )[0]->CustomerPartyMaster[0]->Location[0]->Address[0]->AddressLine[2] = $AddressLine3;
		}
		if ( $xml->xpath( '//x:DataArea' )[0]->CustomerPartyMaster[0]->Location[0]->Address[0]->AddressLine[2] ) {
			$xml->xpath( '//x:DataArea' )[0]->CustomerPartyMaster[0]->Location[0]->Address[0]->AddressLine[2] = '';
		}
		if ( $xml->xpath( '//x:DataArea' )[0]->CustomerPartyMaster[0]->Location[0]->Address[0]->CityName[0] ) {
			$xml->xpath( '//x:DataArea' )[0]->CustomerPartyMaster[0]->Location[0]->Address[0]->CityName[0] = $CityName;
		}
		if ( $xml->xpath( '//x:DataArea' )[0]->CustomerPartyMaster[0]->Location[0]->Address[0]->PostalCode[0] ) {
			$xml->xpath( '//x:DataArea' )[0]->CustomerPartyMaster[0]->Location[0]->Address[0]->PostalCode[0] = $PostalCode;
		}
		if ( $xml->xpath( '//x:DataArea' )[0]->CustomerPartyMaster[0]->Communication[0]->DialNumber[0] ) {
			$xml->xpath( '//x:DataArea' )[0]->CustomerPartyMaster[0]->Communication[0]->DialNumber[0] = $DialNumber;
		}
		if ( $xml->xpath( '//x:DataArea' )[0]->CustomerPartyMaster[0]->Communication[1]->URI[0] ) {
			$xml->xpath( '//x:DataArea' )[0]->CustomerPartyMaster[0]->Communication[1]->URI[0] = $URI;
		}
		/*if($xml->xpath('//x:DataArea')[0]->CustomerPartyMaster[0]->UserArea[0]->Property[0]->NameValue[0]) {
			$xml->xpath('//x:DataArea')[0]->CustomerPartyMaster[0]->UserArea[0]->Property[0]->Property[0]->NameValue[0]-> = $SXe_user4;
			error_log(print_r($xml->xpath('//x:DataArea')[0]->CustomerPartyMaster[0]->UserArea[0]->Property[0]->NameValue,true));
		}*/
		$xmld          = $xml->asXML();
		$propertyStart = strpos( $xmld, '<NameValue type="String" name="SXe_user4">' );
		if ( $propertyStart ) {
			$propertyEnd = strpos( $xmld, '</NameValue>', $propertyStart );
			$xmld        = substr( $xmld, 0, $propertyStart ) . '<NameValue type="String" name="SXe_user4">' . $SXe_user4 . substr( $xmld, $propertyEnd );
		}

		return $xmld;
	} else {
		return $res;
	}
}