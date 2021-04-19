<?php
function get_customer_organization( $customer_id ) {
	$user = get_user_by( 'ID', $customer_id );
	if ( $user ) {
		if ( metadata_exists( 'user', $customer_id, 'CSD_ID' ) ) {
			return get_user_meta( $customer_id, 'CSD_ID', true );
		}
	}

	return false;
}

function get_organization_shipping_details( $organization_id ) {
	$user = get_users(
		array(
			'meta_key'    => 'CSD_ID',
			'meta_value'  => $organization_id,
			'number'      => 1,
			'count_total' => false
		)
	);
	if ( $user ) {
		$user_id = $user[0]->ID;
		if ( metadata_exists( 'user', $user_id, 'wpiai_delivery_addresses' ) ) {
			$shipping_details = get_user_meta( $user_id, 'wpiai_delivery_addresses', true );
			if ( is_array( $shipping_details ) ) {
				$ret_shipping_details = array();
				foreach ( $shipping_details as $shipping_detail ) {
					$ret_shipping_detail                   = array();
					$ret_shipping_detail['first_name']     = $shipping_detail['delivery-first-name'];
					$ret_shipping_detail['last_name']      = $shipping_detail['delivery-last-name'];
					$ret_shipping_detail['company_name']   = $shipping_detail['delivery-company-name'];
					$ret_shipping_detail['country']        = $shipping_detail['delivery-country'];
					$ret_shipping_detail['address_line_1'] = $shipping_detail['delivery-street-address-1'];
					$ret_shipping_detail['address_line_2'] = $shipping_detail['delivery-street-address-2'];
					$ret_shipping_detail['address_line_3'] = $shipping_detail['delivery-street-address-3'];
					$ret_shipping_detail['town_city']      = $shipping_detail['delivery-town-city'];
					$ret_shipping_detail['county']         = $shipping_detail['delivery-county'];
					$ret_shipping_detail['postcode']       = $shipping_detail['delivery-postcode'];
					$ret_shipping_detail['phone']          = $shipping_detail['delivery-phone'];
					$ret_shipping_detail['email']          = $shipping_detail['delivery-email'];
					$ret_shipping_details[]                = $ret_shipping_detail;
				}
				if ( ! empty( $ret_shipping_details ) ) {
					return $ret_shipping_details;
				}
			}
		}
	}

	return false;
}

function get_organization_contact_details( $organization_id ) {
	$user = get_users(
		array(
			'meta_key'    => 'CSD_ID',
			'meta_value'  => $organization_id,
			'number'      => 1,
			'count_total' => false
		)
	);
	if ( $user ) {
		$user_id = $user[0]->ID;
		if ( metadata_exists( 'user', $user_id, 'wpiai_contacts' ) ) {
			$contact_details = get_user_meta( $user_id, 'wpiai_contacts', true );
			if ( is_array( $contact_details ) ) {
				$ret_contact_details = array();
				foreach ( $contact_details as $contact_detail ) {
					$ret_contact_detail                       = array();
					$ret_contact_detail['CSD_ID']             = $contact_detail['contact_CSD_ID'];
					$ret_contact_detail['first_name']         = $contact_detail['contact_first_name'];
					$ret_contact_detail['last_name']          = $contact_detail['contact_last_name'];
					$ret_contact_detail['job_title']          = $contact_detail['contact_job_title'];
					$ret_contact_detail['country']            = $contact_detail['delivery-country'];
					$ret_contact_detail['address_line_1']     = $contact_detail['contact_addr_1'];
					$ret_contact_detail['address_line_2']     = $contact_detail['contact_addr_2'];
					$ret_contact_detail['address_line_3']     = $contact_detail['contact_addr_3'];
					$ret_contact_detail['address_line_4']     = $contact_detail['contact_addr_4'];
					$ret_contact_detail['town_city']          = $contact_detail['delivery-town-city'];
					$ret_contact_detail['postcode']           = $contact_detail['contact_postcode'];
					$ret_contact_detail['phone']              = $contact_detail['contact_phone'];
					$ret_contact_detail['mobile_phone']       = $contact_detail['contact_mobile_phone'];
					$ret_contact_detail['email']              = $contact_detail['contact_email'];
					$ret_contact_detail['marketing_by_phone'] = $contact_detail['contact_phone_channel'];
					$ret_contact_detail['marketing_by_fax']   = $contact_detail['contact_fax_channel'];
					$ret_contact_detail['marketing_by_mail']  = $contact_detail['contact_mail_channel'];
					$ret_contact_detail['marketing_by_email'] = $contact_detail['contact_email_channel'];
					$ret_contact_details[]                    = $ret_contact_detail;
				}
				if ( ! empty( $ret_contact_details ) ) {
					return $ret_contact_details;
				}
			}
		}
	}

	return false;
}

