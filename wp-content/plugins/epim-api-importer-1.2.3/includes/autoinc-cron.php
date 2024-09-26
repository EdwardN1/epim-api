<?php

if (!defined('ABSPATH')) {
    exit;
}

add_filter('cron_schedules', 'epimapi_ten_minute_interval');

// add once 10 minute interval to wp schedules
function epimapi_ten_minute_interval($interval)
{

    $interval['minutes_10'] = array('interval' => 10 * 60, 'display' => 'Once 10 minutes');
    $interval['minutes_1'] = array('interval' => 60, 'display' => 'Once every minute');

    return $interval;
}

register_activation_hook(epimaapi_PLUGINFILE, 'epimaapi_cron_activation');

function epimaapi_cron_activation()
{
    error_log('checking and adding cron events');
    /*if (!wp_next_scheduled('epimaapi_update_branch_stock_daily_action')) {
        wp_schedule_event(strtotime('22:20:00'), 'daily', 'epimaapi_update_branch_stock_daily_action');
    }
    if (!wp_next_scheduled('epimaapi_update_luckins_daily_action')) {
        wp_schedule_event(strtotime('04:00:00'), 'daily', 'epimaapi_update_luckins_daily_action');
    }
    if (!wp_next_scheduled('epimaapi_update_branch_stock_minutes_action')) {
        wp_schedule_event(time(), 'minutes_10', 'epimaapi_update_branch_stock_minutes_action');
    }*/
    if (!wp_next_scheduled('epimaapi_update_every_minute_minute_action')) {
        wp_schedule_event(time(), 'minutes_1', 'epimaapi_update_every_minute_minute_action');
    }
    if (!wp_next_scheduled('epimaapi_update_daily_action')) {
        wp_schedule_event(strtotime('22:20:00'), 'daily', 'epimaapi_update_daily_action');
    }
}


add_action('epimaapi_update_every_minute_minute_action', 'epimaapi_update_every_minute');
add_action('epimaapi_update_daily_action', 'epimaapi_update_daily');
/*add_action('epimaapi_update_branch_stock_minutes_action', 'epimaapi_update_branch_stock_minutes');
add_action('epimaapi_update_branch_stock_daily_action', 'epimaapi_update_branch_stock_daily');
add_action('epimaapi_update_luckins_daily_action', 'epimaapi_update_luckins_daily');*/

function epimaapi_update_daily() {
    if (!wp_next_scheduled('epimaapi_update_every_minute_minute_action')) {
        wp_schedule_event(time(), 'minutes_1', 'epimaapi_update_every_minute_minute_action');
    }
    $current_update = get_option('_epim_update_running');
    cron_log('Current Running Update: ' . $current_update);
    if(get_option('_epim_update_running') == '') {
        $yesterday = date('Y-m-d', strtotime("-1 days"));
        cron_log('Starting daily update for changes from ' . $yesterday);
        epimaapi_background_import_products_from($yesterday);
    }
}

function epimaapi_update_luckins_daily()
{
    epimaapi_update_branch_stock_cron();
    /*if ( ! ( false === get_option( 'epim_schedule_log' ) ) ) {
        update_option( 'epim_schedule_log', $log );
    }*/
}

function epimaapi_update_branch_stock_daily()
{
    return;
    if (!wp_next_scheduled('epimaapi_update_branch_stock_minutes_action')) {
        wp_schedule_event(time(), 'minutes_10', 'epimaapi_update_branch_stock_minutes_action');
    }
    if (!wp_next_scheduled('epimaapi_update_every_minute_minute_action')) {
        wp_schedule_event(time(), 'minutes_1', 'epimaapi_update_every_minute_minute_action');
    }
    if (!wp_next_scheduled('epimaapi_update_luckins_daily_action')) {
        wp_schedule_event(strtotime('04:00:00'), 'daily', 'epimaapi_update_luckins_daily_action');
    }
    $epim_enable_scheduled_updates = false;
    $epim_enable_scheduled_updates_option = get_option('epim_enable_scheduled_updates');
    if (is_array($epim_enable_scheduled_updates_option)) {
        if ($epim_enable_scheduled_updates_option['checkbox_value'] == 1) {
            $epim_enable_scheduled_updates = true;
        }
    }
    $epim_update_schedule = get_option('epim_update_schedule');
    if ($epim_update_schedule == 'daily') {
        if ($epim_enable_scheduled_updates) {
            epimaapi_update_branch_stock_cron();
        } else {

        }
    } else {

    }
    epimaapi_update_all_branch_stock();
}

