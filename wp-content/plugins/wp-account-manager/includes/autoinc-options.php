<?php
add_action('admin_menu', 'create_wpam_settings_page');

function create_wpam_settings_page()
{
    // Add the menu item and page
    $page_title = 'WP Account Manager';
    $menu_title = 'WP Account Manager';
    $capability = 'manage_options';
    $slug = 'wpam-options';
    $callback = 'wpam_settings_page_content';
    $icon = 'dashicons-groups';
    $position = 100;

    //add_menu_page( $page_title, $menu_title, $capability, $slug, $callback, $icon, $position );
    add_submenu_page('options-general.php', $page_title, $menu_title, $capability, $slug, $callback);
}

function wpam_settings_page_content()
{
    ?>
    <div class="wrap">
        <h2>WP Account Manager Settings</h2>
        <form method="post" action="options.php">
            <?php
            settings_fields('wpam_options');
            do_settings_sections('wpam_options');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

add_action( 'admin_init', 'wpam_setup_sections' );

function wpam_setup_sections() {
    add_settings_section( 'wpam_recapture_section', 'reCapture Options', false, 'wpam_options' );
}

/*function wpam_options($arguments) {
    switch( $arguments['id'] ){
        case 'wpam_recapture_section':
            echo 'This is the reCapture Options here!';
            break;
        case 'our_second_section':
            echo 'This one is number two';
            break;
        case 'our_third_section':
            echo 'Third time is the charm!';
            break;
    }
}*/

add_action( 'admin_init', 'setup_wpam_fields' );

function setup_wpam_fields() {
    add_settings_field( 'recapture_site_key', 'Site Key', 'wpam_recapture_site_key_callback' , 'wpam_options', 'wpam_recapture_section' );
    register_setting( 'wpam_options', 'wpam_recapture_site_key' );
    add_settings_field( 'recapture_secret_key', 'Secret Key', 'wpam_recapture_secret_key_callback' , 'wpam_options', 'wpam_recapture_section' );
    register_setting( 'wpam_options', 'wpam_recapture_secret_key' );
}

function wpam_recapture_site_key_callback( $arguments ) {
    echo '<input name="wpam_recapture_site_key" id="wpam_recapture_site_key" type="text" value="' . get_option( 'wpam_recapture_site_key' ) . '" class="regular-text code"/>';
}

function wpam_recapture_secret_key_callback( $arguments ) {
    echo '<input name="wpam_recapture_secret_key" id="wpam_recapture_secret_key" type="text" value="' . get_option( 'wpam_recapture_secret_key' ) . '" class="regular-text code"/>';
}