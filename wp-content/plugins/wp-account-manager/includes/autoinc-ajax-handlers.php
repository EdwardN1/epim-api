<?php
function wpam_checkSecure() {
    if ( ! check_ajax_referer( 'wpam-security-nonce', 'security' ) ) {
        wp_send_json_error( 'Invalid security token sent.' );
        wp_die();
    }
}

add_action( 'wp_ajax_wpam_username_unique', 'ajax_wpam_username_unique' );

function ajax_wpam_username_unique() {
    wpam_checkSecure();
    $result = 'true';
    if ( ! empty( $_POST['post_title'] ) ) {
        if(!wpam_unique_username($_POST['post_title'])) {
            $result = $_POST['post_title'] . ' is already in use, please select another username.';
        }
    }
    echo $result;
    exit;
}