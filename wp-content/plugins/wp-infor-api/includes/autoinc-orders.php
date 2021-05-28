<?php
/**
 *
 * Sales Order Added
 *
 *
 */

//add_action( 'woocommerce_new_order', 'wpiai_order_added' );
add_action( 'woocommerce_thankyou', 'wpiai_order_added' );

function wpiai_order_added( $order_id ) {
	$order = wc_get_order( $order_id );
	/* Insert your code */
	/**
	 *
	 * Add in checks....
	 *
	 * If Guest Account -
	 *    -> Create Contact
	 *    -> Create Shipto
	 *    -> Then Send Order
	 *
	 * if Customer Account -
	 *   -> Selected ShipTo or Need to Create ShipTo
	 *   -> Then Create Order
	 *
	 */
	if($order) {
		if($order->get_created_via()!='rest-api') {
			$url        = get_option( 'wpiai_sales_order_url' );
			$parameters = get_option( 'wpiai_sales_order_parameters' );
			$pRequest   = get_customer_param_record_x( $parameters );
			$xmlRequest = wpiai_get_order_XML( $order_id, 'Add' );
			$updated    = wpiai_get_infor_message_multipart_message( $url, $pRequest, $xmlRequest );
			if ( $updated ) {
				error_log( 'Order: ' . $order_id . ' Added' );
			}
		}
	}

}

/**
 *
 * Sales Order Updated - NB not implemented
 *
 */

function wpiai_order_updated( $order_id ) {
	$order = wc_get_order( $order_id );
	/* Insert your code */
	/*if(($order->get_status() != 'draft')||($order->get_status() != 'auto-draft')) {
		$url        = get_option( 'wpiai_sales_order_url' );
		$parameters = get_option('wpiai_sales_order_parameters');
		$pRequest = get_customer_param_record_x( $parameters );
		$xmlRequest = wpiai_get_order_XML($order_id,'Add');
		$updated    = wpiai_get_infor_message_multipart_message( $url, $pRequest, $xmlRequest );
		if($updated) error_log( 'Order: ' . $order_id . ' Updated with Status: '. $order->get_status());
	}*/

}

add_action( 'woocommerce_update_order', 'wpiai_order_updated' );

/**
 *
 * Display Order Meta on Admin Pages
 *
 */

add_action( 'woocommerce_admin_order_data_after_billing_address', 'wpiai_display_admin_order_meta', 10, 1 );

