<?php
function get_customer_organization($customer_id)
{
    $user = get_user_by('ID', $customer_id);
    if ($user) {
        if (metadata_exists('user', $customer_id, 'CSD_ID')) {
            return get_user_meta($customer_id, 'CSD_ID', true);
        }
    }

    return false;
}

function get_csd_order_id($order_id) {
	$order = wc_get_order( $order_id );
	if($order) {
		$CSD_ID = get_post_meta($order_id,'CSD_ID',true);
		if($CSD_ID) {
			return $CSD_ID;
		}
	}
	return false;
}

function get_organization_shipping_details($organization_id)
{
    $user = get_users(
        array(
            'meta_key' => 'CSD_ID',
            'meta_value' => $organization_id,
            'number' => 1,
            'count_total' => false
        )
    );
    if ($user) {
        $user_id = $user[0]->ID;
        if (metadata_exists('user', $user_id, 'wpiai_delivery_addresses')) {
            $shipping_details = get_user_meta($user_id, 'wpiai_delivery_addresses', true);
            if (is_array($shipping_details)) {
                $ret_shipping_details = array();
                foreach ($shipping_details as $shipping_detail) {
                    if (is_array($shipping_detail)) {
                        $ret_shipping_detail = array();
                        if (array_key_exists('delivery-CSD-ID', $shipping_detail)) {
                            $ret_shipping_detail['CSD_ID'] = $shipping_detail['delivery-CSD-ID'];
                        }
                        if (array_key_exists('delivery-first-name', $shipping_detail)) {
                            $ret_shipping_detail['first_name'] = $shipping_detail['delivery-first-name'];
                        }
                        if (array_key_exists('delivery-last-name', $shipping_detail)) {
                            $ret_shipping_detail['last_name'] = $shipping_detail['delivery-last-name'];
                        }
                        if (array_key_exists('delivery-company-name', $shipping_detail)) {
                            $ret_shipping_detail['company_name'] = $shipping_detail['delivery-company-name'];
                        }
                        if (array_key_exists('delivery-country', $shipping_detail)) {
                            $ret_shipping_detail['country'] = $shipping_detail['delivery-country'];
                        }
                        if (array_key_exists('delivery-street-address-1', $shipping_detail)) {
                            $ret_shipping_detail['address_line_1'] = $shipping_detail['delivery-street-address-1'];
                        }
                        if (array_key_exists('delivery-street-address-2', $shipping_detail)) {
                            $ret_shipping_detail['address_line_2'] = $shipping_detail['delivery-street-address-2'];
                        }
                        if (array_key_exists('delivery-street-address-3', $shipping_detail)) {
                            $ret_shipping_detail['address_line_3'] = $shipping_detail['delivery-street-address-3'];
                        }
                        if (array_key_exists('delivery-town-city', $shipping_detail)) {
                            $ret_shipping_detail['town_city'] = $shipping_detail['delivery-town-city'];
                        }
                        if (array_key_exists('delivery-county', $shipping_detail)) {
                            $ret_shipping_detail['county'] = $shipping_detail['delivery-county'];
                        }
                        if (array_key_exists('delivery-postcode', $shipping_detail)) {
                            $ret_shipping_detail['postcode'] = $shipping_detail['delivery-postcode'];
                        }
                        if (array_key_exists('delivery-phone', $shipping_detail)) {
                            $ret_shipping_detail['phone'] = $shipping_detail['delivery-phone'];
                        }
                        if (array_key_exists('delivery-email', $shipping_detail)) {
                            $ret_shipping_detail['email'] = $shipping_detail['delivery-email'];
                        }
                        $ret_shipping_details[] = $ret_shipping_detail;
                    } else {
                        error_log(print_r($shipping_detail));
                    }

                }
                if (!empty($ret_shipping_details)) {
                    return $ret_shipping_details;
                }
            }
        }
    }

    return false;
}

