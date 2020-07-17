<?php
add_action('admin_enqueue_scripts', 'add_wpam_js');
function add_wpam_js()
{
    $screen = get_current_screen();
    if ('wpam_accounts' == $screen->post_type) {
        wp_enqueue_script('wpam_validate', 'https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.js', array('jquery'));
        wp_enqueue_script('wpam_script_js', wpam_PLUGINURI . '/js/scripts.js');
        wp_localize_script(
            'wpam_script_js',
            'wpam_ajax_object',
            [
                'ajax_url'  => admin_url( 'admin-ajax.php' ),
                'security'  => wp_create_nonce( 'wpam-security-nonce' ),
            ]
        );
    }
}