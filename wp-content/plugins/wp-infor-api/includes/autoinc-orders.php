<?php
/**
 *
 * Sales Order Added
 *
 *
 */

add_action( 'woocommerce_new_order', 'wpiai_order_added' );

function wpiai_order_added( $order_id ){
    $order = wc_get_order( $order_id );
    /* Insert your code */
    error_log('Order: '. $order_id. ' Added');
}

/**
 *
 * Sales Order Updated - NB not implemented
 *
 */

function wpiai_order_updated( $order_id ){
    $order = wc_get_order( $order_id );
    /* Insert your code */
    error_log('Order: '. $order_id. ' Updated');
}

//add_action( 'woocommerce_update_order', 'wpiai_order_updated' );

/**
 *
 * Display Order Meta on Admin Pages
 *
 */

add_action( 'woocommerce_admin_order_data_after_billing_address', 'wpiai_display_admin_order_meta', 10, 1 );

function wpiai_display_admin_order_meta($order){
    echo '<p><strong>'.__('CSD_ID').':</strong> <br/>' . get_post_meta( $order->get_id(), 'CSD_ID', true ) . '</p>';
    echo '<p><strong>'.__('CSD_Customer_ID').':</strong> <br/>' . get_post_meta( $order->get_id(), 'CSD_Customer_ID', true ) . '</p>';
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
    register_post_status( 'wc-quote', array(
        'label'                     => 'Quote',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Quote (%s)', 'Quote (%s)' )
    ) );
}

// Add to list of WC Order statuses
function wpiai_add_quotes_to_order_statuses( $order_statuses ) {

    $new_order_statuses = array();

    // add new order status after processing
    foreach ( $order_statuses as $key => $status ) {

        $new_order_statuses[ $key ] = $status;

        if ( 'wc-processing' === $key ) {
            $new_order_statuses['wc-quote'] = 'Quote';
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

function get_order_XML($order_id, $action, $record) {
    $order = wc_get_order( $order_id );
    if($order) {
        $xmld = get_option('wpiai_sales_order_xml');
        $xml = simplexml_load_string($xmld);

        $nowDT = new DateTime();
        $CreationDateTime = $nowDT->format(DateTime::ATOM);
        $BODID = uniqid();

        $CustomerPartyID = get_option('wpiai_guest_customer_number');
        $user = get_userdata($record['customer_id']);
        if($user) {
            $CustomerPartyID = get_user_meta( $record['customer_id'], 'CSD_ID', true );
        }
        $Name = $record['billing-company'];
        $URI = $record['billing-email'];
        $Location = get_option('wpiai_default_warehouse');

    } else {
        return false;
    }
}