function wpiai_convert_shipping_field_names($fname)
{
    if ($fname == 'delivery_UNIQUE_ID') {
        return 'delivery_UNIQUE_ID';
    }

    if ($fname == 'CSD_ID') {
        return 'delivery-CSD-ID';
    }
    if ($fname == 'first_name') {
        return 'delivery-first-name';
    }
    if ($fname == 'last_name') {
        return 'delivery-last-name';
    }
    if ($fname == 'company_name') {
        return 'delivery-company-name';
    }
    if ($fname == 'country') {
        return 'delivery-country';
    }
    if ($fname == 'address_line_1') {
        return 'delivery-street-address-1';
    }
    if ($fname == 'address_line_2') {
        return 'delivery-street-address-2';
    }
    if ($fname == 'address_line_3') {
        return 'delivery-street-address-3';
    }
    if ($fname == 'town_city') {
        return 'delivery-town-city';
    }
    if ($fname == 'county') {
        return 'delivery-county';
    }
    if ($fname == 'postcode') {
        return 'delivery-postcode';
    }
    if ($fname == 'phone') {
        return 'delivery-phone';
    }
    if ($fname == 'email') {
        return 'delivery-email';
    }

    if ($fname == 'delivery-CSD-ID') {
        return 'CSD_ID';
    }
    if ($fname == 'delivery-first-name') {
        return 'first_name';
    }
    if ($fname == 'delivery-last-name') {
        return 'last_name';
    }
    if ($fname == 'delivery-company-name') {
        return 'company_name';
    }
    if ($fname == 'delivery-country') {
        return 'country';
    }
    if ($fname == 'delivery-street-address-1') {
        return 'address_line_1';
    }
    if ($fname == 'delivery-street-address-2') {
        return 'address_line_2';
    }
    if ($fname == 'delivery-street-address-3') {
        return 'address_line_3';
    }
    if ($fname == 'delivery-town-city') {
        return 'town_city';
    }
    if ($fname == 'delivery-county') {
        return 'county';
    }
    if ($fname == 'delivery-postcode') {
        return 'postcode';
    }
    if ($fname == 'delivery-phone') {
        return 'phone';
    }
    if ($fname == 'delivery-email') {
        return 'email';
    }

    return false;
}

