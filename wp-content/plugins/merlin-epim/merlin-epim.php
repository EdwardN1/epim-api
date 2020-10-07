<?php
/**
 * @package wp_account_manager
 * @version 1.0.0
 */
/*
Plugin Name: Merlin Epim API interface
Plugin URI: https://www.technicks.com
Description: Implements an API interface into the Merlin System for ePim
Author: Edward Nickerson
Version: 1.0.0
Author URI: https://www.technicks.com
*/

define('epmer_FUNCTIONSPATH', plugin_dir_path( __FILE__ ) . '/includes/');
define('epmer_PLUGINPATH', plugin_dir_path( __FILE__ ) );
define('epmer_PLUGINURI',plugin_dir_url(__FILE__));
foreach (glob(wpam_FUNCTIONSPATH.'autoinc-*.php') as $filename)
{
	require_once ($filename);
}