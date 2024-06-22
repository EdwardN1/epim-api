<?php

add_action('acf/init', 'swp_acf_op_init');

function swp_acf_op_init() {
    if ( function_exists( 'acf_add_options_page' ) ) {

        acf_add_options_page( array(
            'page_title'	=> 'API Options',
            'menu_title'	=> 'API Options',
            'menu_slug' 	=> 'api-options',
            'capability'	=> 'edit_posts',
            'redirect'		=> false,
        ));

    }
}
