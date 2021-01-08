<?php

add_action( 'profile_update', 'wpiai_profile_update', 10, 2 );

function wpiai_profile_update( $user_id, $old_user_data ) {
	// Do something
	error_log('Profile update for userID: '.$user_id);
	$url = get_option( 'wpiai_customer_url' );
	$parameters = get_option( 'wpiai_customer_parameters' );
	$xmld = get_option( 'wpiai_customer_xml' );
	$user = get_userdata($user_id);
	if ($user) {
		$xmlRequest = get_customer_XML_record($user_id);
		//$updated = wpiai_get_infor_message_multipart_message($url,$parameters,$xmlRequest);
		//error_log(print_r($updated,true));
	}
}