<?php

function epsm_woocommerce_get_sku( $sku, $product ) {

    $part_code = get_post_meta($product->get_id(),'epim_Part_Code',true);

    //error_log('part code = '.$part_code);

    if($part_code) {
        $sku = $part_code;
    }

    return $sku;
}

add_filter( 'woocommerce_product_get_sku', 'epsm_woocommerce_get_sku', 10, 2 );