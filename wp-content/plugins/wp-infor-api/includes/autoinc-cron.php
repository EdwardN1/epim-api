<?php
register_activation_hook(wpiai_PLUGINFILE, 'wpiai_cron_activation');

function wpiai_cron_activation()
{
    //error_log( 'Running wpiai_cron_activation' );
    _oi('Running wpiai_cron_activation', 'cron');
    if (!wp_next_scheduled('wpiai_every_minute_action')) {
        wp_schedule_event(time(), 'everyminute', 'wpiai_every_minute_action');
    }
    if (!wp_next_scheduled('wpiai_every_twenty_minutes_action')) {
        wp_schedule_event(time(), 'everytwentyminutes', 'wpiai_every_twenty_minutes_action');
    }
	if (!wp_next_scheduled('wpiai_every_hour_action')) {
		wp_schedule_event(time(), 'hourly', 'wpiai_every_hour_action');
	}
    if (!wp_next_scheduled('wpiai_every_day_action')) {
        wp_schedule_event(time(), 'daily', 'wpiai_every_day_action');
    }
}

add_action('wpiai_every_minute_action', 'wpiai_do_every_minute');
add_action('wpiai_every_twenty_minutes_action', 'wpiai_do_every_twenty_minutes');
add_action('wpiai_every_day_action', 'wpiai_do_every_day');
add_action('wpiai_every_hour_action', 'wpiai_do_every_hour');

function wpiai_do_every_hour() {
	//wpiai_import_all_files();
}

function wpiai_do_every_day()
{
    //do something every day
    //error_log('WP Cron is working....Every day Event');
    //wpiai_process_updated_products();
    //$x = get_organization_contact_details(get_customer_organization(45));
    //$x = get_customer_details(45);
    //error_log(print_r($x,true));
    /*$contactDetails = array();
    $contactDetails['first_name'] = 'first_name Test 17';
    $contactDetails['last_name'] = 'last_name Test 1';
    $contactDetails['address_line_1'] = 'contact_addr_1 Test 17';
    $contactDetails['address_line_2'] = 'contact_addr_2 Test 17';
    $x = set_organization_contact_details(get_customer_organization(45),0,$contactDetails);
    if($x) {
        error_log('Test Contact Updated');
    } else {
        error_log('Contact Not Updated');
    }*/
    //wpiai_equalize_shiptos(50);
    //wpiai_equalize_last_shiptos(48);
    /*$shippingDetails = array();

    $shippingDetails['phone'] = 'Penn Studio X';
    $shippingDetails['company_name'] = 'Penn Studio X';
    $shippingDetails['address_line_1'] = 'Penn Studio X';
    $shippingDetails['town_city'] = 'Penn Studio X';
    $shippingDetails['postcode'] = 'Penn Studio X';
    $x = set_organization_shipping_details(get_customer_organization(48),'1000912376-1',$shippingDetails);
    if($x) {
        error_log('Test ShipTo Updated');
    } else {
        error_log('ShipTo Not Updated');
    }*/

    /*update_user_meta(45,'wpiai_delivery_addresses',array());
    update_user_meta(45,'wpiai_last_delivery_addresses',array());*/
    /*$users_updated          = get_option( 'wpiai_users_updated' );
    if ( ! is_array( $users_updated ) ) {
        $users_updated = array();
    }
    $users_updated[] = '54';
    if ( ! update_option( 'wpiai_users_updated', $users_updated ) ) {
        error_log( 'UserID not saved: ' . '54' );
    } else {
        error_log( '54' . ' added to the meta update queue' );
    }*/

    /*$x = getAccountBalances('10324');
    error_log(print_r($x, true));*/

    //get_customer_invoices('10324','2021-01-01','2021-04-26');

    /*error_log(get_organization_id('1000912376'));
    error_log(get_contant_customer_csd_id( 6878 ));
    error_log(get_contant_customer_csd_id( 6844 ));
    error_log(get_contant_customer_csd_id( 6833 ));
    error_log(get_contant_customer_csd_id( 6830 ));
    error_log(get_contant_customer_csd_id( 6879 ));
    error_log(get_contant_customer_csd_id( 6876 ));*/

    /*error_log(get_csd_order_id(61900));*/
    /*$order = wc_get_order( 63105 );
    error_log($order->get_shipping_method());*/
    /*error_log(createContactsAPIRequest(
        1000912376,
        'Mr',
        'Edward',
        'Nickerson',
        'edward223399@4477yyhh.org',
        '0124589631',
        'Addr1',
        'Addr2',
        'Addr3',
        'City',
        'LN4 5HD'
    ));*/

    /*error_log(createCSDContact(
	    10324,
        'Mr',
        'Edward',
        'Nickerson',
        'edward223399@4477ffkkyyhh.org',
        '0124589631',
        'Addr1',
        'Addr2',
        'Addr3',
        'City',
        'LN4 5HD'
    ));*/

    /*error_log(returnCSDShipToID('Set#: 1 Update Successful, Cono: 1 Customer #: 1000912376, Ship To: 6fa61c7aab8de'));*/
    /*$organizationID = '1000912376';
    $customerID = '48';
    $company = 'Technicks So.lutuions';
    $address_1 = 'Addr1';
    $address_2 = 'Addr2';
    $address_3 = 'Addr3';
    $city = 'City';
    $post_code = 'LN2 5TB';
    error_log(createCSDShipTo($organizationID,$customerID,$company,$address_1,$address_2,$address_3,$city,$post_code) );*/
    //wpiai_import_product_prices_and_stock_levels( ' 10324 ' );
    //wpiai_import_default_product_prices_and_stock_levels();
    //wpiai_equalize_shiptos(150);
    //wpiai_update_csd_ship_tos( 150 );
    //wpiai_start('wpiai_import_customer_price_lists');
    //wpiai_stop('wpiai_import_customer_price_lists');
    /*error_log('Processes Running: '.print_r(get_option('wpiai_background_processes_running'),true));
    error_log('Processes Stopping: '.print_r(get_option('wpiai_background_processes_to_stop'),true));
    error_log('Processes Starting: '.print_r(get_option('wpiai_background_processes_to_start'),true));
    error_log('Is It Stopping?: '.wpiai_check_if_stopping('wpiai_import_customer_price_lists'));*/
    //update_option('wpiai_background_processes_to_start','');
    //update_option('wpiai_background_processes_to_stop','');
    //update_option('wpiai_background_processes_running','');

	//error_log(print_r(get_csd_backorders(141055),true));

	///web/wp-content/plugins/wp-infor-api/price_imports/mail122630.csv

	//wpiai_import_price_file('/var/www/html/wp-content/plugins/wp-infor-api/price_imports/testfile.csv');

	//wpiai_import_all_files();

	wpiai_check_user_meta();
    wpiai_process_cached_orders();
	if (!wp_next_scheduled('wpiai_every_minute_action')) {
		wp_schedule_event(time(), 'everyminute', 'wpiai_every_minute_action');
	}
}

