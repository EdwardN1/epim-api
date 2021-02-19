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
	echo '<h3>Meta Data Fields</h3>';
	echo '<table class="widefat fixed">';
    echo '<tr><th><strong>'.__('CSD_ID').':</th><td>' . get_post_meta( $order->get_id(), 'CSD_ID', true ) . '</td></tr>';
    echo '<tr><th><strong>'.__('CSD_Customer_ID').':</strong></th><td>' . get_post_meta( $order->get_id(), 'CSD_Customer_ID', true ).'</td></tr>';
	echo '<tr><th><strong>'.__('suffix').':</strong></th><td>' . get_post_meta( $order->get_id(), 'suffix', true ) . '</td></tr>';
	echo '<tr><th><strong>'.__('po_reference').':</strong></th><td>' . get_post_meta( $order->get_id(), 'po_reference', true ) . '</td></tr>';
	echo '<tr><th><strong>'.__('order_type').':</strong></th><td>' . get_post_meta( $order->get_id(), 'order_type', true ) . '</td></tr>';
	echo '<tr><th><strong>'.__('order_notes').':</strong></th><td>' . get_post_meta( $order->get_id(), 'order_notes', true ) . '</td></tr>';
	echo '<tr><th><strong>'.__('order_status_code').':</strong></th><td>' . get_post_meta( $order->get_id(), 'order_status_code', true ) . '</td></tr>';
	echo '<tr><th><strong>'.__('date_picked').':</strong></th><td>' . get_post_meta( $order->get_id(), 'date_picked', true ) . '</td></tr>';
	echo '<tr><th><strong>'.__('date_shipped').':</strong></th><td>' . get_post_meta( $order->get_id(), 'date_shipped', true ) . '</td></tr>';
	echo '<tr><th><strong>'.__('date_invoiced').':</strong></th><td>' . get_post_meta( $order->get_id(), 'date_invoiced', true ) . '</td></tr>';
	echo '<tr><th><strong>'.__('date_canceled').':</strong></th><td>' . get_post_meta( $order->get_id(), 'date_canceled', true ) . '</td></tr>';
	echo '<tr><th><strong>'.__('approve_type').':</strong></th><td>' . get_post_meta( $order->get_id(), 'approve_type', true ) . '</td></tr>';
	echo '<tr><th><strong>'.__('subtotal_before_tax').':</strong></th><td>' . get_post_meta( $order->get_id(), 'subtotal_before_tax', true ) . '</td></tr>';
	echo '<tr><th><strong>'.__('tax_type').':</strong></th><td>' . get_post_meta( $order->get_id(), 'tax_type', true ) . '</td></tr>';
	echo '<tr><th><strong>'.__('disposition').':</strong></th><td>' . get_post_meta( $order->get_id(), 'disposition', true ) . '</td></tr>';
	echo '<tr><th><strong>'.__('ship_via').':</strong></th><td>' . get_post_meta( $order->get_id(), 'ship_via', true ) . '</td></tr>';
	echo '<tr><th><strong>'.__('shipping_instructions').':</strong></th><td>' . get_post_meta( $order->get_id(), 'shipping_instructions', true ) . '</td></tr>';
	echo '<tr><th><strong>'.__('tendered').':</strong></th><td>' . get_post_meta( $order->get_id(), 'tendered', true ) . '</td></tr>';
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
	        $new_order_statuses['wc-open'] = 'Open';
            $new_order_statuses['wc-entered'] = 'Entered';
	        $new_order_statuses['wc-ordered'] = 'Ordered';
	        $new_order_statuses['wc-picked'] = 'Picked';
	        $new_order_statuses['wc-shipped'] = 'Shipped';
	        $new_order_statuses['wc-invoiced'] = 'Invoiced';
	        $new_order_statuses['wc-paid'] = 'Paid';
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





