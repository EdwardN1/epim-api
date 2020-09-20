<?php
function get_accounts( $data ) {
    $accountargs = array(
        'post_type'      => 'wpam_accounts',
        'posts_per_page' => - 1,
    ) ;

    $accounts = get_posts($accountargs);

    $response = array();

    //$controller = new WP_REST_Posts_Controller('wpam_accounts');

    //$raccounts = array();

    foreach ($accounts as $account) {
        $raccount = array();
        $raccount['ID'] = $account->get_the_id();
        $raccount['post_title'] = $account->get_the_title();
        $raccount['meta_data'] = get_post_meta($account->get_the_id());
        $response[] = $raccount;
    }

    return new WP_REST_Response($response,200);

}

add_action( 'rest_api_init', function () {
    register_rest_route( 'wc/v3', 'accounts', array(
        'methods' => 'GET',
        'callback' => 'get_accounts',
    ));
});