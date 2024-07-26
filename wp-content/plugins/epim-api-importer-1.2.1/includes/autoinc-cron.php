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
    return;
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
        //$log_file_size = filesize($log_file);

        ini_set("log_errors", 1);
        ini_set("error_log", $log_file);

        /*if($log_file_size > 3000000) {
            file_put_contents($log_file,'');
            error_log('Log file reset');
        }*/
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

    if ($epim_update_running == '') {
        return;
    }

    $epim_background_stop_update = get_option('_epim_background_stop_update');
    if ($epim_background_stop_update == 1) {
        update_option('_epim_update_running', '');
        update_option('_epim_background_stop_update', 0);
        update_option('_epim_background_current_index', 0);
        update_option('_epim_background_last_index', 0);
        update_option('_epim_background_product_attribute_data', '');
        update_option('_epim_background_process_data', '');
        update_option('_epim_background_attribute_data', '');
        update_option('_epim_background_product_attribute_data', '');
        return;
    }

    if (($epim_update_running == 'Preparing to process ePim categories') || (substr($epim_update_running, 0, 44) === "Processing categories - Restarting at Index:")) {
        cron_log('Starting or resuming process ePim categories');
        switch (epimapi_process_categories()) {
            case 1:
                cron_log(get_option('_epim_update_running'));
                break;
            case 2:
                update_option('_epim_update_running', 'Preparing to Sort Categories');
                update_option('_epim_background_current_index', 0);
                break;
            default:
                update_option('_epim_update_running', '');
                update_option('_epim_background_process_data', '');
        }
        return;
    }

    if (($epim_update_running == 'Preparing to Sort Categories') || (substr($epim_update_running, 0, 41) === "Sorting categories - Restarting at Index:")) {
        cron_log('Sorting Categories');
        switch (epimapi_sort_categories()) {
            case 1:
                cron_log(get_option('_epim_update_running'));
                break;
            case 2:
                update_option('_epim_update_running', 'Categories Updated and Sorted');
                update_option('_epim_background_current_index', 0);
                break;
            default:
                update_option('_epim_update_running', '');
                update_option('_epim_background_process_data', '');
        }
        return;
    }

    $time_start = microtime(true);
    $epim_background_updates_max_run_time = get_option('epim_background_updates_max_run_time');

    if ($epim_update_running == 'Categories Updated and Sorted') {
        cron_log('Getting All Products to Import');

        switch (epimapi_get_all_products()) {
            case 1:
                cron_log(get_option('_epim_update_running'));
                break;
            case 2:
                update_option('_epim_update_running', 'Preparing to import products');
                cron_log('Preparing to import products');
                break;
            default:
                update_option('_epim_update_running', '');
                update_option('_epim_background_process_data', '');
        }
        return;
    }

    if (($epim_update_running == 'Preparing to import products') || (substr($epim_update_running, 0, 41) === "Importing Products - Restarting at Index:")) {

        $all_variations = get_option('_epim_background_process_data');
        $i = 1;
        $c = count($all_variations);
        if ($epim_update_running == 'Preparing to import products') {
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
            $limit_loop = 0;
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
                                cron_log(epimaapi_create_basic_product($variation['productID'], $variation['variationID'], $variation['productBulletText'], $variation['productName'], $variation['categoryIds']));
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
                /*$limit_loop++;
                if($limit_loop>10) break;*/
            }
        }

        cron_log('Products Imported. Processing Attributes');
        update_option('_epim_update_running', 'Preparing to Sort attributes');
        update_option('_epim_background_process_data', '');
        update_option('_epim_background_current_index', 0);

    }

    //if ($epim_update_running == 'Preparing to process ePim categories') {
    if ($epim_update_running == 'Preparing to Sort attributes') {
        update_option('_epim_update_running', 'Sorting Attributes');

        $args = array('post_type' => 'product', 'posts_per_page' => -1);
        $product_posts = get_posts($args);
        $product_link_data = array();
        cron_log('Setting Attributes for ' . count($product_posts) . ' products');
        if (!empty($product_posts)) {
            $i = 0;
            $attribute_taxonomies = wc_get_attribute_taxonomies();
            $current_attribute_slugs = array();
            foreach ($attribute_taxonomies as $attribute_taxonomy) {

                $watName = $attribute_taxonomy->attribute_name;
                if (!in_array($watName, $current_attribute_slugs)) {
                    //error_log($atName);
                    $current_attribute_slugs[] = $watName;
                } else {
                    cron_log('Attribute ' . $watName . ' is duplicated - deleting');
                    wc_delete_attribute($attribute_taxonomy->attribute_id);
                }
            }
            foreach ($product_posts as $product_post) {
                $wc_metaData = get_post_meta($product_post->ID, '', true);
                if ($wc_metaData) $epim_api_variation_data = $wc_metaData['epim_api_variation_data'][0];
                $variation = json_decode($epim_api_variation_data);
                $product_attributes = array();
                if ($variation) {
                    $i++;
                    //cron_log('Importing Attributes for SKU: ' . $variation->SKU);
                    $product_attribute = array();
                    foreach ($variation->AttributeValues as $attribute_value) {
                        $atName = $attribute_value->AttributeHeaderName;
                        if ($atName == 'Type') {
                            $atName = 'Type Name';
                        }
                        if ($atName == 'type') {
                            $atName = 'Type Name';
                        }
                        if ($atName == 'Product') {
                            $atName = 'Product Name';
                        }
                        if ($atName == 'product') {
                            $atName = 'Product Name';
                        }
                        if ($atName == 'Category') {
                            $atName = 'Category Name';
                        }
                        if ($atName == 'category') {
                            $atName = 'Category Name';
                        }
                        if ($atName) {
                            $slugName = sanitize_title(substr($atName, 0, 27));
                            $attributeWCslug = wc_sanitize_taxonomy_name($slugName);
                            if ($attributeWCslug == 'type') {
                                $attributeWCslug == 'type-name';
                            }
                            $attributeIndex = epim_in_flat_array($attributeWCslug, $current_attribute_slugs);
                            //cron_log($attributeWCName);
                            $WCAttribute = false;
                            if (!$attributeIndex) {
                                $WCAttribute = epim_createAttribute($atName, $attributeWCslug);
                                if (is_wp_error($WCAttribute)) {
                                    cron_log('SKU: ' . $variation->SKU);
                                    cron_log($WCAttribute->get_error_message());
                                }
                            }
                            if ((!$WCAttribute) || (is_wp_error($WCAttribute))) {
                                $WCAttributeID = wc_attribute_taxonomy_id_by_name(wc_sanitize_taxonomy_name($attributeWCslug));
                                if ($WCAttributeID) $WCAttribute = wc_get_attribute($WCAttributeID);
                            }
                            if ($WCAttribute) {
                                if (!property_exists($WCAttribute, 'id')) {
                                    cron_log('SKU: ' . $variation->SKU);
                                    cron_log('Attribute not added : ' . $attributeWCslug);
                                    //cron_log(print_r($WCAttribute, true));
                                } else {
                                    $product_attribute = array();
                                    $product_attribute['taxonomy_name'] = wc_attribute_taxonomy_name($attributeWCslug);
                                    $product_attribute['slug'] = $attributeWCslug;
                                    $product_terms = array();
                                    $current_attribute_slugs[] = $attributeWCslug;
                                    //cron_log('Processing Attribute ' . $attributeWCslug . ' with ID: ' . $WCAttribute->id);
                                    $current_terms = get_terms(array('object_ids' => $WCAttribute->id));
                                    if (is_wp_error(($current_terms))) {
                                        cron_log('SKU: ' . $variation->SKU);
                                        cron_log('Error getting terms for ' . $attributeWCslug);
                                        cron_log($current_terms->get_error_message());
                                    } else {
                                        $term_name = $attribute_value->Value;
                                        $term_slug = sanitize_title(stripslashes(str_replace(' ', '-', $term_name)));
                                        if (strlen($term_name) > 100) {
                                            $term_name = substr($term_name, 0, 99);
                                        }
                                        if (strlen($term_slug) > 28) {
                                            $term_slug = substr($term_slug, 0, 27);
                                        }

                                        if ($term_slug == 'Type') {
                                            $term_slug = 'type-name';
                                        }
                                        if ($term_slug == 'type') {
                                            $term_slug = 'type-name';
                                        }
                                        if ($term_slug == 'Product') {
                                            $term_slug = 'product-name';
                                        }
                                        if ($term_slug == 'product') {
                                            $term_slug = 'product-name';
                                        }
                                        if ($term_slug == 'Category') {
                                            $term_slug = 'category-name';
                                        }
                                        if ($term_slug == 'category') {
                                            $term_slug = 'category-name';
                                        }
                                        if (!empty($current_terms)) {
                                            $current_term_slugs = array();
                                            foreach ($current_terms as $current_term) {
                                                $current_term_slugs[] = $current_term->slug;
                                            }
                                            $termIndex = epim_in_flat_array($term_slug, $current_terms);
                                            if (!$termIndex) {
                                                //cron_log('creating term ' . $term_name . ' with slug ' . $term_slug . ' for attribute ' . $attributeWCslug);
                                                $WCTerm = epim_createTerm($term_name, $term_slug, $attributeWCslug, 0);
                                                if (is_wp_error($WCTerm)) {
                                                    cron_log('SKU: ' . $variation->SKU);
                                                    cron_log('Error creating term ' . $term_slug . ' in Attribute ' . $attributeWCslug);
                                                } else {
                                                    //cron_log('Term ' . $term_slug . ' added to attribute ' . $WCTerm->taxonomy);
                                                }
                                            }
                                        } else {
                                            //cron_log('creating term ' . $term_name . ' with slug ' . $term_slug . ' for attribute ' . $attributeWCslug);
                                            $WCTerm = epim_createTerm($term_name, $term_slug, $attributeWCslug, $variation->SKU, 0);
                                            if (is_wp_error($WCTerm)) {
                                                cron_log('SKU: ' . $variation->SKU);
                                                cron_log('Error creating term ' . $term_slug . ' in Attribute ' . $attributeWCslug);
                                            } else {
                                                //cron_log('Term ' . $term_slug . ' added to attribute ' . $attributeWCslug);
                                            }
                                        }
                                        $wc_taxonomy_name_terms = get_terms(array(
                                            'taxonomy' => $product_attribute['taxonomy_name'],
                                            'hide_empty' => false
                                        ));
                                        if (is_wp_error($wc_taxonomy_name_terms)) {
                                            cron_log($product_attribute['taxonomy_name'] . ' is not an attribute taxonomy');
                                            cron_log($wc_taxonomy_name_terms->get_error_message());
                                        } else {
                                            if (is_array($wc_taxonomy_name_terms)) {
                                                foreach ($wc_taxonomy_name_terms as $term) {
                                                    if ($term->slug == $term_slug) {
                                                        $product_term = array();
                                                        $product_term['id'] = $term->term_id;
                                                        $product_term['name'] = $term_name;
                                                        $product_term['slug'] = $term_slug;
                                                        $product_terms[] = $product_term;
                                                    }
                                                }
                                            }

                                        }
                                    }
                                    if (!empty($product_attribute)) {
                                        if (!empty($product_terms)) $product_attribute['terms'] = $product_terms;
                                        $product_attributes[] = $product_attribute;
                                    }
                                }
                            } else {
                                cron_log('SKU: ' . $variation->SKU);
                                cron_log('Attribute not added or found: ' . $attributeWCslug);
                            }

                        }

                    }
                }
                /*if ($i > 1000) {
                    break;
                }*/
                //cron_log(print_r($product_attributes,true));

                if (!empty($product_attributes)) {
                    //cron_log(print_r($product_attributes,true));
                    $product_link_datum = array();
                    $product_link_datum['id'] = $product_post->ID;
                    $product_link_datum['attributes'] = $product_attributes;
                    $product_link_data[] = $product_link_datum;
                }
                //cron_log($variation->SKU);
                if (($i % 10) == 0) {
                    cron_log($i . ' products processed');
                }
            }
        }

        if (!empty($product_link_data)) {
            if (count($product_link_data) > 3000) {
                $product_link_data_1 = array_slice($product_link_data, 0, 3000);
                if (count($product_link_data) > 6000) {
                    $product_link_data_2 = array_slice($product_link_data, 3000, 3000);
                    $product_link_data_3 = array_slice($product_link_data, 6000);
                    update_option('_epim_background_current_index', 0);
                    cron_log('Preparing to link Products to attributes');
                    update_option('_epim_background_process_data', $product_link_data_1);
                    update_option('_epim_background_attribute_data', $product_link_data_2);
                    update_option('_epim_background_product_attribute_data', $product_link_data_3);
                    update_option('_epim_update_running', 'Preparing to link attributes to products');
                } else {
                    $product_link_data_2 = array_slice($product_link_data, 3000);
                    update_option('_epim_background_current_index', 0);
                    cron_log('Preparing to link Products to attributes');
                    update_option('_epim_background_process_data', $product_link_data_1);
                    update_option('_epim_background_attribute_data', $product_link_data_2);
                    update_option('_epim_background_product_attribute_data', '');
                    update_option('_epim_update_running', 'Preparing to link attributes to products');
                }

            } else {
                update_option('_epim_background_current_index', 0);
                cron_log('Preparing to link Products to attributes');
                update_option('_epim_background_process_data', $product_link_data);
                update_option('_epim_background_attribute_data', '');
                update_option('_epim_background_product_attribute_data', '');
                update_option('_epim_update_running', 'Preparing to link attributes to products');
            }
        } else {
            update_option('_epim_background_current_index', 0);
            cron_log('Import Finished');
            update_option('_epim_background_process_data', '');
            update_option('_epim_background_attribute_data', '');
            update_option('_epim_background_product_attribute_data', '');
            update_option('_epim_update_running', '');
        }

    }

    if (($epim_update_running == 'Preparing to link attributes to products') || ($epim_update_running == 'Restarting linking attributes to products')) {

        update_option('_epim_update_running', 'Linking attributes to products');
        $product_link_data = get_option('_epim_background_process_data');
        $product_set_data = $product_link_data;

        $i = 0;
        if ($product_link_data != '') {
            $cld = count($product_link_data);
            cron_log('Linking attributes to ' . $cld . ' products');
            foreach ($product_link_data as $product_link_datum) {
                $product_meta = array();
                $product_attributes = $product_link_datum['attributes'];
                foreach ($product_attributes as $product_attribute) {
                    wp_set_object_terms($product_link_datum['id'], array(), $product_attribute['taxonomy_name']);
                    $attribute_terms = array();
                    $attribute_term_names = array();
                    if (!empty($product_attribute['terms'])) {
                        foreach ($product_attribute['terms'] as $term) {
                            $attribute_terms[] = $term['id'];
                            $attribute_term_names = $term['name'];
                        }
                    }
                    if (!empty($attribute_terms)) wp_set_object_terms($product_link_datum['id'], $attribute_terms, $product_attribute['taxonomy_name']);

                    $product_meta[$product_attribute['slug']] = array(
                        'name' => $product_attribute['taxonomy_name'],
                        'value' => $attribute_term_names,
                        'position' => 0,
                        'is_visible' => 1,
                        'is_variation' => 1,
                        'is_taxonomy' => '1'
                    );
                }
                update_post_meta($product_link_datum['id'], '_product_attributes', $product_meta);
                array_shift($product_set_data);
                $time_now = microtime(true);
                if (($time_now - $time_start >= $epim_background_updates_max_run_time)) {
                    update_option('_epim_update_running', 'Restarting linking attributes to products');
                    update_option('_epim_background_process_data', $product_set_data);
                    cron_log('Restarting linking attributes to products');
                    return;
                }
                $i++;
                if (($i % 10) == 0) {
                    cron_log($i . ' products linked');
                }
            }
            update_option('_epim_background_process_data', '');
        }

        $product_link_data_2 = get_option('_epim_background_attribute_data');
        $product_set_data = $product_link_data_2;
        if ($product_link_data_2 != '') {
            $cld = count($product_link_data_2);
            cron_log('Linking attributes to ' . $cld . ' additional products');
            foreach ($product_link_data_2 as $product_link_datum) {
                $product_meta = array();
                $product_attributes = $product_link_datum['attributes'];
                foreach ($product_attributes as $product_attribute) {
                    wp_set_object_terms($product_link_datum['id'], array(), $product_attribute['taxonomy_name']);
                    $attribute_terms = array();
                    $attribute_term_names = array();
                    if (!empty($product_attribute['terms'])) {
                        foreach ($product_attribute['terms'] as $term) {
                            $attribute_terms[] = $term['id'];
                            $attribute_term_names = $term['name'];
                        }
                    }
                    if (!empty($attribute_terms)) wp_set_object_terms($product_link_datum['id'], $attribute_terms, $product_attribute['taxonomy_name']);

                    $product_meta[$product_attribute['slug']] = array(
                        'name' => $product_attribute['taxonomy_name'],
                        'value' => $attribute_term_names,
                        'position' => 0,
                        'is_visible' => 1,
                        'is_variation' => 1,
                        'is_taxonomy' => '1'
                    );
                }
                update_post_meta($product_link_datum['id'], '_product_attributes', $product_meta);
                array_shift($product_set_data);
                $time_now = microtime(true);
                if (($time_now - $time_start >= $epim_background_updates_max_run_time)) {
                    update_option('_epim_update_running', 'Restarting linking attributes to products');
                    update_option('_epim_background_attribute_data', $product_set_data);
                    cron_log('Restarting linking attributes to additional products');
                    return;
                }
                $i++;
                if (($i % 10) == 0) {
                    cron_log($i . ' products linked');
                }
            }
            update_option('_epim_background_attribute_data', '');
        }

        $product_link_data_3 = get_option('_epim_background_product_attribute_data');
        $product_set_data = $product_link_data_3;
        if ($product_link_data_3 != '') {
            $cld = count($product_link_data_3);
            cron_log('Linking attributes to ' . $cld . ' more additional products');
            foreach ($product_link_data_3 as $product_link_datum) {
                $product_meta = array();
                $product_attributes = $product_link_datum['attributes'];
                foreach ($product_attributes as $product_attribute) {
                    wp_set_object_terms($product_link_datum['id'], array(), $product_attribute['taxonomy_name']);
                    $attribute_terms = array();
                    $attribute_term_names = array();
                    if (!empty($product_attribute['terms'])) {
                        foreach ($product_attribute['terms'] as $term) {
                            $attribute_terms[] = $term['id'];
                            $attribute_term_names = $term['name'];
                        }
                    }
                    if (!empty($attribute_terms)) wp_set_object_terms($product_link_datum['id'], $attribute_terms, $product_attribute['taxonomy_name']);

                    $product_meta[$product_attribute['slug']] = array(
                        'name' => $product_attribute['taxonomy_name'],
                        'value' => $attribute_term_names,
                        'position' => 0,
                        'is_visible' => 1,
                        'is_variation' => 1,
                        'is_taxonomy' => '1'
                    );
                }
                update_post_meta($product_link_datum['id'], '_product_attributes', $product_meta);
                array_shift($product_set_data);
                $time_now = microtime(true);
                if (($time_now - $time_start >= $epim_background_updates_max_run_time)) {
                    update_option('_epim_update_running', 'Restarting linking attributes to products');
                    update_option('_epim_background_product_attribute_data', $product_set_data);
                    cron_log('Restarting linking attributes to additional products');
                    return;
                }
                $i++;
                if (($i % 10) == 0) {
                    cron_log($i . ' products linked');
                }
            }
            update_option('_epim_background_product_attribute_data', '');
        }


        update_option('_epim_background_current_index', 0);
        cron_log('Preparing to Import Images');
        update_option('_epim_background_process_data', '');
        update_option('_epim_background_attribute_data', '');
        update_option('_epim_background_product_attribute_data', '');
        update_option('_epim_update_running', 'Preparing to Import Images');

    }

    if (($epim_update_running == 'Preparing to Import Images') || ($epim_update_running == 'Restarting Import of Images')) {


        $i = 0;
        if ($epim_update_running == 'Preparing to Import Images') {
            $args = array('post_type' => 'product', 'posts_per_page' => -1, 'fields' => 'ids');
            $product_posts = get_posts($args);
            $product_set_data = $product_posts;
        } else {
            $product_posts = get_option('_epim_background_process_data');
            $product_set_data = $product_posts;
        }
        update_option('_epim_update_running', 'Importing Images');

        //cron_log(print_r($product_posts,true));

        cron_log('Synchonising  Images for ' . count($product_posts) . ' products');
        if (!empty($product_posts)) {
            foreach ($product_posts as $product_post) {
                $wc_metaData = get_post_meta($product_post, '', true);
                if ($wc_metaData) $epim_api_variation_data = $wc_metaData['epim_api_variation_data'][0];
                $variation = json_decode($epim_api_variation_data, true);
                if ($variation) {
                    $epim_images = array();
                    $luckins_images = array();

                    //epim images
                    if ($variation['PictureIds']) {
                        if (is_array($variation['PictureIds'])) {
                            foreach ($variation['PictureIds'] as $pictureId) {
                                $jsonPicture = get_epimaapi_picture($pictureId);
                                $picture = json_decode($jsonPicture);

                                try {
                                    $attachment_ID = epimaapi_imageIDfromAPIID($picture->Id);
                                    if (!$attachment_ID) {
                                        $attachment_ID = uploadMedia($picture->WebPath . '?w=600&h=600');
                                        if ($attachment_ID) {
                                            cron_log('Uploading image - ' . $picture->WebPath);
                                            update_post_meta($attachment_ID, 'epim_api_id', $picture->Id);
                                        }
                                    }
                                } catch (Exception $e) {
                                    cron_log($e->getMessage());
                                }

                                if ($attachment_ID) {
                                    if (!in_array($attachment_ID, $epim_images)) {
                                        $epim_images[] = $attachment_ID;
                                    }
                                }
                            }
                        }
                    }

                    //luckins images
                    if ($variation['LuckinsAssets']) {
                        if (is_array($variation['LuckinsAssets'])) {
                            foreach ($variation['LuckinsAssets'] as $luckinsAsset) {
                                if (is_array($luckinsAsset)) {
                                    if (array_key_exists('Tag', $luckinsAsset)) {
                                        if (array_key_exists('URL', $luckinsAsset)) {
                                            if ($luckinsAsset['Tag'] == 'hi-res') {
                                                try {
                                                    $attachment_ID = epimaapi_imageIDfromAPIID($luckinsAsset['URL']);
                                                    if (!$attachment_ID) {
                                                        cron_log('Uploading image - ' . $luckinsAsset['URL']);
                                                        $attachment_ID = uploadMedia($luckinsAsset['URL']);
                                                        if ($attachment_ID) {
                                                            update_post_meta($attachment_ID, 'epim_api_id', $luckinsAsset['URL']);
                                                        }
                                                    }
                                                } catch (Exception $e) {
                                                    cron_log($e->getMessage());
                                                }

                                                if ($attachment_ID) {
                                                    if (!in_array($attachment_ID, $luckins_images)) {
                                                        $luckins_images[] = $attachment_ID;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    //Sort images...
                    $epimFirst = false;
                    $epim_prioritise_epim_images = get_option('epim_prioritise_epim_images');
                    if (is_array($epim_prioritise_epim_images)) {
                        if ($epim_prioritise_epim_images['checkbox_value'] == 1) {
                            $epimFirst = true;
                        }
                    }
                    if ($epimFirst) {
                        $image_attachment_ids = array_merge($epim_images, $luckins_images);
                    } else {
                        $image_attachment_ids = array_merge($luckins_images, $epim_images);
                    }

                    //cron_log(print_r($image_attachment_ids,true));;

                    //Link images....
                    if (is_array($image_attachment_ids)) {
                        if (count($image_attachment_ids) > 0) {
                            $objProduct = wc_get_product($product_post);

                            if ($objProduct) {
                                if (is_wp_error($objProduct)) {
                                    cron_log($objProduct->get_error_message());
                                } else {
                                    $objProduct->set_image_id(null);
                                    $objProduct->set_gallery_image_ids(null);
                                    $objProduct->set_image_id($image_attachment_ids[0]);
                                    if (count($image_attachment_ids) > 1) {
                                        $p_count = 0;
                                        $productGallery = array();
                                        foreach ($image_attachment_ids as $image_attachment_id) {
                                            if ($p_count != 0) {
                                                $productGallery[] = $image_attachment_id;
                                            }
                                            $p_count++;
                                        }
                                        $objProduct->set_gallery_image_ids($productGallery);
                                    }
                                    $product_id = $objProduct->save();
                                    cron_log('Product ID: '.$product_id . ' images linked.');
                                }
                            }
                        }

                    }
                } else {
                    cron_log('wc_get_product return false or null for Post ID: ' . $product_post);
                }

                array_shift($product_set_data);
                $time_now = microtime(true);
                if (($time_now - $time_start >= $epim_background_updates_max_run_time)) {
                    update_option('_epim_update_running', 'Restarting Import of Images');
                    update_option('_epim_background_process_data', $product_set_data);
                    cron_log('Restarting Import of Images');
                    return;
                }
            }
        }
        update_option('_epim_background_current_index', 0);
        cron_log('Import Finished');
        update_option('_epim_background_process_data', '');
        update_option('_epim_background_attribute_data', '');
        update_option('_epim_background_product_attribute_data', '');
        update_option('_epim_update_running', '');
    }


}

function epim_in_flat_array($array, $value)
{
    if (is_array($array)) {
        foreach ($array as $item) {
            if ($item === $value) return true;
        }
    }
    return false;
}

function epim_in_array($array, $key, $value)
{
    if (is_array($array)) {
        if (array_key_exists($key, $array)) {
            foreach ($array as $item) {
                if ($item[$key] === $value) return $item;
            }
        }
    }
    return false;
}


function epim_createAttribute(string $attributeName, string $attributeSlug)
{
    /*delete_transient('wc_attribute_taxonomies');
    \WC_Cache_Helper::invalidate_cache_group('woocommerce-attributes');*/

    /*$attributeLabels = wp_list_pluck(wc_get_attribute_taxonomies(), 'attribute_label', 'attribute_name');
    $attributeWCName = array_search($attributeSlug, $attributeLabels, TRUE);

    if (!$attributeWCName) {
        $attributeWCName = wc_sanitize_taxonomy_name($attributeSlug);
    }*/

    /*$attributeLabels = wp_list_pluck(wc_get_attribute_taxonomies(), 'attribute_label', 'attribute_name');
    $attributeWCName = array_search($attributeSlug, $attributeLabels, TRUE);

    if (!$attributeWCName) {
        $attributeWCName = wc_sanitize_taxonomy_name($attributeSlug);
    }*/

    $attributeId = wc_attribute_taxonomy_id_by_name($attributeSlug);
    //error_log('$attributeId = '.$attributeId.' for slug '.$attributeSlug);
    if (!$attributeId) {
        $taxonomyName = wc_attribute_taxonomy_name($attributeSlug);
        unregister_taxonomy($taxonomyName);
        $attributeId = wc_create_attribute(array(
            'name' => $attributeName,
            'slug' => $attributeSlug,
            'type' => 'select',
            'order_by' => 'menu_order',
            'has_archives' => 0,
        ));

        register_taxonomy($taxonomyName, apply_filters('woocommerce_taxonomy_objects_' . $taxonomyName, array(
            'product'
        )), apply_filters('woocommerce_taxonomy_args_' . $taxonomyName, array(
            'labels' => array(
                'name' => $attributeSlug,
            ),
            'hierarchical' => FALSE,
            'show_ui' => FALSE,
            'query_var' => TRUE,
            'rewrite' => FALSE,
        )));
    }

    if (is_wp_error($attributeId)) {
        return $attributeId;
    } else {
        return wc_get_attribute($attributeId);
    }


}

function epim_createTerm(string $termName, string $termSlug, string $taxonomy, string $sku, int $order = 0)
{

    $taxonomy = wc_attribute_taxonomy_name($taxonomy);

    if (!$term = get_term_by('slug', $termSlug, $taxonomy)) {
        $term = wp_insert_term($termName, $taxonomy, array(
            'slug' => $termSlug,
        ));
        if (is_wp_error($term)) {
            error_log('SKU: ' . $sku . ' | Error creating term: ' . $termName . ' ¦ $termSlug = ' . $termSlug . ' ¦ $taxonomy = ' . $taxonomy . ' ¦ msg = ' . $term->get_error_message());
        } else {
            $term = get_term_by('id', $term['term_id'], $taxonomy);
            if ($term) {
                update_term_meta($term->term_id, 'order', $order);
            }
        }
    }

    return $term;
}

function epimapi_is_update_stuck()
{
    $epim_update_running = get_option('_epim_update_running');
    if ($epim_update_running != '') {
        $epim_background_current_index = get_option('_epim_background_current_index');
        $epim_background_last_index = get_option('_epim_background_last_index');
        if ('$epim_background_current_index' != 0) {
            if ($epim_background_current_index == $epim_background_last_index) {
                update_option('_epim_update_running', 'Categories Updated and Sorted');
                cron_log('Unfreezing Queue Please wait.');
                return true;
            } else {
                update_option('_epim_background_last_index', $epim_background_current_index);
            }
        }
    }
    return false;
}

function epimaapi_update_branch_stock_minutes()
{
    return;
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
    return;
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
                    epimaapi_create_product($product['Id'], $variationId, $product['BulletText'], $product['Name'], $product['CategoryIds'], $product['PictureIds']);
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
                            epimaapi_create_product($product['Id'], $variationId, $product['BulletText'], $product['Name'], $product['CategoryIds'], $product['PictureIds']);
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