<?php
function get_accounts( $data ) {
	$accountargs = array(
		'post_type'      => 'wpam_accounts',
		'posts_per_page' => - 1,
	);

	//$accounts = new WP_Query( $accountargs );

	$accounts = get_posts($accountargs);

	$response = array();

	//$controller = new WP_REST_Posts_Controller('wpam_accounts');

	//$raccounts = array();

	/*if ( have_posts( $accounts ) ):
		while ( have_posts( $accounts ) ): the_post();
			$raccount               = array();
			$raccount['ID']         = get_the_id();
			$raccount['post_title'] = get_the_title();
			$raccount['meta_data']  = get_post_meta( get_the_id() );
			$response[]             = $raccount;
		endwhile;

		wp_reset_postdata();
	endif;*/

	foreach ( $accounts as $account ) {
		$raccount               = (array) $account;
		$meta_data = get_post_meta($account->ID);
		$raccount['meta_data'] = $meta_data;
		$response[]             = $raccount;
	}

	return new WP_REST_Response($response,200);

}

add_action( 'rest_api_init', function () {
	register_rest_route( 'wc/v3', 'accounts', array(
		'methods'  => 'GET',
		'callback' => 'get_accounts',
	) );
} );