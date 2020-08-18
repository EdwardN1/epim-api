<?php
/**
 *======================================= User Account =====================================
 */
function wpam_accounts_useraccount_meta_box() {
    add_meta_box(
        'wpam_accounts_user',
        __( 'User Account', 'wpamaccounts' ),
        'wpam_accounts_useraccount_meta_box_callback',
        'wpam_accounts'
    );
}

function wpam_accounts_useraccount_meta_box_callback( $post ) {

    // Add a nonce field so we can check for it later.
    wp_nonce_field( 'wpam_accounts_useraccount_nonce', 'wpam_accounts_useraccount_nonce' );

    $value = get_post_meta( $post->ID, '_wpam_accounts_useraccount', true );

    echo '<select id="wpam_accounts_useraccount" name="wpam_accounts_useraccount"><option value="">Not Assigned to User Account</option>';
    $args = array(
        'role'    => 'account_customer',
        'orderby' => 'user_nicename',
        'order'   => 'ASC'
    );
    $users = get_users( $args );
    foreach ( $users as $user ) {
        $selected = '';
        if($value==$user->ID) {
            $selected=' selected';
        }
        echo '<option value="'.$user->ID.'"'.$selected.'>'.$user->display_name.' - '.$user->user_email.'</option>';
    }
    echo '</select>';

    //echo '<input type="text" style="width:100%" id="wpam_accounts_useraccount" name="wpam_accounts_useraccount" value="' . esc_attr( $value ).'">';
}

add_action( 'add_meta_boxes', 'wpam_accounts_useraccount_meta_box' );

function save_wpam_accounts_useraccount_meta_box_data( $post_id ) {

    // Check if our nonce is set.
    if ( ! isset( $_POST['wpam_accounts_useraccount_nonce'] ) ) {
        return;
    }

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $_POST['wpam_accounts_useraccount_nonce'], 'wpam_accounts_useraccount_nonce' ) ) {
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
    if ( ! isset( $_POST['wpam_accounts_useraccount'] ) ) {
        return;
    }

    // Sanitize user input.
    $my_data = sanitize_text_field( $_POST['wpam_accounts_useraccount'] );

    // Update the meta field in the database.
    update_post_meta( $post_id, '_wpam_accounts_useraccount', $my_data );
}

add_action( 'save_post', 'save_wpam_accounts_useraccount_meta_box_data' );