function set_organization_contact_details( $organization_id, $contact_id, $contact_details = array() ) {
	$user = get_users(
		array(
			'meta_key'    => 'CSD_ID',
			'meta_value'  => $organization_id,
			'number'      => 1,
			'count_total' => false
		)
	);
	if ( $user ) {
		$user_id = $user[0]->ID;
		$roles   = $user[0]->roles;
		if ( in_array( 'customer', $roles ) ) {
			$old_contact_details = get_user_meta( $user_id, 'wpiai_contacts', true );
			$new_contact_details = array();
			if ( is_array( $old_contact_details ) ) {
				if($contact_id == '0') {
					//Create New Contact

					return get_organization_contact_details($organization_id);
				}
				$updated = false;
				foreach ($old_contact_details as $old_contact_detail) {
					if($old_contact_detail['contact_CSD_ID']==$contact_id) {
						$array_keys = array_keys($old_contact_detail);
						$new_contact_detail = array();
						foreach ($array_keys as $array_key) {
							if(array_key_exists($array_key,$contact_details)) {
								$new_contact_detail[$array_key] = $contact_details[$array_key];

							} else {

							}

						}
					} else {
						$new_contact_details[] = $old_contact_detail;
					}
				}
				if($updated) {
					update_user_meta($user_id,'wpiai_contacts',$new_contact_details);
					return get_organization_contact_details($organization_id);
				}
			} else {
				$wpiai_contacts = array();
				$wpiai_contacts[] = $contact_details;
				if(metadata_exists('user',$user_id,'wpiai_contacts')) {
					update_user_meta($user_id,'wpiai_contacts',$wpiai_contacts);
					return get_organization_contact_details($organization_id);
				} else {
					add_user_meta($user_id,'wpiai_contacts',$wpiai_contacts);
					return get_organization_contact_details($organization_id);
				}

			}
			$ret_contact_details = array();
			foreach ( $contact_details as $contact_detail ) {
				$ret_contact_detail                       = array();
				$ret_contact_detail['CSD_ID']             = $contact_detail['contact_CSD_ID'];
				$ret_contact_detail['first_name']         = $contact_detail['contact_first_name'];
				$ret_contact_detail['last_name']          = $contact_detail['contact_last_name'];
				$ret_contact_detail['job_title']          = $contact_detail['contact_job_title'];
				$ret_contact_detail['country']            = $contact_detail['delivery-country'];
				$ret_contact_detail['address_line_1']     = $contact_detail['contact_addr_1'];
				$ret_contact_detail['address_line_2']     = $contact_detail['contact_addr_2'];
				$ret_contact_detail['address_line_3']     = $contact_detail['contact_addr_3'];
				$ret_contact_detail['address_line_4']     = $contact_detail['contact_addr_4'];
				$ret_contact_detail['town_city']          = $contact_detail['delivery-town-city'];
				$ret_contact_detail['postcode']           = $contact_detail['contact_postcode'];
				$ret_contact_detail['phone']              = $contact_detail['contact_phone'];
				$ret_contact_detail['mobile_phone']       = $contact_detail['contact_mobile_phone'];
				$ret_contact_detail['email']              = $contact_detail['contact_email'];
				$ret_contact_detail['marketing_by_phone'] = $contact_detail['contact_phone_channel'];
				$ret_contact_detail['marketing_by_fax']   = $contact_detail['contact_fax_channel'];
				$ret_contact_detail['marketing_by_mail']  = $contact_detail['contact_mail_channel'];
				$ret_contact_detail['marketing_by_email'] = $contact_detail['contact_email_channel'];
				$ret_contact_details[]                    = $ret_contact_detail;
			}
			if ( ! empty( $ret_contact_details ) ) {
				return $ret_contact_details;
			}
		}
	}

	return false;
}

