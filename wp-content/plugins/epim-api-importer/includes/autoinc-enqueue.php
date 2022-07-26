<?php

if ( ! defined( 'ABSPATH' ) )
	exit;

add_action('admin_enqueue_scripts', 'epim_admin_enqueue');
function epim_admin_enqueue($hook) {
    if ('toplevel_page_epim' !== $hook) {
        return;
    }
    wp_enqueue_script('jquery-ui-datepicker');
    wp_register_style('jquery-ui', plugins_url('assets/css/jquery-ui-1-8-2.css',__DIR__));
    wp_enqueue_style('jquery-ui');
    wp_enqueue_style( 'wp-color-picker');
    wp_enqueue_script( 'wp-color-picker');
    wp_enqueue_script('epim_process_queue_script', plugins_url('assets/scripts/processQueue.js',__DIR__));
    wp_enqueue_script('epim_admin_scripts', plugins_url('assets/scripts/admin.js',__DIR__),'epim_process_queue_script',filemtime(epimaapi_PLUGINPATH.'/assets/scripts/admin.js' ));
    wp_localize_script(
        'epim_process_queue_script',
        'epim_ajax_object',
        [
            'ajax_url'  => admin_url( 'admin-ajax.php' ),
            'security'  => wp_create_nonce( 'epim-security-nonce' ),
        ]
    );



}

add_action('wp_enqueue_scripts', 'epim_site_scripts', 999);

function epim_site_scripts() {
    global $is_divi;
    if($is_divi) {
        wp_enqueue_style( 'epim-default-css', plugins_url('assets/css/divi-overrides.css',__DIR__), array(), filemtime(epimaapi_PLUGINPATH.'/assets/css/divi-overrides.css' ));
	    wp_enqueue_style( 'epim-override-css', plugins_url('assets/css/divi-overrides-set.css',__DIR__), array('epim-default-css'), filemtime(epimaapi_PLUGINPATH.'/assets/css/divi-overrides.css') );
    }
}