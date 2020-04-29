<?php
/**
 * Plugin Name: Technicks Code Block
 * Author: Edward Nickerson
 * Version: 1.0.0
 */

function load_tcCodeBlock() {
    wp_enqueue_script(
        'technicks-code-block',
        plugin_dir_url(__FILE__) . 'tcCodeBlock.js',
        array('wp-blocks','wp-editor'),
        true
    );
}

add_action('enqueue_block_editor_assets', 'load_tcCodeBlock');