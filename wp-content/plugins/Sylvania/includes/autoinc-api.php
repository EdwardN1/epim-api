<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function swp_make_api_call( $url ) {
    /*$args = array(
        'headers' => array(
            'Authorization' => 'Basic ' . base64_encode( 'sylvania' . ':' . 'likeamotorway' )
        )
    );*/
    $response = wp_safe_remote_get( $url);
    //$response = wp_remote_get( $url,$args);
    error_log(print_r($response,true));
    if ( is_array( $response ) && ! is_wp_error( $response ) ) {
        $apiCall = $response['body'];
    } else {
        if ( is_wp_error( $response ) ) {
            error_log($response->get_error_message());
            error_log('URL called: '. $url);
            $apiCall = false;
        } else {
            error_log(print_r($response,true));
        }
    }

    return $apiCall;
}