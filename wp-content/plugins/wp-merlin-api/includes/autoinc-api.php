<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 *
 *
 ************************************ API Calls*****************************************
 *
 */


function wpmai_make_api_call($query, $method)
{
    $response = null;

    $request = get_option('wpmai_url');

    if (substr($request, -1 != '/')) {
        $request .= '/';
    }

    $datasource = get_option('wpmai_datasource');

    if ($method != '') {
        $request .= $method;
    } else {
        $request .= 'GetSql';
    }

    $request .= '?datasource=' . $datasource;

    if ($query != '') {
        $request .= '&query=' . $query;
    }

    $response = wp_remote_get($request);

    $apiCall = 'Something went wrong';

    if (is_array($response) && !is_wp_error($response)) {
        $apiCall = $response['body'];
    } else {
        if (is_wp_error($response)) {
            $apiCall = 'wp_error: ' . $response->get_error_message();
            error_log($response->get_error_message());
        }
    }

    return $apiCall;

}

function wpmai_make_api_call_getstock($stockID)
{
    $response = null;

    $request = get_option('wpmai_url');

    if (substr($request, -1 != '/')) {
        $request .= '/';
    }

    $request .= 'GetStock';

    $datasource = get_option('wpmai_datasource');

    $request .= '?datasource=' . $datasource . '&company=1&account=webcash&quantity=1&stockid=' . $stockID;

    $response = wp_remote_get($request);

    $apiCall = 'Something went wrong';

    if (is_array($response) && !is_wp_error($response)) {
        $apiCall = $response['body'];
    } else {
        if (is_wp_error($response)) {
            $apiCall = 'wp_error: ' . $response->get_error_message();
            error_log($response->get_error_message());
        }
    }

    return $apiCall;
}

function wpmai_get_check_status()
{
    return wpmai_make_api_call('', 'CheckStatus');
}

function wpmai_get_web_price($sku)
{
    $queryStock = "select stockID, retail_price from stock where main_mpn = '" . $sku . "'";
    $stockXMLstr = wpmai_make_api_call($queryStock, '');
    $data = simplexml_load_string($stockXMLstr);
    $res = false;
    foreach ($data->row as $row) {
        $res = (string)$row->retail_price;
        $id = (string)$row->stockid;
        $getStockXML = wpmai_make_api_call_getstock($id);
        $getStock = simplexml_load_string($getStockXML);
        if ($getStock->disc_price) {
            $res = $getStock->disc_price;
        }
        break;
    }
    return $res;
}

function wpmai_get_stock()
{
    $stockXMLstr = wpmai_make_api_call("select stockID,main_mpn,retail_price,qty_hand from stock where main_mpn != ''", '');
    $data = simplexml_load_string($stockXMLstr);
    $dataArray = array();
    foreach ($data->row as $row) {
        $arrayRow = array();
        $price = (string)$row->retail_price;
        $sku = (string)$row->main_mpn;
        $arrayRow['sku'] = $sku;
        $arrayRow['price'] = $price;
        $arrayRow['qty'] = (string)$row->qty_hand;
        $dataArray[] = $arrayRow;
    }

    return json_encode($dataArray);
}

function wpmai_get_stock_count()
{
    return wpmai_make_api_call("select count(*) from stock where main_mpn != ''", '');
}

function wpmai_get_stock_ids()
{
    return wpmai_make_api_call("select stockID from stock where main_mpn != ''", '');
}