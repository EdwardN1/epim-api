<?php
add_filter('cron_schedules', 'epimapi_ten_minute_interval');

// add once 10 minute interval to wp schedules
function epimapi_ten_minute_interval($interval) {

    $interval['minutes_10'] = array('interval' => 10*60, 'display' => 'Once 10 minutes');

    return $interval;
}

register_activation_hook(epimaapi_PLUGINFILE, 'epimaapi_cron_activation');

function epimaapi_cron_activation() {
    error_log('checking and adding cron events');
    if (!wp_next_scheduled('epimaapi_update_branch_stock_daily_action')) {
        wp_schedule_event( strtotime('22:20:00'), 'daily', 'epimaapi_update_branch_stock_daily_action' );
    }
    if (!wp_next_scheduled('epimaapi_update_branch_stock_minutes_action')) {
        wp_schedule_event(time(), 'minutes_10', 'epimaapi_update_branch_stock_minutes_action');
    }
}

add_action('epimaapi_update_branch_stock_minutes_action','epimaapi_update_branch_stock_minutes');
add_action('epimaapi_update_branch_stock_daily_action','epimaapi_update_branch_stock_daily');

function epimaapi_update_branch_stock_daily() {
    $epim_enable_scheduled_updates = false;
    $epim_enable_scheduled_updates_option = get_option('epim_enable_scheduled_updates');
    if(is_array($epim_enable_scheduled_updates_option)) {
        if($epim_enable_scheduled_updates_option['checkbox_value']==1) {
            $epim_enable_scheduled_updates = true;
        }
    }
    $epim_update_schedule = get_option('epim_update_schedule');
    //error_log('running daily branch stock update');
    if($epim_update_schedule=='daily') {
        if($epim_enable_scheduled_updates){
            epimaapi_update_branch_stock_cron();
        } else {
            //error_log('Daily update aborted - Updates not enabled');
        }
    } else {
        //error_log('Daily update aborted - set to 10 minute updates');
    }
}

function epimaapi_update_branch_stock_minutes() {
    $epim_enable_scheduled_updates = false;
    $epim_enable_scheduled_updates_option = get_option('epim_enable_scheduled_updates');
    if(is_array($epim_enable_scheduled_updates_option)) {
        if($epim_enable_scheduled_updates_option['checkbox_value']==1) {
            $epim_enable_scheduled_updates = true;
        }
    }
    $epim_update_schedule = get_option('epim_update_schedule');
    //error_log('running 10 minute branch stock update');
    if($epim_update_schedule=='minutes') {
        if($epim_enable_scheduled_updates){
            epimaapi_update_branch_stock_cron();
        } else {
           // error_log('10 minute update aborted - Updates not enabled');
        }
    } else {
       // error_log('10 minute update aborted - set to daily updates');
    }
}

function epimaapi_update_branch_stock_cron() {
    $log = '';
    $start = microtime(true);
    //error_log('epimaapi_update_branch_stock_cron started');
    $yesterday = date('dMY',strtotime("-1 days"));
    $branches = json_decode(get_epimaapi_all_branches(),true);
    if(is_array($branches)) {
        foreach ($branches as $branch) {
            if(is_array($branch)) {
                if(array_key_exists('Id',$branch)) {
                    $Id = $branch['Id'];
                    $stockLevels = json_decode(get_epimaapi_get_branch_stock_since($Id,$yesterday),true);
                    if(is_array($stockLevels)) {
                        foreach ($stockLevels as $stock_level) {
                            $log .= epimaapi_update_branch_stock($Id,$stock_level['VariationId'],$stock_level['Stock']).'</br>';
                        }
                    } else {
                        //error_log('epim daily cron - No stock to update for Branch: '.$Id);
                    }
                } else {
                    //error_log('epim daily cron - missing Id for branch');
                }
            } else {
                //error_log('epim daily cron - No Branches returned');
            }
        }
    } else {
        //error_log('epim daily cron - failed to get branches');
    }
    $time_elapsed_secs = microtime(true) - $start;
    $log .= 'Import took '.$time_elapsed_secs.' seconds.';
    //error_log('epimaapi_update_branch_stock_daily Import Took '.$time_elapsed_secs.' seconds');
    if (!(FALSE === get_option('epim_schedule_log'))) update_option('epim_schedule_log', $log);
}