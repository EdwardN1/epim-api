<?php

/**
 *
 * Pricing Functions
 *
 */

function wpiai_get_product_updates_page($restartRowID=0) {
    $url = get_option('wpiai_product_pricing_updates_api_url');
    $operator = get_option('wpiai_product_pricing_updates_operator');
    $lookbackExp = get_option('wpiai_product_pricing_updates_lookbackExp');
    $ionRespStyle = get_option('wpiai_product_pricing_updates_ionapiRespStyle');
    $url .= '?operator='.$operator.'&restartRowId='.$restartRowID.'&lookbackExp='.urlencode($lookbackExp).'&ionapiRespStyle='.$ionRespStyle;
    $request = 'get';
    return wpiai_get_infor_api_response($url,$request);
}

function wpiai_get_product_updates() {
	$api = wpiai_get_product_updates_page();
	$response = json_decode($api);
	$return = array();
	if(is_object($response)) {
		$apiResult = json_decode($response->result);
		if(is_object($apiResult)) {
            $restartRowID = $apiResult->restartRowId;
            $customerProducts = $apiResult->customerProducts;
            if(is_array($customerProducts)) {
                foreach ($customerProducts as $customerProduct) {
                    $return[] = $customerProduct;
                }
                if($restartRowID != '0') {
                    while ($restartRowID != '0') {

                        $api = wpiai_get_product_updates_page($restartRowID);
                        $response = json_decode($api);
                        if(is_object($response)) {
                            $apiResult = json_decode($response->result);
                            if(is_object($apiResult)) {
                                $restartRowID = $apiResult->restartRowId;
                                $customerProducts = $apiResult->customerProducts;
                                if(is_array($customerProducts)) {
                                    foreach ($customerProducts as $customerProduct) {
                                        $return[] = $customerProduct;
                                    }
                                } else {
                                    $restartRowID = 0;
                                }
                            } else {
                                $restartRowID = 0;
                            }
                        } else {
                            $restartRowID = 0;
                        }
                    }
                }
                return $return;
            } else {
                return false;
            }
        } else {
		    return false;
        }

	} else {
		return false;
	}

}

function getWarehouseName( $whse ) {
	$warehouseNames = get_option( 'wpiai_warehouse_names' );
	//error_log('$warehouseNames '.print_r($warehouseNames,true));
	$warehouseIDs   = get_option( 'wpiai_warehouse_ids' );
	//error_log('$warehouseIDs '.print_r($warehouseIDs,true));
	if(!is_array($warehouseIDs)) {
	    return false;
    }

	if(!array_search( $whse, $warehouseIDs )) {
		return false;
	}

	//error_log('Found '.$whse);

	return $warehouseNames[ array_search( $whse, $warehouseIDs ) ];
}

function getPricesQuantities( $response ) {
	//error_log(print_r($response,true));
	$res = array();
	if(is_array($response)) {
		foreach ( $response as $rec ) {
			if ( array_key_exists( 'whse', $rec ) ) {
				//error_log('Warehouse = '.$rec['whse']);
				$whseName = getWarehouseName( $rec['whse'] );
				if($whseName) {
					//error_log('Looking up product in warehouse '.$whseName);
					if ( array_key_exists( 'prod', $rec ) ) {
						if ( array_key_exists( 'price', $rec ) ) {
							if ( array_key_exists( 'netavail', $rec ) ) {
							    $productID = wpiai_get_product_id_by_sku($rec['prod']);
							    if($productID) {
                                    $resRec                  = array();
                                    $resRec['warehouseID']   = $rec['whse'];
                                    $resRec['warehouseName'] = $whseName;
                                    $resRec['SKU']           = $rec['prod'];
                                    $resRec['productID'] = $productID;
                                    $resRec['price']         = $rec['price'];
                                    $resRec['quantity']      = $rec['netavail'];
                                    $res[]                   = $resRec;
                                    //error_log(print_r($resRec,true));
                                }

							}
						}
					}
				} else {
					//$res['error'] = 'Warehouse does not exist - '.$rec['whse'];
				}
			}
		}
	} else {
		//$res['error'] = 'Response is empty';
	}

	return $res;
}

