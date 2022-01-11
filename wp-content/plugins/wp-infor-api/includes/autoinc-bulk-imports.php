<?php

function _oi( $message, $name = 'oi' ) {
	//error_log( date( "Y-m-d H:i:s" ) . ': ' . $message . PHP_EOL, 3, '/home/erfelectrical/public_html/'.$name.'.log' );
}

function wpiai_product_blocks() {
	$wpiai_guest_customer_number = get_option( 'wpiai_guest_customer_number' );

	if ( $wpiai_guest_customer_number ) {
		$timeStart = microtime( true );
		$all_ids   = get_posts( array(
			'post_type'   => 'product',
			'numberposts' => - 1,
			'post_status' => 'publish',
			'fields'      => 'ids',
		) );
		$skus      = array();
		$skuBlocks = array();
		_oi( 'wpiai_product_blocks Number of products to update: ' . count( $all_ids ),'bulk-imports' );
		$i = 0;
		foreach ( $all_ids as $product_id ) {
			$product = wc_get_product( $product_id );
			if(!$product->get_manage_stock()) {
				$skus[]  = $product->get_sku();
				$i ++;
			}
			if ( $i >= 60 ) {
				$skuBlocks[] = $skus;
				$skus        = array();
				$i           = 0;
			}
		}
		if ( ! empty( $skus ) ) {
			$skuBlocks[] = $skus;
			//_oi(print_r($skuBlocks,true),'bulk-imports');
		}
		if ( empty( $skuBlocks ) ) {
			_oi( 'wpiai_product_blocks No Products Found','bulk-imports' );
			return false;
		}
		$timeEnd = microtime( true );
		$time    = $timeEnd - $timeStart;
		_oi( 'wpiai_product_blocks took ' . $time . ' seconds','bulk-imports' );

		return $skuBlocks;
	} else {
		_oi( 'wpiai_product_blocks No Default Customer','bulk-imports' );
	}

	return false;
}

function wpiai_import_customer_price_lists() {
	if(!wpiai_check_if_stopping('wpiai_import_customer_price_lists')) {
		$users = get_users();
		foreach ($users as $user) {
			$roles = $user->roles;
			if ( in_array( 'customer', $roles ) ) {
				$CSD_ID = get_user_meta( $user->ID, 'CSD_ID', true );
				if($CSD_ID) {
					if(wpiai_check_if_stopping('wpiai_import_customer_price_lists')) {
						_oi('wpiai_import_customer_price_lists aborted exiting...','bulk-imports');
						$running = get_option( 'wpiai_background_processes_running' );
						if(in_array('wpiai_import_customer_price_lists',$running)) {
							if (($key = array_search('wpiai_import_customer_price_lists', $running)) !== false) {
								unset($running[$key]);
							}
						}
						update_option('wpiai_background_processes_running',$running);
						return false;
					}
					wpiai_import_product_prices_and_stock_levels($CSD_ID);
				}
			}
		}
	} else {
		_oi('wpiai_import_customer_price_lists aborted exiting...','bulk-imports');
		$running = get_option( 'wpiai_background_processes_running' );
		if(in_array('wpiai_import_customer_price_lists',$running)) {
			if (($key = array_search('wpiai_import_customer_price_lists', $running)) !== false) {
				unset($running[$key]);
			}
		}
		update_option('wpiai_background_processes_running',$running);
		return false;
	}
	_oi('wpiai_import_customer_price_lists completed...','bulk-imports');
	$running = get_option( 'wpiai_background_processes_running' );
	if(in_array('wpiai_import_customer_price_lists',$running)) {
		if (($key = array_search('wpiai_import_customer_price_lists', $running)) !== false) {
			unset($running[$key]);
		}
	}
	update_option('wpiai_background_processes_running',$running);
	return true;
}

function wpiai_import_product_prices_and_stock_levels($customer='') {
	$sku_blocks = wpiai_product_blocks();
	$customer_id = false;
	if($customer != '') {
		$customer_id = get_organization_id($customer);
		if($customer_id) {
			_oi('Getting Customer Price List for CSD_ID: '.$customer.' WP_ID: '.$customer_id,'bulk-imports');
		}
	} else {
		_oi('Getting Default Customer Price Lists','bulk-imports');
	}
	if ( is_array( $sku_blocks ) ) {
		_oi( 'wpiai_import_default_product_prices_and_stock_levels found ' . count( $sku_blocks ) . ' to import','bulk-imports');
		$i = 1;
		foreach ( $sku_blocks as $sku_block ) {
			/*if($i > 1) {
				_oi('Debug just doing 1 block','bulk-imports');
				return;
			}*/
			if(wpiai_check_if_stopping('wpiai_import_customer_price_lists')) {
				_oi('wpiai_import_customer_price_lists aborted exiting...','bulk-imports');
				return false;
			}
			_oi( 'wpiai_import_default_product_prices_and_stock_levels Importing block ' . $i,'bulk-imports' );
			$timeStart                  = microtime( true );
			$defaultBranchStockAndPrice = getBranchStockAndPrice( $customer, $sku_block );
			$timeEnd                    = microtime( true );
			$time                       = $timeEnd - $timeStart;
			_oi( 'wpiai_import_default_product_prices_and_stock_levels getBranchStockAndPrice for block ' . $i . ' took ' . $time . ' seconds','bulk-imports' );
			$defaultwhse = get_option( 'wpiai_default_warehouse' );
			$timeStart                  = microtime( true );
			foreach ( $defaultBranchStockAndPrice as $item ) {
				if(wpiai_check_if_stopping('wpiai_import_customer_price_lists')) {
					_oi('wpiai_import_customer_price_lists aborted exiting...','bulk-imports');
					return false;
				}
				if ( $item['warehouseID'] == $defaultwhse ) {
					$product  = wc_get_product( $item['productID'] );
					if($customer=='') {
						$oldPrice = $product->get_price();
						$product->set_price( round( $item['price'], 2 ) );
						$product->set_regular_price( round( $item['price'], 2 ) ); // To be sure
						$product->set_manage_stock( true );
						$product->set_stock_quantity( $item['quantity'] );
						$product->save();
						_oi( 'Default price for : ' . $product->get_sku() . ' updated from ' . $oldPrice . ' to ' . $product->get_price() . ' Quantity set to ' . $product->get_stock_quantity(), 'bulk-imports' );
					} else {
						if($customer_id) {
							update_post_meta($product->get_id(),'wpiai_customer_price_'.$customer_id,$item['price']);
							_oi('Custom Price Imported '.'wpiai_customer_price_'.$customer_id.' for product '.$product->get_id(),'bulk-imports');
						} else {
							_oi('Invalid Customer '.$customer,'bulk-imports');
						}
					}
					//break;
				}
			}
			$timeEnd                    = microtime( true );
			$time                       = $timeEnd - $timeStart;
			_oi( 'wpiai_import_default_product_prices_and_stock_levels product imports for block ' . $i . ' took ' . $time . ' seconds','bulk-imports' );
			$i ++;
		}
	} else {
		_oi( 'wpiai_import_default_product_prices_and_stock_levels no blocks to import','bulk-imports' );
	}
	return true;
}

function wpiai_import_default_product_prices_and_stock_levels() {
	wpiai_import_product_prices_and_stock_levels();
}