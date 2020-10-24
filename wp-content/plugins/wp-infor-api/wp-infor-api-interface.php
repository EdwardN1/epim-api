<?php
/**
 * @package wp_infor_api
 * @version 1.0.0
 */
/*
Plugin Name: WP Infor API Interface
Plugin URI: https://www.technicks.com
Description: Implements an interface into the INFOR API.
Author: Edward Nickerson
Version: 1.0.0
Author URI: https://www.technicks.com
*/

define('wpiai_FUNCTIONSPATH', plugin_dir_path( __FILE__ ) . '/includes/');
define('wpiai_PLUGINPATH', plugin_dir_path( __FILE__ ) );
define('wpiai_PLUGINURI',plugin_dir_url(__FILE__));
foreach (glob(wpiai_FUNCTIONSPATH.'autoinc-*.php') as $filename)
{
	require_once ($filename);
}