function update_user_to_csd($user_id) {
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

function set_organization_shipping_details($organization_id, $shipping_id, $shipping_details = array())
{
    $user = get_users(
        array(
            'meta_key' => 'CSD_ID',
            'meta_value' => $organization_id,
            'number' => 1,
            'count_total' => false
        )
    );
    if ($user) {
        $user_id = $user[0]->ID;
        $roles = $user[0]->roles;
        if (in_array('customer', $roles)) {
            $old_shipping_details = get_user_meta($user_id, 'wpiai_last_delivery_addresses', true);
            $current_shipTos = get_user_meta($user_id,'wpiai_delivery_addresses',true);
            $new_shipping_details = array();
            if(!is_array($old_shipping_details)) {
                $old_shipping_details = array();
            }
            $updated = false;
            if ($shipping_id == '0') {
                //Create New ShipTo
                $new_shipping = array();

                if (array_key_exists('first_name', $shipping_details)) {
                    $new_shipping[wpiai_convert_shipping_field_names('first_name')] = $shipping_details['first_name'];
                    $updated = true;
                }

                if (array_key_exists('last_name', $shipping_details)) {
                    $new_shipping[wpiai_convert_shipping_field_names('last_name')] = $shipping_details['last_name'];
                    $updated = true;
                }

                if (array_key_exists('company_name', $shipping_details)) {
                    $new_shipping[wpiai_convert_shipping_field_names('company_name')] = $shipping_details['company_name'];
                    $updated = true;
                } else {
                    $new_shipping[wpiai_convert_shipping_field_names('company_name')] = '';
                }

                if (array_key_exists('country', $shipping_details)) {
                    $new_shipping[wpiai_convert_shipping_field_names('country')] = $shipping_details['country'];
                    $updated = true;
                } else {
                    $new_shipping[wpiai_convert_shipping_field_names('country')] = 'GB';
                }

                if (array_key_exists('address_line_1', $shipping_details)) {
                    $new_shipping[wpiai_convert_shipping_field_names('address_line_1')] = $shipping_details['address_line_1'];
                    $updated = true;
                } else {
                    $new_shipping[wpiai_convert_shipping_field_names('address_line_1')] = '';
                }

                if (array_key_exists('address_line_2', $shipping_details)) {
                    $new_shipping[wpiai_convert_shipping_field_names('address_line_2')] = $shipping_details['address_line_2'];
                    $updated = true;
                } else {
                    $new_shipping[wpiai_convert_shipping_field_names('address_line_2')] = '';
                }

                if (array_key_exists('address_line_3', $shipping_details)) {
                    $new_shipping[wpiai_convert_shipping_field_names('address_line_3')] = $shipping_details['address_line_3'];
                    $updated = true;
                } else {
                    $new_shipping[wpiai_convert_shipping_field_names('address_line_3')] = '';
                }

                if (array_key_exists('town_city', $shipping_details)) {
                    $new_shipping[wpiai_convert_shipping_field_names('town_city')] = $shipping_details['town_city'];
                    $updated = true;
                } else {
                    $new_shipping[wpiai_convert_shipping_field_names('town_city')] = '';
                }

                if (array_key_exists('county', $shipping_details)) {
                    $new_shipping[wpiai_convert_shipping_field_names('county')] = $shipping_details['county'];
                    $updated = true;
                }

                if (array_key_exists('postcode', $shipping_details)) {
                    $new_shipping[wpiai_convert_shipping_field_names('postcode')] = $shipping_details['postcode'];
                    $updated = true;
                } else {
                    $new_shipping[wpiai_convert_shipping_field_names('postcode')] = '';
                }

                if (array_key_exists('phone', $shipping_details)) {
                    $new_shipping[wpiai_convert_shipping_field_names('phone')] = $shipping_details['phone'];
                    $updated = true;
                } else {
                    $new_shipping[wpiai_convert_shipping_field_names('phone')] = '';
                }

                if (array_key_exists('email', $shipping_details)) {
                    $new_shipping[wpiai_convert_shipping_field_names('email')] = $shipping_details['email'];
                    $updated = true;
                }


                if ($updated) {
                    //$new_contact['contact_CSD_ID'] = '';
                    //$new_contact['contact_CONTACT_ID'] = uniqid();
                    //$new_contact['CREATED_BY'] = 'WOO';
                    //$new_shipping['delivery_UNIQUE_ID'] = uniqid();
                    $current_shipTos[] = $new_shipping;
                    update_user_meta($user_id, 'wpiai_delivery_addresses', $current_shipTos);

                    $users_updated = get_option('wpiai_users_updated');
                    if (!is_array($users_updated)) {
                        $users_updated = array();
                    }
                    $users_updated[] = $user_id;
                    if (!update_option('wpiai_users_updated', $users_updated)) {
                        error_log('UserID not saved: ' . $user_id);
                    } else {
                        error_log($user_id . ' added to the meta update queue');
                    }

                }

                error_log('New ShipTo Added');

                return get_organization_shipping_details($organization_id);
            }

            //update a shipTo



            if(is_array($current_shipTos)) {
                $new_shipTos = array();
                foreach ($current_shipTos as $current_shipTo) {
                    if($shipping_id==$current_shipTo['delivery-CSD-ID']) {
                        $array_keys = array_keys($shipping_details);
                        $new_shipTo = array();
                        foreach ($array_keys as $array_key) {
                            $real_key = wpiai_convert_shipping_field_names($array_key);
                            //error_log($array_key.' = '.$real_key);
                            if($real_key) {
                                if(array_key_exists($real_key,$current_shipTo)) {
                                    $new_VAL = $shipping_details[$array_key];
                                    $old_VAL = $current_shipTo[$real_key];
                                    //error_log('New: '.$new_VAL.' | Old: '.$old_VAL);
                                    if($new_VAL == $old_VAL) {
                                        $new_shipTo[$real_key] = $old_VAL;
                                        //error_log('No Change: $new_shipTo['.$real_key.'] = '.$old_VAL);
                                    } else {
                                        $new_shipTo[$real_key] = $new_VAL;
                                        //error_log('Updated: $new_shipTo['.$real_key.'] = '.$new_VAL);
                                        $updated = true;
                                    }
                                } else {
                                    $new_shipTo[$real_key] = $shipping_details[$array_key];
                                    $updated = true;
                                }
                            }
                            $current_array_keys = array_keys($current_shipTo);
                            foreach ($current_array_keys as $current_array_key) {
                                if(!array_key_exists($current_array_key,$new_shipTo)) {
                                    $new_shipTo[$current_array_key] = $current_shipTo[$current_array_key];
                                }
                            }
                        }
	                    $new_shipTos[] = $new_shipTo;
                    } else {
                        $new_shipTos[] = $current_shipTo;
                    }
                }
                if($updated) {
                    //error_log('Updated');
                    //error_log(print_r($new_shipTos,true));
                    update_user_meta($user_id, 'wpiai_delivery_addresses', $new_shipTos);
                    $users_updated = get_option('wpiai_users_updated');
                    if (!is_array($users_updated)) {
                        $users_updated = array();
                    }
                    $users_updated[] = $user_id;
                    if (!update_option('wpiai_users_updated', $users_updated)) {
                        error_log('UserID not saved: ' . $user_id);
                    } else {
                        error_log($user_id . ' added to the meta update queue');
                    }
                    error_log('set_organization_shipping_details stage 2');

                    return get_organization_shipping_details($organization_id);
                } else {
                    error_log('Nothing to update in ShipTos');
                    return false;
                }
            } else {
                error_log('Cannot find any Shiptos');
                return false;
            }
        } else {
            error_log('not a customer');
        }
    } else {
        error_log('User Not Found');
    }
    return false;
}

function get_organization_contact_details($organization_id)
{
    $user = get_users(
        array(
            'meta_key' => 'CSD_ID',
            'meta_value' => $organization_id,
            'number' => 1,
            'count_total' => false
        )
    );
    if ($user) {
        $user_id = $user[0]->ID;
        if (metadata_exists('user', $user_id, 'wpiai_contacts')) {
            $contact_details = get_user_meta($user_id, 'wpiai_contacts', true);
            if (is_array($contact_details)) {
                $ret_contact_details = array();
                foreach ($contact_details as $contact_detail) {
                    $ret_contact_detail = array();
                    $ret_contact_detail['CSD_ID'] = $contact_detail['contact_CSD_ID'];
                    $ret_contact_detail['first_name'] = $contact_detail['contact_first_name'];
                    $ret_contact_detail['last_name'] = $contact_detail['contact_last_name'];
                    $ret_contact_detail['job_title'] = $contact_detail['contact_job_title'];
                    $ret_contact_detail['address_line_1'] = $contact_detail['contact_addr_1'];
                    $ret_contact_detail['address_line_2'] = $contact_detail['contact_addr_2'];
                    $ret_contact_detail['address_line_3'] = $contact_detail['contact_addr_3'];
                    $ret_contact_detail['address_line_4'] = $contact_detail['contact_addr_4'];
                    $ret_contact_detail['postcode'] = $contact_detail['contact_postcode'];
                    $ret_contact_detail['phone'] = $contact_detail['contact_phone'];
                    $ret_contact_detail['mobile_phone'] = $contact_detail['contact_mobile_phone'];
                    $ret_contact_detail['email'] = $contact_detail['contact_email'];
                    $ret_contact_detail['marketing_by_phone'] = $contact_detail['contact_phone_channel'];
                    $ret_contact_detail['marketing_by_fax'] = $contact_detail['contact_fax_channel'];
                    $ret_contact_detail['marketing_by_mail'] = $contact_detail['contact_mail_channel'];
                    $ret_contact_detail['marketing_by_email'] = $contact_detail['contact_email_channel'];
                    $ret_contact_details[] = $ret_contact_detail;
                }
                if (!empty($ret_contact_details)) {
                    return $ret_contact_details;
                }
            }
        }
    }

    return false;
}


function wpiai_convert_field_names($fname)
{
    if ($fname == 'contact_first_name') {
        return 'first_name';
    }
    if ($fname == 'contact_last_name') {
        return 'last_name';
    }
    if ($fname == 'contact_job_title') {
        return 'job_title';
    }
    if ($fname == 'contact_addr_1') {
        return 'address_line_1';
    }
    if ($fname == 'contact_addr_2') {
        return 'address_line_2';
    }
    if ($fname == 'contact_addr_3') {
        return 'address_line_3';
    }
    if ($fname == 'contact_addr_4') {
        return 'address_line_4';
    }
    if ($fname == 'contact_postcode') {
        return 'postcode';
    }
    if ($fname == 'contact_phone') {
        return 'phone';
    }
    if ($fname == 'contact_mobile_phone') {
        return 'mobile_phone';
    }
    if ($fname == 'contact_email') {
        return 'email';
    }
    if ($fname == 'contact_phone_channel') {
        return 'marketing_by_phone';
    }
    if ($fname == 'contact_fax_channel') {
        return 'marketing_by_fax';
    }
    if ($fname == 'contact_mail_channel') {
        return 'marketing_by_mail';
    }
    if ($fname == 'contact_email_channel') {
        return 'marketing_by_email';
    }

    if ($fname == 'first_name') {
        return 'contact_first_name';
    }
    if ($fname == 'last_name') {
        return 'contact_last_name';
    }
    if ($fname == 'job_title') {
        return 'contact_job_title';
    }
    if ($fname == 'address_line_1') {
        return 'contact_addr_1';
    }
    if ($fname == 'address_line_2') {
        return 'contact_addr_2';
    }
    if ($fname == 'address_line_3') {
        return 'contact_addr_3';
    }
    if ($fname == 'address_line_4') {
        return 'contact_addr_4';
    }
    if ($fname == 'postcode') {
        return 'contact_postcode';
    }
    if ($fname == 'phone') {
        return 'contact_phone';
    }
    if ($fname == 'mobile_phone') {
        return 'contact_mobile_phone';
    }
    if ($fname == 'email') {
        return 'contact_email';
    }
    if ($fname == 'marketing_by_phone') {
        return 'contact_phone_channel';
    }
    if ($fname == 'marketing_by_fax') {
        return 'contact_fax_channel';
    }
    if ($fname == 'marketing_by_mail') {
        return 'contact_mail_channel';
    }
    if ($fname == 'marketing_by_email') {
        return 'contact_email_channel';
    }


    return 'field_not_found_error';
}

function get_organization_id($organization_id) {
	$user = get_users(
		array(
			'meta_key' => 'CSD_ID',
			'meta_value' => $organization_id,
			'number' => 1,
			'count_total' => false
		)
	);
	if ($user) {
		$user_id = $user[0]->ID;
		if($user_id) {
			return $user_id;
		}
	}
	return false;
}

function set_organization_contact_details($organization_id, $contact_id, $contact_details = array())
{
    $user = get_users(
        array(
            'meta_key' => 'CSD_ID',
            'meta_value' => $organization_id,
            'number' => 1,
            'count_total' => false
        )
    );
    if ($user) {
        $user_id = $user[0]->ID;
        $roles = $user[0]->roles;
        if (in_array('customer', $roles)) {
            $old_contact_details = get_user_meta($user_id, 'wpiai_contacts', true);
            $new_contact_details = array();
            if (is_array($old_contact_details)) {
                $updated = false;
                if ($contact_id == '0') {
                    //Create New Contact
                    $new_contact = array();

                    if (array_key_exists('first_name', $contact_details)) {
                        $new_contact[wpiai_convert_field_names('first_name')] = $contact_details['first_name'];
                        $updated = true;
                    }

                    if (array_key_exists('last_name', $contact_details)) {
                        $new_contact[wpiai_convert_field_names('last_name')] = $contact_details['last_name'];
                        $updated = true;
                    }

                    if (array_key_exists('job_title', $contact_details)) {
                        $new_contact[wpiai_convert_field_names('job_title')] = $contact_details['job_title'];
                        $updated = true;
                    }

                    if (array_key_exists('address_line_1', $contact_details)) {
                        $new_contact[wpiai_convert_field_names('address_line_1')] = $contact_details['address_line_1'];
                        $updated = true;
                    }

                    if (array_key_exists('address_line_2', $contact_details)) {
                        $new_contact[wpiai_convert_field_names('address_line_2')] = $contact_details['address_line_2'];
                        $updated = true;
                    }

                    if (array_key_exists('address_line_3', $contact_details)) {
                        $new_contact[wpiai_convert_field_names('address_line_3')] = $contact_details['address_line_3'];
                        $updated = true;
                    }

                    if (array_key_exists('address_line_4', $contact_details)) {
                        $new_contact[wpiai_convert_field_names('address_line_4')] = $contact_details['address_line_4'];
                        $updated = true;
                    }

                    if (array_key_exists('postcode', $contact_details)) {
                        $new_contact[wpiai_convert_field_names('postcode')] = $contact_details['postcode'];
                        $updated = true;
                    }

                    if (array_key_exists('phone', $contact_details)) {
                        $new_contact[wpiai_convert_field_names('phone')] = $contact_details['phone'];
                        $updated = true;
                    }

                    if (array_key_exists('mobile_phone', $contact_details)) {
                        $new_contact[wpiai_convert_field_names('mobile_phone')] = $contact_details['mobile_phone'];
                        $updated = true;
                    }

                    if (array_key_exists('email', $contact_details)) {
                        $new_contact[wpiai_convert_field_names('email')] = $contact_details['email'];
                        $updated = true;
                    }

                    if (array_key_exists('marketing_by_phone', $contact_details)) {
                        $new_contact[wpiai_convert_field_names('marketing_by_phone')] = $contact_details['marketing_by_phone'];
                        $updated = true;
                    }

                    if (array_key_exists('marketing_by_fax', $contact_details)) {
                        $new_contact[wpiai_convert_field_names('marketing_by_fax')] = $contact_details['marketing_by_fax'];
                        $updated = true;
                    }

                    if (array_key_exists('marketing_by_mail', $contact_details)) {
                        $new_contact[wpiai_convert_field_names('marketing_by_mail')] = $contact_details['marketing_by_mail'];
                        $updated = true;
                    }

                    if (array_key_exists('marketing_by_email', $contact_details)) {
                        $new_contact[wpiai_convert_field_names('marketing_by_email')] = $contact_details['marketing_by_email'];
                        $updated = true;
                    }

                    if ($updated) {
                        //$new_contact['contact_CSD_ID'] = '';
                        //$new_contact['contact_CONTACT_ID'] = uniqid();
                        //$new_contact['CREATED_BY'] = 'WOO';
                        $old_contact_details[] = $new_contact;
                        $users_updated = get_option('wpiai_users_updated');
                        if (!is_array($users_updated)) {
                            $users_updated = array();
                        }
                        $users_updated[] = $user_id;
                        if (!update_option('wpiai_users_updated', $users_updated)) {
                            error_log('UserID not saved: ' . $user_id);
                        } else {
                            error_log($user_id . ' added to the meta update queue');
                        }
                        update_user_meta($user_id, 'wpiai_contacts', $old_contact_details);
                    }

                    return get_organization_contact_details($organization_id);
                }
                foreach ($old_contact_details as $old_contact_detail) {
                    if ($old_contact_detail['contact_CSD_ID'] == $contact_id) {
                        $array_keys = array_keys($old_contact_detail);
                        $new_contact_detail = array();
                        foreach ($array_keys as $array_key) {
                            if (array_key_exists(wpiai_convert_field_names($array_key), $contact_details)) {
                                $new_contact_detail[$array_key] = $contact_details[wpiai_convert_field_names($array_key)];
                                $updated = true;
                            } else {
                                $new_contact_detail[$array_key] = $old_contact_detail[$array_key];
                            }
                        }
                        $new_array_keys = array_keys($contact_details);
                        foreach ($new_array_keys as $new_array_key) {
                            if (!array_key_exists(wpiai_convert_field_names($new_array_key), $new_contact_detail)) {
                                $new_contact_detail[wpiai_convert_field_names($new_array_key)] = $contact_details[$new_array_key];
                                $updated = true;
                            }
                        }
                        $new_contact_detail['contact_CSD_ID'] = $contact_id;
                        $new_contact_details[] = $new_contact_detail;
                    } else {
                        $new_contact_details[] = $old_contact_detail;
                    }
                }
                if ($updated) {
                    update_user_meta($user_id, 'wpiai_contacts', $new_contact_details);
                    $users_updated = get_option('wpiai_users_updated');
                    if (!is_array($users_updated)) {
                        $users_updated = array();
                    }
                    $users_updated[] = $user_id;
                    if (!update_option('wpiai_users_updated', $users_updated)) {
                        error_log('UserID not saved: ' . $user_id);
                    } else {
                        error_log($user_id . ' added to the meta update queue');
                    }

                    return get_organization_contact_details($organization_id);
                } else {
                    error_log('nothing to update');
                }
            } else {
                $wpiai_contacts = array();
                $array_keys = array_keys($contact_details);
                $new_contact = array();
                foreach ($array_keys as $array_key) {
                    $new_contact[wpiai_convert_field_names($array_key)] = $contact_details[$array_key];
                }
                //$new_contact['contact_CSD_ID'] = '';
                //$new_contact['contact_CONTACT_ID'] = uniqid();
                //$new_contact['CREATED_BY'] = 'WOO';
                $wpiai_contacts[] = $new_contact;
                if (metadata_exists('user', $user_id, 'wpiai_contacts')) {
                    update_user_meta($user_id, 'wpiai_contacts', $wpiai_contacts);
                    $users_updated = get_option('wpiai_users_updated');
                    if (!is_array($users_updated)) {
                        $users_updated = array();
                    }
                    $users_updated[] = $user_id;
                    if (!update_option('wpiai_users_updated', $users_updated)) {
                        error_log('UserID not saved: ' . $user_id);
                    } else {
                        error_log($user_id . ' added to the meta update queue');
                    }

                    return get_organization_contact_details($organization_id);
                } else {
                    add_user_meta($user_id, 'wpiai_contacts', $wpiai_contacts);
                    $users_updated = get_option('wpiai_users_updated');
                    if (!is_array($users_updated)) {
                        $users_updated = array();
                    }
                    $users_updated[] = $user_id;
                    if (!update_option('wpiai_users_updated', $users_updated)) {
                        error_log('UserID not saved: ' . $user_id);
                    } else {
                        error_log($user_id . ' added to the meta update queue');
                    }

                    return get_organization_contact_details($organization_id);
                }
            }
        } else {
            error_log('not a customer');
        }
    } else {
        error_log('User Not Found');
    }

    return false;
}

function get_contant_customer_csd_id($contact_id)
{
    $users = get_users();
    foreach ($users as $user) {
        $wpiai_contacts = get_user_meta($user->ID, 'wpiai_contacts', true);
        if (is_array($wpiai_contacts)) {
            foreach ($wpiai_contacts as $wpiai_contact) {
                if ($wpiai_contact['contact_CSD_ID'] == $contact_id) {
                    return get_user_meta($user->ID, 'CSD_ID', true);
                }
            }
        }
    }

    return false;
}

function get_customer_details($customer_id)
{
    $user = get_userdata($customer_id);
    if ($user) {
        $roles = $user->roles;
        if (in_array('customer', $roles)) {
            $customer = new WC_Customer($customer_id);
            $customer_details = array();
            $customer_details['first_name'] = $customer->get_billing_first_name();
            $customer_details['last_name'] = $customer->get_billing_last_name();
            $customer_details['company'] = $customer->get_billing_company();
            $customer_details['address_line_1'] = $customer->get_billing_address_1();
            $customer_details['address_line_2'] = $customer->get_billing_address_2();
            $customer_details['town_city'] = $customer->get_billing_city();
            $customer_details['county'] = $customer->get_billing_state();
            $customer_details['postcode'] = $customer->get_billing_postcode();
            $customer_details['country'] = $customer->get_billing_country();
            $customer_details['email'] = $customer->get_billing_email();
            $customer_details['phone'] = $customer->get_billing_phone();

            return $customer_details;
        }
    }

    return false;
}

function set_customer_details($customer_id, $customer_details = array())
{
    $user_details = get_customer_details($customer_id);
    if ($user_details) {
        $customer = new WC_Customer($customer_id);
        $updated = false;

        if (array_key_exists('first_name', $customer_details)) {
            if ($user_details['first_name'] <> $customer_details['first_name']) {
                $customer->set_billing_first_name($customer_details['first_name']);
                $updated = true;
            }
        }

        if (array_key_exists('last_name', $customer_details)) {
            if ($user_details['last_name'] <> $customer_details['last_name']) {
                $customer->set_billing_last_name($customer_details['last_name']);
                $updated = true;
            }
        }

        if (array_key_exists('company', $customer_details)) {
            if ($user_details['company'] <> $customer_details['company']) {
                $customer->set_billing_company($customer_details['company']);
                $updated = true;
            }
        }

        if (array_key_exists('address_line_1', $customer_details)) {
            if ($user_details['address_line_1'] <> $customer_details['address_line_1']) {
                $customer->set_billing_address_1($customer_details['address_line_1']);
                $updated = true;
            }
        }

        if (array_key_exists('address_line_2', $customer_details)) {
            if ($user_details['address_line_2'] <> $customer_details['address_line_2']) {
                $customer->set_billing_address_2($customer_details['address_line_2']);
                $updated = true;
            }
        }

        if (array_key_exists('town_city', $customer_details)) {
            if ($user_details['town_city'] <> $customer_details['town_city']) {
                $customer->set_billing_city($customer_details['town_city']);
                $updated = true;
            }
        }

        if (array_key_exists('county', $customer_details)) {
            if ($user_details['county'] <> $customer_details['county']) {
                $customer->set_billing_state($customer_details['county']);
                $updated = true;
            }
        }

        if (array_key_exists('postcode', $customer_details)) {
            if ($user_details['postcode'] <> $customer_details['first_name']) {
                $customer->set_billing_postcode($customer_details['postcode']);
                $updated = true;
            }
        }

        if (array_key_exists('country', $customer_details)) {
            if ($user_details['country'] <> $customer_details['country']) {
                $customer->set_billing_country($customer_details['country']);
                $updated = true;
            }
        }

        if (array_key_exists('email', $customer_details)) {
            if ($user_details['email'] <> $customer_details['email']) {
                $customer->set_billing_email($customer_details['email']);
                $updated = true;
            }
        }

        if (array_key_exists('phone', $customer_details)) {
            if ($user_details['phone'] <> $customer_details['phone']) {
                $customer->set_billing_phone($customer_details['phone']);
                $updated = true;
            }
        }

        if ($updated) {
            $customer->save();

            return get_customer_details($customer_id);
        }
    }

    return false;
}