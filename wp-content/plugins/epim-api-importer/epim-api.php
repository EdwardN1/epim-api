<?php
/**
 * @package epim_api
 * @version 1.0.0
 */
/*
Plugin Name: ePim API importer
Plugin URI: https://e-pim.co.uk
Description: This plugin requires you to have an account at https://epim.online and an activated epim api. You wil then be able to import your product data from multiple print and digital sources straight into WooCommerce for an instant online shop.
Author: Edward Nickerson
License: GPLv2 or later
Version: 1.0.1
Author URI: https://www.technicks.com
*/

define('epimaapi_FUNCTIONSPATH', plugin_dir_path( __FILE__ ) . '/includes/');
define('epimaapi_PLUGINPATH', plugin_dir_path( __FILE__ ) );
define('epimaapi_PLUGINURI', plugin_dir_url(__FILE__));
define('epimaapi_PLUGINFILE',__FILE__);
$active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
if ( in_array( 'woocommerce/woocommerce.php', $active_plugins ) ) {
	foreach (glob(epimaapi_FUNCTIONSPATH.'autoinc-*.php') as $filename)
	{
		require_once ($filename);
	}
}


