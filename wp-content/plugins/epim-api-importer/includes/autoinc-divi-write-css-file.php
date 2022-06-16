<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $is_divi;

function epim_write_css_file( $primary, $secondary ) {
	$filename = epimaapi_PLUGINPATH . '/assets/css/divi-overrides.css';
	if ( file_exists( $filename ) ) :
		$file_content     = file_get_contents( $filename );
		$output_file_name = epimaapi_PLUGINPATH . '/assets/css/divi-overrides-set.css';
		$reg = '/^#[a-f0-9]{6}$/i';
		if (preg_match($reg, $primary)) $file_content = str_replace('#9dc133', $primary, $file_content);
		if (preg_match($reg, $secondary)) $file_content = str_replace('#056631', $secondary, $file_content);
		file_put_contents($output_file_name, $file_content);
	endif;
}
