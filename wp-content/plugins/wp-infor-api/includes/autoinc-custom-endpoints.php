<?php
function set_csdorders( $request ) {
	//return array( 'csdorders' => 'Data' );
	$body = json_decode($request->get_body());
	$meta = $body->meta_data;
	if(is_array($meta)) {
		$CSD_ID = false;
		foreach ($meta as $key_value) {
			if($key_value->key=='CSD_ID') {
				$CSD_ID = $key_value->value;
			}
		}
		if($CSD_ID) {
			return array( 'CSD_ID' => $CSD_ID );
		}
		return array( 'error' => 'could not find CSD_ID in meta_data' );
	} else {
		return array( 'error' => 'No meta could not find CSD_ID' );
	}
}

add_action( 'rest_api_init', function () {
	register_rest_route( 'wc/v3', 'csdorders', array(
		'methods' => 'POST',
		'callback' => 'set_csdorders',
	));
});