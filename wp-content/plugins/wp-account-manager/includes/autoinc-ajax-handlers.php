<?php
function wpam_checkSecure() {
    if ( ! check_ajax_referer( 'wpam-security-nonce', 'security' ) ) {
        wp_send_json_error( 'Invalid security token sent.' );
        wp_die();
    }
}

add_action( 'wp_ajax_wpam_username_unique', 'ajax_wpam_username_unique' );
add_action( 'wp_ajax_wpam_email_unique', 'ajax_wpam_email_unique' );

function ajax_wpam_username_unique() {
    wpam_checkSecure();
    $result = 'true';
    if ( ! empty( $_GET['post_title'] ) ) {
        //error_log("ajax_wpam_username_unique: [post_title] = ".$_GET['post_title']. " | [username] = ".$_GET['username']);
        if(!wpam_unique_username($_GET['post_title'],$_GET['username'])) {
            $result = $_GET['post_title'] . ' is already in use, please select another username.';
        }
    }
    echo json_encode($result);
    exit;
}

function ajax_wpam_email_unique() {
    wpam_checkSecure();
    $result = 'true';
    if ( ! empty( $_GET['wpam_accounts_email'] ) ) {
        if(!wpam_unique_email($_GET['wpam_accounts_email'],$_GET['email_address'])) {
            $result = $_GET['email_address'] . ' is already in use, please select another email address.';
        }
    }
    echo json_encode($result);
    exit;
}