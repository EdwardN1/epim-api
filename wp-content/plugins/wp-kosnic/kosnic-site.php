<?php
/**
 * @package epim_api
 * @version 1.0.0
 */
/*
Plugin Name: Kosnic Woocommerce Styling
Plugin URI: https://e-pim.co.uk
Description: Custom sytling for Kosnic website
Author: Edward Nickerson
License: GPLv2 or later
Version: 1.0.0
Author URI: https://www.technicks.com
*/

define('kosnic_FUNCTIONSPATH', plugin_dir_path( __FILE__ ) . '/includes/');
define('kosnic_PLUGINPATH', plugin_dir_path( __FILE__ ) );
define('kosnic_PLUGINURI', plugin_dir_url(__FILE__));
define('kosnic_PLUGINFILE',__FILE__);
$active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
if ( in_array( 'woocommerce/woocommerce.php', $active_plugins ) ) {
	foreach (glob(kosnic_FUNCTIONSPATH.'autoinc-*.php') as $filename)
	{
		require_once ($filename);
	}
}