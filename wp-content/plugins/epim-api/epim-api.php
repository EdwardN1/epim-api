<?php
/**
 * @package epim_api
 * @version 1.0.0
 */
/*
Plugin Name: ePim API integration
Plugin URI: https://www.technicks.com
Description: Implements WordPress and WooCommerce integration with ePim from NG15
Author: Edward Nickerson
License: GPLv2 or later
Version: 1.0.0
Author URI: https://www.technicks.com
*/

define('ea_FUNCTIONSPATH', plugin_dir_path( __FILE__ ) . '/includes/');
define('ea_PLUGINPATH', plugin_dir_path( __FILE__ ) );
define('ea_PLUGINURI', plugins_url().'/epim-api');
$active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
if ( in_array( 'woocommerce/woocommerce.php', $active_plugins ) ) {
	foreach (glob(ea_FUNCTIONSPATH.'autoinc-*.php') as $filename)
	{
		require_once ($filename);
	}
}


