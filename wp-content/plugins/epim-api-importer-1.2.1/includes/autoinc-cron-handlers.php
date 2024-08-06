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

    if(!is_array($all_variations)) {
        return 0;
    }

    $c = count($all_variations);
    $variations = $all_variations;
    if ($epim_update_running != 'Preparing to import products') {
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
                return 1;
            }
            /*$limit_loop++;
            if($limit_loop>10) break;*/
        }
    } else {
        return 0;
    }
    cron_log('Products Imported.');
    update_option('_epim_background_process_data', '');
    update_option('_epim_background_current_index', 0);
    return 2;


}

//0: Failed/Stopped/Nothing to do | 1: Still running | 2: Finished
function epimapi_sort_attributes() {
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
                            $attributeWCslug = 'type-name';
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
    } else {
        update_option('_epim_background_current_index', 0);
        cron_log('No Attribute Data');
        update_option('_epim_background_process_data', '');
        update_option('_epim_background_attribute_data', '');
        update_option('_epim_background_product_attribute_data', '');
        return 0;
    }

    update_option('_epim_background_process_data', '');
    update_option('_epim_background_attribute_data', '');
    update_option('_epim_background_product_attribute_data', '');

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
            } else {
                $product_link_data_2 = array_slice($product_link_data, 3000);
                update_option('_epim_background_current_index', 0);
                cron_log('Preparing to link Products to attributes');
                update_option('_epim_background_process_data', $product_link_data_1);
                update_option('_epim_background_attribute_data', $product_link_data_2);
                update_option('_epim_background_product_attribute_data', '');
            }

        } else {
            update_option('_epim_background_current_index', 0);
            cron_log('Preparing to link Products to attributes');
            update_option('_epim_background_process_data', $product_link_data);
            update_option('_epim_background_attribute_data', '');
            update_option('_epim_background_product_attribute_data', '');
        }
        return 2;
    } else {
        update_option('_epim_background_current_index', 0);
        cron_log('No Attribute Data');
        update_option('_epim_background_process_data', '');
        update_option('_epim_background_attribute_data', '');
        update_option('_epim_background_product_attribute_data', '');
        return 0;
    }

}

//0: Failed/Stopped/Nothing to do | 1: Still running | 2: Finished
function epimapi_link_attributes() {
    update_option('_epim_update_running', 'Linking attributes to products');
    $time_start = microtime(true);
    $epim_background_updates_max_run_time = get_option('epim_background_updates_max_run_time');
    $product_link_data = get_option('_epim_background_process_data');
    $product_link_data_2 = get_option('_epim_background_attribute_data');
    $product_link_data_3 = get_option('_epim_background_product_attribute_data');

    if(($product_link_data=='')&&($product_link_data_2=='')&&($product_link_data_3=='')) return 0;

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
                update_option('_epim_background_process_data', $product_set_data);
                return 1;
            }
            $i++;
            if (($i % 10) == 0) {
                cron_log($i . ' products linked');
            }
        }
        update_option('_epim_background_process_data', '');
    }


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
                update_option('_epim_background_attribute_data', $product_set_data);
                return 1;
            }
            $i++;
            if (($i % 10) == 0) {
                cron_log($i . ' products linked');
            }
        }
        update_option('_epim_background_attribute_data', '');
    }


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
                update_option('_epim_background_product_attribute_data', $product_set_data);
                return 1;
            }
            $i++;
            if (($i % 10) == 0) {
                cron_log($i . ' products linked');
            }
        }
        update_option('_epim_background_product_attribute_data', '');
    }

    update_option('_epim_background_current_index', 0);
    update_option('_epim_background_process_data', '');
    update_option('_epim_background_attribute_data', '');
    update_option('_epim_background_product_attribute_data', '');
    return 2;
}

//0: Failed/Stopped/Nothing to do | 1: Still running | 2: Finished
function epimapi_import_images() {

    $epim_update_running = get_option('_epim_update_running');
    $time_start = microtime(true);
    $epim_background_updates_max_run_time = get_option('epim_background_updates_max_run_time');

    if ($epim_update_running == 'Preparing to Import Images') {
        $args = array('post_type' => 'product', 'posts_per_page' => -1, 'fields' => 'ids');
        $product_posts = get_posts($args);
        $product_set_data = $product_posts;
    } else {
        $product_posts = get_option('_epim_background_process_data');
        $product_set_data = $product_posts;
    }

    update_option('_epim_update_running', 'Importing Images');

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
                update_option('_epim_background_process_data', $product_set_data);
                return 1;
            }
        }
    }
    update_option('_epim_background_current_index', 0);
    update_option('_epim_background_process_data', '');
    update_option('_epim_background_attribute_data', '');
    update_option('_epim_background_product_attribute_data', '');

    return 2;
}