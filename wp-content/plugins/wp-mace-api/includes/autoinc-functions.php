<?php
if ( ! defined( 'ABSPATH' ) )
    exit;


function wpmace_get_api_response($url,$data) {

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_POST, true);

    $headers[] = "Content-Type: application/xml";

    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

    $apicall = curl_exec($ch);
    curl_close($ch);
    return $apicall;
}