<?php
if ( ! defined( 'ABSPATH' ) )
    exit;

function wpmace_api_checkSecure() {
    if ( ! check_ajax_referer( 'wpmace-security-nonce', 'security' ) ) {
        wp_send_json_error( 'Invalid security token sent.' );
        wp_die();
    }
}

add_action( 'wp_ajax_mace_get_request_products_response', 'ajax_mace_get_request_products_response' );

function ajax_mace_get_request_products_response() {
    wpmace_api_checkSecure();
    header( "Content-Type: application/json" );
    $url = get_option('wpmace_request_products_api_url');
    $data = get_option('wpmace_request_products_api_body');
    echo json_encode(wpmace_get_api_response($url,$data));
    exit;
}