function wpiai_do_every_minute()
{
    // do something every minute
    //error_log('WP Cron is working....Every Minute Event');
    wpiai_check_user_meta();
    //wpiai_check_processes();
    wpiai_process_cached_orders();
    //wpiai_clear_old_contacts(45);
    //wpiai_reset_old_contacts(45);
    //wpiai_process_products(150,1200,true);
}

function wpiai_do_every_twenty_minutes()
{
    // do something every twenty minutes
    //error_log('WP Cron is working....');
    wpiai_process_updated_products();
}

add_filter('cron_schedules', 'wpiai_add_cron_interval');
function wpiai_add_cron_interval($schedules)
{
    $schedules['everyminute'] = array(
        'interval' => 60, // time in seconds
        'display' => 'Every Minute'
    );
    $schedules['everytwentyminutes'] = array(
        'interval' => 1200, // time in seconds
        'display' => 'Every Twenty Minutes'
    );

    return $schedules;
}

function wpiai_process_cached_orders()
{
    $delay = get_option('wpiai_cache_ttl');
    if(!is_numeric($delay)) {
        return;
    }
     //error_log('Delay is numeric');

    $headers = array(
        'Content-Type' => 'application/json; charset=utf-8',
        'Authorization' => 'Basic ' . base64_encode( get_option('wpiai_consumer_key') . ':' . get_option('wpiai_consumer_secret') )
    );

    // WP_Query arguments
    $coargs = array(
        'post_type' => array('wpiai_cached_orders'),
	    'posts_per_page' => -1
    );

    $orders = new WP_Query($coargs);

    $deleteOrders = array();

    if($orders->have_posts()) {
        //error_log('Found orders to process');
        while ($orders->have_posts()) {
            $orders->the_post();
            $CSD_ID = get_the_title();
            $order_ID = get_the_ID();
            $request = get_post_meta($order_ID,'_wpiai_cached_order_request',true);
            $time_stamp = get_post_meta($order_ID,'_wpiai_cached_order_time_stamp',true);
            $now = time();
            if(is_numeric($time_stamp)) {
                //error_log('timestamp is numeric');
                $time_alive = $now - $time_stamp;
                error_log('Processing order: '.$CSD_ID);
                error_log('Time to Live = '.($delay-$time_alive).' seconds');
               //error_log('$now = '.$now);
               // error_log(gmdate("M d Y H:i:s", $now));
                //error_log('$time_stamp = '.$time_stamp);
                //error_log(gmdate("M d Y H:i:s", $time_stamp));
                //error_log('$delay = '.$delay);
                if($time_alive >= $delay) {
                    $pargs = array(
                        'headers' => $headers,
                        'body' => $request,
                    );
                    $response = wp_remote_post(get_site_url().'/wp-json/wc/v3/orders',$pargs);
                    if(is_wp_error($response)) {
                        error_log($response->get_error_message());
                    } else {
                        $woo_order = json_decode($response['body']);
                        $url        = get_option( 'wpiai_sales_order_url' );
                        $parameters = get_option( 'wpiai_sales_order_change_parameters' );
                        $pRequest   = get_customer_param_record_x( $parameters );
                        $xmlRequest = wpiai_get_order_XML( $woo_order->id, 'Change' );
                        $updated    = wpiai_get_infor_message_multipart_message( $url, $pRequest, $xmlRequest );
                        if ( $updated ) {
                            //error_log( 'Order: ' . $order_id . ' Added' );
                            _oi( 'Order: ' . $woo_order->id . ' Added','orders' );
                        }
                        $deleteOrders[] = $order_ID;
                        error_log('Order: '.$CSD_ID. ' has been processed');
                    }
                }
            }
        }
    }

    wp_reset_postdata();

    foreach ($deleteOrders as $deleteOrder) {
        wp_delete_post($deleteOrder);
    }

}

