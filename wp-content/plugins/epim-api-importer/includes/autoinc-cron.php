<?php
add_filter( 'cron_schedules', 'epimapi_ten_minute_interval' );

// add once 10 minute interval to wp schedules
function epimapi_ten_minute_interval( $interval ) {

	$interval['minutes_10'] = array( 'interval' => 10 * 60, 'display' => 'Once 10 minutes' );
	$interval['minutes_1']  = array( 'interval' => 60, 'display' => 'Once every minute' );

	return $interval;
}

register_activation_hook( epimaapi_PLUGINFILE, 'epimaapi_cron_activation' );

function epimaapi_cron_activation() {
	error_log( 'checking and adding cron events' );
	if ( ! wp_next_scheduled( 'epimaapi_update_branch_stock_daily_action' ) ) {
		wp_schedule_event( strtotime( '22:20:00' ), 'daily', 'epimaapi_update_branch_stock_daily_action' );
	}
	if ( ! wp_next_scheduled( 'epimaapi_update_branch_stock_minutes_action' ) ) {
		wp_schedule_event( time(), 'minutes_10', 'epimaapi_update_branch_stock_minutes_action' );
	}
	if ( ! wp_next_scheduled( 'epimaapi_update_every_minute_minute_action' ) ) {
		wp_schedule_event( time(), 'minutes_1', 'epimaapi_update_every_minute_minute_action' );
	}
}

add_action( 'epimaapi_update_branch_stock_minutes_action', 'epimaapi_update_branch_stock_minutes' );
add_action( 'epimaapi_update_every_minute_minute_action', 'epimaapi_update_every_minute' );
add_action( 'epimaapi_update_branch_stock_daily_action', 'epimaapi_update_branch_stock_daily' );

function epimaapi_update_branch_stock_daily() {
	$epim_enable_scheduled_updates        = false;
	$epim_enable_scheduled_updates_option = get_option( 'epim_enable_scheduled_updates' );
	if ( is_array( $epim_enable_scheduled_updates_option ) ) {
		if ( $epim_enable_scheduled_updates_option['checkbox_value'] == 1 ) {
			$epim_enable_scheduled_updates = true;
		}
	}
	$epim_update_schedule = get_option( 'epim_update_schedule' );
	//error_log('running daily branch stock update');
	if ( $epim_update_schedule == 'daily' ) {
		if ( $epim_enable_scheduled_updates ) {
			epimaapi_update_branch_stock_cron();
		} else {
			//error_log('Daily update aborted - Updates not enabled');
		}
	} else {
		//error_log('Daily update aborted - set to 10 minute updates');
	}
}

function cron_log($log) {
    $log_dir= WP_PLUGIN_DIR . '/epim-api-importer';
    if(is_dir($log_dir)) {
        $log_file = $log_dir.'/cron-log.log';
        ini_set("log_errors", 1);
        ini_set("error_log", $log_file);
        error_log($log);
    }
}

