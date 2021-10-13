<?php
add_filter( 'cron_schedules', 'wpmai_twenty_minute_interval' );

// add once 10 minute interval to wp schedules
function wpmai_twenty_minute_interval( $interval ) {
	$interval['minutes_20'] = array( 'interval' => 20 * 60, 'display' => 'Once 20 minutes' );

	return $interval;
}

register_activation_hook( wpmai_PLUGINFILE, 'wpmai_cron_activation' );

function wpmai_cron_activation() {
	//error_log('Activating');
	if ( ! wp_next_scheduled( 'wpmai_update_branch_stock_action' ) ) {
		wp_schedule_event( time(), 'minutes_20', 'wpmai_update_branch_stock_action' );
		//error_log('Trying to activate wpmai_update_branch_stock_action');
	} else {
		//error_log('wpmai_update_branch_stock_action already active');
	}

}

add_action( 'wpmai_update_branch_stock_action', 'wpmai_update_branch_stock_cron' );

function wpmai_update_branch_stock_cron() {
	wpmai_cron_log('wpmai_update_branch_stock_cron starting');
	$time_start = microtime(true);
	$json_products = wpmai_get_stock();
	$products = json_decode($json_products,true);
	//wpmai_cron_log(print_r($products,true));
	$i = 0;
	if(is_array($products)) {
		foreach ($products as $product) {
			if($product['sku']) {
				$id = wc_get_product_id_by_sku( $product['sku'] );
				if($id>0) {
					$p = wc_get_product( $id );
					$p->set_price( $product['price'] );
					$p->set_regular_price( $product['price'] );
					$p->set_manage_stock( true );
					$p->set_stock_quantity( $product['qty'] );
					$p->save();
					$i ++;
				}
			}
		}
	} else {
		wpmai_cron_log('No products');
	}
	$time_elapsed_secs = microtime( true ) - $time_start;
	wpmai_cron_log('wpmai_update_branch_stock_cron took '.$time_elapsed_secs.' seconds');
	wpmai_cron_log($i.' products imported');
}


function wpmai_cron_log($log) {
	if(is_dir(wpmai_PLUGINPATH)) {
		$log_file = wpmai_PLUGINPATH.'/cron-log.log';
		ini_set("log_errors", 1);
		ini_set("error_log", $log_file);
		error_log($log);
	}
}