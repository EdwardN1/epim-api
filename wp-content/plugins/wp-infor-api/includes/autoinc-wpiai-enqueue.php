<?php
if ( ! defined( 'ABSPATH' ) )
	exit;

add_action('admin_enqueue_scripts', 'wpiai_admin_enqueue');
function wpiai_admin_enqueue($hook) {
	if ('toplevel_page_infor-options' !== $hook) {
		return;
	}
	wp_enqueue_script('wpiai_admin_ajax', plugins_url('assets/scripts/processQueue.js',__DIR__));
	wp_enqueue_script('wpiai_admin_scripts', plugins_url('assets/scripts/admin.js',__DIR__));
	wp_localize_script(
		'wpiai_admin_scripts',
		'wpiai_ajax_object',
		[
			'ajax_url'  => admin_url( 'admin-ajax.php' ),
			'security'  => wp_create_nonce( 'wpiai-security-nonce' ),
		]
	);
}