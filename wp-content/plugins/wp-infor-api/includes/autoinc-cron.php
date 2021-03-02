<?php
register_activation_hook(wpiai_PLUGINFILE, 'wpiai_cron_activation');

function wpiai_cron_activation() {
    error_log('Running wpiai_cron_activation');
    if (! wp_next_scheduled ( 'wpiai_every_minute_action' )) {
        wp_schedule_event(time(), 'everyminute', 'wpiai_every_minute_action');
    }
	if (! wp_next_scheduled ( 'wpiai_every_twenty_minutes_action' )) {
		wp_schedule_event(time(), 'everytwentyminutes', 'wpiai_every_twenty_minutes_action');
	}
}

add_action('wpiai_every_minute_action', 'wpiai_do_every_minute');
add_action('wpiai_every_twenty_minutes_action', 'wpiai_do_every_twenty_minutes');

function wpiai_do_every_minute() {
    // do something every minute
    //error_log('WP Cron is working....Every Minute Event');
    wpiai_check_user_meta();
}

function wpiai_do_every_twenty_minutes() {
	// do something every twenty minutes
	//error_log('WP Cron is working....');
}

add_filter( 'cron_schedules', 'wpiai_add_cron_interval' );
function wpiai_add_cron_interval( $schedules ) {
    $schedules['everyminute'] = array(
        'interval'  => 60, // time in seconds
        'display'   => 'Every Minute'
    );
	$schedules['everytwentyminutes'] = array(
		'interval'  => 1200, // time in seconds
		'display'   => 'Every Twenty Minutes'
	);
    return $schedules;
}

function get_woo_skus_ids() {
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => - 1
    );
    $loop = new WP_Query( $args );
    $res  = array();
    if ( $loop->have_posts() ):
        while ( $loop->have_posts() ): $loop->the_post();

            global $product;
            $sku   = $product->get_sku();
            $id = get_the_id();
            $res[] = array(
              'id' => $id,
              'sku' => $sku
            );
        endwhile;
    endif;
    wp_reset_postdata();
    return $res;
}

function wpiai_check_user_meta() {
    //error_log('Checking User Meta');
    $users_updated = get_option('wpiai_users_updated');
    update_option('wpiai_users_updated',array());
    foreach ($users_updated as $user_id) {
        error_log('Processing ShipTos for user_id: '.$user_id);
        $shipTo_meta = get_user_meta( $user_id, 'wpiai_delivery_addresses', true );
        $shipTo      = array();
        $shipAdd = array();
        $shipChange = array();
        if(is_array($shipTo_meta)) {
            foreach ( $shipTo_meta as $shipTo_m ) {
                $shipTo_rec = $shipTo_m;
                if ( ( $shipTo_rec['delivery-CSD-ID'] == '' ) || ( ! array_key_exists( 'delivery-CSD-ID', $shipTo_rec ) ) ) {
                    $shipTo_rec['CREATED_BY'] = 'WOO';
                    if ( ( $shipTo_rec['delivery_UNIQUE_ID'] == '' ) || ( ! array_key_exists( 'delivery_UNIQUE_ID', $shipTo_rec ) ) ) {
                        $shipTo_rec['delivery_UNIQUE_ID'] = uniqid();
                        $shipAdd[] = $shipTo_rec;
                    }
                } else {
                    $shipTo_rec['CREATED_BY'] = 'EXTERNAL';
                }
                if ( ( $shipTo_rec['delivery_UNIQUE_ID'] == '' ) || ( ! array_key_exists( 'delivery_UNIQUE_ID', $shipTo_rec ) ) ) {
                    $shipTo_rec['delivery_UNIQUE_ID'] = uniqid();
                    $shipChange[] = $shipTo_rec;
                }
                $shipTo[] = $shipTo_rec;
            }
        } else {
            error_log('No ShipTo Meta');
        }
        if(!update_user_meta( $user_id, 'wpiai_delivery_addresses', $shipTo )) {
            error_log('Ship To Update Failed for $user_id: '.$user_id);
        } else {
            $shipTo_url        = get_option( 'wpiai_ship_to_url' );
            $shipTo_paramaters = set_messageid(get_option('wpiai_ship_to_parameters'));
            if ( ( count( $shipAdd ) > 0 )) {
                foreach($shipAdd as $add_shipTo) {
                    $shipTo_xml = get_shipTo_XML_record($user_id,'Add',$add_shipTo);
                    error_log('shipto Add');
                    $updated    = wpiai_get_infor_message_multipart_message( $shipTo_url, $shipTo_paramaters, $shipTo_xml );
                }
            }
            if ( ( count( $shipChange ) > 0 )) {
                foreach($shipChange as $update_shipTo) {
                    $shipTo_xml = get_shipTo_XML_record($user_id,'Change',$update_shipTo);
                    error_log('shipto Change');
                    $updated    = wpiai_get_infor_message_multipart_message( $shipTo_url, $shipTo_paramaters, $shipTo_xml );
                }
            }
        }
    }
}