<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function swp_make_api_call( $url ) {
    $response = wp_safe_remote_get( $url);
    if ( is_array( $response ) && ! is_wp_error( $response ) ) {
        $apiCall = $response['body'];
    } else {
        if ( is_wp_error( $response ) ) {
            error_log($response->get_error_message());
            error_log('URL called: '. $url);
            $apiCall = false;
        }
    }

    return $apiCall;
}