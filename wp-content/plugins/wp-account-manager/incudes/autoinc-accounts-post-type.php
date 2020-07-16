<?php
// Our custom post type function
function create_wpam_posttype() {

    register_post_type( 'wpam_accounts',
        // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Accounts', 'wpamaccounts' ),
                'singular_name' => __( 'Account', 'wpamaccounts' ),
                'all_items' => __('All s', 'wpamaccounts'), /* the all items menu item */
                'add_new' => __('Add New', 'wpamaccounts'), /* The add new menu item */
                'add_new_item' => __('Add New Account', 'wpamaccounts'), /* Add New Display Title */
                'edit' => __( 'Edit', 'wpamaccounts' ), /* Edit wpamaccounts */
                'edit_item' => __('Edit Account', 'wpamaccounts'), /* Edit Display Title */
                'new_item' => __('New Account', 'wpamaccounts'), /* New Display Title */
                'view_item' => __('View Account', 'wpamaccounts'), /* View Display Title */
                'search_items' => __('Search Accounts', 'wpamaccounts'), /* Search Custom Type Title */
                'not_found' =>  __('Nothing found in the Database.', 'wpamaccounts'), /* This displays if there are no entries yet */
                'not_found_in_trash' => __('Nothing found in Trash', 'wpamaccounts'), /* This displays if there is nothing in the trash */
            ),
            'menu_icon' => 'dashicons-groups',
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'wpam_accounts'),
            'show_in_rest' => true,

        )
    );
}
// Hooking up our function to theme setup
add_action( 'init', 'create_wpam_posttype' );

add_filter('use_block_editor_for_post_type', 'prefix_disable_gutenberg', 10, 2);
function prefix_disable_gutenberg($current_status, $post_type)
{
    // Use your post type key instead of 'product'
    if ($post_type === 'wpam_accounts') return false;
    return $current_status;
}

add_action( 'init', function() {
    remove_post_type_support( 'wpam_accounts', 'editor' );
}, 99);

//branch address

function wpam_accounts_username_meta_box() {
    add_meta_box(
        'wpam_accounts_username',
        __( 'Username', 'wpamaccounts' ),
        'wpam_accounts_username_meta_box_callback',
        'wpam_accounts'
    );
}

function wpam_accounts_username_meta_box_callback( $post ) {

    // Add a nonce field so we can check for it later.
    wp_nonce_field( 'wpam_accounts_username_nonce', 'wpam_accounts_username_nonce' );

    $value = get_post_meta( $post->ID, '_wpam_accounts_username', true );

    echo '<textarea style="width:100%" id="wpam_accounts_username" name="wpam_accounts_username">' . esc_attr( $value ) . '</textarea>';
}

add_action( 'add_meta_boxes', 'wpam_accounts_username_meta_box' );

function save_wpam_accounts_username_meta_box_data( $post_id ) {

    // Check if our nonce is set.
    if ( ! isset( $_POST['wpam_accounts_username_nonce'] ) ) {
        return;
    }

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $_POST['wpam_accounts_username_nonce'], 'wpam_accounts_username_nonce' ) ) {
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
    if ( ! isset( $_POST['wpam_accounts_username'] ) ) {
        return;
    }

    // Sanitize user input.
    $my_data = sanitize_text_field( $_POST['wpam_accounts_username'] );

    // Update the meta field in the database.
    update_post_meta( $post_id, '_wpam_accounts_username', $my_data );
}

add_action( 'save_post', 'save_wpam_accounts_username_meta_box_data' );

//branch Phone

function branch_phone_meta_box() {
    add_meta_box(
        'branch_phone',
        __( 'Branch Phone', 'clickcollect' ),
        'branch_phone_meta_box_callback',
        'branches'
    );
}

function branch_phone_meta_box_callback( $post ) {

    // Add a nonce field so we can check for it later.
    wp_nonce_field( 'branch_phone_nonce', 'branch_phone_nonce' );

    $value = get_post_meta( $post->ID, '_branch_phone', true );

    echo '<input type="text" style="width:100%" id="branch_phone" name="branch_phone" value="' . esc_attr( $value ).'">';
}

add_action( 'add_meta_boxes', 'branch_phone_meta_box' );

function save_branch_phone_meta_box_data( $post_id ) {

    // Check if our nonce is set.
    if ( ! isset( $_POST['branch_phone_nonce'] ) ) {
        return;
    }

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $_POST['branch_phone_nonce'], 'branch_phone_nonce' ) ) {
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
    if ( ! isset( $_POST['branch_phone'] ) ) {
        return;
    }

    // Sanitize user input.
    $my_data = sanitize_text_field( $_POST['branch_phone'] );

    // Update the meta field in the database.
    update_post_meta( $post_id, '_branch_phone', $my_data );
}

add_action( 'save_post', 'save_branch_phone_meta_box_data' );

//branch Email

function branch_email_meta_box() {
    add_meta_box(
        'branch_email',
        __( 'Branch Email Address', 'clickcollect' ),
        'branch_email_meta_box_callback',
        'branches'
    );
}

function branch_email_meta_box_callback( $post ) {

    // Add a nonce field so we can check for it later.
    wp_nonce_field( 'branch_email_nonce', 'branch_email_nonce' );

    $value = get_post_meta( $post->ID, '_branch_email', true );

    echo '<input type="email" style="width:100%" id="branch_email" name="branch_email" value="' . esc_attr( $value ).'">';
}

add_action( 'add_meta_boxes', 'branch_email_meta_box' );

function save_branch_email_meta_box_data( $post_id ) {

    // Check if our nonce is set.
    if ( ! isset( $_POST['branch_email_nonce'] ) ) {
        return;
    }

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $_POST['branch_email_nonce'], 'branch_email_nonce' ) ) {
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
    if ( ! isset( $_POST['branch_email'] ) ) {
        return;
    }

    // Sanitize user input.
    $my_data = sanitize_text_field( $_POST['branch_email'] );

    // Update the meta field in the database.
    update_post_meta( $post_id, '_branch_email', $my_data );
}

add_action( 'save_post', 'save_branch_email_meta_box_data' );