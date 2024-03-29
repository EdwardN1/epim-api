<?php
add_action('wp_enqueue_scripts', 'kosnic_site_scripts', 999);

function kosnic_site_scripts() {
	global $wp_styles; // Call global $wp_styles variable to add conditional wrapper around ie stylesheet the WordPress way

	// Adding scripts file in the footer
	wp_enqueue_script( 'what-input-js', kosnic_PLUGINURI . 'js/what-input.js', array( 'jquery' ), filemtime(kosnic_PLUGINPATH . 'js/what-input.js'), true );
	wp_enqueue_script( 'foundation-js', kosnic_PLUGINURI . 'js/foundation.min.js', array( 'what-input-js' ), filemtime(kosnic_PLUGINPATH . 'js/foundation.min.js'), true );
	wp_enqueue_script( 'init-foundation-js', kosnic_PLUGINURI . 'js/init-foundation.js', array( 'foundation-js' ), filemtime(kosnic_PLUGINPATH . 'js/init-foundation.js'), true );


	// Register main stylesheet
	wp_enqueue_style( 'foundation-css', kosnic_PLUGINURI . 'css/style.css', array(), filemtime(kosnic_PLUGINPATH . 'css/style.css'), 'all' );
	wp_enqueue_style( 'kosnic-css', kosnic_PLUGINURI . 'assets/styles/style.css', array('foundation-css'), filemtime(kosnic_PLUGINPATH . 'assets/styles/style.css'), 'all' );

}