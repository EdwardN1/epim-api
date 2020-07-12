<?php
function payment_gateway_disable_on_account( $available_gateways ) {

    // Get current user
    $user = wp_get_current_user();

    // If ‘cash on delivery — cod’ (what we have named ‘On account’) is active AND
    // customer IS NOT ‘customer_with_account’ role
    // then remove cod
    if ( isset( $available_gateways['payment_on_account'] ) && !in_array( 'account_customer', $user->roles ) ){
        unset(  $available_gateways['payment_on_account'] );
    }

    return $available_gateways;
}

add_filter( 'woocommerce_available_payment_gateways', 'payment_gateway_disable_on_account' );

function payment_gateway_validate_account( $available_gateways ) {
    global $woocommerce;

    if(is_null($woocommerce->cart)) {
        return $available_gateways;
    }

    // Get current user & details
    $user = wp_get_current_user();
    $user_id = $user->ID;
    $credit_limit = get_user_meta( $user_id, 'credit_limit', true );

    // Get cart subtotal without formatting
    $amount = floatval( preg_replace( '#[^\d.]#', '', $woocommerce->cart->get_cart_subtotal() ) );

    // Set credit limit to default of 5000 if nothing is set
    if ( empty( $credit_limit ) ) {
        $credit_limit = 50;
    }

    // Work out remaining
    $credit_remaining = $credit_limit - $amount;

    // If ‘cash on delivery — cod’ (what we have named ‘On account’) is active AND
    // customer IS NOT ‘customer_with_account’ role OR
    // customer IS spending more than 5000 (or custom limit)
    // then remove cod
    if ( isset( $available_gateways['payment_on_account'] ) && !in_array( 'account_customer', $user->roles ) || $credit_remaining < 0 ) {
        unset(  $available_gateways['payment_on_account'] );
    }

    return $available_gateways;
}

add_filter( 'woocommerce_available_payment_gateways', 'payment_gateway_validate_account' );