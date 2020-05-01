<?php
/**
Plugin Name: WC Pickup Store
Plugin URI: https://www.keylormendoza.com/plugins/wc-pickup-store/
Description: Let you to set up a custom post type for stores available to use it as shipping method Local pickup in WooCommerce. It allows your client to choose an store in the Checkout page and also adds the details in order details and email.
Version: 1.5.23
Requires at least: 4.7
Tested up to: 5.3.2
WC requires at least: 3.0
WC tested up to: 4.0.1
Author: Keylor Mendoza A.
Author URI: https://www.keylormendoza.com
License: GPLv2
Text Domain: wc-pickup-store
*/

if (!defined('ABSPATH')) { exit; }

if (!defined('WPS_PLUGIN_FILE')) {
	define('WPS_PLUGIN_FILE', plugin_basename(__FILE__));
}

if (!defined('WPS_TEXTDOMAIN')) {
	define('WPS_TEXTDOMAIN', 'wc-pickup-store');
}

if (!defined('WPS_PLUGIN_VERSION')) {
	define('WPS_PLUGIN_VERSION', '1.5.23');
}

/**
** Check if WooCommerce is active
**/
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
	add_action('admin_notices', 'wps_store_inactive_notice');
	return;
}

function wps_store_inactive_notice() {
	if ( current_user_can( 'activate_plugins' ) ) :
		if ( !class_exists( 'WooCommerce' ) ) :
			?>
			<div id="message" class="error">
				<p>
					<?php
					printf(
						__('%s requires %sWooCommerce%s to be active.', 'wc-pickup-store'),
						'<strong>WC Pickup Store</strong>',
						'<a href="http://wordpress.org/plugins/woocommerce/" target="_blank" >',
						'</a>'
					);
					?>
				</p>
			</div>		
			<?php
		endif;
	endif;
}

include plugin_dir_path(__FILE__) . '/includes/wps-init.php';
include plugin_dir_path(__FILE__) . '/includes/wps-admin.php';
include plugin_dir_path(__FILE__) . '/includes/wps-functions.php';
include plugin_dir_path(__FILE__) . '/includes/widget-stores.php';
include plugin_dir_path(__FILE__) . '/includes/post_type-store.php';
include plugin_dir_path(__FILE__) . '/includes/vc_stores.php';

