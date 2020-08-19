<?php
/**
 * @package wp_addon_account_manager_valid_customer
 * @version 1.0.0
 */
/*
Plugin Name: WP Account Manager Addon Validate Account Customer
Plugin URI: https://www.technicks.com
Description: Extends WP Account Manager and Woo Purchase on Account so Account Holders can place orders using a WP User to be paid on account.
Author: Edward Nickerson
Version: 1.0.0
Author URI: https://www.technicks.com
*/

define('wpamvc_FUNCTIONSPATH', plugin_dir_path(__FILE__) . '/includes/');
define('wpamvc_PLUGINPATH', plugin_dir_path(__FILE__));
define('wpamvc_PLUGINURI', plugin_dir_url(__FILE__));
$active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
if ( in_array( 'wp-account-manager/wp-account-manager.php', $active_plugins ) ) {
    if ( in_array( 'woo-purchase-on-account/woo-purchase-on-account.php', $active_plugins ) ) {
        foreach (glob(wpamvc_FUNCTIONSPATH . 'autoinc-*.php') as $filename) {
            require_once($filename);
        }
    }
}
