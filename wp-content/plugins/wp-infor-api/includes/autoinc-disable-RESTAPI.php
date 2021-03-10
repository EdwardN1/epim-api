<?php
function restrict_rest_api_to_localhost() {
	$whitelist = [ '127.0.0.1', "::1" ];
	$whitelist2 = [ '84.66.124.172', "::1" ];

	$wpiai_api_enabled = get_option('wpiai_api_enabled');

	if($wpiai_api_enabled != 1) {
		if ( ! in_array( $_SERVER['REMOTE_ADDR'], $whitelist ) ) {
			if ( ! in_array( $_SERVER['REMOTE_ADDR'], $whitelist2 ) ) {
				die( 'REST API is disabled.' );
			}
		}
	}
}
add_action( 'rest_api_init', 'restrict_rest_api_to_localhost', 0 );