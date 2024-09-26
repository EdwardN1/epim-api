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
    swp_daily_menus();
    swp_daily_consumer_menus();
    //swp_daily_applications();
    //swp_delete_all_products();
    //swp_daily_products();
    swp_daily_menu_files();
    swp_daily_consumer_menu_files();

    //swp_delete_all_images();
    //swp_get_current_media();
    //swp_test_stuff();
}

function swp_get_current_media()
{
    $all_media = array();
    $current_media = array();
    $att_args = array(
        'post_type' => 'attachment',
        'numberposts' => -1,
        'post_status' => null,
        'post_parent' => null, // any parent
    );
    $attachments = get_posts($att_args);

    foreach ($attachments as $attachment) {
        $this_media = array(
            'id' => $attachment->ID,
            'post_name' => $attachment->post_title,
            'post_status' => $attachment->post_status,
            'guid' => $attachment->guid,

        );
        $all_media[] = $this_media;
        if (get_field('sylvania_import', $attachment->ID) == 1) $current_media[] = $this_media;
    }
    //error_log(print_r($current_media,true));
    //error_log('number of current media = '.count($current_media));
    //error_log('number of all media = '.count($all_media));
    return $current_media;
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
    /*$p_args = array(
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
    error_log('Test Stuff Finished');*/
    /*$url = 'https://media.sylvania-group.com/assets/8796388479436/ProductInsaverSlim1653998843513_ProductImages_3.jpg';
    $path = parse_url($url, PHP_URL_PATH);
    error_log(pathinfo(basename($path), PATHINFO_FILENAME));*/
    /*$att_args = array(
        'post_type' => 'attachment',
        'numberposts' => -1,
        'post_status' => null,
        'post_parent' => null, // any parent
    );
    $attachments = get_posts($att_args);
    error_log(print_r($attachments, true));*/
}

function swp_delete_all_products()
{
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
        wp_delete_post($value, true);
    }
    error_log('Products deleted');
}

function swp_delete_all_images()
{
    $args = array(
        'post_type' => 'attachment',
        'numberposts' => -1,
        'post_status' => null,
        'post_parent' => null, // any parent
    );
    $attachments = get_posts($args);
    $epI = 0;

    if ($attachments) {
        $a_ids = array();
        $a_names = array();
        foreach ($attachments as $attachment) {
            if (get_field('sylvania_import', $attachment->ID) == 1) {
                $a_names[] = $attachment->post_title;
            }
        }
        foreach ($attachments as $attachment) {
            if (in_array($attachment->post_title, $a_names)) $a_ids[] = $attachment->ID;
        }
        foreach ($a_ids as $a_id) {
            if (wp_delete_attachment($a_id, true)) {
                $epI++;
            }
        }
    }
    error_log(' Number of imported images deleted = ' . $epI);
}