function get_customer_details( $customer_id ) {
	$user = get_userdata( $customer_id );
	if ( $user ) {
		$roles = $user->roles;
		if ( in_array( 'customer', $roles ) ) {
			$customer                           = new WC_Customer( $customer_id );
			$customer_details                   = array();
			$customer_details['first_name']     = $customer->get_billing_first_name();
			$customer_details['last_name']      = $customer->get_billing_last_name();
			$customer_details['company']        = $customer->get_billing_company();
			$customer_details['address_line_1'] = $customer->get_billing_address_1();
			$customer_details['address_line_2'] = $customer->get_billing_address_2();
			$customer_details['town_city']      = $customer->get_billing_city();
			$customer_details['county']         = $customer->get_billing_state();
			$customer_details['postcode']       = $customer->get_billing_postcode();
			$customer_details['country']        = $customer->get_billing_country();
			$customer_details['email']          = $customer->get_billing_email();
			$customer_details['phone']          = $customer->get_billing_phone();

			return $customer_details;
		}
	}

	return false;
}

function set_customer_details( $customer_id, $customer_details = array() ) {
	$user_details = get_customer_details( $customer_id );
	if ( $user_details ) {
		$customer = new WC_Customer( $customer_id );
		$updated  = false;

		if ( array_key_exists( 'first_name', $customer_details ) ) {
			if ( $user_details['first_name'] <> $customer_details['first_name'] ) {
				$customer->set_billing_first_name( $customer_details['first_name'] );
				$updated = true;
			}
		}

		if ( array_key_exists( 'last_name', $customer_details ) ) {
			if ( $user_details['last_name'] <> $customer_details['last_name'] ) {
				$customer->set_billing_last_name( $customer_details['last_name'] );
				$updated = true;
			}
		}

		if ( array_key_exists( 'company', $customer_details ) ) {
			if ( $user_details['company'] <> $customer_details['company'] ) {
				$customer->set_billing_company( $customer_details['company'] );
				$updated = true;
			}
		}

		if ( array_key_exists( 'address_line_1', $customer_details ) ) {
			if ( $user_details['address_line_1'] <> $customer_details['address_line_1'] ) {
				$customer->set_billing_address_1( $customer_details['address_line_1'] );
				$updated = true;
			}
		}

		if ( array_key_exists( 'address_line_2', $customer_details ) ) {
			if ( $user_details['address_line_2'] <> $customer_details['address_line_2'] ) {
				$customer->set_billing_address_2( $customer_details['address_line_2'] );
				$updated = true;
			}
		}

		if ( array_key_exists( 'town_city', $customer_details ) ) {
			if ( $user_details['town_city'] <> $customer_details['town_city'] ) {
				$customer->set_billing_city( $customer_details['town_city'] );
				$updated = true;
			}
		}

		if ( array_key_exists( 'county', $customer_details ) ) {
			if ( $user_details['county'] <> $customer_details['county'] ) {
				$customer->set_billing_state( $customer_details['county'] );
				$updated = true;
			}
		}

		if ( array_key_exists( 'postcode', $customer_details ) ) {
			if ( $user_details['postcode'] <> $customer_details['first_name'] ) {
				$customer->set_billing_postcode( $customer_details['postcode'] );
				$updated = true;
			}
		}

		if ( array_key_exists( 'country', $customer_details ) ) {
			if ( $user_details['country'] <> $customer_details['country'] ) {
				$customer->set_billing_country( $customer_details['country'] );
				$updated = true;
			}
		}

		if ( array_key_exists( 'email', $customer_details ) ) {
			if ( $user_details['email'] <> $customer_details['email'] ) {
				$customer->set_billing_email( $customer_details['email'] );
				$updated = true;
			}
		}

		if ( array_key_exists( 'phone', $customer_details ) ) {
			if ( $user_details['phone'] <> $customer_details['phone'] ) {
				$customer->set_billing_phone( $customer_details['phone'] );
				$updated = true;
			}
		}

		if ( $updated ) {
			$customer->save();

			return get_customer_details( $customer_id );
		}
	}

	return false;
}