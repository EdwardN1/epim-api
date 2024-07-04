<?php
/**
 * Created by PhpStorm.
 * User: Edward Nickerson
 * Date: 21/01/2019
 * Time: 18:20
 */

add_filter('acf/settings/show_admin', 'show_acf');

function show_acf() {
	/*global $current_user;
	get_currentuserinfo();
	$ret = true;
	$email = (string) $current_user->user_email;
	if($email != 'edward@technicks.com') {
		$ret = false;
	}*/
	return isDeveloper();
}

function isDeveloper() {
    global $current_user;
    get_currentuserinfo();
    $ret = true;
    $email = (string) $current_user->user_email;
    if($email != 'edward@technicks.com') {
        $ret = false;
    }
    return $ret;
}

function cpt_ui_remove_menu_items() {
    if( !isDeveloper() ):
        remove_menu_page('cptui_main_menu');
        remove_menu_page('edit.php?post_type=api_product');
        remove_menu_page('edit.php?post_type=api_application');
        remove_menu_page('admin.php?page=meowapps-main-menu');
    endif;
}
add_action( 'admin_init', 'cpt_ui_remove_menu_items' );