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
                    $ret_shipping_detail = array();
                    $ret_shipping_detail['first_name'] = $shipping_detail['delivery-first-name'];
                    $ret_shipping_detail['last_name'] = $shipping_detail['delivery-last-name'];
                    $ret_shipping_detail['company_name'] = $shipping_detail['delivery-company-name'];
                    $ret_shipping_detail['country'] = $shipping_detail['delivery-country'];
                    $ret_shipping_detail['address_line_1'] = $shipping_detail['delivery-street-address-1'];
                    $ret_shipping_detail['address_line_2'] = $shipping_detail['delivery-street-address-2'];
                    $ret_shipping_detail['address_line_3'] = $shipping_detail['delivery-street-address-3'];
                    $ret_shipping_detail['town_city'] = $shipping_detail['delivery-town-city'];
                    $ret_shipping_detail['county'] = $shipping_detail['delivery-county'];
                    $ret_shipping_detail['postcode'] = $shipping_detail['delivery-postcode'];
                    $ret_shipping_detail['phone'] = $shipping_detail['delivery-phone'];
                    $ret_shipping_detail['email'] = $shipping_detail['delivery-email'];
                    $ret_shipping_details[] = $ret_shipping_detail;
                }
                if (!empty($ret_shipping_details)) {
                    return $ret_shipping_details;
                }
            }
        }
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
            $shipping_details = get_user_meta($user_id, 'wpiai_contacts', true);
            if (is_array($shipping_details)) {
                $ret_shipping_details = array();
                foreach ($shipping_details as $shipping_detail) {
                    $ret_shipping_detail = array();
                    $ret_shipping_detail['first_name'] = $shipping_detail['contact_first_name'];
                    $ret_shipping_detail['last_name'] = $shipping_detail['contact_last_name'];
                    $ret_shipping_detail['job_title'] = $shipping_detail['contact_job_title'];
                    $ret_shipping_detail['country'] = $shipping_detail['delivery-country'];
                    $ret_shipping_detail['address_line_1'] = $shipping_detail['contact_addr_1'];
                    $ret_shipping_detail['address_line_2'] = $shipping_detail['contact_addr_2'];
                    $ret_shipping_detail['address_line_3'] = $shipping_detail['contact_addr_3'];
                    $ret_shipping_detail['address_line_4'] = $shipping_detail['contact_addr_4'];
                    $ret_shipping_detail['town_city'] = $shipping_detail['delivery-town-city'];
                    $ret_shipping_detail['postcode'] = $shipping_detail['contact_postcode'];
                    $ret_shipping_detail['phone'] = $shipping_detail['contact_phone'];
                    $ret_shipping_detail['mobile_phone'] = $shipping_detail['contact_mobile_phone'];
                    $ret_shipping_detail['email'] = $shipping_detail['contact_email'];
                    $ret_shipping_detail['marketing_by_phone'] = $shipping_detail['contact_phone_channel'];
                    $ret_shipping_detail['marketing_by_fax'] = $shipping_detail['contact_fax_channel'];
                    $ret_shipping_detail['marketing_by_mail'] = $shipping_detail['contact_mail_channel'];
                    $ret_shipping_detail['marketing_by_email'] = $shipping_detail['contact_email_channel'];
                    $ret_shipping_details[] = $ret_shipping_detail;
                }
                if (!empty($ret_shipping_details)) {
                    return $ret_shipping_details;
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