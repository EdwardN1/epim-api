<?php
add_filter('pre_option_woocommerce_enable_guest_checkout', 'wpamvc_disable_guest_checkout');

function wpamvc_disable_guest_checkout($value)
{
    $account = new Account;
    if ($account->sessionLogin()) {
        $value = 'no';
    }
    return $value;
}

add_action('woocommerce_before_checkout_form', 'wpamvc_remove_login_form', 4);
function wpamvc_remove_login_form()
{
    if (!is_user_logged_in()) {
        $account = new Account;
        if ($account->sessionLogin()) {
            remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10);
        } else {
            remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10);
            add_action('woocommerce_before_checkout_form', 'wpamvc_checkout_login_form', 20);
        }
    }
}

function wpamvc_checkout_login_form()
{
    echo '<div class="woocommerce-info"> Returning customer? <a href="' . get_permalink(get_option('woocommerce_myaccount_page_id')) . '">Click here to login</a>	</div>';
}

/*add_action( 'woocommerce_order_status_completed', 'wpamvc_woocommerce_order_status_completed', 10, 1 );
function wpamvc_woocommerce_order_status_completed( $order_id ) {
	error_log( "============================== Order complete for order ============================".$order_id);
}

add_action( 'woocommerce_order_status_processing', 'wpamvc_woocommerce_order_status_processing', 10, 1 );
function wpamvc_woocommerce_order_status_processing( $order_id ) {
	error_log( "============================== Order processing for order ============================".$order_id);
}*/

add_filter('woocommerce_checkout_customer_id', 'wpamvc_filter_order_customer_id');

function wpamvc_filter_order_customer_id($userid)
{
    $account = new Account;
    if ($account->sessionLogin()) {
        //error_log( print_r( $order, true ) );
        $user_ID = get_post_meta($account->getId(), '_wpam_accounts_useraccount', true);
        $userid = $user_ID;
    }
    return $userid;
}

function wpamvc_override_checkout_fields($fields)
{
    $account = new Account;
    if ($account->sessionLogin()) {
        $billing_repeater = get_post_meta($account->getId(), '_wpam_billing_repeater_fields', true);
        $delivery_repeater = get_post_meta($account->getId(), '_wpam_delivery_repeater_fields', true);
        if ($billing_repeater):
            $first_billing = $billing_repeater[0];
            if ($first_billing):
                // Billing
                $fields['billing']['billing_first_name']['default'] = $first_billing["billing-first-name"];
                $fields['billing']['billing_last_name']['default'] = $first_billing["billing-last-name"];
                $fields['billing']['billing_company']['default'] = $first_billing["billing-company-name"];
                $fields['billing']['billing_address_1']['default'] = $first_billing["billing-street-address-1"];
                $fields['billing']['billing_address_2']['default'] = $first_billing["billing-street-address-2"];
                $fields['billing']['billing_city']['default'] = $first_billing["billing-town-city"];
                $fields['billing']['billing_postcode']['default'] = $first_billing["billing-postcode"];
                $fields['billing']['billing_phone']['default'] = $first_billing["billing-phone"];
                $fields['billing']['billing_email']['default'] = $first_billing["billing-email"];
            endif;
        endif;

        if ($delivery_repeater):
            $first_delivery = $delivery_repeater[0];
            if ($first_delivery):
              // Delivery
                 $fields['shipping']['shipping_first_name']['default'] = $first_delivery["delivery-first-name"];
                 $fields['shipping']['shipping_last_name']['default'] = $first_delivery["delivery-last-name"];
                 $fields['shipping']['shipping_company']['default'] = $first_delivery["delivery-company-name"];
                 $fields['shipping']['shipping_address_1']['default'] = $first_delivery["delivery-street-address-1"];
                 $fields['shipping']['shipping_address_2']['default'] = $first_delivery["delivery-street-address-2"];
                 $fields['shipping']['shipping_city']['default'] = $first_delivery["delivery-town-city"];
                 $fields['shipping']['shipping_postcode']['default'] = $first_delivery["delivery-postcode"];
                 $fields['shipping']['shipping_phone']['default'] = $first_delivery["delivery-phone"];
                 $fields['shipping']['shipping_email']['default'] = $first_delivery["delivery-email"];
            endif;
        endif;


    }
    return $fields;
}

add_filter('woocommerce_checkout_fields', 'wpamvc_override_checkout_fields');


/**
 * Change the default state and country on the checkout page
 */
add_filter('default_checkout_billing_country', 'wpamvc_change_default_checkout_country');
add_filter('default_checkout_billing_state', 'wpamvc_change_default_checkout_state');

function wpamvc_change_default_checkout_country($country)
{
    $account = new Account;
    if ($account->sessionLogin()) {
        $billing_repeater = get_post_meta($account->getId(), '_wpam_billing_repeater_fields', true);
        if ($billing_repeater):
            $first_billing = $billing_repeater[0];
            if ($first_billing) {
                return $first_billing["billing-country"];
            }
        endif;
    }
    return $country; // country code
}

function wpamvc_change_default_checkout_state($state)
{
    $account = new Account;
    if ($account->sessionLogin()) {
        $billing_repeater = get_post_meta($account->getId(), '_wpam_billing_repeater_fields', true);
        if ($billing_repeater):
            $first_billing = $billing_repeater[0];
            if ($first_billing) {
                return $first_billing["billing-county"];
            }
        endif;
    }
    return $state; // state code
}

add_filter('default_checkout_shipping_country', 'wpamvc_change_default_shipping_checkout_country');
add_filter('default_checkout_shipping_state', 'wpamvc_change_default_shipping_checkout_state');

function wpamvc_change_default_shipping_checkout_country($country)
{
    $account = new Account;
    if ($account->sessionLogin()) {
        $billing_repeater = get_post_meta($account->getId(), '_wpam_delivery_repeater_fields', true);
        if ($billing_repeater):
            $first_billing = $billing_repeater[0];
            if ($first_billing) {
                return $first_billing["delivery-country"];
            }
        endif;
    }
    return $country; // country code
}

function wpamvc_change_default_shipping_checkout_state($state)
{
    $account = new Account;
    if ($account->sessionLogin()) {
        $billing_repeater = get_post_meta($account->getId(), '_wpam_delivery_repeater_fields', true);
        if ($billing_repeater):
            $first_billing = $billing_repeater[0];
            if ($first_billing) {
                return $first_billing["delivery-county"];
            }
        endif;
    }
    return $state; // state code
}