function cron_log($log)
{
    $log_dir = WP_PLUGIN_DIR . '/epim-api-importer';
    if (is_dir($log_dir)) {
        $log_file = $log_dir . '/cron-log.log';
        //$log_file_size = filesize($log_file);

        ini_set("log_errors", 1);
        ini_set("error_log", $log_file);

        /*if($log_file_size > 3000000) {
            file_put_contents($log_file,'');
            error_log('Log file reset');
        }*/
        error_log($log);
    }
}

function epimaapi_update_every_minute()
{
    /*if (!wp_next_scheduled('epimaapi_update_branch_stock_daily_action')) {
        wp_schedule_event(strtotime('22:20:00'), 'daily', 'epimaapi_update_branch_stock_daily_action');
    }
    if (!wp_next_scheduled('epimaapi_update_branch_stock_minutes_action')) {
        wp_schedule_event(time(), 'minutes_10', 'epimaapi_update_branch_stock_minutes_action');
    }
    if (!wp_next_scheduled('epimaapi_update_luckins_daily_action')) {
        wp_schedule_event(strtotime('04:00:00'), 'daily', 'epimaapi_update_luckins_daily_action');
    }*/

    //update_option('_epim_cron_busy', '');

    if (!wp_next_scheduled('epimaapi_update_daily_action')) {
        wp_schedule_event(strtotime('22:20:00'), 'daily', 'epimaapi_update_daily_action');
    }

    set_time_limit(0);
    $epim_update_running = get_option('_epim_update_running');

    if ($epim_update_running == '') {
        update_option('_epim_cron_busy', '');
        update_option('_epim_background_stop_update', 0);
        update_option('_epim_background_current_index', 0);
        update_option('_epim_background_last_index', 0);
        update_option('_epim_background_product_attribute_data', '');
        update_option('_epim_background_process_data', '');
        update_option('_epim_background_attribute_data', '');
        update_option('_epim_background_product_attribute_data', '');
        update_option('_epim_background_category_data','');
        //update_option('_epim_products_to_process','');
        update_option('_epim_products_processed','');
        update_option('_epim_cron_busy', '');
        for($p = 1; $p <=9; $p++){
            update_option('_epim_product_link_data_'.$p.'000', '');
        }
        //cron_log('Checking for Updates - No updates to run');
        return;
    }

    //cron_log('Running epimaapi_update_every_minute');

    $epim_background_stop_update = get_option('_epim_background_stop_update');
    if ($epim_background_stop_update == 1) {
        cron_log('Stopping updates');
        update_option('_epim_update_running', '');
        update_option('_epim_background_stop_update', 0);
        update_option('_epim_background_current_index', 0);
        update_option('_epim_background_last_index', 0);
        update_option('_epim_background_product_attribute_data', '');
        update_option('_epim_background_process_data', '');
        update_option('_epim_background_attribute_data', '');
        update_option('_epim_background_product_attribute_data', '');
        update_option('_epim_background_category_data','');
        update_option('_epim_products_to_process','');
        update_option('_epim_products_processed','');
        update_option('_epim_cron_busy', '');
        for($p = 1; $p <=9; $p++){
            update_option('_epim_product_link_data_'.$p.'000', '');
        }
        return;
    }

    if(get_option('_epim_cron_busy') == '1') {
        cron_log('Currently '.$epim_update_running);
        return;
    }

    if (($epim_update_running == 'Preparing to process ePim categories') /*|| (substr($epim_update_running, 0, 44) === "Processing categories - Restarting at Index:")*/) {
        cron_log('Processing ePim categories');

       /* update_option('_epim_update_running', 'Preparing to Import Images');
        return;*/

        update_option('_epim_cron_busy', '1');

        switch (epimapi_process_categories()) {
            case 1:
                cron_log(get_option('_epim_update_running'));
                break;
            case 2:
                update_option('_epim_update_running', 'Preparing to Sort Categories');
                cron_log('Preparing to sort categories');
                update_option('_epim_background_current_index', 0);
                break;
            default:
                update_option('_epim_update_running', '');
                update_option('_epim_background_category_data','');
                update_option('_epim_background_current_index', 0);
        }
        update_option('_epim_cron_busy', '');
        return;
    }



    if (($epim_update_running == 'Preparing to Sort Categories') /*|| (substr($epim_update_running, 0, 41) === "Sorting categories - Restarting at Index:")*/) {
        update_option('_epim_cron_busy', '1');
        cron_log('Sorting Categories');
        switch (epimapi_sort_categories()) {
            case 1:
                cron_log(get_option('_epim_update_running'));
                break;
            case 2:
                update_option('_epim_update_running', 'Preparing to import products');
                cron_log('Preparing to import products');
                update_option('_epim_background_category_data','');
                update_option('_epim_background_current_index', 0);
                break;
            default:
                update_option('_epim_update_running', '');
                update_option('_epim_background_category_data','');
                update_option('_epim_background_current_index', 0);
        }
        update_option('_epim_cron_busy', '');
        return;
    }

    if ($epim_update_running == 'Getting All Products to Import') {
        update_option('_epim_cron_busy', '1');
        cron_log('Getting All Products to Import');

        switch (epimapi_get_all_products()) {
            case 1:
                cron_log(get_option('_epim_update_running'));
                break;
            case 2:
                update_option('_epim_update_running', 'Preparing to process ePim categories');
                cron_log('Preparing to process ePim categories');
                break;
            default:
                update_option('_epim_update_running', '');
                update_option('_epim_background_process_data', '');
                update_option('_epim_background_current_index', 0);
        }
        update_option('_epim_cron_busy', '');
        return;
    }

    if (($epim_update_running == 'Preparing to import products') || (substr($epim_update_running, 0, 41) === "Importing Products - Restarting at Index:")) {

        update_option('_epim_cron_busy', '1');

        cron_log('Importing Products');

        switch (epimapi_import_products()) {
            case 1:
                cron_log(get_option('_epim_update_running'));
                break;
            case 2:
                update_option('_epim_update_running', 'Preparing to Sort attributes');
                update_option('_epim_background_process_data', '');
                cron_log('Preparing to Sort attributes');
                break;
            default:
                update_option('_epim_update_running', '');
                update_option('_epim_background_process_data', '');
                update_option('_epim_background_current_index', 0);
        }
        update_option('_epim_cron_busy', '');
        return;

    }

    if ($epim_update_running == 'Preparing to Sort attributes') {

        update_option('_epim_cron_busy', '1');

        switch (epimapi_sort_attributes()) {
            case 1:
                cron_log('Restarting Sorting attributes');
                update_option('_epim_cron_busy', '0');
                update_option('_epim_update_running', 'Preparing to Sort attributes');
                break;
            case 2:
                update_option('_epim_update_running', 'Preparing to link attributes to products');
                cron_log('Preparing to link attributes to products');
                update_option('_epim_products_processed','');
                break;
            default:
                update_option('_epim_update_running', 'Preparing to Import Images');
                update_option('_epim_background_process_data', '');
                update_option('_epim_background_current_index', 0);
        }
        update_option('_epim_cron_busy', '');
        return;

    }

    if (($epim_update_running == 'Preparing to link attributes to products') || ($epim_update_running == 'Restarting linking attributes to products')) {

        update_option('_epim_cron_busy', '1');

        update_option('_epim_update_running', 'Linking attributes to products');

        switch (epimapi_link_attributes()) {
            case 1:
                update_option('_epim_update_running', 'Restarting linking attributes to products');
                cron_log('Restarting linking attributes to products');
                break;
            case 2:
                cron_log('Preparing to Import Images');
                update_option('_epim_update_running', 'Preparing to Import Images');
                break;
            default:
                update_option('_epim_update_running', '');
                update_option('_epim_background_process_data', '');
                update_option('_epim_background_current_index', 0);

        }
        update_option('_epim_cron_busy', '');
        return;

    }

    if (($epim_update_running == 'Preparing to Import Images') || ($epim_update_running == 'Restarting Import of Images')) {

        update_option('_epim_cron_busy', '1');

        switch (epimapi_import_images()) {
            case 1:
                update_option('_epim_update_running', 'Restarting Import of Images');
                cron_log('Restarting Import of Images');
                break;
            case 2:
                cron_log('Import Finished');
                update_option('_epim_update_running', '');
                break;
            default:
                update_option('_epim_update_running', '');
                update_option('_epim_background_process_data', '');
                update_option('_epim_background_current_index', 0);
        }
        update_option('_epim_cron_busy', '');
        update_option('_epim_products_to_process','');
        update_option('_epim_products_processed','');
        return;

    }


}

