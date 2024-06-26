<?php

if (!defined('ABSPATH')) {
    exit;
}

add_filter('cron_schedules', 'epimapi_ten_minute_interval');

// add once 10 minute interval to wp schedules
function epimapi_ten_minute_interval($interval)
{

    $interval['minutes_10'] = array('interval' => 10 * 60, 'display' => 'Once 10 minutes');
    $interval['minutes_1'] = array('interval' => 60, 'display' => 'Once every minute');

    return $interval;
}

register_activation_hook(epimaapi_PLUGINFILE, 'epimaapi_cron_activation');

function epimaapi_cron_activation()
{
    error_log('checking and adding cron events');
    if (!wp_next_scheduled('epimaapi_update_branch_stock_daily_action')) {
        wp_schedule_event(strtotime('22:20:00'), 'daily', 'epimaapi_update_branch_stock_daily_action');
    }
    if (!wp_next_scheduled('epimaapi_update_luckins_daily_action')) {
        wp_schedule_event(strtotime('04:00:00'), 'daily', 'epimaapi_update_luckins_daily_action');
    }
    if (!wp_next_scheduled('epimaapi_update_branch_stock_minutes_action')) {
        wp_schedule_event(time(), 'minutes_10', 'epimaapi_update_branch_stock_minutes_action');
    }
    if (!wp_next_scheduled('epimaapi_update_every_minute_minute_action')) {
        wp_schedule_event(time(), 'minutes_1', 'epimaapi_update_every_minute_minute_action');
    }
}

add_action('epimaapi_update_branch_stock_minutes_action', 'epimaapi_update_branch_stock_minutes');
add_action('epimaapi_update_every_minute_minute_action', 'epimaapi_update_every_minute');
add_action('epimaapi_update_branch_stock_daily_action', 'epimaapi_update_branch_stock_daily');
add_action('epimaapi_update_luckins_daily_action', 'epimaapi_update_luckins_daily');

function epimaapi_update_luckins_daily()
{
    epimaapi_update_branch_stock_cron();
    /*if ( ! ( false === get_option( 'epim_schedule_log' ) ) ) {
        update_option( 'epim_schedule_log', $log );
    }*/
}

function epimaapi_update_branch_stock_daily()
{
    if (!wp_next_scheduled('epimaapi_update_branch_stock_minutes_action')) {
        wp_schedule_event(time(), 'minutes_10', 'epimaapi_update_branch_stock_minutes_action');
    }
    if (!wp_next_scheduled('epimaapi_update_every_minute_minute_action')) {
        wp_schedule_event(time(), 'minutes_1', 'epimaapi_update_every_minute_minute_action');
    }
    if (!wp_next_scheduled('epimaapi_update_luckins_daily_action')) {
        wp_schedule_event(strtotime('04:00:00'), 'daily', 'epimaapi_update_luckins_daily_action');
    }
    $epim_enable_scheduled_updates = false;
    $epim_enable_scheduled_updates_option = get_option('epim_enable_scheduled_updates');
    if (is_array($epim_enable_scheduled_updates_option)) {
        if ($epim_enable_scheduled_updates_option['checkbox_value'] == 1) {
            $epim_enable_scheduled_updates = true;
        }
    }
    $epim_update_schedule = get_option('epim_update_schedule');
    if ($epim_update_schedule == 'daily') {
        if ($epim_enable_scheduled_updates) {
            epimaapi_update_branch_stock_cron();
        } else {

        }
    } else {

    }
    epimaapi_update_all_branch_stock();
}

function cron_log($log)
{
    $log_dir = WP_PLUGIN_DIR . '/epim-api-importer';
    if (is_dir($log_dir)) {
        $log_file = $log_dir . '/cron-log.log';
        $log_file_size = filesize($log_file);

        ini_set("log_errors", 1);
        ini_set("error_log", $log_file);

        if($log_file_size > 3000000) {
            file_put_contents($log_file,'');
            error_log('Log file reset');
        }
        error_log($log);
    }
}

