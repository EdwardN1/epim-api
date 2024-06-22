<?php

if (!defined('ABSPATH')) {
    exit;
}

require_once(ABSPATH . 'wp-admin/includes/post.php');

register_activation_hook(swp_PLUGINFILE, 'swp_cron_activation');

function swp_cron_activation()
{
    error_log('checking and adding cron events');
    if (!wp_next_scheduled('swp_daily_action')) {
        wp_schedule_event(strtotime('23:50:00'), 'daily', 'swp_daily_action');
    }

}

add_action('swp_daily_action', 'swp_daily');

function swp_daily()
{
    //swp_daily_menus();
    //swp_daily_applications();
    //swp_daily_products();
    swp_test_stuff();
}

function swp_test_stuff()
{
    /*$applications = get_terms(array(
        'taxonomy' => 'applications',
        'hide_empty' => false,
    ));
    foreach ($applications as $application) {
        error_log(print_r($application, true));
        error_log(print_r(get_fields('applications_' . $application->term_id), true));
        //update_field('applicationhybrispk','123456789','applications_'.$application->term_id);
        $new_term = wp_insert_term('test' . uniqid(), 'applications');
        error_log(print_r($new_term, true));
    }*/
    $p_args = array(
        'numberposts' => -1,
        'post_type' => 'products'
    );
    $p = get_posts($p_args);
    $ptd = array();
    foreach ($p as $item) {
        $ptd[] = $item->ID;
    }
    foreach ($ptd as $value) {
        wp_delete_post($value,true);
    }
    error_log('Test Stuff Finished');
}

function swp_daily_products()
{
    $a_args = array(
        'numberposts' => -1,
        'post_type' => 'api_application'
    );
    $applications = get_posts($a_args);
    foreach ($applications as $application) {
        if (!term_exists($application->post_title, 'application')) {
            wp_insert_term($application->post_title, 'application');
        }
    }

    $api_p_args = array(
        'numberposts' => -1,
        'post_type' => 'api_product'
    );
    $api_products = get_posts($api_p_args);
    $p_args = array(
        'numberposts' => -1,
        'post_type' => 'products'
    );
    $products = get_posts($p_args);
    $current_products = array();
    foreach ($products as $product) {
        $current_products[] = get_field('productid', $product->ID);
    }
    foreach ($api_products as $api_product) {
        $cron_user = get_user_by('email', get_bloginfo('admin_email'));
        $cron_user_id = $cron_user->ID;
        if (have_rows('product_data', $api_product->ID)):
            while (have_rows('product_data', $api_product->ID)): the_row();
                $productid = get_sub_field('productid');
                $productmainassetpath = get_sub_field('productmainassetpath');
                $productpath = get_sub_field('productpath');
                $productname = get_sub_field(('productname'));
                if (!in_array($productid, $current_products)) {
                    $cat_ids = array();
                    if (have_rows('applications' . $api_product->ID)):
                        while (have_rows('applications', $api_product->ID)): the_row();
                            $cat_ids[] = get_sub_field('wp_id');
                        endwhile;
                    endif;

                    $newProduct_args = array(
                        'post_title' => $productname,
                        'post_type' => 'products',
                        'post_status' => 'publish',
                        'post_author' => $cron_user_id,
                        'post_content' => $productname,
                        'post_category' => $cat_ids
                    );
                    $new_product_id = wp_insert_post($newProduct_args);
                    /*if(!is_wp_error($new_product_id)) {
                        $current_products[] = $productid;
                        update_field('sylvania_link',$productpath,$new_product_id);
                        $f_imageID = swp_uploadMedia($productmainassetpath);
                        if($f_imageID) {
                            set_post_thumbnail($new_product_id,$f_imageID);
                        }
                    }*/
                }
            endwhile;
        endif;
    }
    error_log('swp_daily_products finished');
}