function epim_in_flat_array($array, $value)
{
    $x = 0;
    if (is_array($array)) {
        foreach ($array as $item) {
            if ($item === $value) return $x;
            $x++;
        }
    }
    return -1;
}

function epim_in_array($array, $key, $value)
{
    if (is_array($array)) {
        if (array_key_exists($key, $array)) {
            foreach ($array as $item) {
                if ($item[$key] === $value) return $item;
            }
        }
    }
    return false;
}


function epim_createAttribute(string $attributeName, string $attributeSlug)
{

    $attributeSlug = apply_filters( 'epim_custom_attribute_slug', $attributeSlug, $attributeName );

    $attributeId = wc_attribute_taxonomy_id_by_name($attributeSlug);
    if (!$attributeId) {
        $taxonomyName = wc_attribute_taxonomy_name($attributeSlug);
        unregister_taxonomy($taxonomyName);
        $attributeId = wc_create_attribute(array(
            'name' => $attributeName,
            'slug' => $attributeSlug,
            'type' => 'select',
            'order_by' => 'menu_order',
            'has_archives' => 0,
        ));

        register_taxonomy($taxonomyName, apply_filters('woocommerce_taxonomy_objects_' . $taxonomyName, array(
            'product'
        )), apply_filters('woocommerce_taxonomy_args_' . $taxonomyName, array(
            'labels' => array(
                'name' => $attributeSlug,
            ),
            'hierarchical' => FALSE,
            'show_ui' => FALSE,
            'query_var' => TRUE,
            'rewrite' => FALSE,
        )));
    }

    if (is_wp_error($attributeId)) {
        return $attributeId;
    } else {
        return wc_get_attribute($attributeId);
    }


}

