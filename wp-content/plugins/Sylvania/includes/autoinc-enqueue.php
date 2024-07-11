<?php
function swp_site_scripts() {
    global $wp_styles; // Call global $wp_styles variable to add conditional wrapper around ie stylesheet the WordPress way

    // Adding scripts file in the footer
    //if(!file_exists(swp_PLUGINPATH . 'styles/style.css')) error_log('FILE DOES NOT EXIST: '.swp_PLUGINPATH . 'styles/style.css');
    wp_enqueue_script( 'swp-site-js', swp_PLUGINURI . 'scripts/js/slick.min.js', array( 'jquery' ), filemtime(swp_PLUGINPATH . 'scripts/js/slick.min.js'), true );

    // Register main stylesheet
    wp_enqueue_style( 'swp-site-css', swp_PLUGINURI . 'styles/style.css', array(), filemtime(swp_PLUGINPATH . 'styles/styles.css'), 'all' );


}
add_action('wp_enqueue_scripts', 'swp_site_scripts', 999);