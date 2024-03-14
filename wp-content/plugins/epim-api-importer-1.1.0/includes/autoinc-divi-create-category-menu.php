<?php
if (!defined('ABSPATH')) {
    exit;
}

global $is_divi;

if ($is_divi) {
    $menu_exists = wp_get_nav_menu_object('ePim Category Menu');
    if (!$menu_exists) {
        $menu_id = wp_create_nav_menu('ePim Category Menu');
    }
}

function epim_clear_category_menu()
{
    global $is_divi;
    $res = false;
    if ($is_divi) {
        $menu = wp_get_nav_menu_object('ePim Category Menu');
        $menu_items = wp_get_nav_menu_items($menu->term_id);
        if (!empty($menu_items)) {
            foreach ($menu_items as $item) {
                //error_log('wp_delete_post $item ='.$item);
                wp_delete_post($item->ID,true);
            }
        }
        $res = $menu->term_id;
    }
    //error_log('epim_clear_category_menu completed');
    return $res;
}

function epim_generate_category_menu($max_parents = 10)
{
    global $is_divi;
    error_log('Generating Category Menu');
    if ($is_divi) {

        $menuID = epim_clear_category_menu();

        if (!$menuID) return;

        $allcategories = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => true, 'parent' => 0]);
        $categories = array();
        if (count($allcategories) <= $max_parents) {
            $categories = $allcategories;
        } else {
            $categories = array_slice($allcategories, 0, $max_parents);
        }

        //error_log('Number of categories to add to menu = ' . count($categories));

        $l1 = array();
        $l2 = array();
        $l3 = array();
        $l4 = array();
        $l5 = array();

        foreach ($categories as $category) {
            if ($category->slug != 'uncategorized') {
                $menu_rec = array();
                $menu_rec['parentID'] = 0;
                $menu_rec['termID'] = $category->term_id;
                $menu_rec['title'] = $category->name;
                $alias = get_term_meta( $category->term_id, 'epim_api_alias', true );

                if($alias) {
                    if($alias!='') {
                        $menu_rec['title'] = $alias;
                    }
                }
                $menu_rec['URL'] = get_category_link($category->term_id);
                $l1[] = $menu_rec;
            }
        }

        foreach ($l1 as $l1_item) {
            $l1cats = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => true, 'parent' => $l1_item['termID']]);
            foreach ($l1cats as $l1cat) {
                $menu_rec = array();
                $menu_rec['parentID'] = $l1_item['termID'];
                $menu_rec['termID'] = $l1cat->term_id;
                $menu_rec['title'] = $l1cat->name;
                $alias = get_term_meta( $l1cat->term_id, 'epim_api_alias', true );
                //error_log($menu_rec['termID'].' $alias = '.$alias);
                if($alias) {
                    if($alias!='') {
                        $menu_rec['title'] = $alias;
                    }
                }
                $menu_rec['URL'] = get_category_link($l1cat->term_id);
                $l2[] = $menu_rec;
            }
        }

        foreach ($l2 as $l2_item) {
            $l2cats = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => true, 'parent' => $l2_item['termID']]);
            foreach ($l2cats as $l2cat) {
                $menu_rec = array();
                $menu_rec['parentID'] = $l2_item['termID'];
                $menu_rec['termID'] = $l2cat->term_id;
                $menu_rec['title'] = $l2cat->name;
                $alias = get_term_meta( $l2cat->term_id, 'epim_api_alias', true );
                if($alias) {
                    if($alias!='') {
                        $menu_rec['title'] = $alias;
                    }
                }
                $menu_rec['URL'] = get_category_link($l2cat->term_id);
                $l3[] = $menu_rec;
            }
        }

        foreach ($l3 as $l3_item) {
            $l3cats = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => true, 'parent' => $l3_item['termID']]);
            foreach ($l3cats as $l3cat) {
                $menu_rec = array();
                $menu_rec['parentID'] = $l3_item['termID'];
                $menu_rec['termID'] = $l3cat->term_id;
                $menu_rec['title'] = $l3cat->name;
                $alias = get_term_meta( $l3cat->term_id, 'epim_api_alias', true );
                if($alias) {
                    if($alias!='') {
                        $menu_rec['title'] = $alias;
                    }
                }
                $menu_rec['URL'] = get_category_link($l3cat->term_id);
                $l4[] = $menu_rec;
            }
        }

        foreach ($l4 as $l4_item) {
            $l4cats = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => true, 'parent' => $l4_item['termID']]);
            foreach ($l4cats as $l4cat) {
                $menu_rec = array();
                $menu_rec['parentID'] = $l4_item['termID'];
                $menu_rec['termID'] = $l4cat->term_id;
                $menu_rec['title'] = $l4cat->name;
                $alias = get_term_meta( $l4cat->term_id, 'epim_api_alias', true );
                if($alias) {
                    if($alias!='') {
                        $menu_rec['title'] = $alias;
                    }
                }
                $menu_rec['URL'] = get_category_link($l4cat->term_id);
                $l5[] = $menu_rec;
            }
        }

        //error_log('Menu Level 1 item count = '.count($l1));
        //error_log('Menu Level 2 item count = '.count($l2));
        //error_log('Menu Level 3 item count = '.count($l3));
        //error_log('Menu Level 4 item count = '.count($l4));
        //error_log('Menu Level 5 item count = '.count($l5));

        $l1_menu_items = array();

        foreach ($l1 as $l1_menu_rec) {
            $l1_menu_item = array();

            $l1_menu_id = wp_update_nav_menu_item($menuID, 0, array(
                'menu-item-title' => $l1_menu_rec['title'],
                'menu-item-classes' => 'epim_parent_menu_item',
                'menu-item-url' => $l1_menu_rec['URL'],
                'menu-item-status' => 'publish'
            ));

            //error_log('Added '.$l1_menu_rec['title'].' ('.$l1_menu_item['termID'].')');

            $l1_menu_item['menuID'] = $l1_menu_id;
            $l1_menu_item['termID'] = $l1_menu_rec['termID'];
            $l1_menu_items[] = $l1_menu_item;
        }


        $l2_menu_items = array();

        foreach ($l2 as $l2_cat) {
            $l2_menu_items = array();
            foreach ($l1_menu_items as $l1_menu_item) {
                if($l2_cat['parentID']==$l1_menu_item['termID']) {
                    $l2_menu_id = wp_update_nav_menu_item($menuID, 0, array(
                        'menu-item-title' => $l2_cat['title'],
                        'menu-item-classes' => 'epim_sub_menu_item',
                        'menu-item-url' => $l2_cat['URL'],
                        'menu-item-parent-id' => $l1_menu_item['menuID'],
                        'menu-item-status' => 'publish'
                    ));
                    //error_log('Added '.$l1_menu_item['termID'].' -- '.$l2_menu_rec['title'].' ('.$l2_menu_rec['termID'].')');
                    $l2_menu_item['menuID'] = $l2_menu_id;
                    $l2_menu_item['termID'] = $l2_cat['termID'];
                    $l2_menu_items[] = $l2_menu_item;
                }
            }
        }

        $l3_menu_items = array();

        foreach ($l3 as $l3_cat) {
            $l3_menu_items = array();
            foreach ($l2_menu_items as $l2_menu_item) {
                if($l3_cat['parentID']==$l2_menu_item['termID']) {
                    $l3_menu_id = wp_update_nav_menu_item($menuID, 0, array(
                        'menu-item-title' => $l3_cat['title'],
                        'menu-item-classes' => 'epim_sub_menu_item',
                        'menu-item-url' => $l3_cat['URL'],
                        'menu-item-parent-id' => $l2_menu_item['menuID'],
                        'menu-item-status' => 'publish'
                    ));
                    //error_log('Added '.$l1_menu_item['termID'].' -- '.$l2_menu_rec['title'].' ('.$l2_menu_rec['termID'].')');
                    $l3_menu_item['menuID'] = $l3_menu_id;
                    $l3_menu_item['termID'] = $l3_cat['termID'];
                    $l3_menu_items[] = $l3_menu_item;
                }
            }
        }

        $l4_menu_items = array();

        foreach ($l4 as $l4_cat) {
            $l4_menu_items = array();
            foreach ($l3_menu_items as $l3_menu_item) {
                if($l4_cat['parentID']==$l3_menu_item['termID']) {
                    $l4_menu_id = wp_update_nav_menu_item($menuID, 0, array(
                        'menu-item-title' => $l4_cat['title'],
                        'menu-item-classes' => 'epim_sub_menu_item',
                        'menu-item-url' => $l4_cat['URL'],
                        'menu-item-parent-id' => $l3_menu_item['menuID'],
                        'menu-item-status' => 'publish'
                    ));
                    //error_log('Added '.$l1_menu_item['termID'].' -- '.$l2_menu_rec['title'].' ('.$l2_menu_rec['termID'].')');
                    $l4_menu_item['menuID'] = $l4_menu_id;
                    $l4_menu_item['termID'] = $l4_cat['termID'];
                    $l4_menu_items[] = $l4_menu_item;
                }
            }
        }

        $l5_menu_items = array();

        foreach ($l5 as $l5_cat) {
            $l5_menu_items = array();
            foreach ($l4_menu_items as $l4_menu_item) {
                if($l5_cat['parentID']==$l4_menu_item['termID']) {
                    $l5_menu_id = wp_update_nav_menu_item($menuID, 0, array(
                        'menu-item-title' => $l5_cat['title'],
                        'menu-item-classes' => 'epim_sub_menu_item',
                        'menu-item-url' => $l5_cat['URL'],
                        'menu-item-parent-id' => $l4_menu_item['menuID'],
                        'menu-item-status' => 'publish'
                    ));
                    //error_log('Added '.$l1_menu_item['termID'].' -- '.$l2_menu_rec['title'].' ('.$l2_menu_rec['termID'].')');
                    $l5_menu_item['menuID'] = $l5_menu_id;
                    $l5_menu_item['termID'] = $l5_cat['termID'];
                    $l5_menu_items[] = $l5_menu_item;
                }
            }
        }

        //error_log('Menu Created');


    }
}
