<?php
function get_order_XML() {

}

function wpiai_order_added( $order_id ){
    $order = wc_get_order( $order_id );
    /* Insert your code */
    error_log('Order: '. $order_id. ' Added');
}

add_action( 'woocommerce_new_order', 'wpiai_order_added' );

function wpiai_order_updated( $order_id ){
    $order = wc_get_order( $order_id );
    /* Insert your code */
    error_log('Order: '. $order_id. ' Updated');
}

//add_action( 'woocommerce_update_order', 'wpiai_order_updated' );