function createProductsRequest( $customer, $products ) {
    $wpiai_guest_customer_number = get_option('wpiai_guest_customer_number');
    if(!is_numeric($wpiai_guest_customer_number)) $wpiai_guest_customer_number = 9;
	$request = '{"request": {"companyNumber": 1,"operatorInit": "WOO",';
	if ( $customer != '' ) {
		$request .= '"customerNumber": ' . $customer . ',';
	} else {
		$request .= '"customerNumber": '.$wpiai_guest_customer_number.',';
	}
	$request .= '"getPriceBreaks": true,"useDefaultWhse": false,"sendFullQtyOnOrder": true,"checkOtherWhseInventory": true,"pricingMethod": "full","tOemultprcinV2": {"t-oemultprcinV2": [';
	if ( is_array( $products ) ) {
		$prodStr = '';
		foreach ($products as $product) {
			//error_log(print_r($product,true));
			$prodStr .= '{"seqno": 1,"prod": "' . $product . '","qtyord": 1,"unit": "EACH"},';
		}
		$prodStr = rtrim($prodStr,',');
		$request .= $prodStr;
	} else {
		$request .= '{"seqno": 1,"prod": "' . $products . '","qtyord": 1,"unit": "EACH"}';
	}
	$request .= ']}}}';
	return $request;
}

function getDefaultProductPrices($customer,$products) {
	$responseArray = getBranchStockAndPrice($customer,$products);
	$defaultWhse = get_option('wpiai_default_warehouse');
	$res = array();
	foreach ($responseArray as $item) {
		if($item['warehouseID'] == $defaultWhse) {
			$product = array();
			$product['SKU'] = $item['SKU'];
			$product['price'] = $item['price'];
			$res[] = $product;
		}
	}
	return $res;
}

function wpiai_get_product_id_by_sku($sku)
{
    global $wpdb;
    $product_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku));
    if ($product_id) return $product_id;
    return null;
}

function getBranchStockAndPrice($customer,$products) {
	$request = createProductsRequest($customer,$products);
	$url = get_option('wpiai_product_api_url');
	$response = wpiai_get_infor_api_response($url,$request);
	$allArray = json_decode($response,true);
	$stkArray = $allArray['response']['tOemultprcoutV2']['t-oemultprcoutV2'];
	return getPricesQuantities($stkArray);
}

function get_infor_price($price,$product) {
    $sku = $product->get_sku();
    $transient = get_transient('wpiai_default_product_price_'.$sku);
    if(!empty($transient)) {
        return $transient;
    }
	$prices = getDefaultProductPrices('',$sku);
	if($prices[0]['price']) {
        set_transient( 'wpiai_default_product_price_'.$sku, $prices[0]['price'], HOUR_IN_SECONDS );
		return $prices[0]['price'];
	} else {
		return $price;
	}
}

function check_customer_price($price,$product) {
	$userID = get_current_user_id();
	if($userID == 0) {
		return $price;
	}
	if(!metadata_exists('post',$product->get_id(),'wpiai_customer_price_'.$userID)) {
		return $price;
	}
	return get_post_meta($product->get_id(),'wpiai_customer_price_'.$userID,true);
}

add_filter('woocommerce_product_get_price', 'check_customer_price', 99, 2);
add_filter('woocommerce_product_get_regular_price', 'check_customer_price', 99, 2);

//add_filter('woocommerce_product_get_price', 'get_infor_price', 99, 2);
//add_filter('woocommerce_product_get_regular_price', 'get_infor_price', 99, 2);

/**
 *
 * Stock Functions
 *
 */

add_filter( 'woocommerce_product_tabs', 'wpiai_new_branchstock_tab' );
function wpiai_new_branchstock_tab( $tabs ) {
	// Add the new tab
	$tabs['branch_stock'] = array(
		'title'       => __( 'Branch Stock', 'text-domain' ),
		'priority'    => 50,
		'callback'    => 'wpiai_new_branchstock_tab_content'
	);
	return $tabs;
}

function wpiai_new_branchstock_tab_content() {
	// The new tab content
	global $product;
	$sku = $product->get_sku();
	$stock = getBranchStockAndPrice('',$sku);
	echo ('<table class="infor-branch-stock-table">');
	foreach ($stock as $item) {
		echo '<tr><td>'.$item['warehouseName'].'</td><td>'.$item['quantity'].'</td></tr>';
	}
	echo('</table>');
}