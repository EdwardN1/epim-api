<?php
function create_wpam_sessions_posttype() {



    register_post_type( 'wpam_sessions',
        // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Sessions', 'wpamaccounts' ),
                'singular_name' => __( 'Session', 'wpamaccounts' ),
                'all_items' => __('All Sessions', 'wpamaccounts'), /* the all items menu item */
                'add_new' => __('Add New', 'wpamaccounts'), /* The add new menu item */
                'add_new_item' => __('Add New Sessions', 'wpamaccounts'), /* Add New Display Title */
                'edit' => __( 'Edit', 'wpamaccounts' ), /* Edit wpamaccounts */
                'edit_item' => __('Edit Session', 'wpamaccounts'), /* Edit Display Title */
                'new_item' => __('New Session', 'wpamaccounts'), /* New Display Title */
                'view_item' => __('View Session', 'wpamaccounts'), /* View Display Title */
                'search_items' => __('Search Sessions', 'wpamaccounts'), /* Search Custom Type Title */
                'not_found' =>  __('Nothing found in the Database.', 'wpamaccounts'), /* This displays if there are no entries yet */
                'not_found_in_trash' => __('Nothing found in Trash', 'wpamaccounts'), /* This displays if there is nothing in the trash */
            ),
            'menu_icon' => 'dashicons-groups',
            'public' => false,
            'has_archive' => false,
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'show_in_nav_menus'   => false,
            'show_ui'             => is_super_admin(),
            'show_in_menu'        => is_super_admin(),
            'show_in_admin_bar'   => is_super_admin(),
            'rewrite' => array('slug' => 'wpam_sessions'),
            'show_in_rest' => true,

        )
    );
}
// Hooking up our function to theme setup
add_action( 'init', 'create_wpam_sessions_posttype' );

add_filter('use_block_editor_for_post_type', 'wpam_sessions_prefix_disable_gutenberg', 10, 2);
function wpam_sessions_prefix_disable_gutenberg($current_status, $post_type)
{
    // Use your post type key instead of 'product'
    if ($post_type === 'wpam_accounts') return false;
    return $current_status;
}

add_action( 'init', function() {
    remove_post_type_support( 'wpam_sessions', 'editor' );
}, 99);

function wpam_sessions_change_title_text( $title ){
    $screen = get_current_screen();

    if  ( 'wpam_sessions' == $screen->post_type ) {
        $title = 'Enter Session ID';
    }

    return $title;
}

add_filter( 'enter_title_here', 'wpam_sessions_change_title_text' );

/**
 * Stop this post type autosaving:
 */

add_action( 'admin_enqueue_scripts', 'wpam_sessions_admin_enqueue_scripts' );
function wpam_sessions_admin_enqueue_scripts() {
    if ( 'wpam_sessions' == get_post_type() )
        wp_dequeue_script( 'autosave' );
}

/**
 *======================================= SessionID =====================================
 */

function wpam_sessions_account_id_meta_box() {
    add_meta_box(
        'wpam_sessions_account_id_container',
        __( 'Account ID', 'wpamaccounts' ),
        'wpam_sessions_account_id_meta_box_callback',
        'wpam_sessions'
    );
}

function wpam_sessions_account_id_meta_box_callback( $post ) {

    // Add a nonce field so we can check for it later.
    wp_nonce_field( 'wpam_sessions_account_id_nonce', 'wpam_sessions_account_id_nonce' );

    $value = get_post_meta( $post->ID, '_wpam_sessions_account_id', true );

    echo '<input type="text" style="width:100%" id="wpam_sessions_account_id" name="wpam_sessions_account_id" value="' . esc_attr( $value ).'">';
}

add_action( 'add_meta_boxes', 'wpam_sessions_account_id_meta_box' );

function save_wpam_sessions_account_id_meta_box_data( $post_id ) {

    // Check if our nonce is set.
    if ( ! isset( $_POST['wpam_sessions_account_id_nonce'] ) ) {
        return;
    }

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $_POST['wpam_sessions_account_id_nonce'], 'wpam_sessions_account_id_nonce' ) ) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions.
    if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }

    }
    else {

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    /* OK, it's safe for us to save the data now. */

    // Make sure that it is set.
    if ( ! isset( $_POST['wpam_sessions_account_id'] ) ) {
        return;
    }

    // Sanitize user input.
    $my_data = sanitize_text_field( $_POST['wpam_sessions_account_id'] );

    // Update the meta field in the database.
    update_post_meta( $post_id, '_wpam_sessions_account_id', $my_data );
}

add_action( 'save_post', 'save_wpam_sessions_account_id_meta_box_data' );

/**
 *======================================= Login Time =====================================
 */

function wpam_sessions_login_time_meta_box() {
    add_meta_box(
        'wpam_sessions_login_time_container',
        __( 'Login Time', 'wpamaccounts' ),
        'wpam_sessions_login_time_meta_box_callback',
        'wpam_sessions'
    );
}

function wpam_sessions_login_time_meta_box_callback( $post ) {

    // Add a nonce field so we can check for it later.
    wp_nonce_field( 'wpam_sessions_login_time_nonce', 'wpam_sessions_login_time_nonce' );

    $value = get_post_meta( $post->ID, '_wpam_sessions_login_time', true );

    echo '<input type="text" style="width:100%" id="wpam_sessions_login_time" name="wpam_sessions_login_time" value="' . esc_attr( $value ).'">';
}

add_action( 'add_meta_boxes', 'wpam_sessions_login_time_meta_box' );

function save_wpam_sessions_login_time_meta_box_data( $post_id ) {

    // Check if our nonce is set.
    if ( ! isset( $_POST['wpam_sessions_login_time_nonce'] ) ) {
        return;
    }

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $_POST['wpam_sessions_login_time_nonce'], 'wpam_sessions_login_time_nonce' ) ) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions.
    if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }

    }
    else {

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    /* OK, it's safe for us to save the data now. */

    // Make sure that it is set.
    if ( ! isset( $_POST['wpam_sessions_login_time'] ) ) {
        return;
    }

    // Sanitize user input.
    $my_data = sanitize_text_field( $_POST['wpam_sessions_login_time'] );

    // Update the meta field in the database.
    update_post_meta( $post_id, '_wpam_sessions_login_time', $my_data );
}

add_action( 'save_post', 'save_wpam_sessions_login_time_meta_box_data' );