<?php
/**
 * @package sylvania_wp
 * @version 1.0.0
 */
/*
Plugin Name: Sylvania WP plugin
Plugin URI: https://e-pim.co.uk
Description: Custom API for Sylvania Lighting
Author: Edward Nickerson
License: GPLv2 or later
Version: 1.0.0
Author URI: https://www.technicks.com
*/

define('swp_FUNCTIONSPATH', plugin_dir_path( __FILE__ ) . '/includes/');
define('swp_PLUGINPATH', plugin_dir_path( __FILE__ ) );
define('swp_PLUGINURI', plugin_dir_url(__FILE__));
define('swp_PLUGINFILE',__FILE__);
define('swp_EN_API','https://uat-product.sylvania-group.com/en-int/CategoriesAPI');
define('swp_FR_API','https://uat-product.sylvania-group.com/fr-fr/CategoriesAPI');
define('swp_ENG_ROOT_URI','https://www.sylvania-lighting.com/product/en-int/');
define('swp_FR_ROOT_URI','https://www.sylvania-lighting.com/product/fr-fr/');
/*$active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
if ( in_array( 'woocommerce/woocommerce.php', $active_plugins ) ) {

}*/
foreach (glob(swp_FUNCTIONSPATH.'autoinc-*.php') as $filename)
{
    require_once ($filename);
}
