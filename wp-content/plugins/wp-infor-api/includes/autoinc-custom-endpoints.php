<?php
function set_csdorders( $request ) {
	//return array( 'csdorders' => 'Data' );
	$d = json_decode($request->get_body());
	$r = $d->meta_data;
	return $r;

}

add_action( 'rest_api_init', function () {
	register_rest_route( 'wc/v3', 'csdorders', array(
		'methods' => 'POST',
		'callback' => 'set_csdorders',
	));
});