<?php
if ( ! defined( 'ABSPATH' ) )
    exit;

function wpmai_api_checkSecure() {
    if ( ! check_ajax_referer( 'wpmai-security-nonce', 'security' ) ) {
        wp_send_json_error( 'Invalid security token sent.' );
        wp_die();
    }
}

add_action( 'wp_ajax_wpmai_get_check_status', 'ajax_wpmai_get_check_status' );
add_action( 'wp_ajax_wpmai_get_start_import', 'ajax_wpmai_get_start_import' );
add_action( 'wp_ajax_wpmai_update_product', 'ajax_wpmai_update_product' );

function ajax_wpmai_get_check_status() {
    wpmai_api_checkSecure();
    header( "Content-Type: application/json" );
    echo json_encode(wpmai_get_check_status());
    exit;
}

function ajax_wpmai_get_start_import() {
    wpmai_api_checkSecure();
    header( "Content-Type: application/json" );
    echo json_encode(wpmai_get_stock());
    exit;
}

function ajax_wpmai_update_product() {
    wpmai_api_checkSecure();
    $id = wc_get_product_id_by_sku($_POST['sku']);
    if($id > 0) {
        //update_post_meta($id, '_manage_stock', 'yes');
	    $webPrice = wpmai_get_web_price($_POST['sku']);
        $product = wc_get_product( $id );
        $product->set_price($_POST['price']);
        if($webPrice) {
	        $product->set_regular_price($webPrice);
        } else {
	        $product->set_regular_price($_POST['price']);
        }
        $product->set_manage_stock(true);
        $product->set_stock_quantity($_POST['qty']);
        $product->save();
        update_post_meta($id, '_price', $_POST['price']);
        update_post_meta($id, '_regular_price', $_POST['price']);
        wc_delete_product_transients( $id );
        //wc_update_product_stock($id,$_POST['qty']);

        echo $_POST['sku'].' updated'. ' Price = '.wc_price(wc_get_price_to_display($product));
    } else {
        echo 'sku: '.$_POST['sku'].' not found';
    }
    exit;
}