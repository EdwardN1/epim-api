<?php

function _oi( $message ) {
	error_log( date( "Y-m-d H:i:s" ) . ': ' . $message . PHP_EOL, 3, '/var/www/html/oi.log' );
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
		_oi( 'wpiai_product_blocks Number of products to update: ' . count( $all_ids ) );
		$i = 0;
		foreach ( $all_ids as $product_id ) {
			$product = wc_get_product( $product_id );
			if(!$product->get_manage_stock()) {
				$skus[]  = $product->get_sku();
				$i ++;
			}
			if ( $i >= 100 ) {
				$skuBlocks[] = $skus;
				$skus        = array();
				$i           = 0;
			}
		}
		if ( ! empty( $skus ) ) {
			$skuBlocks[] = $skus;
			_oi(print_r($skuBlocks,true));
		}
		if ( empty( $skuBlocks ) ) {
			_oi( 'wpiai_product_blocks No Products Found' );

			return false;
		}
		$timeEnd = microtime( true );
		$time    = $timeEnd - $timeStart;
		_oi( 'wpiai_product_blocks took ' . $time . ' seconds' );

		return $skuBlocks;
	} else {
		_oi( 'wpiai_product_blocks No Default Customer' );
	}

	return false;
}

function wpiai_import_default_product_prices_and_stock_levels() {
	$sku_blocks = wpiai_product_blocks();
	if ( is_array( $sku_blocks ) ) {
		_oi( 'wpiai_import_default_product_prices_and_stock_levels found ' . count( $sku_blocks ) . ' to import' );
		$i = 1;
		foreach ( $sku_blocks as $sku_block ) {
			_oi( 'wpiai_import_default_product_prices_and_stock_levels Importing block ' . $i );
			$timeStart                  = microtime( true );
			$defaultBranchStockAndPrice = getBranchStockAndPrice( '', $sku_block );
			$timeEnd                    = microtime( true );
			$time                       = $timeEnd - $timeStart;
			_oi( 'wpiai_import_default_product_prices_and_stock_levels getBranchStockAndPrice for block ' . $i . ' took ' . $time . ' seconds' );
			$defaultwhse = get_option( 'wpiai_default_warehouse' );
			$timeStart                  = microtime( true );
			foreach ( $defaultBranchStockAndPrice as $item ) {
				if ( $item['warehouseID'] == $defaultwhse ) {
					$product  = wc_get_product( $item['productID'] );
					$oldPrice = $product->get_price();
					$product->set_price( round( $item['price'], 2 ) );
					$product->set_regular_price( round( $item['price'], 2 ) ); // To be sure
					$product->set_manage_stock( true );
					$product->set_stock_quantity( $item['quantity'] );
					$product->save();
					_oi( 'Price for : ' . $product->get_sku() . ' updated from ' . $oldPrice . ' to ' . $product->get_price() . ' Quantity set to ' . $product->get_stock_quantity() );
					//break;
				}
			}
			$timeEnd                    = microtime( true );
			$time                       = $timeEnd - $timeStart;
			_oi( 'wpiai_import_default_product_prices_and_stock_levels product imports for block ' . $i . ' took ' . $time . ' seconds' );
			$i ++;
		}
	} else {
		_oi( 'wpiai_import_default_product_prices_and_stock_levels no blocks to import' );
	}
}