function epimaapi_update_every_minute() {
	$epim_update_running = get_option( '_epim_update_running' );
    $epim_background_updates_max_run_time = get_option('epim_background_updates_max_run_time');
	if(($epim_update_running=='Preparing to process ePim categories')||(substr( $epim_update_running, 0, 44 ) === "Processing categories - Restarting at Index:")) {
	    cron_log('Starting or resuming process ePim categories');
		$epim_background_process_data = get_option('_epim_background_process_data');
		if(is_array($epim_background_process_data)) {
			$i = 1;
			$c = count($epim_background_process_data);
			$time_start = microtime(true);
			foreach ($epim_background_process_data as $category) {
				$epim_update_running = 'Process category '.$i.'/'.$c;
				if($i>=get_option('_epim_background_current_index')) {
					if ( array_key_exists( 'Id', $category ) ) {
						if ( array_key_exists( 'Name', $category ) ) {
							$ParentID        = null;
							$picture_webpath = '';
							$picture_ids     = array();
							if ( $category['ParentId'] ) {
								$ParentID = $category['ParentId'];
							}
                            cron_log('Importing Id:'.$category['Id'].' Name: '.$category['Name']);
							epimaapi_create_category( $category['Id'], $category['Name'], $ParentID, $picture_webpath, $picture_ids );
						}
					}
					update_option( '_epim_background_current_index', $i-1 );
				}
				$i++;
				$time_now = microtime(true);
				if(($time_now-$time_start>=$epim_background_updates_max_run_time)) {
					update_option('_epim_update_running','Processing categories - Restarting at Index: '.$i.'/'.$c);
					return;
				}
				update_option('_epim_update_running',$epim_update_running);
			}
			update_option('_epim_update_running','Preparing to Sort Categories');
			update_option('_epim_background_current_index',0);
		} else {
			update_option('_epim_update_running','');
			update_option('_epim_background_process_data','');
		}
	}

	if(($epim_update_running=='Preparing to Sort Categories')||(substr( $epim_update_running, 0, 41 ) === "Sorting categories - Restarting at Index:")) {
		$time_start = microtime(true);
		$terms = get_terms( [
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
		] );
		$i = 1;
		$c = count($terms);
		foreach ( $terms as $term ) {
			$epim_update_running = 'Sorting Category '.$i.'/'.$c;
			if($i>=get_option('_epim_background_current_index')) {
				$api_parents = get_term_meta( $term->term_id, 'epim_api_parent_id', true );
				if ( $api_parents != '' ) {
					$parent = epimaapi_getTermFromID( $api_parents, $terms );
					if ( $parent ) {
						$term_id = $term->term_id;

						$epim_api_id           = get_term_meta( $term_id, 'epim_api_id', true );
						$epim_api_parent_id    = get_term_meta( $term_id, 'epim_api_parent_id', true );
						$epim_api_picture_ids  = get_term_meta( $term_id, 'epim_api_picture_ids', true );
						$epim_api_picture_link = get_term_meta( $term_id, 'epim_api_picture_link', true );

						wp_update_term( $term_id, 'product_cat', array( 'parent' => $parent->term_id ) );

						update_term_meta( $term_id, 'epim_api_id', $epim_api_id );
						update_term_meta( $term_id, 'epim_api_parent_id', $epim_api_parent_id );
						update_term_meta( $term_id, 'epim_api_picture_ids', $epim_api_picture_ids );
						update_term_meta( $term_id, 'epim_api_picture_link', $epim_api_picture_link );
					}
				}
				update_option('_epim_background_current_index',$i-1);
			}
			$i++;
			$time_now = microtime(true);
			if(($time_now-$time_start>=$epim_background_updates_max_run_time)) {
			    cron_log('Sorting categories - Restarting at Index: '.$i.'/'.$c);
				update_option('_epim_update_running','Sorting categories - Restarting at Index: '.$i.'/'.$c);
				return;
			}
			cron_log($epim_update_running);
			update_option('_epim_update_running',$epim_update_running);
		}
		cron_log('Categories Updated and Sorted');
		update_option('_epim_update_running','Categories Updated and Sorted');
		update_option('_epim_background_current_index',0);
	}

	if($epim_update_running == 'Categories Updated and Sorted') {
		update_option('_epim_update_running','Getting All Products to Import');
		cron_log('Getting All Products to Import');
		$allProductsResponse = json_decode(get_epimaapi_all_products(),true);
		$variations = array();
		if ( json_last_error() == JSON_ERROR_NONE ) {
			if (array_key_exists('Results', $allProductsResponse)) {
				foreach ( $allProductsResponse['Results'] as $Product ) {
					$categories = array();
					$pictures = array();
					if(array_key_exists('CategoryIds',$Product)) {
						$categories = $Product['CategoryIds'];
					}
					if(array_key_exists('PictureIds',$Product)) {
						$pictures = $Product['PictureIds'];
					}
					if(array_key_exists('VariationIds',$Product)) {
						if(is_array($Product['VariationIds'])) {
							foreach ($Product['VariationIds'] as $variation_id) {
								$variation = array();
								$variation['productID'] = $Product['Id'];
								$variation['variationID'] = $variation_id;
								$variation['productBulletText'] = $Product['BulletText'];
								$variation['productName'] = $Product['Name'];
								$variation['categoryIds'] = $categories;
								$variation['pictureIds'] = $pictures;
								$variations[] = $variation;
							}
						}

					}
				}
			}
		} else {
			cron_log('ePim is returning garbage, getting all products.');
		}
		update_option( '_epim_background_process_data', $variations );
		update_option('_epim_update_running','Preparing to import products');
		cron_log('Found '.count($variations). ' products to import');
		cron_log('Preparing to import products');
	}

	if(($epim_update_running == 'Preparing to import products')||(substr( $epim_update_running, 0, 41 ) === "Importing Products - Restarting at Index:")) {
		$time_start = microtime(true);
		$variations = get_option('_epim_background_process_data');
		$i = 1;
		$c = count($variations);
		update_option('_epim_update_running','Importing '.$c.' Products');
		cron_log('Importing '.$c.'Products');
		if(is_array($variations)) {
			foreach ($variations as $variation) {
				update_option('_epim_update_running','Importing product '.$i.'/'.$c);
				if($i>=get_option('_epim_background_current_index')) {
					if ( is_array( $variation ) ) {
						if ( array_key_exists( 'variationID', $variation ) ) {
							cron_log('Importing variation ID: '.$variation['variationID']);
							cron_log( epimaapi_create_product( $variation['productID'], $variation['variationID'], $variation['productBulletText'], $variation['productName'], $variation['categoryIds'], $variation['pictureIds'] ) );
						}
					}
					update_option( '_epim_background_current_index', $i-1 );
				}

				$i++;
				$time_now = microtime(true);
				if(($time_now-$time_start>=$epim_background_updates_max_run_time)) {
					cron_log('Importing Products - Restarting at Index: '.$i.'/'.$c);
					update_option('_epim_update_running','Importing Products - Restarting at Index: '.$i.'/'.$c);
					return;
				}
			}
		}
		update_option('_epim_background_current_index',0);
		cron_log('Import Finished');
		update_option('_epim_update_running','');
	}
}