function wpiai_get_woo_skus_ids($max, $seconds)
{
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1
    );
    $loop = new WP_Query($args);
    $res = array();
    $i = 1;
    if ($loop->have_posts()):
        while ($loop->have_posts()): $loop->the_post();
            $id = get_the_id();
            $product = wc_get_product($id);
            if ($max > 0) {
                $product_price_updated = get_post_meta($id, 'product_price_updated', true);
                if ($product_price_updated == '') {
                    $product_price_updated = 0;
                }
                $now = time();
                if (is_numeric($seconds)) {
                    if (($now - $product_price_updated) > $seconds) {
                        $sku = $product->get_sku();
                        error_log('$sku = ' . $sku);
                        $res[] = array(
                            'id' => $id,
                            'sku' => $sku
                        );
                    }
                } else {
                    $sku = $product->get_sku();
                    error_log('!is_numeric($seconds) $sku = ' . $sku);
                    $res[] = array(
                        'id' => $id,
                        'sku' => $sku
                    );
                }

                if ($max < $i) {
                    return $res;
                }
                $i++;
            } else {
                $sku = $product->get_sku();
                error_log('$max !> 0, $sku = ' . $sku);
                $res[] = array(
                    'id' => $id,
                    'sku' => $sku
                );
            }

        endwhile;
    endif;
    wp_reset_postdata();

    return $res;
}


function wpiai_process_updated_products()
{
    $timeStart = microtime(true);
    $updatedProducts = wpiai_get_product_updates();
    //error_log(print_r($updatedProducts,true));

    if (is_array($updatedProducts)) {
        $defaultPricesFor = array();
        $customerPricesFor = array();
        foreach ($updatedProducts as $updatedProduct) {
            if (is_object($updatedProduct)) {
                if ($updatedProduct->product) {
                    $wpiai_guest_customer_number = get_option('wpiai_guest_customer_number');

                    if (($updatedProduct->customer == 0) || ($updatedProduct->customer == $wpiai_guest_customer_number)) {
                        //set default price.
                        //error_log(print_r($updatedProduct['product'],true));
                        //$productID = wpiai_get_product_id_by_sku($updatedProduct->product);
                        //if($productID) {
                        $defaultPricesForRec = array();
                        //$defaultPricesForRec['product'] = $productID;
                        $defaultPricesForRec['product_CSD_ID'] = $updatedProduct->product;
                        $defaultPricesFor[] = $defaultPricesForRec;
                        //}
                    } else {
                        //set customer's price
                        //$productID = wpiai_get_product_id_by_sku($updatedProduct->product);
                        //if($productID) {
                        $users = get_users(array(
                                'meta_key' => 'CSD_ID',
                                'meta_value' => $updatedProduct->customer,
                                'number' => 1,
                                'count_total' => false
                            )
                        );
                        if ($users) {
                            if (is_object($users[0])) {
                                $user = $users[0];
                                $customerPricesForRec = array();
                                $customerPricesForRec['customer'] = $user->ID;
                                //$customerPricesForRec['product'] = $productID;
                                $customerPricesForRec['product_CSD_ID'] = $updatedProduct->product;
                                $customerPricesForRec['customer_CSD_ID'] = $updatedProduct->customer;
                                $customerPricesFor[] = $customerPricesForRec;
                            }
                        }
                        //}

                    }
                }
            }
        }


        $defaultwhse = get_option('wpiai_default_warehouse');
        if ($defaultwhse) {
            $productsList = array();
            foreach ($defaultPricesFor as $defaultPrice) {
                $productsList[] = $defaultPrice['product_CSD_ID'];
            }
            $defaultBranchStockAndPrice = getBranchStockAndPrice('', $productsList);
            foreach ($defaultBranchStockAndPrice as $item) {
                if ($item['warehouseID'] == $defaultwhse) {
                    $product = wc_get_product($item['productID']);
                    $oldPrice = $product->get_price();
                    $product->set_price(round($item['price'], 2));
                    $product->set_regular_price(round($item['price'], 2)); // To be sure
                    $product->save();
                    //error_log('Price for : '.$product->get_sku().' updated from '.$oldPrice.' to '.$product->get_price());
                    //break;
                }
            }
            $customerProductList = array();
            foreach ($customerPricesFor as $customerPrice) {
                $customerProductItem = array();
                $customerProductItem['userID'] = $customerPrice['customer'];
                $customerProductItem['customer_CSD_ID'] = $customerPrice['customer_CSD_ID'];
                $customerProductItem['productData'] = getBranchStockAndPrice($customerPrice['customer_CSD_ID'], $customerPrice['product_CSD_ID']);
                $customerProductList[] = $customerProductItem;
            }
            foreach ($customerProductList as $customerProduct) {
                $metaField = 'wpiai_customer_price_' . $customerProduct['userID']; // Woo ID
                $productData = $customerProduct['productData'];

                if (is_array($productData)) {
                    foreach ($productData as $productDatum) {
                        if ($productDatum['warehouseID'] == $defaultwhse) {
                            update_post_meta($productDatum['productID'], $metaField, $productDatum['price']);
                        }
                    }
                }

            }
            //error_log(print_r($customerProductList, true));
        }

    }


    $timeEnd = microtime(true);
    $time = $timeEnd - $timeStart;
    error_log('wpiai_process_updated_products took ' . $time . ' seconds');
}

