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
    //error_log(print_r($response,true));
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

// Takes an image url as an argument and upload image to wordpress and returns the media id, later we will use this id to assign the image.
function swp_uploadMedia( $image_url ) {
    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
    require_once( ABSPATH . 'wp-admin/includes/media.php' );
    //error_log('Downloading - '.$image_url);
    try {
        $tmp  = download_url( $image_url );
        $url_path     = parse_url( $image_url, PHP_URL_PATH );
        $url_filename = '';
        if ( is_string( $url_path ) && '' !== $url_path ) {
            $url_filename = basename( $url_path );
        }
        $file = array(
            'name'     => $url_filename,
            'tmp_name' => $tmp
        );

        if ( is_wp_error( $tmp ) ) {
            $error_string = $tmp->get_error_message();
            //@unlink( $file['tmp_name'] );
            //error_log( $image_url . ' | ' . $error_string );
            throw new Exception( $error_string );

            return false;
        } else {
            $media = media_handle_sideload( $file, 0 );
            if ( is_wp_error( $media ) ) {
                $error_string = $media->get_error_message();
                @unlink( $file['tmp_name'] );
                //error_log( $image_url . ' | ' . $error_string );
                throw new Exception( $error_string );
            } else {
                return $media;
            }
        }
    } catch ( Exception $exception ) {
        error_log('Import Error : '.$image_url);
        error_log( $exception->getMessage() );
    }

    return false;
}