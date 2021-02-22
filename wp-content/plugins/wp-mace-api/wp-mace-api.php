<?php
/*
Plugin Name: WP Mace API Interface
Plugin URI: https://www.technicks.com
Description: Implements an interface into the Mace API.
Author: Edward Nickerson
Version: 1.0.0
Author URI: https://www.technicks.com
*/

define( 'wpmace_FUNCTIONSPATH', plugin_dir_path( __FILE__ ) . '/includes/' );
define( 'wpmace_PLUGINPATH', plugin_dir_path( __FILE__ ) );
define( 'wpmace_PLUGINURI', plugin_dir_url( __FILE__ ) );
foreach ( glob( wpmace_FUNCTIONSPATH . 'autoinc-*.php' ) as $filename ) {
require_once( $filename );
}