function epim_createTerm(string $termName, string $termSlug, string $taxonomy, string $sku, int $order = 0)
{

    $taxonomy = wc_attribute_taxonomy_name($taxonomy);

    if (!$term = get_term_by('slug', $termSlug, $taxonomy)) {
        $term = wp_insert_term($termName, $taxonomy, array(
            'slug' => $termSlug,
        ));
        if (is_wp_error($term)) {
            error_log('SKU: ' . $sku . ' | Error creating term: ' . $termName . ' ¦ $termSlug = ' . $termSlug . ' ¦ $taxonomy = ' . $taxonomy . ' ¦ msg = ' . $term->get_error_message());
        } else {
            $term = get_term_by('id', $term['term_id'], $taxonomy);
            if ($term) {
                update_term_meta($term->term_id, 'order', $order);
            }
        }
    }

    return $term;
}

function epimapi_is_update_stuck()
{
    $epim_update_running = get_option('_epim_update_running');
    if ($epim_update_running != '') {
        $epim_background_current_index = get_option('_epim_background_current_index');
        $epim_background_last_index = get_option('_epim_background_last_index');
        if ('$epim_background_current_index' != 0) {
            if ($epim_background_current_index == $epim_background_last_index) {
                update_option('_epim_update_running', 'Categories Updated and Sorted');
                cron_log('Unfreezing Queue Please wait.');
                return true;
            } else {
                update_option('_epim_background_last_index', $epim_background_current_index);
            }
        }
    }
    return false;
}

function epimaapi_update_branch_stock_minutes()
{
    return;
    $epim_update_running = get_option('_epim_update_running');
    if ($epim_update_running != '') {
        //cron_log('Branch Stock Update Cancelled - other updates running(10 minute update)');
        //error_log('Branch Stock Update Cancelled - other updates running(10 minute update)');
        return;
    }
    if (!wp_next_scheduled('epimaapi_update_branch_stock_daily_action')) {
        wp_schedule_event(strtotime('22:20:00'), 'daily', 'epimaapi_update_branch_stock_daily_action');
    }
    if (!wp_next_scheduled('epimaapi_update_every_minute_minute_action')) {
        wp_schedule_event(time(), 'minutes_1', 'epimaapi_update_every_minute_minute_action');
    }
    set_time_limit(0);
    if (!wp_next_scheduled('epimaapi_update_every_minute_minute_action')) {
        wp_schedule_event(time(), 'minutes_1', 'epimaapi_update_every_minute_minute_action');
    }
    $epim_enable_scheduled_updates = false;
    $epim_enable_scheduled_updates_option = get_option('epim_enable_scheduled_updates');
    if (is_array($epim_enable_scheduled_updates_option)) {
        if ($epim_enable_scheduled_updates_option['checkbox_value'] == 1) {
            $epim_enable_scheduled_updates = true;
        }
    }
    $epim_update_schedule = get_option('epim_update_schedule');
    //error_log('running 10 minute branch stock update');
    if ($epim_update_schedule == 'minutes') {
        if ($epim_enable_scheduled_updates) {
            cron_log('Updating Branch Stock (10 minute update)');
            epimaapi_update_branch_stock_cron();
        } else {
            //cron_log('10 minute update aborted - Updates not enabled');
        }
    } else {
        //cron_log('10 minute branch price and stock update aborted - set to daily updates');
    }

    $epim_update_running = get_option('_epim_update_running');
    if ($epim_update_running == '') {
        update_option('_epim_background_current_index', 0);
        $iso = (new \DateTime('-30 minutes', new \DateTimeZone("UTC")))->format(\DateTime::ATOM);
        //error_log($iso);
        epimaapi_background_import_products_from($iso);
        return;
    }
}

