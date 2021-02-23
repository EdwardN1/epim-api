<?php
/**
 * @package wp_infor_api
 * @version 1.0.0
 */
/*
Plugin Name: WP Infor API Interface
Plugin URI: https://www.technicks.com
Description: Implements an interface into the INFOR API.
Author: Edward Nickerson
Version: 1.0.0
Author URI: https://www.technicks.com
*/

define('wpiai_FUNCTIONSPATH', plugin_dir_path( __FILE__ ) . '/includes/');
define('wpiai_PLUGINPATH', plugin_dir_path( __FILE__ ) );
define('wpiai_PLUGINURI',plugin_dir_url(__FILE__));
define('wpiai_PLUGINFILE',__FILE__);

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

foreach (glob(wpiai_FUNCTIONSPATH.'autoinc-*.php') as $filename)
{
	require_once ($filename);
}