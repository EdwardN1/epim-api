<?php


add_shortcode('poa_woocommerce_my_account','wpamvc_my_account');

function wpamvc_my_account($atts) {

	if(!is_user_logged_in()) {
		echo do_shortcode('[wpam recapture="true"]');
	} else {
		echo do_shortcode('[woocommerce_my_account]');
	}


}

add_action('wpam_before_log_in_form','wpamvc_before_log_in_form');
add_action('wpam_before_logged_in_success','wpamvc_before_logged_in_success');
add_action('wpam_before_logout_success','wpamvc_before_logout_success');

function wpamvc_before_log_in_form() {
	echo do_shortcode('[woocommerce_my_account]');
	echo '<div class="pay-on-account-login-section"><h2>Pay on Account Login</h2>';
}

function wpamvc_before_logged_in_success() {
	echo '<div class="pay-on-account-login-section"><h2>Your Account</h2>';
}

function wpamvc_before_logout_success() {
	echo do_shortcode('[woocommerce_my_account]');
	echo '<div class="pay-on-account-login-section"><h2>Pay on Account Login</h2>';
}