function epimaapi_update_branch_stock_cron()
{
    return;
    /*define( 'WP_USE_THEMES', false );
    require( $_SERVER['DOCUMENT_ROOT'] .'/wp-load.php' );*/
    $log = '';
    $start = microtime(true);
    //error_log('epimaapi_update_branch_stock_cron started');
    $yesterday = date('Y-m-d', strtotime("-1 days"));
    $branches = json_decode(get_epimaapi_all_branches(), true);
    if (is_array($branches)) {
        foreach ($branches as $branch) {
            if (is_array($branch)) {
                if (array_key_exists('Id', $branch)) {
                    $Id = $branch['Id'];
                    $stockLevels = json_decode(get_epimaapi_get_branch_stock_since($Id, $yesterday), true);
                    if (is_array($stockLevels)) {
                        foreach ($stockLevels as $stock_level) {
                            $datetime_now = date('d/m/Y h:i:s a', time());
                            $this_log = $datetime_now . ': ' . epimaapi_update_branch_stock($Id, $stock_level['VariationId'], $stock_level['Stock']);
                            $log .= $this_log . '</br>';
                            //error_log($this_log);
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
    $updatedProducts = get_epimaapi_all_changed_products_since($yesterday);
    $jProducts = json_decode($updatedProducts, true);
    if (is_array($jProducts)) {
        $limit = $jProducts['Limit'];
        if ($limit > 0) {
            $totalResults = $jProducts['TotalResults'];
            $pages = ceil($totalResults / $limit);
            $products = $jProducts['Results'];
            foreach ($products as $product) {
                foreach ($product['VariationIds'] as $variationId) {
                    epimaapi_create_product($product['Id'], $variationId, $product['BulletText'], $product['Name'], $product['CategoryIds'], $product['PictureIds']);
                }
            }
            for ($i = 1; $i <= $pages; $i++) {
                $start = $i * $limit;
                $pagedProducts = get_epimaapi_all_changed_products_since_starting($start, $yesterday);
                $jpProducts = json_decode($pagedProducts, true);
                $products = $jpProducts['Results'];
                if (is_array($products)) {
                    foreach ($products as $product) {
                        foreach ($product['VariationIds'] as $variationId) {
                            epimaapi_create_product($product['Id'], $variationId, $product['BulletText'], $product['Name'], $product['CategoryIds'], $product['PictureIds']);
                        }
                    }
                }
            }
        }
    }
    $time_elapsed_secs = microtime(true) - $start;
    $log .= 'Import took ' . $time_elapsed_secs . ' seconds.';

    if (!(false === get_option('epim_schedule_log'))) {
        //error_log('epimaapi_update_branch_stock_daily Import Took '.$time_elapsed_secs.' seconds');
        update_option('epim_schedule_log', $log);
        //error_log(get_option('epim_schedule_log'));
    }
}

function epimaapi_update_all_branch_stock()
{
    $start = microtime(true);
    $json_all_branches = json_decode(get_epimaapi_all_branches());
    foreach ($json_all_branches as $branch) {
        $stock_in_branch = json_decode(get_epimaapi_branch_stock($branch->Id));
        foreach ($stock_in_branch as $stock_item) {
            cron_log(epimaapi_update_branch_stock($branch->Id, $stock_item->VariationId, $stock_item->Stock));
        }
    }
    $time_elapsed_secs = microtime(true) - $start;
    cron_log('Import took ' . $time_elapsed_secs . ' seconds.');
    cron_log('finished.....');
}