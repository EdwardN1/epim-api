<?php
/**
 * @package epim_api
 * @version 1.0.0
 */
/*
Plugin Name: DH Stainless ePim plugin
Plugin URI: https://e-pim.co.uk
Description: Custom ePim API for DH Stainless
Author: Edward Nickerson
License: GPLv2 or later
Version: 1.0.0
Author URI: https://www.technicks.com
*/

define('dhstainless_FUNCTIONSPATH', plugin_dir_path( __FILE__ ) . '/includes/');
define('dhstainless_PLUGINPATH', plugin_dir_path( __FILE__ ) );
define('dhstainless_PLUGINURI', plugin_dir_url(__FILE__));
define('dhstainless_PLUGINFILE',__FILE__);
$active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
if ( in_array( 'woocommerce/woocommerce.php', $active_plugins ) ) {
	foreach (glob(dhstainless_FUNCTIONSPATH.'autoinc-*.php') as $filename)
	{
		require_once ($filename);
	}
}