function epimaapi_update_branch_stock_minutes() {
	if ( ! wp_next_scheduled( 'epimaapi_update_every_minute_minute_action' ) ) {
		wp_schedule_event( time(), 'minutes_1', 'epimaapi_update_every_minute_minute_action' );
	}
	$epim_enable_scheduled_updates        = false;
	$epim_enable_scheduled_updates_option = get_option( 'epim_enable_scheduled_updates' );
	if ( is_array( $epim_enable_scheduled_updates_option ) ) {
		if ( $epim_enable_scheduled_updates_option['checkbox_value'] == 1 ) {
			$epim_enable_scheduled_updates = true;
		}
	}
	$epim_update_schedule = get_option( 'epim_update_schedule' );
	//error_log('running 10 minute branch stock update');
	if ( $epim_update_schedule == 'minutes' ) {
		if ( $epim_enable_scheduled_updates ) {
			epimaapi_update_branch_stock_cron();
		} else {
			// error_log('10 minute update aborted - Updates not enabled');
		}
	} else {
		// error_log('10 minute update aborted - set to daily updates');
	}
}

function epimaapi_update_branch_stock_cron() {
	$log   = '';
	$start = microtime( true );
	//error_log('epimaapi_update_branch_stock_cron started');
	$yesterday = date( 'dMY', strtotime( "-1 days" ) );
	$branches  = json_decode( get_epimaapi_all_branches(), true );
	if ( is_array( $branches ) ) {
		foreach ( $branches as $branch ) {
			if ( is_array( $branch ) ) {
				if ( array_key_exists( 'Id', $branch ) ) {
					$Id          = $branch['Id'];
					$stockLevels = json_decode( get_epimaapi_get_branch_stock_since( $Id, $yesterday ), true );
					if ( is_array( $stockLevels ) ) {
						foreach ( $stockLevels as $stock_level ) {
							$log .= epimaapi_update_branch_stock( $Id, $stock_level['VariationId'], $stock_level['Stock'] ) . '</br>';
						}
					} else {
						//error_log('epim daily cron - No stock to update for Branch: '.$Id);
					}
				} else {
					//error_log('epim daily cron - missing Id for branch');
				}
			} else {
				//error_log('epim daily cron - No Branches returned');
			}
		}
	} else {
		//error_log('epim daily cron - failed to get branches');
	}
	$updatedProducts = get_epimaapi_all_changed_products_since( $yesterday );
	$jProducts       = json_decode( $updatedProducts, true );
	if ( is_array( $jProducts ) ) {
		$limit = $jProducts['Limit'];
		if($limit > 0) {
			$totalResults = $jProducts['TotalResults'];
			$pages        = ceil( $totalResults / $limit );
			$products     = $jProducts['Results'];
			foreach ( $products as $product ) {
				foreach ( $product['VariationIds'] as $variationId ) {
					epimaapi_create_product( $product['Id'], $variationId, $product['BulletText'], $product['Name'], $product['CategoryIds'], $product['PictureIds'] );
				}
			}
			for ( $i = 1; $i <= $pages; $i ++ ) {
				$start         = $i * $limit;
				$pagedProducts = get_epimaapi_all_changed_products_since_starting( $start, $yesterday );
				$jpProducts    = json_decode( $pagedProducts, true );
				$products      = $jpProducts['Results'];
				if ( is_array( $products ) ) {
					foreach ( $products as $product ) {
						foreach ( $product['VariationIds'] as $variationId ) {
							epimaapi_create_product( $product['Id'], $variationId, $product['BulletText'], $product['Name'], $product['CategoryIds'], $product['PictureIds'] );
						}
					}
				}
			}
		}
	}
	$time_elapsed_secs = microtime( true ) - $start;
	$log               .= 'Import took ' . $time_elapsed_secs . ' seconds.';
	//error_log('epimaapi_update_branch_stock_daily Import Took '.$time_elapsed_secs.' seconds');
	if ( ! ( false === get_option( 'epim_schedule_log' ) ) ) {
		update_option( 'epim_schedule_log', $log );
	}
}