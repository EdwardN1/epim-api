<?php
add_filter( 'pre_option_woocommerce_enable_guest_checkout', 'wpamvc_disable_guest_checkout' );

function wpamvc_disable_guest_checkout( $value ) {
	$account = new Account;
	if ($account->sessionLogin()) {
		$value = 'no';
	}
	return $value;
}

add_action( 'woocommerce_before_checkout_form', 'wpamvc_remove_login_form', 4 );
function wpamvc_remove_login_form() {
	if( ! is_user_logged_in() ) {
		$account = new Account;
		if ($account->sessionLogin()) {
			remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
		} else {
			remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
			add_action( 'woocommerce_before_checkout_form', 'wpamvc_checkout_login_form', 20 );
		}
	}
}

function wpamvc_checkout_login_form() {
	echo '<div class="woocommerce-info"> Returning customer? <a href="'.get_permalink( get_option('woocommerce_myaccount_page_id') ).'">Click here to login</a>	</div>';
}

add_action( 'woocommerce_order_status_completed', 'wpamvc_woocommerce_order_status_completed', 10, 1 );
function wpamvc_woocommerce_order_status_completed( $order_id ) {
	error_log( "============================== Order complete for order ============================".$order_id);
}

add_action( 'woocommerce_order_status_processing', 'wpamvc_woocommerce_order_status_processing', 10, 1 );
function wpamvc_woocommerce_order_status_processing( $order_id ) {
	error_log( "============================== Order processing for order ============================".$order_id);
}



