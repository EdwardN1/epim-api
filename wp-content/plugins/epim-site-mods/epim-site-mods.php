<?php
/**
 * @package epim_api
 * @version 1.0.0
 */
/*
Plugin Name: ePim Default Site Modifications
Plugin URI: https://e-pim.co.uk
Description: General Additions for ePim default site
Author: Edward Nickerson
License: GPLv2 or later
Version: 1.0.1
Author URI: https://www.technicks.com
*/

define('epsm_FUNCTIONSPATH', plugin_dir_path( __FILE__ ) . '/includes/');
define('epsm_PLUGINPATH', plugin_dir_path( __FILE__ ) );
define('epsm_PLUGINURI', plugin_dir_url(__FILE__));
define('epsm_PLUGINFILE',__FILE__);
$active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
if ( in_array( 'woocommerce/woocommerce.php', $active_plugins ) ) {
	foreach (glob(epsm_FUNCTIONSPATH.'autoinc-*.php') as $filename)
	{
		require_once ($filename);
	}
}


