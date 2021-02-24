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

	$request = get_option( 'wpmai_url' );

	if ( substr( $request, - 1 != '/' ) ) {
		$request .= '/';
	}

	$datasource = get_option( 'wpmai_datasource' );

	if ( $method != '' ) {
		$request .= $method;
	} else {
		$request .= 'GetSql';
	}

	$request .= '?datasource=' . $datasource;

	if ( $query != '' ) {
		$request .= '&query=' . $query;
	}

	$response = wp_remote_get( $request );

	$apiCall = 'Something went wrong';

	if ( is_array( $response ) && ! is_wp_error( $response ) ) {
		$apiCall = $response['body'];
	} else {
		if ( is_wp_error( $response ) ) {
			$apiCall = 'wp_error: ' . $response->get_error_message();
			error_log( $response->get_error_message() );
		}
	}

	return $apiCall;

}

function wpmai_post_api_response($url,$data) {

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);

	curl_setopt($ch, CURLOPT_POST, true);

	$headers[] = "Content-Type: application/x-www-form-urlencoded";

	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

	curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

	$apicall = curl_exec($ch);
	curl_close($ch);
	return $apicall;
}

function wpmai_make_api_call_getstock( $stockID ) {
	$response = null;

	$request = get_option( 'wpmai_url' );

	if ( substr( $request, - 1 != '/' ) ) {
		$request .= '/';
	}

	$request .= 'GetStock';

	$datasource = get_option( 'wpmai_datasource' );

	$request .= '?datasource=' . $datasource . '&company=1&account=webcash&quantity=1&stockid=' . $stockID;

	$response = wp_remote_get( $request );

	$apiCall = 'Something went wrong';

	if ( is_array( $response ) && ! is_wp_error( $response ) ) {
		$apiCall = $response['body'];
	} else {
		if ( is_wp_error( $response ) ) {
			$apiCall = 'wp_error: ' . $response->get_error_message();
			error_log( $response->get_error_message() );
		}
	}

	return $apiCall;
}

function wpmai_make_api_call_getmultistock( $stockIDs ) {
	$response = null;

	$input = '';

	if ( is_array( $stockIDs ) ) {
		foreach ( $stockIDs as $stockID ) {
			$input .= $stockID . ',1,';
		}
		$input = rtrim( $input, ', ' );
	}

	$datasource = get_option( 'wpmai_datasource' );

	$postArray = array(
		'headers'     => array(
			'Content-Type' => 'application/x-www-form-urlencoded'
		),
		'body'        => array(
			'datasource' => $datasource,
			'company' => '1',
			'account' => 'webcash',
			'input' => $input,
			'rate' => '1'
		)
	);
	$url = get_option( 'wpmai_url' );

	if ( substr( $url, - 1 != '/' ) ) {
		$url .= '/';
	}

	$url .= 'GetStockMulti';


	$response = wp_remote_post($url,$postArray);

	$apiCall = 'Something went wrong';

	if ( is_array( $response ) && ! is_wp_error( $response ) ) {
		$apiCall = $response['body'];
	} else {
		if ( is_wp_error( $response ) ) {
			$apiCall = 'wp_error: ' . $response->get_error_message();
			error_log( $response->get_error_message() );
		}
	}

	return $apiCall;
}

function wpmai_get_check_status() {
	return wpmai_make_api_call( '', 'CheckStatus' );
}

function wpmai_get_web_price( $sku ) {
	$queryStock  = "select stockID, retail_price from stock where main_mpn = '" . $sku . "'";
	$stockXMLstr = wpmai_make_api_call( $queryStock, '' );
	$data        = simplexml_load_string( $stockXMLstr );
	$res         = false;
	foreach ( $data->row as $row ) {
		$res         = (string) $row->retail_price;
		$id          = (string) $row->stockid;
		$getStockXML = wpmai_make_api_call_getstock( $id );
		$getStock    = simplexml_load_string( $getStockXML );
		if ( $getStock->disc_price ) {
			$res = $getStock->disc_price;
		}
		break;
	}

	return $res;
}

function get_woo_skus() {
	$args = array(
		'post_type'      => 'product',
		'posts_per_page' => - 1
	);
	$loop = new WP_Query( $args );
	$res  = array();
	if ( $loop->have_posts() ):
		while ( $loop->have_posts() ): $loop->the_post();

			global $product;
			$sku   = $product->get_sku();
			$res[] = $sku;
		endwhile;
	endif;
	wp_reset_postdata();
	return $res;
}

add_filter( 'http_request_timeout', 'wpmai_timeout_extend' );

function wpmai_timeout_extend( $time )
{
	// Default timeout is 5
	return 220;
}

function wpmai_get_stock() {
	//$timeStarted = microtime( true );
	$stockXMLstr = wpmai_make_api_call( "select stockID,main_mpn,retail_price,qty_hand from stock where main_mpn != ''", '' );
	$data        = simplexml_load_string( $stockXMLstr );

	$skus = get_woo_skus();
	$inputArray  = array();

	$iMax = 100000;

	$i = 0;

	foreach ( $data->row as $row ) {
		$stockID      = (string) $row->stockid;
		$sku          = (string) $row->main_mpn;
		if(in_array($sku,$skus)) {
			if ((!in_array($stockID,$inputArray))&&($i<$iMax)) {
				$inputArray[] = $stockID;
				$i++;
			}
		}
	}
	$pricesXMLstr = wpmai_make_api_call_getmultistock( $inputArray );

	$priceData    = simplexml_load_string( $pricesXMLstr );
	//error_log(print_r($priceData,true));

	//error_log( 'API took ' . ( microtime( true ) - $timeStarted ) . ' seconds' );
	//$timeStarted = microtime( true );

	//error_log(print_r($priceData,true));
	$prices = array();
	foreach ( $priceData->row as $row ) {
		$price              = (string) $row->disc_price;
		$stockID            = (string) $row->stockid;
		$prices[ $stockID ] = $price;
	}

	//error_log(print_r($prices,true));
	//error_log(count($prices). ' prices returned');

	$dataArray = array();
	foreach ( $data->row as $row ) {
		$arrayRow            = array();
		$price               = (string) $row->retail_price;
		$sku                 = (string) $row->main_mpn;
		$stockID             = (string) $row->stockid;
		$arrayRow['stockID'] = $stockID;
		$arrayRow['sku']     = $sku;
		$arrayRow['price']   = $price;
		$arrayRow['qty'] = (string) $row->qty_hand;
		if ( array_key_exists( $stockID, $prices ) ) {
			$arrayRow['price'] = $prices[ $stockID ];
		}
		if(in_array($sku,$skus)) {
			$dataArray[]     = $arrayRow;
		}

	}

	//error_log( 'Data processing took ' . ( microtime( true ) - $timeStarted ) . ' seconds' );

	return json_encode( $dataArray );
}

function wpmai_get_stock_count() {
	return wpmai_make_api_call( "select count(*) from stock where main_mpn != ''", '' );
}

function wpmai_get_stock_ids() {
	return wpmai_make_api_call( "select stockID from stock where main_mpn != ''", '' );
}