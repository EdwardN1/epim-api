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
	error_log( 'Profile update registered for userID: ' . $user_id );
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
		 * Ship To Processing
		 */

		if ( in_array( 'customer', $roles ) ) {
			$oldShipTo = get_user_meta( $user_id, 'wpiai_last_delivery_addresses', true );
			if ( ! $oldShipTo ) {
				$oldShipTo = array();
			}
			if ( $oldShipTo == '' ) {
				$oldShipTo = array();
			}
			$shipTo_meta = get_user_meta( $user_id, 'wpiai_delivery_addresses', true );

			$shipTo      = array();
			if(is_array($shipTo_meta)) {
				foreach ( $shipTo_meta as $shipTo_m ) {
					$shipTo_rec = $shipTo_m;
					if ( ( $shipTo_rec['delivery_UNIQUE_ID'] == '' ) || ( ! array_key_exists( 'delivery_UNIQUE_ID', $shipTo_rec ) ) ) {
						error_log( 'setting delivery_UNIQUE_ID' );
						$shipTo_rec['delivery_UNIQUE_ID'] = uniqid();
					}
					$shipTo[] = $shipTo_rec;
				}
			}
			update_user_meta( $user_id, 'wpiai_delivery_addresses', $shipTo );

			$shipTo = get_user_meta( $user_id, 'wpiai_delivery_addresses', true );

			$shipToDiff = compare_multi_Arrays( $oldShipTo, $shipTo, 'delivery_UNIQUE_ID' );

			if ( ( count( $shipToDiff["added"] ) > 0 ) || ( count( $shipToDiff["removed"] ) > 0 ) || ( count( $shipToDiff["changed"] ) > 0 ) ) {
				$shipTo_url        = get_option( 'wpiai_ship_to_url' );
				$shipTo_paramaters = set_messageid(get_option('wpiai_ship_to_parameters'));
				foreach($shipToDiff["added"] as $add_shipTo) {
					$shipTo_xml = get_shipTo_XML_record($user_id,'Add',$add_shipTo);
					//$updated    = wpiai_get_infor_message_multipart_message( $shipTo_url, $shipTo_paramaters, $shipTo_xml );
				}
				foreach($shipToDiff["removed"] as $remove_shipTo) {
					$shipTo_xml = get_shipTo_XML_record($user_id,'Delete',$remove_shipTo);
					//$updated    = wpiai_get_infor_message_multipart_message( $shipTo_url, $shipTo_paramaters, $shipTo_xml );
				}
				foreach($shipToDiff["changed"] as $update_shipTo) {
					$shipTo_xml = get_shipTo_XML_record($user_id,'Change',$update_shipTo);
					//$updated    = wpiai_get_infor_message_multipart_message( $shipTo_url, $shipTo_paramaters, $shipTo_xml );
				}
				update_user_meta( $user_id, 'wpiai_last_delivery_addresses', $shipTo );
			}
		}

		/**
		 * Contact Processing
		 */
		if ( in_array( 'customer', $roles ) ) {
			$oldContacts = get_user_meta( $user_id, 'wpiai_last_contacts', true );
			if ( ! $oldContacts ) {
				$oldContacts = array();
			}
			if ( $oldContacts == '' ) {
				$oldContacts = array();
			}
			$contacts_meta = get_user_meta( $user_id, 'wpiai_contacts', true );
			$contacts      = array();
			if(is_array($contacts_meta)) {
				foreach ( $contacts_meta as $contactm ) {
					$contact_rec = $contactm;
					if ( ( $contact_rec['contact_CONTACT_ID'] == '' ) || ( ! array_key_exists( 'contact_CONTACT_ID', $contact_rec ) ) ) {
						error_log( 'setting contact_CONTACT_ID for user:'.$user_id );
						$contact_rec['contact_CONTACT_ID'] = uniqid();
					}
					$contacts[] = $contact_rec;
				}
			}
			update_user_meta( $user_id, 'wpiai_contacts', $contacts );

			$contacts = get_user_meta( $user_id, 'wpiai_contacts', true );

			if(count($contacts)==0) {
				/**
				 * Create a contact from billing address as none exist
				 */
				$contact = array();
				$contact['contact_first_name'] = get_user_meta($user_id,'first_name',true);
                $contact['contact_last_name'] = get_user_meta($user_id,'last_name',true);
				$contact['contact_email'] = $user->user_email;
				$contact['contact_addr_1'] = get_user_meta($user_id,'billing_address_1',true);
				$contact['contact_addr_2'] = get_user_meta($user_id,'billing_address_2',true);
				$contact['contact_addr_3'] = get_user_meta($user_id,'billing_city',true);
				$contact['contact_addr_4'] = get_user_meta($user_id,'billing_country',true);
				$contact['contact_postcode'] = get_user_meta($user_id,'billing_postcode',true);
				$contact['contact_phone'] = get_user_meta($user_id,'billing_phone',true);
				$contact['contact_CONTACT_ID'] = uniqid();
				$contacts[] = $contact;
				update_user_meta( $user_id, 'wpiai_contacts', $contacts );
			}

			$contactDiff = compare_multi_Arrays( $oldContacts, $contacts, 'contact_CONTACT_ID' );
			//error_log( print_r( $contactDiff, true ) );

			if ( ( count( $contactDiff["added"] ) > 0 ) || ( count( $contactDiff["removed"] ) > 0 ) || ( count( $contactDiff["changed"] ) > 0 ) ) {
				/**
				 *
				 * Send changes to Infor for contacts here.
				 *
				 */
				$contact_url        = get_option( 'wpiai_contact_url' );
				$contact_paramaters = set_messageid(get_option('wpiai_contact_parameters'));
				foreach($contactDiff["added"] as $add_contact) {
                    $contact_xml = get_contact_XML_record($user_id,'Add',$add_contact);
					//$updated    = wpiai_get_infor_message_multipart_message( $contact_url, $contact_paramaters, $contact_xml );
                }
                foreach($contactDiff["removed"] as $remove_contact) {
                    $contact_xml = get_contact_XML_record($user_id,'Delete',$remove_contact);
	                //$updated    = wpiai_get_infor_message_multipart_message( $contact_url, $contact_paramaters, $contact_xml );
                }
                foreach($contactDiff["changed"] as $update_contact) {
                    $contact_xml = get_contact_XML_record($user_id,'Change',$update_contact);
	                //$updated    = wpiai_get_infor_message_multipart_message( $contact_url, $contact_paramaters, $contact_xml );
                }
				update_user_meta( $user_id, 'wpiai_last_contacts', $contacts );
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

function get_shipTo_XML_record( $user_id, $action, $record ) {

}

function get_contact_XML_record( $user_id, $action, $record ) {
	$res  = false;
	$user = get_userdata( $user_id );
	if ( $user ) {
		$xmld = get_option( 'wpiai_contact_xml' );
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
        if($record['contact_addr_2'] != '') {
            $AddressLine .= ', '.$record['contact_addr_2'];
        }
        $CityName = $record['contact_addr_3'];
        $PostalCode = $record['contact_postcode'];
        $DialNumber = $record['contact_phone'];
        $URI = $record['contact_email'];

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
        if ( $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->CommunicationDetail[0]->Address[0]->CityName[0] ) {
            $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->CommunicationDetail[0]->Address[0]->CityName[0] = $CityName;
        }
        if ( $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->CommunicationDetail[0]->Address[0]->PostalCode[0] ) {
            $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->CommunicationDetail[0]->Address[0]->PostalCode[0] = $PostalCode;
        }
        if ( $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->CommunicationDetail[1]->DialNumber[0] ) {
            $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->CommunicationDetail[1]->DialNumber[0] = $DialNumber;
        }
        if ( $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->CommunicationDetail[4]->DialNumber[0] ) {
            $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->CommunicationDetail[4]->DialNumber[0] = $DialNumber;
        }
        if ( $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->CommunicationDetail[5]->URI[0] ) {
            $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->CommunicationDetail[5]->URI[0] = $URI;
        }
		if ( $xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->EmployerReference [0]->DocumentID[0]->ID[0] ) {
			$xml->xpath( '//x:DataArea' )[0]->ContactMaster[0]->EmployerReference [0]->DocumentID[0]->ID[0] = $Customer_CSD_ID;
		}
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