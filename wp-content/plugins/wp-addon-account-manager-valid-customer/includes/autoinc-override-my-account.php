<?php

global $wpamIsLoggedIn;
$wpamIsLoggedIn = 0;

add_action('plugins_loaded','wpamvc_check_for_login');

function wpamvc_check_for_login() {
    global $wpamIsLoggedIn;
    $account = new Account;
    $wpamIsLoggedIn = 0;
    if($account->sessionLogin()) {
        $wpamIsLoggedIn = 1;
    }
}

add_action('wpam_logged_in','wpamvc_logged_in');

function wpamvc_logged_in() {
    global $wpamIsLoggedIn;
    $wpamIsLoggedIn = 1;
    error_log('wpamvc_logged_in $wpamIsLoggedIn = '.$wpamIsLoggedIn);
}

add_action('wpam_logout','wpamvc_logout');

function wpamvc_logout(){
    global $wpamIsLoggedIn;
    $wpamIsLoggedIn = 0;
}

add_action( 'woocommerce_after_customer_login_form', 'pay_on_account_login' );

function pay_on_account_login() {
    global $wpamIsLoggedIn;
	if( ! is_user_logged_in() ){
		if($wpamIsLoggedIn==1) {
			error_log('Logged in already Error');
			//wp_redirect($_SERVER['HTTP_REFERER']);
		} else {
			echo '<div class="pay-on-account-login-section"><h2>Pay on account login</h2>';
			echo do_shortcode('[wpam recapture="true"]');
			echo '</div>';
		}

	}
}

add_shortcode('poa_woocommerce_my_account','wpamvc_my_account');
function wpamvc_my_account($atts) {
    global $wpamIsLoggedIn;
    $na = new Account();
    $na->sessionLogin();
    error_log('wpamvc_my_account $wpamIsLoggedIn = '.$wpamIsLoggedIn);
	if($wpamIsLoggedIn==1) {
		echo '<div class="pay-on-account-login-section"><h2>Your Account</h2>';
		echo do_shortcode('[wpam recapture="true"]');
		echo '</div>';
	} else {
		echo do_shortcode('[woocommerce_my_account]');
	}
}


