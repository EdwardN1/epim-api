<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

register_activation_hook( swp_PLUGINFILE, 'swp_cron_activation' );

function swp_cron_activation() {
    error_log( 'checking and adding cron events' );
    if ( ! wp_next_scheduled( 'swp_daily_action' ) ) {
        wp_schedule_event( strtotime( '23:50:00' ), 'daily', 'swp_daily_action' );
    }

}

add_action( 'swp_daily_action', 'swp_daily' );

function swp_daily() {
    $ENGjson = swp_make_api_call(swp_EN_API);
    $FRjson = swp_make_api_call(swp_FR_API);

    $eng_menu_array = json_decode($ENGjson,true);

    //error_log(print_r($eng_menu_array,true));

    $menu_name = 'primary-menu';
    $locations = get_nav_menu_locations();
    //error_log(print_r($locations,true));
    $menu_id   = $locations[ $menu_name ] ;
    $menu      =  wp_get_nav_menu_object( $menu_id );
    $menu_items = wp_get_nav_menu_items( $menu );

    //error_log(print_r($menu_items,true));

    $productsID = -1;

    if ( ! empty( $menu_items ) ) {
        foreach ( $menu_items as $item ) {
            if($item->title=="Products") {
                $productsID = $item->ID;
            }
        }
        if($productsID<0) {
            $productsID = wp_update_nav_menu_item( $menu->term_id, 0, array(
                'menu-item-title'   => __( 'Products' ),
                'menu-item-classes' => 'swp_product_menu_item',
                'menu-item-url'     => swp_ENG_ROOT_URI.'categories/',
                'menu-item-status'  => 'publish'
            ) );
        }


        $categories_to_delete = array();

        $menu_items = wp_get_nav_menu_items( $menu );
        if($productsID>0) {

            foreach ( $menu_items as $item ) {
                if($item->menu_item_parent==$productsID) {
                    $categories_to_delete[] = $item->ID;
                }

            }

            foreach ($categories_to_delete as $cm_item) {
                foreach ( $menu_items as $item ) {
                    if($cm_item==$item->menu_item_parent) {
                        wp_delete_post( $item->ID );
                    }
                }
            }

            foreach ($categories_to_delete as $cm_item) {
                wp_delete_post( $cm_item );
            }

            foreach ($eng_menu_array as $eng_menu_cat=>$eng_menu_items) {
                $cat_first = $eng_menu_items[0];
                $cat_slug = $cat_first[2];
                if($cat_slug) {
                    $cat_menu_id = wp_update_nav_menu_item( $menu->term_id, 0, array(
                        'menu-item-title'   => __( $eng_menu_cat ),
                        'menu-item-classes' => 'swp_product_menu_item',
                        'menu-item-url'     => swp_ENG_ROOT_URI.'category/'.$cat_slug,
                        'menu-item-parent-id' => $productsID,
                        'menu-item-status'  => 'publish'
                    ) );
                    foreach ($eng_menu_items as $eng_menu_item) {
                        if(is_array($eng_menu_item)) {
                            if (count($eng_menu_item)==3) {
                                if($eng_menu_item[2]=$cat_slug) {
                                    wp_update_nav_menu_item( $menu->term_id, 0, array(
                                        'menu-item-title'   => __( $eng_menu_item[0] ),
                                        'menu-item-classes' => 'swp_product_menu_item',
                                        'menu-item-url'     => swp_ENG_ROOT_URI.'category/'.$cat_slug.'/'.$eng_menu_item[1].'/families',
                                        'menu-item-parent-id' => $cat_menu_id,
                                        'menu-item-status'  => 'publish'
                                    ));
                                }
                            }
                        }
                    }

                }
            }

        }



    }

}