function epimaapi_update_every_minute()
{
    if (!wp_next_scheduled('epimaapi_update_branch_stock_daily_action')) {
        wp_schedule_event(strtotime('22:20:00'), 'daily', 'epimaapi_update_branch_stock_daily_action');
    }
    if (!wp_next_scheduled('epimaapi_update_branch_stock_minutes_action')) {
        wp_schedule_event(time(), 'minutes_10', 'epimaapi_update_branch_stock_minutes_action');
    }
    if (!wp_next_scheduled('epimaapi_update_luckins_daily_action')) {
        wp_schedule_event(strtotime('04:00:00'), 'daily', 'epimaapi_update_luckins_daily_action');
    }

    set_time_limit(0);
    $epim_update_running = get_option('_epim_update_running');
    //error_log('_epim_update_running: '.$epim_update_running);
    if ($epim_update_running == '') {
        return;
    }
    if ($epim_update_running != 'Categories Updated and Sorted') {
        if ($epim_update_running != 'Getting All Products to Import') {
            epimapi_is_update_stuck();
        }
    }
    $epim_background_stop_update = get_option('_epim_background_stop_update');
    if ($epim_background_stop_update == 1) {
        update_option('_epim_update_running', '');
        update_option('_epim_background_stop_update', 0);
        update_option('_epim_background_current_index', 0);
        update_option('_epim_background_last_index', 0);
        update_option('_epim_background_product_attribute_data','');
        update_option('_epim_background_process_data', '');
        update_option('_epim_background_attribute_data', '');
        update_option('_epim_background_product_attribute_data','');
        return;
    }
    $epim_background_updates_max_run_time = get_option('epim_background_updates_max_run_time');
    if (($epim_update_running == 'Preparing to process ePim categories') || (substr($epim_update_running, 0, 44) === "Processing categories - Restarting at Index:")) {
        cron_log('Starting or resuming process ePim categories');
        $epim_background_process_data = get_option('_epim_background_process_data');
        if (is_array($epim_background_process_data)) {
            $i = 1;
            $c = count($epim_background_process_data);
            $time_start = microtime(true);
            foreach ($epim_background_process_data as $category) {
                if (get_option('_epim_update_running') == '') {
                    return;
                }
                $epim_update_running = 'Process category ' . $i . '/' . $c;
                if ($i >= get_option('_epim_background_current_index')) {
                    if (array_key_exists('Id', $category)) {
                        if (array_key_exists('Name', $category)) {
                            $ParentID = null;
                            $picture_webpath = '';
                            $picture_ids = array();
                            if ($category['ParentId']) {
                                $ParentID = $category['ParentId'];
                            }
                            cron_log('Importing Id:' . $category['Id'] . ' Name: ' . $category['Name'] . ' Alias: ' . $category['Alias']);
                            epimaapi_create_category($category['Id'], $category['Name'], $ParentID, $picture_webpath, $picture_ids, $category['Alias']);
                        }
                    }
                    update_option('_epim_background_current_index', $i - 1);
                }
                $i++;
                $time_now = microtime(true);
                if (($time_now - $time_start >= $epim_background_updates_max_run_time)) {
                    update_option('_epim_update_running', 'Processing categories - Restarting at Index: ' . $i . '/' . $c);
                    return;
                }
                update_option('_epim_update_running', $epim_update_running);
            }
            update_option('_epim_update_running', 'Preparing to Sort Categories');
            update_option('_epim_background_current_index', 0);
        } else {
            update_option('_epim_update_running', '');
            update_option('_epim_background_process_data', '');
        }
    }

    if (($epim_update_running == 'Preparing to Sort Categories') || (substr($epim_update_running, 0, 41) === "Sorting categories - Restarting at Index:")) {
        $time_start = microtime(true);
        $terms = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
        ]);
        $i = 1;
        $c = count($terms);
        foreach ($terms as $term) {
            if (get_option('_epim_update_running') == '') {
                return;
            }
            $epim_update_running = 'Sorting Category ' . $i . '/' . $c;
            if ($i >= get_option('_epim_background_current_index')) {
                $api_parents = get_term_meta($term->term_id, 'epim_api_parent_id', true);
                if ($api_parents != '') {
                    $parent = epimaapi_getTermFromID($api_parents, $terms);
                    if ($parent) {
                        $term_id = $term->term_id;

                        $epim_api_id = get_term_meta($term_id, 'epim_api_id', true);
                        $epim_api_alias = get_term_meta($term_id, 'epim_api_alias', true);
                        $epim_api_parent_id = get_term_meta($term_id, 'epim_api_parent_id', true);
                        $epim_api_picture_ids = get_term_meta($term_id, 'epim_api_picture_ids', true);
                        $epim_api_picture_link = get_term_meta($term_id, 'epim_api_picture_link', true);

                        wp_update_term($term_id, 'product_cat', array('parent' => $parent->term_id));

                        update_term_meta($term_id, 'epim_api_id', $epim_api_id);
                        update_term_meta($term_id, 'epim_api_alias', $epim_api_alias);
                        update_term_meta($term_id, 'epim_api_parent_id', $epim_api_parent_id);
                        update_term_meta($term_id, 'epim_api_picture_ids', $epim_api_picture_ids);
                        update_term_meta($term_id, 'epim_api_picture_link', $epim_api_picture_link);
                    }
                }
                update_option('_epim_background_current_index', $i - 1);
            }
            $i++;
            $time_now = microtime(true);
            if (($time_now - $time_start >= $epim_background_updates_max_run_time)) {
                cron_log('Sorting categories - Restarting at Index: ' . $i . '/' . $c);
                update_option('_epim_update_running', 'Sorting categories - Restarting at Index: ' . $i . '/' . $c);
                return;
            }
            cron_log($epim_update_running);
            update_option('_epim_update_running', $epim_update_running);
        }
        cron_log('Categories Updated and Sorted');
        update_option('_epim_update_running', 'Categories Updated and Sorted');
        update_option('_epim_background_current_index', 0);
    }

    if ($epim_update_running == 'Categories Updated and Sorted') {
        update_option('_epim_update_running', 'Getting All Products to Import');
        cron_log('Getting All Products to Import');
        $allProductsResponse = json_decode(get_epimaapi_all_products(), true);
        $variations = array();
        if (json_last_error() == JSON_ERROR_NONE) {
            if (is_array($allProductsResponse)) {
                if (array_key_exists('Results', $allProductsResponse)) {
                    foreach ($allProductsResponse['Results'] as $Product) {
                        //error_log(print_r($Product,true));
                        if (get_option('_epim_update_running') == '') {
                            return;
                        }
                        $categories = array();
                        $pictures = array();
                        if (array_key_exists('CategoryIds', $Product)) {
                            $categories = $Product['CategoryIds'];
                        }
                        if (array_key_exists('PictureIds', $Product)) {
                            $pictures = $Product['PictureIds'];
                        }
                        if (array_key_exists('VariationIds', $Product)) {
                            if (is_array($Product['VariationIds'])) {
                                foreach ($Product['VariationIds'] as $variation_id) {
                                    if ($epim_update_running == '') {
                                        return;
                                    }
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
            }
        } else {
            cron_log('ePim is not returning valid JSON, getting all products.');
        }
        update_option('_epim_background_process_data', $variations);
        update_option('_epim_update_running', 'Preparing to import products');
        cron_log('Found ' . count($variations) . ' products to import');
        cron_log('Preparing to import products');
    }

    if (($epim_update_running == 'Preparing to import products') || (substr($epim_update_running, 0, 41) === "Importing Products - Restarting at Index:")) {
        $time_start = microtime(true);
        $all_variations = get_option('_epim_background_process_data');
        $i = 1;
        $c = count($all_variations);
        if ($epim_update_running == 'Preparing to import products') {
            update_option('_epim_background_attribute_data', '');
            $variations = $all_variations;
        } else {
            $i = get_option('_epim_background_current_index') - 1;
            $variations = array_slice($all_variations, $i);
            $cLeft = count($variations);
            cron_log('Restarting at index: ' . $i . ' There are ' . $cLeft . ' variations still to process');
        }

        update_option('_epim_update_running', 'Importing ' . $c . ' Products');
        cron_log('Importing ' . $c . ' Products');

        if (is_array($variations)) {
            foreach ($variations as $variation) {
                if (get_option('_epim_update_running') == '') {
                    return;
                }
                update_option('_epim_update_running', 'Importing product ' . $i . '/' . $c);
                if ($i >= get_option('_epim_background_current_index')) {
                    if (is_array($variation)) {
                        if (array_key_exists('variationID', $variation)) {
                            cron_log('Importing variation ID: ' . $variation['variationID']);
                            try {
                                cron_log(epimaapi_create_product($variation['productID'], $variation['variationID'], $variation['productBulletText'], $variation['productName'], $variation['categoryIds'], $variation['pictureIds'], true));
                            } catch (SomeException $ignored) {
                                cron_log($ignored->getMessage());
                                //error_log('Exception Caught: '.$ignored->getMessage());
                            }

                        }
                    }
                    update_option('_epim_background_current_index', $i - 1);
                }

                $i++;
                $time_now = microtime(true);
                if (($time_now - $time_start >= $epim_background_updates_max_run_time)) {
                    cron_log('Importing Products - Restarting at Index: ' . $i . '/' . $c);
                    update_option('_epim_update_running', 'Importing Products - Restarting at Index: ' . $i . '/' . $c);
                    return;
                }
            }
        }
        $atts_to_do = get_option('_epim_background_attribute_data');
        //error_log('$atts_to_do:');
        //error_log(print_r($atts_to_do,true));
        if (is_array($atts_to_do)) {
            cron_log('Products Imported. Processing Attributes');
            update_option('_epim_update_running', 'Preparing to sort attributes');
            update_option('_epim_background_process_data', '');
            update_option('_epim_background_current_index', 0);
        } else {
            update_option('_epim_background_current_index', 0);
            cron_log('Import Finished');
            update_option('_epim_background_process_data', '');
            update_option('_epim_update_running', '');
        }

    }

    //Sort Attributes
    if (($epim_update_running == 'Preparing to sort attributes') || (substr($epim_update_running, 0, 41) === "Sorting attributes - Restarting at Index:")) {
        $time_start = microtime(true);
        $atts_to_do = get_option('_epim_background_attribute_data');
        $i = 1;
        $c = count($atts_to_do);
        if ($epim_update_running == 'Preparing to sort attributes') {
            $attributes = $atts_to_do;
        } else {
            $i = get_option('_epim_background_current_index') - 1;
            $attributes = array_slice($atts_to_do, $i);
            $cLeft = count($attributes);
            cron_log('Restarting at index: ' . $i . ' There are ' . $cLeft . ' attributes still to process');
        }
        update_option('_epim_update_running', 'Importing ' . $c . ' Attributes');
        cron_log('Importing ' . $c . ' Attributes');
        if (is_array($attributes)) {
            foreach ($attributes as $attribute) {
                if (get_option('_epim_update_running') == '') {
                    return;
                }
                update_option('_epim_update_running', 'Importing attribute ' . $i . '/' . $c);
                if ($i >= get_option('_epim_background_current_index')) {
                    if (is_array($attribute)) {
                        /*
                         *
                         * ***********************Import attribute and terms*************************
                         *
                         * */
                        $WCAttribute = epim_createAttribute($attribute['name'], $attribute['slug']);
                        if (!is_wp_error($WCAttribute)) {
                            //cron_log('Attribute added/updated: ' . $attribute['name']);;
                            $terms = $attribute['terms'];
                            $term_order = 0;
                            foreach ($terms as $term) {
                                $WCTerm = epim_createTerm($term['name'], $term['slug'], $attribute['slug'], $term_order);
                                if ($WCTerm) {
                                    //cron_log('Term ' . $term['name'] . ' added to Attribute ' . $attribute['name']);
                                }
                                $term_order++;
                            }
                        } else {
                            cron_log('Unable to add/update attribute: ' . $attribute['name'].' error is :'.$WCAttribute->get_error_message());
                        }
                        update_option('_epim_background_current_index', $i - 1);
                    }
                    $i++;
                    $time_now = microtime(true);
                    if (($time_now - $time_start >= $epim_background_updates_max_run_time)) {
                        cron_log('Sorting attributes - Restarting at Index: ' . $i . '/' . $c);
                        update_option('_epim_update_running', 'Sorting attributes - Restarting at Index: ' . $i . '/' . $c);
                        return;
                    }
                }
            }
        }

        $products_to_link = get_option('_epim_background_product_attribute_data');
        if (is_array($products_to_link)) {
            cron_log('Attributes Imported. Preparing to link products to attributes.');
            update_option('_epim_update_running', 'Preparing to link products to attributes');
            update_option('_epim_background_process_data', '');
            update_option('_epim_background_attribute_data', '');
            update_option('_epim_background_current_index', 0);
        } else {
            update_option('_epim_background_current_index', 0);
            cron_log('Import Finished');
            update_option('_epim_background_process_data', '');
            update_option('_epim_background_attribute_data', '');
            update_option('_epim_background_product_attribute_data','');
            update_option('_epim_update_running', '');
        }

    }

    //Link Products to Attributes
    if (($epim_update_running == 'Preparing to link products to attributes') || (substr($epim_update_running, 0, 53) === "Linking products to attributes - Restarting at Index:")) {
        $time_start = microtime(true);
        $product_attribute_data = get_option('_epim_background_product_attribute_data');
        //cron_log('$product_attribute_data:');
        //cron_log(print_r($product_attribute_data,true));
        //cron_log('Each item in $product_attribute_data:');
        $products_to_process = array();
        $ids_seen = array();
        foreach ($product_attribute_data as $product_attribute_datum) {
            foreach ($product_attribute_datum as $item) {
                if(is_array($item)) {
                    if(array_key_exists('id',$item)) {
                        if(!in_array($item['id'],$ids_seen)) {
                            //cron_log('Product ID: '.$item['id']);
                            $ids_seen[] = $item['id'];
                            if(array_key_exists('attributes', $item)) {
                                $product_to_process = array();
                                $product_to_process['id'] =$item['id'];
                                $product_to_process['attributes'] = $item['attributes'];
                                $products_to_process[] = $product_to_process;
                                /*foreach ($item['attributes'] as $i_attribute) {
                                    cron_log('-- Attribute: '.$i_attribute['slug']);
                                    if(array_key_exists('terms',$i_attribute)) {
                                        foreach ($i_attribute['terms'] as $i_a_term) {
                                            cron_log('-- -- term: '.$i_a_term['slug']);
                                        }
                                    }
                                }*/

                            }
                        }

                    }
                }

            }
        }
        $products_to_link = $products_to_process;
        $i = 1;
        $c = count($products_to_link);
        if ($epim_update_running != 'Preparing to link products to attributes') {
            $i = get_option('_epim_background_current_index') - 1;
            $products_to_link = array_slice($products_to_link, $i);
            $cLeft = count($products_to_link);
            cron_log('Restarting at index: ' . $i . ' There are ' . $cLeft . ' products still to link');
        }
        update_option('_epim_update_running', 'Linking ' . $c . ' Products');
        cron_log('Linking ' . $c . ' Products');
        if (is_array($products_to_link)) {
            //error_log(print_r($products_to_link,true));
            foreach ($products_to_link as $product_to_link) {
                if (get_option('_epim_update_running') == '') {
                    return;
                }
                update_option('_epim_update_running', 'Linking Product ' . $i . '/' . $c);
                if ($i >= get_option('_epim_background_current_index')) {
                    if (is_array($product_to_link)) {

                        // Remove Links to Attributes and Terms??

                        //Update Post Meta

                        //Create New Term Relationships

                        $product_meta = array();

                        //cron_log(print_r($product_to_link,true));

                        foreach ($product_to_link['attributes'] as $product_attribute) {
                            //error_log(print_r($product_attribute, true));
                            $c1 = 0;
                            $product_term_ids = array();
                            $wc_taxonomy_name = wc_attribute_taxonomy_name($product_attribute['slug']);
                            $wc_taxonomy_name_terms = get_terms(array(
                                'taxonomy' => $wc_taxonomy_name,
                                'hide_empty' => false
                            ));
                            if (is_wp_error($wc_taxonomy_name_terms)) {
                                error_log($wc_taxonomy_name . ' is not an attribute taxonomy');
                                error_log($wc_taxonomy_name_terms->get_error_message());
                            } else {
                                if (is_array($wc_taxonomy_name_terms)) {
                                    foreach ($wc_taxonomy_name_terms as $term) {
                                        foreach ($product_attribute['terms'] as $pa_term) {
                                            if ($term->slug == $pa_term['slug']) $product_term_ids[] = $term->term_id;
                                        }

                                    }
                                } else {
                                    if (is_object($wc_taxonomy_name_terms)) {

                                    } else {
                                        error_log('Mo terms found for ' . $wc_taxonomy_name);
                                    }
                                }
                            }
                            wp_set_object_terms($product_to_link['id'], array(), $wc_taxonomy_name);
                            if (!empty($product_term_ids)) {
                                //cron_log('Linking '.count($product_term_ids).' from taxonomy '. $wc_taxonomy_name.' to product '. $product_to_link['id']);
                                wp_set_object_terms($product_to_link['id'], $product_term_ids, $wc_taxonomy_name);
                            }

                            $product_meta[$product_attribute['slug']] = array(
                                'name' => $wc_taxonomy_name,
                                'value' => $product_attribute['terms'],
                                'position' => $c1,
                                'is_visible' => 1,
                                'is_variation' => 1,
                                'is_taxonomy' => '1'
                            );
                        }
                        update_post_meta($product_to_link['id'], '_product_attributes', $product_meta);
                        update_option('_epim_background_current_index', $i - 1);
                    }
                    $i++;
                    $time_now = microtime(true);
                    if (($time_now - $time_start >= $epim_background_updates_max_run_time)) {
                        cron_log('Linking products to attributes - Restarting at Index: ' . $i . '/' . $c);
                        update_option('_epim_update_running', 'Linking products to attributes - Restarting at Index: ' . $i . '/' . $c);
                        return;
                    }
                }
            }
            update_option('_epim_background_current_index', 0);
            cron_log('Import Finished');
            update_option('_epim_background_process_data', '');
            update_option('_epim_background_attribute_data', '');
            update_option('_epim_background_product_attribute_data','');
            update_option('_epim_update_running', '');
        } else {
            update_option('_epim_background_current_index', 0);
            cron_log('Import Finished');
            update_option('_epim_background_process_data', '');
            update_option('_epim_background_attribute_data', '');
            update_option('_epim_background_product_attribute_data','');
            update_option('_epim_update_running', '');
        }
    }
}

function epimapi_is_update_stuck() {
    $epim_update_running = get_option('_epim_update_running');
    if ($epim_update_running != '') {
        $epim_background_current_index = get_option('_epim_background_current_index');
        $epim_background_last_index = get_option('_epim_background_last_index');
        if('$epim_background_current_index' != 0) {
            if($epim_background_current_index == $epim_background_last_index) {
                update_option('_epim_update_running', 'Categories Updated and Sorted');
                cron_log('Unfreezing Queue Please wait.');
                return true;
            } else {
                update_option('_epim_background_last_index',$epim_background_current_index);
            }
        }
    }
    return false;
}

function epimaapi_update_branch_stock_minutes()
{
    $epim_update_running = get_option('_epim_update_running');
    if ($epim_update_running != '') {
        //cron_log('Branch Stock Update Cancelled - other updates running(10 minute update)');
        //error_log('Branch Stock Update Cancelled - other updates running(10 minute update)');
        return;
    }
    if (!wp_next_scheduled('epimaapi_update_branch_stock_daily_action')) {
        wp_schedule_event(strtotime('22:20:00'), 'daily', 'epimaapi_update_branch_stock_daily_action');
    }
    if (!wp_next_scheduled('epimaapi_update_every_minute_minute_action')) {
        wp_schedule_event(time(), 'minutes_1', 'epimaapi_update_every_minute_minute_action');
    }
    set_time_limit(0);
    if (!wp_next_scheduled('epimaapi_update_every_minute_minute_action')) {
        wp_schedule_event(time(), 'minutes_1', 'epimaapi_update_every_minute_minute_action');
    }
    $epim_enable_scheduled_updates = false;
    $epim_enable_scheduled_updates_option = get_option('epim_enable_scheduled_updates');
    if (is_array($epim_enable_scheduled_updates_option)) {
        if ($epim_enable_scheduled_updates_option['checkbox_value'] == 1) {
            $epim_enable_scheduled_updates = true;
        }
    }
    $epim_update_schedule = get_option('epim_update_schedule');
    //error_log('running 10 minute branch stock update');
    if ($epim_update_schedule == 'minutes') {
        if ($epim_enable_scheduled_updates) {
            cron_log('Updating Branch Stock (10 minute update)');
            epimaapi_update_branch_stock_cron();
        } else {
            //cron_log('10 minute update aborted - Updates not enabled');
        }
    } else {
        //cron_log('10 minute branch price and stock update aborted - set to daily updates');
    }

    $epim_update_running = get_option('_epim_update_running');
    if ($epim_update_running == '') {
        update_option('_epim_background_current_index', 0);
        $iso = (new \DateTime('-30 minutes', new \DateTimeZone("UTC")))->format(\DateTime::ATOM);
        //error_log($iso);
        epimaapi_background_import_products_from($iso);
        return;
    }
}

function epimaapi_update_branch_stock_cron()
{
    /*define( 'WP_USE_THEMES', false );
    require( $_SERVER['DOCUMENT_ROOT'] .'/wp-load.php' );*/
    $log = '';
    $start = microtime(true);
    //error_log('epimaapi_update_branch_stock_cron started');
    $yesterday = date('Y-m-d', strtotime("-1 days"));
    $branches = json_decode(get_epimaapi_all_branches(), true);
    if (is_array($branches)) {
        foreach ($branches as $branch) {
            if (is_array($branch)) {
                if (array_key_exists('Id', $branch)) {
                    $Id = $branch['Id'];
                    $stockLevels = json_decode(get_epimaapi_get_branch_stock_since($Id, $yesterday), true);
                    if (is_array($stockLevels)) {
                        foreach ($stockLevels as $stock_level) {
                            $datetime_now = date('d/m/Y h:i:s a', time());
                            $this_log = $datetime_now . ': ' . epimaapi_update_branch_stock($Id, $stock_level['VariationId'], $stock_level['Stock']);
                            $log .= $this_log . '</br>';
                            //error_log($this_log);
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
    $updatedProducts = get_epimaapi_all_changed_products_since($yesterday);
    $jProducts = json_decode($updatedProducts, true);
    if (is_array($jProducts)) {
        $limit = $jProducts['Limit'];
        if ($limit > 0) {
            $totalResults = $jProducts['TotalResults'];
            $pages = ceil($totalResults / $limit);
            $products = $jProducts['Results'];
            foreach ($products as $product) {
                foreach ($product['VariationIds'] as $variationId) {
                    epimaapi_create_product($product['Id'], $variationId, $product['BulletText'], $product['Name'], $product['CategoryIds'], $product['PictureIds'], true);
                }
            }
            for ($i = 1; $i <= $pages; $i++) {
                $start = $i * $limit;
                $pagedProducts = get_epimaapi_all_changed_products_since_starting($start, $yesterday);
                $jpProducts = json_decode($pagedProducts, true);
                $products = $jpProducts['Results'];
                if (is_array($products)) {
                    foreach ($products as $product) {
                        foreach ($product['VariationIds'] as $variationId) {
                            epimaapi_create_product($product['Id'], $variationId, $product['BulletText'], $product['Name'], $product['CategoryIds'], $product['PictureIds'], true);
                        }
                    }
                }
            }
        }
    }
    $time_elapsed_secs = microtime(true) - $start;
    $log .= 'Import took ' . $time_elapsed_secs . ' seconds.';

    if (!(false === get_option('epim_schedule_log'))) {
        //error_log('epimaapi_update_branch_stock_daily Import Took '.$time_elapsed_secs.' seconds');
        update_option('epim_schedule_log', $log);
        //error_log(get_option('epim_schedule_log'));
    }
}

function epimaapi_update_all_branch_stock()
{
    $start = microtime(true);
    $json_all_branches = json_decode(get_epimaapi_all_branches());
    foreach ($json_all_branches as $branch) {
        $stock_in_branch = json_decode(get_epimaapi_branch_stock($branch->Id));
        foreach ($stock_in_branch as $stock_item) {
            cron_log(epimaapi_update_branch_stock($branch->Id, $stock_item->VariationId, $stock_item->Stock));
        }
    }
    $time_elapsed_secs = microtime(true) - $start;
    cron_log('Import took ' . $time_elapsed_secs . ' seconds.');
    cron_log('finished.....');
}