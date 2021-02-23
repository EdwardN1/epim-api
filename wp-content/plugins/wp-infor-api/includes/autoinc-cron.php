<?php
register_activation_hook(__FILE__, 'wpiai_cron_activation');

function wpiai_cron_activation() {
    error_log('Running wpiai_cron_activation');
    if (! wp_next_scheduled ( 'wpiai_every_minute_action' )) {
        wp_schedule_event(time(), 'everyminute', 'wpiai_every_minute_action');
    }
}

add_action('wpiai_every_minute_action', 'wpiai_do_every_minute');

function wpiai_do_every_minute() {
    // do something every minute
    error_log('WP Cron is working....');
}

add_filter( 'cron_schedules', 'wpiai_add_cron_interval' );
function wpiai_add_cron_interval( $schedules ) {
    $schedules['everyminute'] = array(
        'interval'  => 60, // time in seconds
        'display'   => 'Every Minute'
    );
    return $schedules;
}