/*function wpiai_process_updated_products_x() {
	$timeStart       = microtime( true );
	$updatedProducts = json_decode( wpiai_get_product_updates() );
	//error_log(print_r($updatedProducts,true));
	if ( is_object( $updatedProducts ) ) {
		if ( is_array( $updatedProducts->customerProducts ) ) {
			$productArray = array();
			foreach ( $updatedProducts->customerProducts as $product ) {
				//error_log( 'Product =' . $product->product );

				$productArray[] = $product->product;
			}
			$customers = get_users( array( 'role__in' => array( 'customer' ) ) );
			foreach ( $customers as $customer ) {
				$user_id = $customer->ID;
				$CSD_ID  = get_user_meta( $user_id, 'CSD_ID', true );
				error_log( 'Getting Price List for: ' . $user_id );
				$productPrices = getBranchStockAndPrice( $CSD_ID, $productArray );
				//error_log(print_r($productPrices,true));
				$defaultWhse        = get_option( 'wpiai_default_warehouse' );
				$productUpdateArray = array();
				foreach ( $productPrices as $productPrice ) {
					if ( $productPrice['warehouseID'] == $defaultWhse ) {
						$price                = array();
						$price['customer_id'] = $user_id;
						$price['price']       = $productPrice['price'];
						$price['SKU']         = $productPrice['SKU'];
						$price['product_id']  = wpiai_get_product_id_by_sku( $productPrice['SKU'] );
						$productUpdateArray[] = $price;
					}
				}
				error_log( print_r( $productUpdateArray, true ) );
			}
		} else {
			error_log( '$updatedProducts->product is not an array' );
		}
	} else {
		error_log( '$updatedProducts not an object' );
	}
	$timeEnd = microtime( true );
	$time    = $timeEnd - $timeStart;
	error_log( 'wpiai_process_updated_products took ' . $time . ' seconds' );
}*/

function wpiai_process_products($max, $seconds, $log = false)
{
    $timeStart = microtime(true);
    $products = wpiai_get_woo_skus_ids($max, $seconds);
    $productSKUs = array();
    $lastProduct = 'no product sku';
    foreach ($products as $product) {
        $lastProduct = $product['sku'];
        if ($product['id'] > 0) {
            $productSKUs[] = $product['sku'];
            $lastProduct = $product['sku'];
            if (!update_post_meta($product['id'], 'product_price_updated', time())) {
                if ($log === true) {
                    error_log('Product ' . $product['sku'] . ' error setting or not changed product_price_updated');
                }
            }
        }
    }
    $prices = getDefaultProductPrices('', $productSKUs);
    foreach ($prices as $price) {
        $sku = $price['SKU'];
        $p = $price['price'];
        $k = array_search($sku, $products);
        if ($k) {
            $id = $products[$k]['id'];
            $wooProduct = wc_get_product($id);
            if ($log === true) {
                $oldPrice = $wooProduct->get_price();
                error_log('Product $sku old price = ' . $oldPrice . ' new price = ' . $p);
            }
            $wooProduct->set_regular_price($p);
            $wooProduct->set_price($p);
            $wooProduct->save();
            if (!update_post_meta($id, 'product_price_updated', time())) {
                if ($log === true) {
                    error_log('Product ' . $sku . ' error setting or not changed product_price_updated');
                }
            }
        }
    }

    $timeEnd = microtime(true);
    if ($log === true) {
        $time = $timeEnd - $timeStart;
        error_log('wpiai_process_products took ' . $time . ' seconds. Last Product = ' . $lastProduct);
    }

}


function wpiai_array_equal($a, $b)
{
    //_oi(print_r(array_diff_assoc( $a, $b )));
    return (
        is_array($a)
        && is_array($b)
        && count($a) == count($b)
        && array_diff_assoc($a, $b) === array_diff_assoc($b, $a)
    );
}

function wpiai_different_array_indexes($array1, $array2)
{
    $r = array();
    if (!is_array($array1) || !is_array($array2)) {
        //error_log('Need two arrays to compare');
        return false;
    }
    if (count($array1) <> count($array2)) {
        _oi('Compared Arrays are of different sizes, can not compare these');

        return false;
    }
    $i = 0;
    foreach ($array1 as $array1_item) {
        if (!wpiai_array_equal($array1_item, $array2[$i])) {
            $r[] = $i;
        }
        $i++;
    }
    if (count($r) > 0) {
        return $r;
    }

    _oi('Arrays are identical');

    return false;
}

function wpiai_equalize_shiptos($user_id)
{
    $wpiai_delivery_addresses = get_user_meta($user_id, 'wpiai_delivery_addresses', true);
    if (is_array($wpiai_delivery_addresses)) {
        update_user_meta($user_id, 'wpiai_last_delivery_addresses', $wpiai_delivery_addresses);
    }
}

