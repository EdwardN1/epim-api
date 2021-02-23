<?php
register_activation_hook(wpiai_PLUGINFILE, 'wpiai_cron_activation');

function wpiai_cron_activation() {
    //error_log('Running wpiai_cron_activation');
    if (! wp_next_scheduled ( 'wpiai_every_minute_action' )) {
        wp_schedule_event(time(), 'everyminute', 'wpiai_every_minute_action');
    }
	if (! wp_next_scheduled ( 'wpiai_every_twenty_minutes_action' )) {
		wp_schedule_event(time(), 'everytwentyminutes', 'wpiai_every_twenty_minutes_action');
	}
}

add_action('wpiai_every_minute_action', 'wpiai_do_every_minute');
add_action('wpiai_every_twenty_minutes_action', 'wpiai_do_every_twenty_minutes');

function wpiai_do_every_minute() {
    // do something every minute
    //error_log('WP Cron is working....');
}

function wpiai_do_every_twenty_minutes() {
	// do something every twenty minutes
	//error_log('WP Cron is working....');
}

add_filter( 'cron_schedules', 'wpiai_add_cron_interval' );
function wpiai_add_cron_interval( $schedules ) {
    $schedules['everyminute'] = array(
        'interval'  => 60, // time in seconds
        'display'   => 'Every Minute'
    );
	$schedules['everytwentyminutes'] = array(
		'interval'  => 1200, // time in seconds
		'display'   => 'Every Twenty Minutes'
	);
    return $schedules;
}