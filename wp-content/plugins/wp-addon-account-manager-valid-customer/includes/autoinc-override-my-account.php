<?php


add_action( 'woocommerce_after_customer_login_form', 'pay_on_account_login' );

function pay_on_account_login() {
	$account = new Account;
	if( ! is_user_logged_in() ){
		if($account->sessionLogin()) {
			error_log('Logged in already');
			wp_redirect($_SERVER['HTTP_REFERER']);
		} else {
			error_log('Not Logged in try again');
			if($account)
			echo '<div class="pay-on-account-login-section grid-container"><h2>Pay on account login</h2>';
			echo do_shortcode('[wpam recapture="true"]');
			echo '</div>';
		}

	}
}

add_shortcode('poa_woocommerce_my_account','wpamvc_my_account');
function wpamvc_my_account($atts) {
	$account = new Account;
	if($account->sessionLogin()) {
		echo '<div class="pay-on-account-login-section grid-container"><h2>Your Account</h2>';
		echo do_shortcode('[wpam recapture="true"]');
		echo '</div>';
	} else {
		echo do_shortcode('[woocommerce_my_account]');
	}
}