function wpiai_display_admin_order_meta( $order ) {
	echo '<h3>Meta Data Fields</h3>';
	echo '<table class="widefat fixed">';
	echo '<tr><th><strong>' . __( 'CSD_ID' ) . ':</th><td>' . get_post_meta( $order->get_id(), 'CSD_ID', true ) . '</td></tr>';
	echo '<tr><th><strong>' . __( 'CSD_Customer_ID' ) . ':</strong></th><td>' . get_post_meta( $order->get_id(), 'CSD_Customer_ID', true ) . '</td></tr>';
	echo '<tr><th><strong>' . __( 'suffix' ) . ':</strong></th><td>' . get_post_meta( $order->get_id(), 'suffix', true ) . '</td></tr>';
	echo '<tr><th><strong>' . __( 'po_reference' ) . ':</strong></th><td>' . get_post_meta( $order->get_id(), 'po_reference', true ) . '</td></tr>';
	echo '<tr><th><strong>' . __( 'order_type' ) . ':</strong></th><td>' . get_post_meta( $order->get_id(), 'order_type', true ) . '</td></tr>';
	echo '<tr><th><strong>' . __( 'order_notes' ) . ':</strong></th><td>' . get_post_meta( $order->get_id(), 'order_notes', true ) . '</td></tr>';
	echo '<tr><th><strong>' . __( 'order_status_code' ) . ':</strong></th><td>' . get_post_meta( $order->get_id(), 'order_status_code', true ) . '</td></tr>';
	echo '<tr><th><strong>' . __( 'date_picked' ) . ':</strong></th><td>' . get_post_meta( $order->get_id(), 'date_picked', true ) . '</td></tr>';
	echo '<tr><th><strong>' . __( 'date_shipped' ) . ':</strong></th><td>' . get_post_meta( $order->get_id(), 'date_shipped', true ) . '</td></tr>';
	echo '<tr><th><strong>' . __( 'date_invoiced' ) . ':</strong></th><td>' . get_post_meta( $order->get_id(), 'date_invoiced', true ) . '</td></tr>';
	echo '<tr><th><strong>' . __( 'date_canceled' ) . ':</strong></th><td>' . get_post_meta( $order->get_id(), 'date_canceled', true ) . '</td></tr>';
	echo '<tr><th><strong>' . __( 'approve_type' ) . ':</strong></th><td>' . get_post_meta( $order->get_id(), 'approve_type', true ) . '</td></tr>';
	echo '<tr><th><strong>' . __( 'subtotal_before_tax' ) . ':</strong></th><td>' . get_post_meta( $order->get_id(), 'subtotal_before_tax', true ) . '</td></tr>';
	echo '<tr><th><strong>' . __( 'tax_type' ) . ':</strong></th><td>' . get_post_meta( $order->get_id(), 'tax_type', true ) . '</td></tr>';
	echo '<tr><th><strong>' . __( 'disposition' ) . ':</strong></th><td>' . get_post_meta( $order->get_id(), 'disposition', true ) . '</td></tr>';
	echo '<tr><th><strong>' . __( 'ship_via' ) . ':</strong></th><td>' . get_post_meta( $order->get_id(), 'ship_via', true ) . '</td></tr>';
	echo '<tr><th><strong>' . __( 'shipping_instructions' ) . ':</strong></th><td>' . get_post_meta( $order->get_id(), 'shipping_instructions', true ) . '</td></tr>';
	echo '<tr><th><strong>' . __( 'tendered' ) . ':</strong></th><td>' . get_post_meta( $order->get_id(), 'tendered', true ) . '</td></tr>';
	echo '<tr><th><strong>' . __( 'payment_term' ) . ':</strong></th><td>' . get_post_meta( $order->get_id(), 'payment_term', true ) . '</td></tr>';
	echo '<tr><th><strong>' . __( 'paymentterm_duedate' ) . ':</strong></th><td>' . get_post_meta( $order->get_id(), 'paymentterm_duedate', true ) . '</td></tr>';
	echo '</table>';
	//echo '<p><strong>'.__('Random ID').':</strong> <br/>' . uniqidReal(8) . '</p>';
}

/**
 *
 * Custom Order Statuses
 *
 */

add_action( 'init', 'wpiai_register_quote_order_status' );
// Register new status
function wpiai_register_quote_order_status() {
	register_post_status( 'wc-open', array(
		'label'                     => 'Open',
		'public'                    => true,
		'exclude_from_search'       => false,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop( 'Open (%s)', 'Open (%s)' )
	) );
	register_post_status( 'wc-entered', array(
		'label'                     => 'Entered',
		'public'                    => true,
		'exclude_from_search'       => false,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop( 'Entered (%s)', 'Entered (%s)' )
	) );
	register_post_status( 'wc-ordered', array(
		'label'                     => 'Ordered',
		'public'                    => true,
		'exclude_from_search'       => false,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop( 'Ordered (%s)', 'Ordered (%s)' )
	) );
	register_post_status( 'wc-picked', array(
		'label'                     => 'Picked',
		'public'                    => true,
		'exclude_from_search'       => false,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop( 'Picked (%s)', 'Picked (%s)' )
	) );
	register_post_status( 'wc-shipped', array(
		'label'                     => 'Shipped',
		'public'                    => true,
		'exclude_from_search'       => false,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop( 'Shipped (%s)', 'Shipped (%s)' )
	) );
	register_post_status( 'wc-invoiced', array(
		'label'                     => 'Invoiced',
		'public'                    => true,
		'exclude_from_search'       => false,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop( 'Invoiced (%s)', 'Invoiced (%s)' )
	) );
	register_post_status( 'wc-paid', array(
		'label'                     => 'Paid',
		'public'                    => true,
		'exclude_from_search'       => false,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop( 'Paid (%s)', 'Paid (%s)' )
	) );
	register_post_status( 'wc-cancelled', array(
		'label'                     => 'Cancelled',
		'public'                    => true,
		'exclude_from_search'       => false,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop( 'Cancelled (%s)', 'Cancelled (%s)' )
	) );
}

