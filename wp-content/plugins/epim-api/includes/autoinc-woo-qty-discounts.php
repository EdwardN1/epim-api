<?php
add_action( 'woocommerce_before_calculate_totals', 'epim_quantity_based_pricing', 9999 );

function epim_quantity_based_pricing( $cart ) {

    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;

    if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ) return;

    // Define discount rules and thresholds
    $threshold1 = 101; // Change price if items > 100
    $discount1 = 0.05; // Reduce unit price by 5%
    $threshold2 = 1001; // Change price if items > 1000
    $discount2 = 0.1; // Reduce unit price by 10%

    foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
        if ( $cart_item['quantity'] >= $threshold1 && $cart_item['quantity'] < $threshold2 ) {
            $price = round( $cart_item['data']->get_price() * ( 1 - $discount1 ), 2 );
            $cart_item['data']->set_price( $price );
        } elseif ( $cart_item['quantity'] >= $threshold2 ) {
            $price = round( $cart_item['data']->get_price() * ( 1 - $discount2 ), 2 );
            $cart_item['data']->set_price( $price );
        }
    }

}