<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

register_activation_hook( swp_PLUGINFILE, 'swp_cron_activation' );

function swp_cron_activation() {
    error_log( 'checking and adding cron events' );
    if ( ! wp_next_scheduled( 'swp_daily_action' ) ) {
        wp_schedule_event( strtotime( '23:50:00' ), 'daily', 'swp_daily_action' );
    }

}

add_action( 'swp_daily_action', 'swp_daily' );

function swp_daily() {
    $ENGCats = swp_make_api_call(swp_EN_API);
    $FRCats = swp_make_api_call(swp_FR_API);

    
}