function swp_daily_products()
{

    $current_media = swp_get_current_media();

    $a_args = array(
        'numberposts' => -1,
        'post_type' => 'api_application'
    );
    $applications = get_posts($a_args);
    foreach ($applications as $application) {
        $applicationmainassetpath = get_field('applicationmainassetpath', $application->ID);
        $application_term = term_exists($application->post_title, 'applications');
        if (!$application_term) {
            $new_term_id = wp_insert_term($application->post_title, 'applications');
            if (!is_wp_error($new_term_id)) {
                $application_term = $new_term_id;
            }
        } else {
            if (is_array($application_term)) {
                $application_term = $application_term['term_id'];
            }
        }
        if ($application_term) {
            //$applicationmainassetpath = get_field('applicationmainassetpath',$application->ID);
            if ($applicationmainassetpath != '/img/no-image-available.jpg') {
                $f_imageID = 0;
                $media_path = parse_url($applicationmainassetpath, PHP_URL_PATH);
                $media_title = pathinfo(basename($media_path), PATHINFO_FILENAME);
                foreach ($current_media as $media_item) {
                    if ($media_item['post_name'] == $media_title) $f_imageID = $media_item['id'];
                }
                if ($f_imageID == 0) {
                    $f_imageID = swp_uploadMedia($applicationmainassetpath);
                    if ($f_imageID) $current_media[] = array(
                        'id' => $f_imageID,
                        'post_name' => $media_title
                    );
                }
                if ($f_imageID) {
                    update_field('image', $f_imageID, 'applications_' . $application_term);
                    update_field('sylvania_import', 1, $f_imageID);
                    $row = array(
                        'type' => 'application',
                        'id' => $application->post_title,
                    );
                    $pid_added = false;
                    if (have_rows('used_by', $f_imageID)) {
                        while (have_rows('used_by', $f_imageID)): the_row();
                            if (get_sub_field('id') == $application->post_title) $pid_added = true;
                        endwhile;
                    }
                    if (!$pid_added) add_row('used_by', $row, $f_imageID);
                }
            }
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
        $cat_ids = array();
        if (have_rows('applications', $api_product->ID)):
            while (have_rows('applications', $api_product->ID)): the_row();
                $cat_ids[] = get_sub_field('applicationhybrispk');
            endwhile;
        endif;
        if (have_rows('product_data', $api_product->ID)):
            while (have_rows('product_data', $api_product->ID)): the_row();
                $productid = get_sub_field('productid');
                $productmainassetpath = get_sub_field('productmainassetpath');
                $productpath = get_sub_field('productpath');
                $productname = get_sub_field(('productname'));
                if (!in_array($productid, $current_products)) {


                    $newProduct_args = array(
                        'post_title' => $productname,
                        'post_type' => 'products',
                        'post_status' => 'publish',
                        'post_author' => $cron_user_id,
                        'post_content' => $productname,
                        //'post_category' => $cat_ids
                    );
                    $new_product_id = wp_insert_post($newProduct_args);
                    if (!is_wp_error($new_product_id)) {
                        //error_log('===========================$cat_ids========================');
                        //error_log(print_r($cat_ids,true));
                        $terms_set = wp_set_object_terms($new_product_id, $cat_ids, 'applications');
                        //error_log('===========================$terms_set========================');
                        //error_log(print_r($terms_set,true));
                        $current_products[] = $productid;
                        update_field('productid', $productid, $new_product_id);
                        update_field('sylvania_link', $productpath, $new_product_id);
                    }
                    if (!is_wp_error($new_product_id)) {
                        $current_products[] = $productid;
                        update_field('sylvania_link', $productpath, $new_product_id);

                        if ($productmainassetpath != '/img/no-image-available.jpg') {
                            $f_imageID = 0;
                            $media_path = parse_url($productmainassetpath, PHP_URL_PATH);
                            $media_title = pathinfo(basename($media_path), PATHINFO_FILENAME);
                            foreach ($current_media as $media_item) {
                                if ($media_item['post_name'] == $media_title) $f_imageID = $media_item['id'];
                            }
                            //error_log($media_title.' - $f_imageID = '.$f_imageID);
                            if ($f_imageID == 0) {
                                $f_imageID = swp_uploadMedia($productmainassetpath);
                                if ($f_imageID) $current_media[] = array(
                                    'id' => $f_imageID,
                                    'post_name' => $media_title
                                );
                            }
                            //$f_imageID = swp_uploadMedia($productmainassetpath);
                            if ($f_imageID) {
                                set_post_thumbnail($new_product_id, $f_imageID);
                                update_field('sylvania_import', 1, $f_imageID);
                                $row = array(
                                    'type' => 'product',
                                    'id' => $productid
                                );
                                $pid_added = false;
                                if (have_rows('used_by', $f_imageID)) {
                                    while (have_rows('used_by', $f_imageID)): the_row();
                                        if (get_sub_field('id') == $productid) $pid_added = true;
                                    endwhile;
                                }
                                if (!$pid_added) add_row('used_by', $row, $f_imageID);
                            }
                        }

                    }
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
                                    update_field('applicationmainassetpath', $ApplicationMainAssetPath, $a_post_id);
                                    update_field('applicationhybrispk', $ApplicationHybrisPK, $a_post_id);
                                    $a_row = array(
                                        'language' => $wpml_extension,
                                        'applicationname' => $ApplicationName,
                                        'applicationkey' => $ApplicationKey
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

function swp_daily_menu_files()
{
    $current_language = apply_filters('wpml_current_language', NULL);
    $wpml_languages = apply_filters('wpml_active_languages', NULL, 'orderby=id&order=desc');
    $avail_languages = array();
    foreach ($wpml_languages as $wpml_language) {
        $avail_languages[] = $wpml_language['code'];
    }
    if (have_rows('available_languages', 'option')) {

        while (have_rows('available_languages', 'option')) : the_row();
            $wpml_extension = get_sub_field('wpml_extension');
            if (in_array($wpml_extension, $avail_languages)) {
                if (!is_dir(swp_PLUGINPATH . 'menu/' . $wpml_extension)) mkdir(swp_PLUGINPATH . 'menu/' . $wpml_extension, 0755, true);
                do_action('wpml_switch_language', $wpml_extension);
                $menu_name = 'primary-menu';
                $locations = get_nav_menu_locations();
                $menu_id = $locations[$menu_name];
                $menu = wp_get_nav_menu_object($menu_id);
                $menu_items = wp_get_nav_menu_items($menu);
                $zero_level = array();
                $first_level = array();
                $second_level = array();
                $all_items = array();
                foreach ($menu_items as $menu_item) {
                    $ID = $menu_item->ID;
                    $post_title = $menu_item->post_title;
                    $url = $menu_item->url;
                    $menu_item_parent = $menu_item->menu_item_parent;
                    $this_item = array(
                        'ID' => $ID,
                        'Title' => $post_title,
                        'url' => $url,
                        'parent' => $menu_item_parent
                    );
                    $all_items[] = $this_item;
                }
                foreach ($all_items as $an_item) {
                    if($an_item['parent']==0) {
                        $zero_level[] = $an_item;
                    }
                }
                //error_log(print_r($all_items,true));
                foreach ($zero_level as $parent) {
                    foreach ($all_items as $an_item) {
                        if($an_item['parent']==$parent['ID']) {
                            $first_level[] = $an_item;
                        }
                    }
                }
                //error_log(print_r($all_items,true));
                foreach ($first_level as $parent) {
                    foreach ($all_items as $an_item) {
                        if($an_item['parent']==$parent['ID']) {
                            $second_level[] = $an_item;
                        }
                    }
                }
                $export = array();
                foreach ($zero_level as $zl) {
                    $e_item = $zl;
                    $e_item['children'] = array();
                    foreach ($first_level as $fl) {
                        if($fl['parent']==$zl['ID']) {
                            $z_child = $fl;
                            $z_child['children'] = array();
                            foreach ($second_level as $sl) {
                                if($sl['parent']==$fl['ID']) {
                                    $fl_child = $sl;
                                    $z_child['children'][] = $fl_child;
                                }
                            }
                            $e_item['children'][] = $z_child;
                        }
                    }
                    $export[] = $e_item;
                }
                $menu_json = json_encode($export,JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
                if($menu_json) {
                    $menu_file = fopen(swp_PLUGINPATH . 'menu/' . $wpml_extension.'/main-menu.json',"w");
                    fwrite($menu_file,$menu_json);
                    fclose($menu_file);
                }
                //error_log(print_r(json_encode($export),true));
            }
        endwhile;
    }
}
function swp_daily_consumer_menu_files()
{
    $current_language = apply_filters('wpml_current_language', NULL);
    $wpml_languages = apply_filters('wpml_active_languages', NULL, 'orderby=id&order=desc');
    $avail_languages = array();
    foreach ($wpml_languages as $wpml_language) {
        $avail_languages[] = $wpml_language['code'];
    }
    if (have_rows('available_languages', 'option')) {

        while (have_rows('available_languages', 'option')) : the_row();
            $wpml_extension = get_sub_field('wpml_extension');
            if (in_array($wpml_extension, $avail_languages)) {
                if (!is_dir(swp_PLUGINPATH . 'menu/' . $wpml_extension)) mkdir(swp_PLUGINPATH . 'menu/' . $wpml_extension, 0755, true);
                do_action('wpml_switch_language', $wpml_extension);
                $menu_name = 'syl_consumer_menu';
                $locations = get_nav_menu_locations();
                $menu_id = $locations[$menu_name];
                $menu = wp_get_nav_menu_object($menu_id);
                $menu_items = wp_get_nav_menu_items($menu);
                $zero_level = array();
                $first_level = array();
                $second_level = array();
                $all_items = array();
                foreach ($menu_items as $menu_item) {
                    $ID = $menu_item->ID;
                    $post_title = $menu_item->post_title;
                    $url = $menu_item->url;
                    $menu_item_parent = $menu_item->menu_item_parent;
                    $this_item = array(
                        'ID' => $ID,
                        'Title' => $post_title,
                        'url' => $url,
                        'parent' => $menu_item_parent
                    );
                    $all_items[] = $this_item;
                }
                foreach ($all_items as $an_item) {
                    if($an_item['parent']==0) {
                        $zero_level[] = $an_item;
                    }
                }
                //error_log(print_r($all_items,true));
                foreach ($zero_level as $parent) {
                    foreach ($all_items as $an_item) {
                        if($an_item['parent']==$parent['ID']) {
                            $first_level[] = $an_item;
                        }
                    }
                }
                //error_log(print_r($all_items,true));
                foreach ($first_level as $parent) {
                    foreach ($all_items as $an_item) {
                        if($an_item['parent']==$parent['ID']) {
                            $second_level[] = $an_item;
                        }
                    }
                }
                $export = array();
                foreach ($zero_level as $zl) {
                    $e_item = $zl;
                    $e_item['children'] = array();
                    foreach ($first_level as $fl) {
                        if($fl['parent']==$zl['ID']) {
                            $z_child = $fl;
                            $z_child['children'] = array();
                            foreach ($second_level as $sl) {
                                if($sl['parent']==$fl['ID']) {
                                    $fl_child = $sl;
                                    $z_child['children'][] = $fl_child;
                                }
                            }
                            $e_item['children'][] = $z_child;
                        }
                    }
                    $export[] = $e_item;
                }
                $menu_json = json_encode($export,JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
                if($menu_json) {
                    $menu_file = fopen(swp_PLUGINPATH . 'menu/' . $wpml_extension.'/consumer-menu.json',"w");
                    fwrite($menu_file,$menu_json);
                    fclose($menu_file);
                }
                //error_log(print_r(json_encode($export),true));
            }
        endwhile;
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
            $products_translation = strtolower(get_sub_field('products_name'));
            $JSON = swp_make_api_call($api_root);
            $primary_menu_name = false;
            //error_log($language . ' - ' . $wpml_extension);
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
                                'menu-item-url' => $product_link_root . 'professional/'.$products_translation.'/',
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
                                        'menu-item-url' => $product_link_root . 'professional/'.$products_translation.'/' . $cat_slug,
                                        'menu-item-parent-id' => $productsID,
                                        'menu-item-status' => 'publish'
                                    ));
                                    $menu_items = $menu_product['Children'];
                                    foreach ($menu_items as $menu_item) {
                                        wp_update_nav_menu_item($menu->term_id, 0, array(
                                            'menu-item-title' => __($menu_item['Name']),
                                            'menu-item-classes' => 'swp_product_menu_item',
                                            'menu-item-url' => $product_link_root . 'professional/'.$products_translation.'/' . $cat_slug . '/' . $menu_item['URL'] . '/',
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


}

function swp_daily_consumer_menus()
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
            $products_translation = strtolower(get_sub_field('products_name'));
            $JSON = swp_make_api_call($api_root);
            $primary_menu_name = false;
            //error_log($language . ' - ' . $wpml_extension);
            if (in_array($wpml_extension, $avail_languages)) {
                if ($JSON) {
                    do_action('wpml_switch_language', $wpml_extension);
                    $menu_array = json_decode($JSON, true);
                    $menu_products = $menu_array['Children'][1]['Children'][0]['Children'];
                    $menu1URL = $menu_array['Children'][1]['URL'];
                    $menu2URL = $menu_array['Children'][1]['Children'][0]['URL'];

                    $menu_name = 'syl_consumer_menu';
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
                                'menu-item-url' => $product_link_root . $menu1URL.'/'.$menu2URL,
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



                            foreach ($menu_products as $menu_product) {
                                $menu_name = $menu_product['Name'];
                                $cat_slug = $menu_product['URL'];
                                if ($cat_slug) {
                                    $cat_menu_id = wp_update_nav_menu_item($menu->term_id, 0, array(
                                        'menu-item-title' => __($menu_name),
                                        'menu-item-classes' => 'swp_product_menu_item',
                                        'menu-item-url' => $product_link_root . $menu1URL.'/'.$menu2URL.'/' . $cat_slug,
                                        'menu-item-parent-id' => $productsID,
                                        'menu-item-status' => 'publish'
                                    ));
                                    $menu_items = $menu_product['Children'];
                                    foreach ($menu_items as $menu_item) {
                                        wp_update_nav_menu_item($menu->term_id, 0, array(
                                            'menu-item-title' => __($menu_item['Name']),
                                            'menu-item-classes' => 'swp_product_menu_item',
                                            'menu-item-url' => $product_link_root . $menu1URL.'/'.$menu2URL.'/' . $cat_slug . '/' . $menu_item['URL'] . '/',
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


}