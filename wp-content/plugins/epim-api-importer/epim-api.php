<?php
/**
 * @package epim_api
 * @version 1.1.2
 */
/*
Plugin Name: ePim API importer
Plugin URI: https://e-pim.co.uk
Description: This plugin requires you to have an account at https://epim.online and an activated epim api. You wil then be able to import your product data from multiple print and digital sources straight into WooCommerce for an instant online shop.
Author: Edward Nickerson
License: GPLv2 or later
Version: 1.1.2
Author URI: https://www.technicks.com
*/

define('epimaapi_FUNCTIONSPATH', plugin_dir_path( __FILE__ ) . '/includes/');
define('epimaapi_PLUGINPATH', plugin_dir_path( __FILE__ ) );
define('epimaapi_PLUGINURI', plugin_dir_url(__FILE__));
define('epimaapi_PLUGINFILE',__FILE__);

global $is_divi;
$is_divi = false;

$active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );

if ( in_array( 'woocommerce/woocommerce.php', $active_plugins ) ) {
	if( !in_array('click-collect/click-collect.php',$active_plugins)) {
		define('cac_FUNCTIONSPATH', plugin_dir_path( __FILE__ ) . '/includes/');
		define('cac_PLUGINPATH', plugin_dir_path( __FILE__ ) );
		foreach (glob(epimaapi_FUNCTIONSPATH.'autoinc-candc-*.php') as $filename)
		{
			require_once ($filename);
		}
	}
	if( !in_array('woo-purchase-on-account/woo-purchase-on-account.php',$active_plugins)) {
		define('wpoa_FUNCTIONSPATH', plugin_dir_path( __FILE__ ) . '/includes/');
		define('wpoa_PLUGINPATH', plugin_dir_path( __FILE__ ) );
		foreach (glob(epimaapi_FUNCTIONSPATH.'autoinc-wpoa-*.php') as $filename)
		{
			require_once ($filename);
		}
	}
    $divi_theme = wp_get_theme();
    if('Divi' == $divi_theme->name) {
        $is_divi = true;
        foreach (glob(epimaapi_FUNCTIONSPATH.'autoinc-divi-*.php') as $filename)
        {
            require_once ($filename);
        }
    }
	foreach (glob(epimaapi_FUNCTIONSPATH.'autoinc-*.php') as $filename)
	{
		if(strpos($filename,'autoinc-wpoa')===false) {
			if(strpos($filename,'autoinc-candc')===false) {
                if(strpos($filename,'autoinc-divi-')===false) {
                    require_once ($filename);
                }
			}
		}
	}
}


