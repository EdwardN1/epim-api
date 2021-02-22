<?php
if ( ! defined( 'ABSPATH' ) )
    exit;

add_action('admin_enqueue_scripts', 'wpmace_admin_enqueue');
function wpmace_admin_enqueue($hook) {
    if ('toplevel_page_mace-options' !== $hook) {
        return;
    }
    wp_enqueue_script('wpmace_admin_scripts', plugins_url('assets/scripts/admin.js',__DIR__));
    wp_localize_script(
        'wpmace_admin_scripts',
        'wpmace_ajax_object',
        [
            'ajax_url'  => admin_url( 'admin-ajax.php' ),
            'security'  => wp_create_nonce( 'wpmace-security-nonce' ),
        ]
    );
}