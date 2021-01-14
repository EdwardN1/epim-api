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
		$roles = $user->roles;
		if(in_array('customer',$roles)) {
			$xmlRequest = get_customer_XML_record($user_id);
			$CSD_ID = get_user_meta($user_id,'CSD_ID',true);
			if($CSD_ID == '') {
				$updated = wpiai_get_infor_message_multipart_message($url,$parameters,$xmlRequest);
				error_log(print_r($updated,true));
			}

			//error_log('Customer account');
			//error_log(print_r($roles,true));
		} else {
			//error_log('Not a Customer account');
			//error_log(print_r($roles,true));
		}
	}
}