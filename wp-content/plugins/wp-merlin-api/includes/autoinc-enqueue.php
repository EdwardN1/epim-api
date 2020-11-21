<?php
if ( ! defined( 'ABSPATH' ) )
    exit;

add_action('admin_enqueue_scripts', 'wpmai_admin_enqueue');
function wpmai_admin_enqueue($hook) {
    if ('toplevel_page_merlin-options' !== $hook) {
        return;
    }
    wp_enqueue_script('wpmai_process_queue_script', plugins_url('assets/scripts/processQueue.js',__DIR__));
    wp_enqueue_script('wpmai_admin_scripts', plugins_url('assets/scripts/admin.js',__DIR__),'wpmai_process_queue_script');
    wp_localize_script(
        'wpmai_admin_scripts',
        'wpmai_ajax_object',
        [
            'ajax_url'  => admin_url( 'admin-ajax.php' ),
            'security'  => wp_create_nonce( 'wpmai-security-nonce' ),
        ]
    );
}