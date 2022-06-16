<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $is_divi;

if($is_divi) {
    $menu_exists = wp_get_nav_menu_object( 'ePim Category Menu' );
    if( !$menu_exists) {
        $menu_id = wp_create_nav_menu('ePim Category Menu');
    }
}

function epim_generate_category_menu($max_parents = 10) {
    global $is_divi;
    if ($is_divi) {
        $menu = wp_get_nav_menu_object( 'ePim Category Menu' );
        $menu_items = wp_get_nav_menu_items($menu->term_id);
        if ( ! empty( $menu_items ) ) {
            foreach ( $menu_items as $item ) {
                //error_log('wp_delete_post $item ='.$item);
                wp_delete_post( $item->ID );
            }
            $categories = get_terms( ['taxonomy' => 'product_cat', 'hide_empty' => true, 'parent' => 0] );
            $i = 1;
            foreach ($categories as $category) {
                if($category->slug != 'uncategorized') {
                    //error_log('adding category: '.$category->name);
                    if($i <= $max_parents) {
                        $parent_menu_id = wp_update_nav_menu_item($menu->term_id, 0, array(
                            'menu-item-title' => __($category->name),
                            'menu-item-classes' => 'epim_parent_menu_item',
                            'menu-item-url' => get_category_link($category->term_id),
                            'menu-item-status' => 'publish'));
                        $i++;
                        //error_log(print_r($parent_menu_id, true));
                        if($parent_menu_id) {
                            $sub_categories = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => true, 'parent' => $category->term_id]);
                            //error_log(print_r($sub_categories,true));
                            foreach ($sub_categories as $sub_category) {
                                $Sub_parent_menu_id = wp_update_nav_menu_item($menu->term_id, 0, array(
                                    'menu-item-title' => __($sub_category->name),
                                    'menu-item-classes' => 'epim_sub_menu_item',
                                    'menu-item-url' => get_category_link($sub_category->term_id),
                                    'menu-item-parent-id' => $parent_menu_id,
                                    'menu-item-status' => 'publish'));
                                if(is_wp_error($Sub_parent_menu_id)) {
                                    //error_log($Sub_parent_menu_id->get_error_message());
                                }
                            }
                        }
                    }
                }

            }
        }
        //error_log(print_r($menu,true));
    }
}
