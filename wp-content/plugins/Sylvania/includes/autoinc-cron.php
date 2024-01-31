<?php

if (!defined('ABSPATH')) {
    exit;
}

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
    $all_menus = wp_get_nav_menus();
    //error_log(print_r($all_menus,true));
    $get_fr = get_term(42679);
    $fr_meta = get_term_meta(42669);
    error_log(print_r($fr_meta,true));
    $ENGjson = swp_make_api_call(swp_EN_API);
    $FRjson = swp_make_api_call(swp_FR_API);

    $primary_menu_name = false;

    if ($ENGjson) {

        $eng_menu_array = json_decode($ENGjson, true);

        //error_log(print_r($eng_menu_array,true));

        $menu_name = 'primary-menu';
        $locations = get_nav_menu_locations();
        //error_log(print_r($locations,true));
        $menu_id = $locations[$menu_name];
        $menu = wp_get_nav_menu_object($menu_id);
        $menu_items = wp_get_nav_menu_items($menu);

        $primary_menu_name = $menu->name;

        //error_log(print_r($menu_items,true));

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

                foreach ($eng_menu_array as $eng_menu_cat => $eng_menu_items) {
                    $cat_first = $eng_menu_items[0];
                    $cat_slug = $cat_first[2];
                    if ($cat_slug) {
                        $cat_menu_id = wp_update_nav_menu_item($menu->term_id, 0, array(
                            'menu-item-title' => __($eng_menu_cat),
                            'menu-item-classes' => 'swp_product_menu_item',
                            'menu-item-url' => swp_ENG_ROOT_URI . 'category/' . $cat_slug,
                            'menu-item-parent-id' => $productsID,
                            'menu-item-status' => 'publish'
                        ));
                        foreach ($eng_menu_items as $eng_menu_item) {
                            if (is_array($eng_menu_item)) {
                                if (count($eng_menu_item) == 3) {
                                    if ($eng_menu_item[2] = $cat_slug) {
                                        wp_update_nav_menu_item($menu->term_id, 0, array(
                                            'menu-item-title' => __($eng_menu_item[0]),
                                            'menu-item-classes' => 'swp_product_menu_item',
                                            'menu-item-url' => swp_ENG_ROOT_URI . 'category/' . $cat_slug . '/' . $eng_menu_item[1] . '/families',
                                            'menu-item-parent-id' => $cat_menu_id,
                                            'menu-item-status' => 'publish'
                                        ));
                                    }
                                }
                            }
                        }

                    }
                }

            }

        }
        error_log('English Menu Processed');
    } else {
        error_log('No menu retreived');
    }

    if ($FRjson&&$primary_menu_name) {

        $fr_menu_array = json_decode($FRjson, true);

        //error_log(print_r($fr_menu_array,true));

        $fr_menu_name = $primary_menu_name.'-fr';

        $fr_menu = wp_get_nav_menu_object($fr_menu_name);

        //error_log(print_r($fr_menu,true));
        $menu_items = wp_get_nav_menu_items($fr_menu);

        //error_log(print_r($menu_items,true));

        $productsID = -1;

        if (!empty($menu_items)) {
            foreach ($menu_items as $item) {
                //error_log($item->title);
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

            $menu_items = wp_get_nav_menu_items($fr_menu);
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

                foreach ($fr_menu_array as $fr_menu_cat => $fr_menu_items) {
                    $cat_first = $fr_menu_items[0];
                    $cat_slug = $cat_first[2];
                    if ($cat_slug) {
                        $cat_menu_id = wp_update_nav_menu_item($menu->term_id, 0, array(
                            'menu-item-title' => __($fr_menu_cat),
                            'menu-item-classes' => 'swp_product_menu_item',
                            'menu-item-url' => swp_FR_ROOT_URI . 'category/' . $cat_slug,
                            'menu-item-parent-id' => $productsID,
                            'menu-item-status' => 'publish'
                        ));
                        foreach ($fr_menu_items as $fr_menu_item) {
                            if (is_array($fr_menu_item)) {
                                if (count($fr_menu_item) == 3) {
                                    if ($fr_menu_item[2] = $cat_slug) {
                                        wp_update_nav_menu_item($menu->term_id, 0, array(
                                            'menu-item-title' => __($fr_menu_item[0]),
                                            'menu-item-classes' => 'swp_product_menu_item',
                                            'menu-item-url' => swp_FR_ROOT_URI . 'category/' . $cat_slug . '/' . $fr_menu_item[1] . '/families',
                                            'menu-item-parent-id' => $cat_menu_id,
                                            'menu-item-status' => 'publish'
                                        ));
                                    }
                                }
                            }
                        }

                    }
                }

            }

        }
        error_log('French Menu Processed');
    } else {
        error_log('No menu retreived');
    }

}