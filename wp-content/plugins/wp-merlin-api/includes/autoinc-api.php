<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 *
 *
 ************************************ API Calls*****************************************
 *
 */


function wpmai_make_api_call( $query, $method ) {
    $response = null;

    $request = get_option('wpmai_url');

    if ( substr( $request, - 1 != '/' ) ) {
        $request .= '/';
    }

    $datasource = get_option('wpmai_datasource');

    if($method != '') {
        $request .=  $method;
    } else {
        $request .= 'GetSql';
    }

    $request .= '?datasource='. $datasource;

    if($query != '') {
        $request .= '&query='.$query;
    }

    $response = wp_remote_get($request);

    $apiCall = 'Something went wrong';

    if ( is_array( $response ) && ! is_wp_error( $response ) ) {
        $apiCall = $response['body'];
    } else {
        if(is_wp_error( $response )) {
            $apiCall = 'wp_error: '.$response->get_error_message();
            error_log($response->get_error_message());
        }
    }

    return $apiCall;

}

function wpmai_get_check_status(){
    return wpmai_make_api_call('','CheckStatus');
}

function wpmai_get_stock() {
    $stockXMLstr =  wpmai_make_api_call("select stockID,main_mpn,retail_price,qty_hand from stock where main_mpn != ''",'');
    $data = simplexml_load_string($stockXMLstr);
    //$count = $data['count'];
    $dataArray = array();
    foreach ($data->row as $row) {
        $arrayRow = array();
        $arrayRow['sku'] = (string)$row->main_mpn;
        $arrayRow['price'] = (string)$row->retail_price;
        $arrayRow['qty'] = (string)$row->qty_hand;
        $dataArray[] = $arrayRow;
    }
    return json_encode($dataArray);
}

function wpmai_get_stock_count() {
    return wpmai_make_api_call("select count(*) from stock where main_mpn != ''",'');
}

function wpmai_get_stock_ids() {
    return wpmai_make_api_call("select stockID from stock where main_mpn != ''",'');
}