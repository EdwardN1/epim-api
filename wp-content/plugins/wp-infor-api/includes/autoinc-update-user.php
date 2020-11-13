<?php

add_action( 'profile_update', 'wpiai_profile_update', 10, 2 );

function wpiai_profile_update( $user_id, $old_user_data ) {
	// Do something
	$url = get_option( 'wpiai_customer_url' );
	$parameters = get_option( 'wpiai_customer_parameters' );
	$xmld = get_option( 'wpiai_customer_xml' );
	$user = get_userdata($user_id);
	if ($user) {
		$xml = simplexml_load_string($xmld);

	}
}