function wpiai_equalize_last_shiptos($user_id)
{
    $wpiai_delivery_addresses = get_user_meta($user_id, 'wpiai_last_delivery_addresses', true);
    if (is_array($wpiai_delivery_addresses)) {
        update_user_meta($user_id, 'wpiai_delivery_addresses', $wpiai_delivery_addresses);
    }
}

function wpiai_clear_old_contacts($user_id)
{
    $blank_array = array();
    update_user_meta($user_id, 'wpiai_last_contacts', $blank_array);
}

function wpiai_reset_old_contacts($user_id)
{
    $contacts = get_user_meta($user_id, 'wpiai_contacts', true);
    if (is_array($contacts)) {
        update_user_meta($user_id, 'wpiai_last_contacts', $contacts);
    }
}

function wpiai_update_csd_contacts($user_id)
{
    $contactRecs = get_user_meta($user_id, 'wpiai_contacts', true);
    if (is_array($contactRecs)) {
        $oldContacts = get_user_meta($user_id, 'wpiai_last_contacts', true);
        if (is_array($oldContacts)) {
            //Check for added contacts
            if (count($contactRecs) <> count($oldContacts)) {
                foreach ($contactRecs as $contact_rec) {
                    $contact_CONTACT_ID = $contact_rec['contact_CONTACT_ID'];
                    $found = false;
                    foreach ($oldContacts as $oldContact) {
                        if ($oldContact['contact_CONTACT_ID'] == $contact_CONTACT_ID) {
                            $found = true;
                        }
                    }
                    if (!$found) {
                        $oldContacts[] = $contact_rec;
                    }
                }
            }
            $changedContacts = wpiai_different_array_indexes($contactRecs, $oldContacts);
            if ($changedContacts) {
                $contactRec_url = get_option('wpiai_contact_url');
                $contactRec_paramaters = set_messageid(get_option('wpiai_contact_parameters'));
                foreach ($changedContacts as $changedContact) {
                    $contactRec_xml = get_contact_XML_record($user_id, 'Change', $contactRecs[$changedContact]);
                    //error_log('Contact Change: '.$contactRecs[$changedContact]['contact_CONTACT_ID']);
                    $updated = wpiai_get_infor_message_multipart_message($contactRec_url, $contactRec_paramaters, $contactRec_xml);
                }
                update_user_meta($user_id, 'wpiai_last_contacts', $contactRecs);
            } else {
                //error_log('No contacts to update');
            }
        }
        /*foreach ($contactRecs as $contact_rec) {
            if($contact_rec['contact_CONTACT_ID']=='') {
                $contactRec_url = get_option('wpiai_contact_url');
                $contactRec_paramaters = set_messageid(get_option('wpiai_contact_parameters'));
                $contactRec_xml = get_contact_XML_record($user_id, 'Change', $contact_rec);
                $updated = wpiai_get_infor_message_multipart_message($contactRec_url, $contactRec_paramaters, $contactRec_xml);
            }
        }*/
    }
}

function wpiai_update_csd_ship_tos($user_id)
{
    _oi('wpiai_update_csd_ship_tos for ' . $user_id);
    $shiptoRecs = get_user_meta($user_id, 'wpiai_delivery_addresses', true);
    if (is_array($shiptoRecs)) {
        $oldShipTos = get_user_meta($user_id, 'wpiai_last_delivery_addresses', true);
        if (is_array($oldShipTos)) {
            //Check for added contacts
            if (count($shiptoRecs) <> count($oldShipTos)) {
                foreach ($shiptoRecs as $shipTo_rec) {
                    $delivery_UNIQUE_ID = $shipTo_rec['delivery_UNIQUE_ID'];
                    $found = false;
                    foreach ($oldShipTos as $oldShipTo) {
                        if ($oldShipTo['delivery_UNIQUE_ID'] == $delivery_UNIQUE_ID) {
                            $found = true;
                        }
                    }
                    if (!$found) {
                        $oldShipTos[] = $shipTo_rec;
                    }
                }
            } else {
                _oi('No ShipTo Recs Added or Removed');
            }
            $changedShipTos = wpiai_different_array_indexes($shiptoRecs, $oldShipTos);
            if ($changedShipTos) {
                $shipToRec_url = get_option('wpiai_ship_to_url');
                $shipToRec_paramaters = set_messageid(get_option('wpiai_ship_to_parameters'));
                foreach ($changedShipTos as $changedShipTo) {
                    $shipToRec_xml = get_shipTo_XML_record($user_id, 'Change', $shiptoRecs[$changedShipTo]);
                    //error_log('Contact Change: '.$shiptoRecs[$changedShipTo]['contact_CONTACT_ID']);
                    _oi('ShipTo Change: ' . $shiptoRecs[$changedShipTo]['delivery-CSD-ID']);
                    $updated = wpiai_get_infor_message_multipart_message($shipToRec_url, $shipToRec_paramaters, $shipToRec_xml);
                }
                update_user_meta($user_id, 'wpiai_last_delivery_addresses', $shiptoRecs);
            } else {
                //error_log('No contacts to update');
            }
        } else {
            _oi('No old shipto records for ' . $user_id . '. Updating CSD ShipTos with ' . count($shiptoRecs) . ' Shiptos');
            foreach ($shiptoRecs as $shipTo_rec) {
                $shipToRec_url = get_option('wpiai_ship_to_url');
                $shipToRec_paramaters = set_messageid(get_option('wpiai_ship_to_parameters'));
                $shipToRec_xml = get_shipTo_XML_record($user_id, 'Change', $shipTo_rec);
                $updated = wpiai_get_infor_message_multipart_message($shipToRec_url, $shipToRec_paramaters, $shipToRec_xml);
            }
            update_user_meta($user_id, 'wpiai_last_delivery_addresses', $shiptoRecs);
        }
    }
}


