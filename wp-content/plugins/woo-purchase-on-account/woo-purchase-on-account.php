<?php
/**
 * @package click_collect
 * @version 1.0.0
 */
/*
Plugin Name: Purchase on account for WooCommerce
Plugin URI: https://www.technicks.com
Description: Adds in a purchase on account payment type for WooCommerce
Author: Edward Nickerson
Version: 1.0.0
Author URI: https://www.technicks.com
*/

defined( 'ABSPATH' ) or exit;

define('wpoa_FUNCTIONSPATH', plugin_dir_path( __FILE__ ) . '/includes/');
define('wpoa_PLUGINPATH', plugin_dir_path( __FILE__ ) );

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

    foreach (glob(wpoa_FUNCTIONSPATH . 'autoinc-*.php') as $filename) {
        require_once($filename);
    }
}