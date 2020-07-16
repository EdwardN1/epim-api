<?php
/**
 * @package wp_account_manager
 * @version 1.0.0
 */
/*
Plugin Name: WP Account Manager
Plugin URI: https://www.technicks.com
Description: Implements a separate account management system for Worpdress to protect front end pages without access to WP users or CMS.
Author: Edward Nickerson
Version: 1.0.0
Author URI: https://www.technicks.com
*/

define('wpam_FUNCTIONSPATH', plugin_dir_path( __FILE__ ) . '/includes/');
define('wpam_PLUGINPATH', plugin_dir_path( __FILE__ ) );
foreach (glob(wpam_FUNCTIONSPATH.'autoinc-*.php') as $filename)
{
    require_once ($filename);
}