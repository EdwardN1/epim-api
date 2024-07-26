<?php

//0: Failed/Stopped/Nothing to do | 1: Still running | 2: Finished
function epimapi_process_categories()
{
    $epim_background_process_data = get_option('_epim_background_process_data');

    if (is_array($epim_background_process_data)) {

        $time_start = microtime(true);
        $epim_background_updates_max_run_time = get_option('epim_background_updates_max_run_time');
        $i = 1;
        $c = count($epim_background_process_data);

        foreach ($epim_background_process_data as $category) {
            if (get_option('_epim_update_running') == '') {
                return 0;
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
                return 1;
            }
            update_option('_epim_update_running', $epim_update_running);
        }
        return 2;

    } else {
        return 0;
    }
}

//0: Failed/Stopped/Nothing to do | 1: Still running | 2: Finished
function epimapi_sort_categories()
{
    $terms = get_terms([
        'taxonomy' => 'product_cat',
        'hide_empty' => false,
    ]);
    $i = 1;
    $c = count($terms);
    $time_start = microtime(true);
    $epim_background_updates_max_run_time = get_option('epim_background_updates_max_run_time');
    foreach ($terms as $term) {
        if (get_option('_epim_update_running') == '') {
            return 0;
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
            return 1;
        }
        cron_log($epim_update_running);
        update_option('_epim_update_running', $epim_update_running);
    }
    cron_log('Categories Updated and Sorted');

    return 2;
}

//0: Failed/Stopped/Nothing to do | 1: Still running | 2: Finished
function epimapi_get_all_products()
{
    update_option('_epim_update_running', 'Getting All Products to Import');
    $allProductsResponse = json_decode(get_epimaapi_all_products(), true);
    $variations = array();
    if (json_last_error() == JSON_ERROR_NONE) {
        if (is_array($allProductsResponse)) {
            if (array_key_exists('Results', $allProductsResponse)) {
                foreach ($allProductsResponse['Results'] as $Product) {
                    if (get_option('_epim_update_running') == '') {
                        return 0;
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
        cron_log('ePim is not returning valid JSON, trying to get all products.');
        return 0;
    }
    if(count($variations)>0) {
        cron_log('Found ' . count($variations) . ' products to import');
        update_option('_epim_background_process_data', $variations);
        return 2;
    } else {
        cron_log('No products found to import');
        return 0;
    }
}

//0: Failed/Stopped/Nothing to do | 1: Still running | 2: Finished
function epimapi_import_products() {
    $epim_update_running = get_option('_epim_update_running');
    $time_start = microtime(true);
    $epim_background_updates_max_run_time = get_option('epim_background_updates_max_run_time');
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