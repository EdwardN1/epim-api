<?php

/**
 * @package wp_merlin_api
 * @version 1.0.0
 */
/*
Plugin Name: WP Merlin API Interface
Plugin URI: https://www.technicks.com
Description: Implements an interface into the Merlin API.
Author: Edward Nickerson
Version: 1.0.0
Author URI: https://www.technicks.com
*/

define( 'wpmai_FUNCTIONSPATH', plugin_dir_path( __FILE__ ) . '/includes/' );
define( 'wpmai_PLUGINPATH', plugin_dir_path( __FILE__ ) );
define( 'wpmai_PLUGINURI', plugin_dir_url( __FILE__ ) );
foreach ( glob( wpmai_FUNCTIONSPATH . 'autoinc-*.php' ) as $filename ) {
	require_once( $filename );
}