// Add to list of WC Order statuses
function wpiai_add_quotes_to_order_statuses( $order_statuses ) {

	$new_order_statuses = array();

	// add new order status after processing
	foreach ( $order_statuses as $key => $status ) {

		$new_order_statuses[ $key ] = $status;

		if ( 'wc-processing' === $key ) {
			$new_order_statuses['wc-open']      = 'Open';
			$new_order_statuses['wc-entered']   = 'Entered';
			$new_order_statuses['wc-ordered']   = 'Ordered';
			$new_order_statuses['wc-picked']    = 'Picked';
			$new_order_statuses['wc-shipped']   = 'Shipped';
			$new_order_statuses['wc-invoiced']  = 'Invoiced';
			$new_order_statuses['wc-paid']      = 'Paid';
			$new_order_statuses['wc-cancelled'] = 'Cancelled';
		}
	}

	return $new_order_statuses;
}

add_filter( 'wc_order_statuses', 'wpiai_add_quotes_to_order_statuses' );


/**
 *
 * Helpers
 *
 */

function wpiai_get_order_XML( $order_id, $action ) {
	$order = wc_get_order( $order_id );
	if ( $order ) {
		$xmld = get_option( 'wpiai_sales_order_xml' );
		$xml  = simplexml_load_string( $xmld );

		$nowDT            = new DateTime();
		$CreationDateTime = $nowDT->format( DateTime::ATOM );
		$BODID            = uniqid();

		$CustomerPartyID = get_option( 'wpiai_guest_customer_number' );
		$user            = get_userdata( $order->get_customer_id() );
		$WooCustomerID   = '';
		if ( $user ) {
			$CustomerPartyID = get_user_meta( $order->get_customer_id(), 'CSD_ID', true );
			$WooCustomerID   = $order->get_customer_id();
		}


		//$SalesOrderHeader_DocumentID_ID = $CustomerPartyID;
		$AlternateDocumentID_ID_schemeAgencyID = $CustomerPartyID;
		$AlternateDocumentID_ID                = $order->get_meta( 'po_reference', true );
		/**
		 * Add in po_reference meta data field into front end;
		 */
		$Reference_NameValue_0                 = $order->get_meta( 'shipping_instructions', true );
		$Reference_NameValue_1                 = $order->get_total();


		$Document_DateTime = $order->get_date_created()->format( DateTime::ATOM );
		//$OrderTypeCode = '';
		$Status_Code                                = $order->get_status();
		$CustomerParty_PartyIDs_ID                  = $CustomerPartyID;
		$CustomerParty_Name                         = $order->get_billing_company();
		$CustomerParty_Communication_URI            = $order->get_billing_email();
		$ShipFromParty_Location_ID                  = get_option( 'wpiai_default_warehouse' );
		$ShipToParty_Location_Address_AddressLine_0 = $order->get_billing_address_1();
		$ShipToParty_Location_Address_AddressLine_1 = $order->get_billing_address_2();
		//$ShipToParty_Location_Address_AddressLine_3 = $order->get_billing_address_2();
		$ShipToParty_Location_Address_CityName   = $order->get_billing_city();
		$ShipToParty_Location_Address_PostalCode = $order->get_billing_postcode();
		$TransportationMethodCode = '08'; //07 for Click and Collect 04 for Carrier delivery
		if(get_post_meta($order_id,'_delivery_type',true)=='pickup') {
			$TransportationMethodCode = '07';
		}
		$contact_ordered = get_post_meta($order_id,'_contact_ordered',true);
        $contact_shipto = get_post_meta($order_id,'_contact_shipto',true);
        $shippingto_address = get_post_meta($order_id,'_shippingto_address',true);
        $contactCSDID = '';
        if(is_array($contact_shipto)) {
	        $first_name = $contact_shipto['first_name'];
	        $last_name = $contact_shipto['last_name'];
	        $phone = $contact_shipto['telephone'];
	        $contactCSDID = createCSDContact($CustomerPartyID,'',$first_name,$last_name,'',$phone,'','','','','');
        } else {
        	$contactCSDID = $contact_shipto;
        }
        $shipToCSDID = '';
        if(is_array($shippingto_address)) {
	        $shipping_first_name = $shippingto_address['shipping_first_name'];
	        $shipping_last_name = $shippingto_address['shipping_last_name'];
	        $shipping_address_1 = $shippingto_address['shipping_address_1'];
	        $shipping_address_2 = $shippingto_address['shipping_address_2'];
	        $shipping_postcode = $shippingto_address['shipping_postcode'];
	        $shipping_city = $shippingto_address['shipping_city'];
	        $shipping_company = $shippingto_address['shipping_company'];
	        $shipping_country = $shippingto_address['shipping_country'];
	        $shipToCSDID = createCSDShipTo(
	        	$CustomerPartyID,
		        $WooCustomerID,
		        $shipping_company,
		        $shipping_address_1,
		        $shipping_address_2,
		        $shipping_country,
		        $shipping_city,
		        $shipping_postcode
	        );
        } else {
	        $shipToCSDID = $shippingto_address;
        }
		$shipping_total = $order->get_shipping_total();
		$shipping_tax = $order->get_shipping_tax();
		$payment_method = $order->get_payment_method();
		$shipping_method = $order->get_shipping_method();
		/**
		 * This is how the order is shipped need to check the:
		 * shipping_lines
		 *  -> method_id
		 *  -> method_title
		 *  -> total
		 *
		 *  Need to create a line as an order line to the order for shipping
		 */
		//$RequiredDeliveryDateTime = '';
		$UserArea_Property_3_NameValue = $WooCustomerID;

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

		if ( $xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->AlternateDocumentID[0]->ID[0] ) {
			$xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->AlternateDocumentID[0]->ID[0]->attributes()['schemeAgencyID'] = $AlternateDocumentID_ID_schemeAgencyID;
			$xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->AlternateDocumentID[0]->ID[0]                                 = $AlternateDocumentID_ID;
		}

		if ( $xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->Reference[0]->NameValue[0] ) {
			$xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->Reference[0]->NameValue[0] = $Reference_NameValue_0;
		}

		if ( $xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->Reference[0]->NameValue[1] ) {
			$xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->Reference[0]->NameValue[1] = $Reference_NameValue_1;
		}

		if ( $xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->DocumentDateTime[0] ) {
			$xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->DocumentDateTime[0] = $Document_DateTime;
		}

		if ( $xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->Status[0]->Code[0] ) {
			$xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->Status[0]->Code[0] = 'Open';
		}

		if ( $xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->CustomerParty[0]->PartyIDs[0]->ID[0] ) {
			$xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->CustomerParty[0]->PartyIDs[0]->ID[0] = $CustomerParty_PartyIDs_ID;
		}

		if ( $xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->CustomerParty[0]->Name[0] ) {
			$xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->CustomerParty[0]->Name[0] = $CustomerParty_Name;
		}

		if ( $xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->CustomerParty[0]->Communication[0]->URI[0] ) {
			$xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->CustomerParty[0]->Communication[0]->URI[0] = $CustomerParty_Communication_URI;
		}

		if ( $xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->ShipFromParty[0]->Location[0]->ID[0] ) {
			$xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->ShipFromParty[0]->Location[0]->ID[0] = $ShipFromParty_Location_ID;
		}

		/*if ( $xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->ShipToParty[0]->Location[0]->Address[0]->AddressLine[0] ) {
			$xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->ShipToParty[0]->Location[0]->Address[0]->AddressLine[0] = $ShipToParty_Location_Address_AddressLine_0;
		}*/

		/*if ( $xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->ShipToParty[0]->Location[0]->Address[0]->AddressLine[1] ) {
			$xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->ShipToParty[0]->Location[0]->Address[0]->AddressLine[1] = $ShipToParty_Location_Address_AddressLine_1;
		}

		if ( $xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->ShipToParty[0]->Location[0]->Address[0]->CityName[0] ) {
			$xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->ShipToParty[0]->Location[0]->Address[0]->CityName[0] = $ShipToParty_Location_Address_CityName;
		}

		if ( $xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->ShipToParty[0]->Location[0]->Address[0]->PostalCode[0] ) {
			$xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->ShipToParty[0]->Location[0]->Address[0]->PostalCode[0] = $ShipToParty_Location_Address_PostalCode;
		}*/

		if ( $xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->ShipToParty[0]->PartyIDs[0]->ID[0] ) {
			$xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->ShipToParty[0]->PartyIDs[0]->ID[0] = $shipToCSDID;
			//error_log('$shipToCSDID = '.$shipToCSDID);
			//error_log('XML = '.$xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->ShipToParty[0]->PartyIDs[0]->ID[0]);
		}

		if ( $xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->TransportationMethodCode [0] ) {
			$xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->TransportationMethodCode [0] = $TransportationMethodCode;
		}

		if ( $xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->UserArea[0]->Property[0]->NameValue[0] ) {
			$xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->UserArea[0]->Property[0]->NameValue[0] = $order_id;
		}

		if($contact_ordered) {
			if ( $xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->UserArea[0]->Property[1]->NameValue[0] ) {
				$xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->UserArea[0]->Property[1]->NameValue[0] = $contactCSDID;
			}
		}

		if($contact_shipto) {
			if ( $xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->UserArea[0]->Property[1]->NameValue[0] ) {
				$xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->UserArea[0]->Property[1]->NameValue[0] = $contactCSDID;
			}
		}

		if ( $xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->UserArea[0]->Property[2]->NameValue[0] ) {
			$xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->UserArea[0]->Property[2]->NameValue[0] = $CustomerPartyID;
		}

		if ( $xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->UserArea[0]->Property[3]->NameValue[0] ) {
			$xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->UserArea[0]->Property[3]->NameValue[0] = $shipping_total - $shipping_tax;
		}

		if ( $xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->UserArea[0]->Property[4]->NameValue[0] ) {
			if($payment_method=='stripe') {
				$xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->UserArea[0]->Property[4]->NameValue[0] = 'Paid';
			}
			if($payment_method=='cod') {
				$xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderHeader[0]->UserArea[0]->Property[4]->NameValue[0] = 'Account';
			}

		}

		// Get and Loop Over Order Items
		$SalesOrderLines = '';
		if ( $order->get_items() ) {
			$SalesOrderLineNum = 0;
			foreach ( $order->get_items() as $item_id => $item ) {
				//$product_id       = $item->get_product_id();
				$product = $item->get_product();
				if($product) {
					$sku = $product->get_sku();
					//$name             = $item->get_name();
					$quantity = $item->get_quantity();
					$itemID   = $item->get_id();
					/*$total            = $item->get_total();
					$line_number      = $item->get_meta( 'line_number', true );
					$line_status      = $item->get_meta( 'line_status', true );
					$item_notes       = $item->get_meta( 'item_notes', true );
					$back_order       = $item->get_meta( 'back_order', true );
					$price_paid       = $item->get_meta( 'price_paid', true );
					$uom              = $item->get_meta( 'uom', true );
					$quantity_shipped = $item->get_meta( 'quantity_shipped', true );*/
					$xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderLine[ $SalesOrderLineNum ]->Item[0]->ItemID[0]->ID[0]              = $sku;
					$xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderLine[ $SalesOrderLineNum ]->Quantity[0]                            = $quantity;
					$xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderLine[ $SalesOrderLineNum ]->UserArea[0]->Property[0]->NameValue[0] = $itemID;
					$xml->xpath( '//x:DataArea' )[0]->SalesOrder[0]->SalesOrderLine[ $SalesOrderLineNum ]->UserArea[0]->Property[0]->NameValue[0]->addAttribute('name','SXe_user2');
					$SalesOrderLineNum ++;
				}
			}
		}

		$xmld          = $xml->asXML();

		return $xmld;


	} else {
		return false;
	}
}