function wpiai_process_user_shiptos($user_id)
{
    error_log('Processing ShipTos for user_id: ' . $user_id);
    $shiptoRec_meta = get_user_meta($user_id, 'wpiai_delivery_addresses', true);
    $shipToRec = array();
    $shipToAdd = array();
    //$shipToChange = array();
    $hasShipping = is_array($shiptoRec_meta);
    if ($hasShipping) {
        if (empty($shiptoRec_meta)) {
            $hasShipping = false;
        }
    }
    $add_first_shipping = false;
    if ($hasShipping) {
        foreach ($shiptoRec_meta as $shipToRec_m) {
            $shipToRec_rec = $shipToRec_m;
            if (!array_key_exists('delivery-CSD-ID', $shipToRec_rec)) {
                $shipToRec_rec['delivery-CSD-ID'] = '';
            }
            if (!array_key_exists('delivery_UNIQUE_ID', $shipToRec_rec)) {
                $shipToRec_rec['delivery_UNIQUE_ID'] = '';
            }
            if (($shipToRec_rec['delivery-CSD-ID'] == '')) {
                //$shipToRec_rec['contact_CREATED_BY'] = 'WOO';
                if ($shipToRec_rec['delivery_UNIQUE_ID'] == '') {
                    $shipToRec_rec['delivery_UNIQUE_ID'] = uniqid();
                }
                $shipToAdd[] = $shipToRec_rec;
            } else {
                //$shipToRec_rec['contact_CREATED_BY'] = 'EXTERNAL';
            }
            if ($shipToRec_rec['delivery_UNIQUE_ID'] == '') {
                $shipToRec_rec['delivery_UNIQUE_ID'] = uniqid();
                $shipToChange[] = $shipToRec;
            }
            $shipToRec[] = $shipToRec_rec;
            //$lastContactRec[] = $shipToRec_rec;
        }
    } else {
        //error_log('No Contact Meta');
        $user_CSD_ID = get_user_meta($user_id, 'CSD_ID', true);
        if ($user_CSD_ID != '') {
            $customer = new WC_Customer($user_id);
            $shipping_company = $customer->get_shipping_company();
            $shipping_address_1 = $customer->get_shipping_address_1();
            $shipping_address_2 = $customer->get_shipping_address_2();
            $shipping_city = $customer->get_shipping_city();
            $shipping_state = $customer->get_shipping_state();
            $shipping_postcode = $customer->get_shipping_postcode();
            $shipping_country = $customer->get_shipping_country();
            $first_shipping = array();
            $first_shipping['delivery-company-name'] = $shipping_company;
            $first_shipping['delivery-country'] = $shipping_country;
            $first_shipping['delivery-street-address-1'] = $shipping_address_1;
            $first_shipping['delivery-street-address-2'] = $shipping_address_2;
            $first_shipping['delivery-town-city'] = $shipping_city;
            //$first_shipping['delivery-street-address-3'] = $shipping_state;
            $first_shipping['delivery-postcode'] = $shipping_postcode;
            $first_shipping['delivery-phone'] = '';
            $first_shipping['delivery_UNIQUE_ID'] = uniqid();
            $shipToRec[] = $first_shipping;
            $add_first_shipping = true;
        }
    }
    //update_user_meta($user_id, 'wpiai_last_contacts', $lastContactRec);
    if (!update_user_meta($user_id, 'wpiai_delivery_addresses', $shipToRec)) {
        error_log('Contact wpiai_delivery_addresses Failed or not changed for $user_id: ' . $user_id);
        wpiai_update_csd_ship_tos($user_id);
    } else {
        $shipToRec_url = get_option('wpiai_ship_to_url');
        $shipToRec_paramaters = set_messageid(get_option('wpiai_ship_to_parameters'));

        if ((count($shipToAdd) > 0)) {
            $wpiai_last_delivery_addresses = get_user_meta($user_id, 'wpiai_last_delivery_addresses', true);
            if (!is_array($wpiai_last_delivery_addresses)) {
                $wpiai_last_delivery_addresses = array();
            }
            foreach ($shipToAdd as $add_shipto) {
                $wpiai_last_delivery_addresses[] = $add_shipto;
                $shipToRec_xml = get_shipTo_XML_record($user_id, 'Add', $add_shipto);
                //error_log('shipto Add');
                $updated = wpiai_get_infor_message_multipart_message($shipToRec_url, $shipToRec_paramaters, $shipToRec_xml);
            }
            update_user_meta($user_id, 'wpiai_last_delivery_addresses', $wpiai_last_delivery_addresses);
        }

        /*if ( $add_first_shipping ) {
            $shipToRec_xml                   = get_shipTo_XML_record( $user_id, 'Add', $add_shipto );
            $updated = wpiai_get_infor_message_multipart_message( $shipToRec_url, $shipToRec_paramaters, $shipToRec_xml );
        }*/

        /*if ( ( count( $shipToChange ) > 0 ) ) {
            $wpiai_last_delivery_addresses = get_user_meta( $user_id, 'wpiai_delivery_addresses', true );
            foreach ( $shipToChange as $add_shipto ) {
                $wpiai_last_delivery_addresses[] = $add_shipto;
                $shipToRec_xml                   = get_shipTo_XML_record( $user_id, 'Change', $add_shipto );
                $updated = wpiai_get_infor_message_multipart_message( $shipToRec_url, $shipToRec_paramaters, $shipToRec_xml );
            }
            update_user_meta( $user_id, 'wpiai_last_delivery_addresses', $wpiai_last_delivery_addresses );
        }*/


        wpiai_update_csd_ship_tos($user_id);
    }
    error_log('Finished Processing ShipTos for user_id: ' . $user_id);
}