function swp_daily_applications()
{
    $current_language = apply_filters('wpml_current_language', NULL);
    $wpml_languages = apply_filters('wpml_active_languages', NULL, 'orderby=id&order=desc');
    $avail_languages = array();
    foreach ($wpml_languages as $wpml_language) {
        $avail_languages[] = $wpml_language['code'];
    }
    if (have_rows('available_languages', 'option')) {
        while (have_rows('available_languages', 'option')) : the_row();
            $language = get_sub_field('language');
            $wpml_extension = get_sub_field('wpml_extension');
            $product_link_root = get_sub_field('product_link_root');
            $applications_root = get_sub_field('applications_root');
            $JSON = swp_make_api_call($applications_root);
            if (in_array($wpml_extension, $avail_languages)) {
                if ($JSON) {
                    do_action('wpml_switch_language', $wpml_extension);
                    $application_data = json_decode($JSON, true);
                    if ($application_data) {
                        foreach ($application_data as $product) {
                            $ApplicationHybrisPK = $product['ApplicationHybrisPK'];
                            $ApplicationName = $product['ApplicationName'];
                            $ApplicationKey = $product['ApplicationKey'];
                            $ProductId = $product['ProductId'];
                            $ApplicationMainAssetPath = $product['ApplicationMainAssetPath'];
                            $ProductMainAssetPath = $product['ProductMainAssetPath'];
                            $ProductPath = $product['ProductPath'];
                            $ProductName = $product['ProductName'];
                            $a_post_id = post_exists($ApplicationHybrisPK, '', '', 'api_application');
                            if ($a_post_id == 0) {
                                $cron_user = get_user_by('email', get_bloginfo('admin_email'));
                                $cron_user_id = $cron_user->ID;
                                $na_Args = array(
                                    'post_title' => $ApplicationHybrisPK,
                                    'post_type' => 'api_application',
                                    'post_status' => 'publish',
                                    'post_author' => $cron_user_id,
                                    'post_content' => $ApplicationName
                                );
                                $a_post_id = wp_insert_post($na_Args);
                            }

                            $p_post_id = post_exists($ProductId, '', '', 'api_product');
                            if ($p_post_id == 0) {
                                $cron_user = get_user_by('email', get_bloginfo('admin_email'));
                                $cron_user_id = $cron_user->ID;
                                $np_Args = array(
                                    'post_title' => $ProductId,
                                    'post_type' => 'api_product',
                                    'post_status' => 'publish',
                                    'post_author' => $cron_user_id,
                                    'post_content' => $ProductName
                                );
                                $p_post_id = wp_insert_post($np_Args);
                            }

                            if (is_wp_error($p_post_id)) {
                                error_log($a_post_id->get_error_message());
                                $p_post_id = 0;
                            }

                            if (is_wp_error($a_post_id)) {
                                error_log($a_post_id->get_error_message());
                            } else {
                                if ($a_post_id > 0) {
                                    $a_row = array(
                                        'language' => $wpml_extension,
                                        'applicationname' => $ApplicationName,
                                        'applicationkey' => $ApplicationKey,
                                        'applicationmainassetpath' => $ApplicationMainAssetPath,
                                        'applicationhybrispk' => $ApplicationHybrisPK,
                                    );
                                    $app_language_found = false;
                                    if (have_rows('application_data', $a_post_id)):
                                        while (have_rows('application_data', $a_post_id)): the_row();
                                            if (get_sub_field('language') == $wpml_extension) {
                                                $app_language_found = true;
                                                update_sub_field('applicationname', $ApplicationName);
                                                update_sub_field('applicationkey', $ApplicationKey);
                                            }

                                        endwhile;
                                    endif;
                                    if (!$app_language_found) add_row('application_data', $a_row, $a_post_id);

                                    if ($p_post_id > 0) {
                                        $app_product_found = false;
                                        if (have_rows('products', $a_post_id)):
                                            while (have_rows('products', $a_post_id)): the_row();
                                                if (get_sub_field('productid') == $ProductId) $app_product_found = true;
                                            endwhile;
                                        endif;
                                        if (!$app_product_found) {
                                            $p_row = array(
                                                'productid' => $ProductId,
                                                'wp_id' => $p_post_id
                                            );
                                            add_row('products', $p_row, $a_post_id);
                                        }

                                        $language_found = false;
                                        if (have_rows('product_data', $p_post_id)):
                                            while (have_rows('product_data', $p_post_id)):
                                                the_row();
                                                if (get_sub_field('language') == $wpml_extension) {
                                                    $language_found = true;
                                                    //error_log('found');
                                                    update_sub_field('productname', $ProductName);
                                                    update_sub_field('productpath', $ProductPath);
                                                }
                                            endwhile;
                                        endif;
                                        if (!$language_found) {
                                            $np_row = array(
                                                'language' => $wpml_extension,
                                                'productid' => $ProductId,
                                                'productmainassetpath' => $ProductMainAssetPath,
                                                'productpath' => $ProductPath,
                                                'productname' => $ProductName
                                            );
                                            add_row('product_data', $np_row, $p_post_id);
                                        }

                                        $application_found = false;
                                        if (have_rows('applications', $p_post_id)):
                                            while (have_rows('applications', $p_post_id)): the_row();
                                                if (get_sub_field('applicationhybrispk') == $ApplicationHybrisPK) $application_found = true;
                                            endwhile;
                                        endif;
                                        if (!$application_found) {
                                            $npa_row = array(
                                                'applicationhybrispk' => $ApplicationHybrisPK,
                                                'wp_id' => $a_post_id,
                                            );
                                            add_row('applications', $npa_row, $p_post_id);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        endwhile;
        do_action('wpml_switch_language', $current_language);

        error_log('swp_daily_applications - finished');
    }
}

function swp_daily_menus()
{

    $current_language = apply_filters('wpml_current_language', NULL);
    $wpml_languages = apply_filters('wpml_active_languages', NULL, 'orderby=id&order=desc');
    $avail_languages = array();
    foreach ($wpml_languages as $wpml_language) {
        $avail_languages[] = $wpml_language['code'];
    }
    //error_log(print_r($avail_languages,true));
    if (have_rows('available_languages', 'option')) {
        while (have_rows('available_languages', 'option')) : the_row();
            $language = get_sub_field('language');
            $wpml_extension = get_sub_field('wpml_extension');
            $api_root = get_sub_field('api_root');
            $product_link_root = get_sub_field('product_link_root');
            $products_name = get_sub_field('products_name');

            $JSON = swp_make_api_call($api_root);
            $primary_menu_name = false;
            error_log($language . ' - ' . $wpml_extension);
            if (in_array($wpml_extension, $avail_languages)) {
                if ($JSON) {
                    do_action('wpml_switch_language', $wpml_extension);
                    $menu_array = json_decode($JSON, true);

                    $menu_name = 'primary-menu';
                    $locations = get_nav_menu_locations();
                    $menu_id = $locations[$menu_name];
                    $menu = wp_get_nav_menu_object($menu_id);
                    $menu_items = wp_get_nav_menu_items($menu);

                    $primary_menu_name = $menu->name;

                    $productsID = -1;

                    if (!empty($menu_items)) {
                        foreach ($menu_items as $item) {
                            if ($item->title == $products_name) {
                                $productsID = $item->ID;
                            }
                        }
                        if ($productsID < 0) {
                            $productsID = wp_update_nav_menu_item($menu->term_id, 0, array(
                                'menu-item-title' => __($products_name),
                                'menu-item-classes' => 'swp_product_menu_item',
                                'menu-item-url' => $product_link_root . 'professional/products/',
                                'menu-item-status' => 'publish'
                            ));
                        }

                        $categories_to_delete = array();

                        $menu_items = wp_get_nav_menu_items($menu);
                        if ($productsID > 0) {

                            foreach ($menu_items as $item) {
                                if ($item->menu_item_parent == $productsID) {
                                    $categories_to_delete[] = $item->ID;
                                }

                            }

                            foreach ($categories_to_delete as $cm_item) {
                                foreach ($menu_items as $item) {
                                    if ($cm_item == $item->menu_item_parent) {
                                        wp_delete_post($item->ID);
                                    }
                                }
                            }

                            foreach ($categories_to_delete as $cm_item) {
                                wp_delete_post($cm_item);
                            }

                            $menu_products = $menu_array['Children'][0]['Children'][0]['Children'];

                            foreach ($menu_products as $menu_product) {
                                $menu_name = $menu_product['Name'];
                                $cat_slug = $menu_product['URL'];
                                if ($cat_slug) {
                                    $cat_menu_id = wp_update_nav_menu_item($menu->term_id, 0, array(
                                        'menu-item-title' => __($menu_name),
                                        'menu-item-classes' => 'swp_product_menu_item',
                                        'menu-item-url' => $product_link_root . 'professional/products/' . $cat_slug,
                                        'menu-item-parent-id' => $productsID,
                                        'menu-item-status' => 'publish'
                                    ));
                                    $menu_items = $menu_product['Children'];
                                    foreach ($menu_items as $menu_item) {
                                        wp_update_nav_menu_item($menu->term_id, 0, array(
                                            'menu-item-title' => __($menu_item['Name']),
                                            'menu-item-classes' => 'swp_product_menu_item',
                                            'menu-item-url' => $product_link_root . 'professional/products/' . $cat_slug . '/' . $menu_item['URL'] . '/',
                                            'menu-item-parent-id' => $cat_menu_id,
                                            'menu-item-status' => 'publish'
                                        ));
                                        /*if (is_array($eng_menu_item)) {
                                            if (count($eng_menu_item) == 3) {
                                                if ($eng_menu_item[2] = $cat_slug) {

                                                }
                                            }
                                        }*/
                                    }

                                }
                            }

                        }

                    }
                    error_log($language . ' Menu Processed');
                } else {
                    error_log('No menu retreived for ' . $language);
                }
            } else {
                error_log('WPML extension ' . $wpml_extension . ' is not an active language for ' . $language);
            }
        endwhile;
        do_action('wpml_switch_language', $current_language);
    }

    /*$primary_menu_name = false;

    if ($ENGjson) {

        $eng_menu_array = json_decode($ENGjson, true);

        $menu_name = 'primary-menu';
        $locations = get_nav_menu_locations();
        $menu_id = $locations[$menu_name];
        $menu = wp_get_nav_menu_object($menu_id);
        $menu_items = wp_get_nav_menu_items($menu);

        $primary_menu_name = $menu->name;

        $productsID = -1;

        if (!empty($menu_items)) {
            foreach ($menu_items as $item) {
                if ($item->title == "Products") {
                    $productsID = $item->ID;
                }
            }
            if ($productsID < 0) {
                $productsID = wp_update_nav_menu_item($menu->term_id, 0, array(
                    'menu-item-title' => __('Products'),
                    'menu-item-classes' => 'swp_product_menu_item',
                    'menu-item-url' => swp_ENG_ROOT_URI . 'categories/',
                    'menu-item-status' => 'publish'
                ));
            }

            $categories_to_delete = array();

            $menu_items = wp_get_nav_menu_items($menu);
            if ($productsID > 0) {

                foreach ($menu_items as $item) {
                    if ($item->menu_item_parent == $productsID) {
                        $categories_to_delete[] = $item->ID;
                    }

                }

                foreach ($categories_to_delete as $cm_item) {
                    foreach ($menu_items as $item) {
                        if ($cm_item == $item->menu_item_parent) {
                            wp_delete_post($item->ID);
                        }
                    }
                }

                foreach ($categories_to_delete as $cm_item) {
                    wp_delete_post($cm_item);
                }

                $eng_menu_products = $eng_menu_array['Children'][0]['Children'][0]['Children'];

                foreach ($eng_menu_products as $eng_menu_product) {
                    $eng_menu_name = $eng_menu_product['Name'];
                    $cat_slug = $eng_menu_product['URL'];
                    if ($cat_slug) {
                        $cat_menu_id = wp_update_nav_menu_item($menu->term_id, 0, array(
                            'menu-item-title' => __($eng_menu_name),
                            'menu-item-classes' => 'swp_product_menu_item',
                            'menu-item-url' => swp_ENG_ROOT_URI . 'category/' . $cat_slug,
                            'menu-item-parent-id' => $productsID,
                            'menu-item-status' => 'publish'
                        ));
                        $eng_menu_items = $eng_menu_product['Children'];
                        foreach ($eng_menu_items as $eng_menu_item) {
                            wp_update_nav_menu_item($menu->term_id, 0, array(
                                'menu-item-title' => __($eng_menu_item['Name']),
                                'menu-item-classes' => 'swp_product_menu_item',
                                'menu-item-url' => swp_ENG_ROOT_URI . 'category/' . $cat_slug . '/' . $eng_menu_item['URL'] . '/families',
                                'menu-item-parent-id' => $cat_menu_id,
                                'menu-item-status' => 'publish'
                            ));
                        }

                    }
                }

            }

        }
        error_log('English Menu Processed');
    } else {
        error_log('No menu retreived');
    }

    $primary_menu_name = false;*/


}