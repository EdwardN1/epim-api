<?php
/**
 * @package click_collect
 * @version 1.0.0
 */
/*
Plugin Name: Click and Collect for WooCommerce
Plugin URI: https://www.technicks.com
Description: Implements Multiple Branch Click and Collect for WooCommerce
Author: Edward Nickerson
Version: 1.0.0
Author URI: https://www.technicks.com
*/

define('ea_FUNCTIONSPATH', plugin_dir_path( __FILE__ ) . '/includes/');
define('ea_PLUGINPATH', plugin_dir_path( __FILE__ ) );
foreach (glob(ea_FUNCTIONSPATH.'autoinc-*.php') as $filename)
{
    require_once ($filename);
}