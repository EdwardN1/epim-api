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

add_action('wp_enqueue_scripts','add_wpam_scripts');
function add_wpam_scripts() {
    wp_register_script( 'wpam-recapture-js', 'https://www.google.com/recaptcha/api.js' , '', '', true );
}

// add async and defer attributes to enqueued scripts
function wpam_recapture_script_loader_tag($tag, $handle) {

    if ($handle === 'wpam-recapture-ja') {
        if (false === stripos($tag, 'async')) {
            $tag = str_replace(' src', ' async="async" src', $tag);
        }
        if (false === stripos($tag, 'defer')) {
            $tag = str_replace('<script ', '<script defer ', $tag);
        }
    }

    return $tag;

}
add_filter('script_loader_tag', 'wpam_recapture_script_loader_tag', 10, 2);