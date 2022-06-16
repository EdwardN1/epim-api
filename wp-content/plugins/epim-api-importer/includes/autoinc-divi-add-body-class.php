<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_filter( 'body_class','epim_divi_body_classes' );
function epim_divi_body_classes( $classes ) {

    $classes[] = 'epim-divi';

    return $classes;

}