function wpiai_process_user_contacts($user_id)
{
    error_log('Processing Contacts for user_id: ' . $user_id);
    $contactRec_meta = get_user_meta($user_id, 'wpiai_contacts', true);
    //$lastContactRec_meta = get_user_meta($user_id, 'wpiai_last_contacts', true);
    $contactRec = array();
    //$lastContactRec = array();
    /*if(is_array($lastContactRec_meta)) {
        foreach ($lastContactRec_meta as $lastContactRec_m) {
            $lastContactRec[] = $lastContactRec_m;
        }
    }*/
    $contactAdd = array();
    $hasContacts = is_array($contactRec_meta);
    if ($hasContacts) {
        if (empty($contactRec_meta)) {
            $hasContacts = false;
        }
    }
    $add_first_contact = false;
    if ($hasContacts) {
        foreach ($contactRec_meta as $contactRec_m) {
            $contactRec_rec = $contactRec_m;
            if (($contactRec_rec['contact_CSD_ID'] == '') || (!array_key_exists('contact_CSD_ID', $contactRec_rec))) {
                //$contactRec_rec['contact_CREATED_BY'] = 'WOO';
                if (($contactRec_rec['contact_CONTACT_ID'] == '') || (!array_key_exists('contact_CONTACT_ID', $contactRec_rec))) {
                    $contactRec_rec['contact_CONTACT_ID'] = uniqid();
                    $contactAdd[] = $contactRec_rec;
                }
            } else {
                //$contactRec_rec['contact_CREATED_BY'] = 'EXTERNAL';
            }
            if (($contactRec_rec['contact_CONTACT_ID'] == '') || (!array_key_exists('contact_CONTACT_ID', $contactRec_rec))) {
                $contactRec_rec['contact_CONTACT_ID'] = uniqid();
            }
            $contactRec[] = $contactRec_rec;
            //$lastContactRec[] = $contactRec_rec;
        }
    } else {
        error_log('No Contact Meta');
        $user_CSD_ID = get_user_meta($user_id, 'CSD_ID', true);
        if ($user_CSD_ID != '') {
            error_log('Creating First Contact..');
            $customer = new WC_Customer($user_id);
            $user_email = $customer->get_email(); // Get account email
            $first_name = $customer->get_first_name();
            $last_name = $customer->get_last_name();
            $billing_address_1 = $customer->get_billing_address_1();
            $billing_address_2 = $customer->get_billing_address_2();
            $billing_city = $customer->get_billing_city();
            $billing_state = $customer->get_billing_state();
            $billing_postcode = $customer->get_billing_postcode();
            $billing_phone = $customer->get_billing_phone();
            $job_title = get_user_meta($user_id, '_contact_job_title', true);
            $user_marketing = get_user_meta($user_id, '_user_marketing', true);
            $first_contact = array();
            $first_contact['contact_email_channel'] = false;
            $first_contact['contact_phone_channel'] = false;
            $first_contact['contact_mail_channel'] = false;
            $first_contact['contact_fax_channel'] = false;
            if (is_array($user_marketing)) {
                if (array_key_exists('email', $user_marketing)) {
                    $first_contact['contact_email_channel'] = true;
                }
                if (array_key_exists('telephone', $user_marketing)) {
                    $first_contact['contact_phone_channel'] = true;
                }
                if (array_key_exists('mail', $user_marketing)) {
                    $first_contact['contact_mail_channel'] = true;
                }
                if (array_key_exists('sms', $user_marketing)) {
                    $first_contact['contact_fax_channel'] = true;
                }
            }
            $first_contact['contact_first_name'] = $first_name;
            $first_contact['contact_last_name'] = $last_name;
            $first_contact['contact_job_title'] = $job_title;
            $first_contact['contact_addr_1'] = $billing_address_1;
            $first_contact['contact_addr_2'] = $billing_address_2;
            $first_contact['contact_addr_3'] = $billing_city;
            $first_contact['contact_addr_4'] = $billing_state;
            $first_contact['contact_email'] = $user_email;
            $first_contact['contact_postcode'] = $billing_postcode;
            $first_contact['contact_phone'] = $billing_phone;
            $first_contact['contact_type'] = '';
            $first_contact['contact_CONTACT_ID'] = uniqid();
            $contactRec[] = $first_contact;
            $add_first_contact = true;
        } else {
            error_log('$user_CSD_ID not set yet wait..');
        }
    }
    //update_user_meta($user_id, 'wpiai_last_contacts', $lastContactRec);
    if (!update_user_meta($user_id, 'wpiai_contacts', $contactRec)) {
        error_log('Contact update_user_meta Failed or not changed for $user_id: ' . $user_id);
        wpiai_update_csd_contacts($user_id);
    } else {
        error_log('API Call Contacts for $user_id: ' . $user_id);
        $contactRec_url = get_option('wpiai_contact_url');
        $contactRec_paramaters = set_messageid(get_option('wpiai_contact_parameters'));

        if ((count($contactAdd) > 0)) {
            foreach ($contactAdd as $add_contact) {
                $contactRec_xml = get_contact_XML_record($user_id, 'Add', $add_contact);
                //error_log('contact Add');
                $updated = wpiai_get_infor_message_multipart_message($contactRec_url, $contactRec_paramaters, $contactRec_xml);
            }
        }

        if ($add_first_contact) {
            $contactRec_xml = get_contact_XML_record($user_id, 'Add', $contactRec[0]);
            $updated = wpiai_get_infor_message_multipart_message($contactRec_url, $contactRec_paramaters, $contactRec_xml);
        }
        wpiai_update_csd_contacts($user_id);
    }
    error_log('Finished Processing Contacts for user_id: ' . $user_id);
}

function wpiai_check_user_meta()
{
    //error_log('Checking User Meta');
    $users_updated = get_option('wpiai_users_updated');
    update_option('wpiai_users_updated', array());
    foreach ($users_updated as $user_id) {
        wpiai_process_user_shiptos($user_id);
        error_log('wpiai_process_user_shiptos completed');
        wpiai_process_user_contacts($user_id);
        error_log('wpiai_process_user_contacts completed');
    }
}

function wpiai_start($fName)
{
    error_log('Starting ' . $fName);
    $start = get_option('wpiai_background_processes_to_start');
    $running = get_option('wpiai_background_processes_running');
    if (!is_array($start)) {
        $start = array();
    }
    if (!is_array($running)) {
        $running = array();
    }
    if ($fName) {
        if (!in_array($fName, $start)) {
            if (!in_array($fName, $running)) {
                $start[] = $fName;
                update_option('wpiai_background_processes_to_start', $start);
            }
        }
    }
}

function wpiai_stop($fName)
{
    error_log('Stopping ' . $fName);
    $start = get_option('wpiai_background_processes_to_start');
    $running = get_option('wpiai_background_processes_running');
    if (!is_array($start)) {
        $start = array();
    }
    if (!is_array($running)) {
        $running = array();
    }
    if ($fName) {
        if (in_array($fName, $start)) {
            if (($key = array_search($fName, $start)) !== false) {
                unset($start[$key]);
            }
        }
        if (in_array($fName, $running)) {
            $stopping = get_option('wpiai_background_processes_to_stop');
            if (!is_array($stopping)) {
                $stopping = array();
            }
            if (!in_array($stopping)) {
                $stopping[] = $fName;
                update_option('wpiai_background_processes_to_stop', $stopping);
            }
        }
    }
}

function wpiai_check_if_stopping($fName)
{
    $GLOBALS['wp_object_cache']->delete('wpiai_background_processes_to_stop', 'options');
    $stopping = get_option('wpiai_background_processes_to_stop');
    if (!$stopping) {
        //error_log($fName.' is not stopping');
        return false;
    }
    if (!is_array($stopping)) {
        //error_log($fName.' is not stopping');
        return false;
    }
    if (in_array($fName, $stopping)) {
        error_log($fName . ' is stopping');
        return true;
    }
    //error_log($fName.' is not stopping');
    return false;
}

function wpiai_check_processes()
{
    error_log('Checking Infor Processes');
    $to_start = get_option('wpiai_background_processes_to_start');
    if (is_array($to_start)) {
        foreach ($to_start as $start) {
            if (function_exists($start)) {
                if (!wpiai_check_if_stopping($start)) {
                    $running = get_option('wpiai_background_processes_running');
                    if (!is_array($running)) {
                        $running = array();
                    }
                    if (!in_array($start, $running)) {
                        $running[] = $start;
                        update_option('wpiai_background_processes_running', $running);
                        error_log('Running ' . $start);
                        $start();
                    }
                }
            }
        }
    }
    $to_start = array();
    update_option('wpiai_background_processes_to_start', $to_start);
    $to_stop = get_option('wpiai_background_processes_to_stop');
    $stops_to_go = array();
    if (is_array($to_stop)) {
        foreach ($to_stop as $stop) {
            $running = get_option('wpiai_background_processes_running');
            if (!in_array($stop, $running)) {
                $stops_to_go[] = $stop;
            }
        }
    }
    foreach ($stops_to_go as $go) {
        if (($key = array_search($go, $to_stop)) !== false) {
            unset($to_stop[$key]);
        }
    }

}