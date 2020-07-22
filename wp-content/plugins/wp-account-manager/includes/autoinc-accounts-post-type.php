<?php
// Our custom post type function
function create_wpam_posttype() {

    register_post_type( 'wpam_accounts',
        // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Accounts', 'wpamaccounts' ),
                'singular_name' => __( 'Account', 'wpamaccounts' ),
                'all_items' => __('All Accounts', 'wpamaccounts'), /* the all items menu item */
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
            'public' => false,
            'has_archive' => false,
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'show_in_nav_menus'   => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_admin_bar'   => true,
            'rewrite' => array('slug' => 'wpam_accounts'),
            'show_in_rest' => true,

        )
    );
}
// Hooking up our function to theme setup
add_action( 'init', 'create_wpam_posttype' );

add_filter('use_block_editor_for_post_type', 'wpam_prefix_disable_gutenberg', 10, 2);
function wpam_prefix_disable_gutenberg($current_status, $post_type)
{
    // Use your post type key instead of 'product'
    if ($post_type === 'wpam_accounts') return false;
    return $current_status;
}

add_action( 'init', function() {
    remove_post_type_support( 'wpam_accounts', 'editor' );
}, 99);

function wpam_change_title_text( $title ){
	$screen = get_current_screen();

	if  ( 'wpam_accounts' == $screen->post_type ) {
		$title = 'Enter Username';
	}

	return $title;
}

add_filter( 'enter_title_here', 'wpam_change_title_text' );

/**
 * Stop this post type autosaving:
 */

add_action( 'admin_enqueue_scripts', 'wpam_admin_enqueue_scripts' );
function wpam_admin_enqueue_scripts() {
    if ( 'wpam_accounts' == get_post_type() )
        wp_dequeue_script( 'autosave' );
}

/**
 *======================================= Account Username =====================================
 */

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

	echo '<input type="text" style="width:100%" id="wpam_accounts_username" name="wpam_accounts_username" value="' . esc_attr( $value ).'">';
}

//add_action( 'add_meta_boxes', 'wpam_accounts_username_meta_box' );

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

/**
 * ============================================= Account Password ===============================================
 */

function wpam_accounts_password_meta_box() {
	add_meta_box(
		'wpam_accounts_password_container',
		__( 'Password', 'wpamaccounts' ),
		'wpam_accounts_password_meta_box_callback',
		'wpam_accounts'
	);
}

function wpam_accounts_password_meta_box_callback( $post ) {

	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'wpam_accounts_password_nonce', 'wpam_accounts_password_nonce' );

	$value = get_post_meta( $post->ID, '_wpam_accounts_password', true );

	echo '<input type="password" style="width:100%" id="wpam_accounts_password" name="wpam_accounts_password" value="">';
}

add_action( 'add_meta_boxes', 'wpam_accounts_password_meta_box' );

function save_wpam_accounts_password_meta_box_data( $post_id ) {

	// Check if our nonce is set.
	if ( ! isset( $_POST['wpam_accounts_password_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['wpam_accounts_password_nonce'], 'wpam_accounts_password_nonce' ) ) {
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
	if ( ! isset( $_POST['wpam_accounts_password'] ) ) {
		return;
	}

	// Sanitize user input.
	$my_data = sanitize_text_field( $_POST['wpam_accounts_password'] );

	if($my_data != '') {
		$hash = password_hash($my_data, PASSWORD_DEFAULT);
		update_post_meta( $post_id, '_wpam_accounts_password', $hash );
	}

	// Update the meta field in the database.

}

add_action( 'save_post', 'save_wpam_accounts_password_meta_box_data' );

/**
 *  =========================== Account Email Address ==================================
 */

function wpam_accounts_email_meta_box() {
    add_meta_box(
        'wpam_accounts_email_container',
        __( 'Account Email Address', 'wpamaccounts' ),
        'wpam_accounts_email_meta_box_callback',
        'wpam_accounts'
    );
}

function wpam_accounts_email_meta_box_callback( $post ) {

    // Add a nonce field so we can check for it later.
    wp_nonce_field( 'wpam_accounts_email_nonce', 'wpam_accounts_email_nonce' );

    $value = get_post_meta( $post->ID, '_wpam_accounts_email', true );

    echo '<input type="email" style="width:100%" id="wpam_accounts_email" name="wpam_accounts_email" value="' . esc_attr( $value ).'">';
}

add_action( 'add_meta_boxes', 'wpam_accounts_email_meta_box' );

function save_wpam_accounts_email_meta_box_data( $post_id ) {

    // Check if our nonce is set.
    if ( ! isset( $_POST['wpam_accounts_email_nonce'] ) ) {
        return;
    }

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $_POST['wpam_accounts_email_nonce'], 'wpam_accounts_email_nonce' ) ) {
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
    if ( ! isset( $_POST['wpam_accounts_email'] ) ) {
        return;
    }

    // Sanitize user input.
    $my_data = sanitize_text_field( $_POST['wpam_accounts_email'] );

    // Update the meta field in the database.
    update_post_meta( $post_id, '_wpam_accounts_email', $my_data );
	/*if(wpam_unique_email($my_data)) {
		update_post_meta( $post_id, '_wpam_accounts_email', $my_data );
	} else {
		wpam_error('duplicate_email',$my_data.' email address already in use.');
	}*/

}

add_action( 'save_post', 'save_wpam_accounts_email_meta_box_data' );

/**
 *  =========================== Account account_enabled ==================================
 */

function wpam_accounts_account_enabled_meta_box() {
	add_meta_box(
		'wpam_accounts_account_enabled_container',
		__( 'Account Enabled', 'wpamaccounts' ),
		'wpam_accounts_account_enabled_meta_box_callback',
		'wpam_accounts'
	);
}

function wpam_accounts_account_enabled_meta_box_callback( $post ) {

	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'wpam_accounts_account_enabled_nonce', 'wpam_accounts_account_enabled_nonce' );

	$value = get_post_meta( $post->ID, '_wpam_accounts_account_enabled', true );

	echo '<label for="wpam_accounts_account_enabled"><input type="checkbox" id="wpam_accounts_account_enabled" name="wpam_accounts_account_enabled" value="yes" ';
	if($value=='yes') echo ' checked';
	echo ' /> Account Enabled</label>';
}

add_action( 'add_meta_boxes', 'wpam_accounts_account_enabled_meta_box' );

function save_wpam_accounts_account_enabled_meta_box_data( $post_id ) {

	// Check if our nonce is set.
	if ( ! isset( $_POST['wpam_accounts_account_enabled_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['wpam_accounts_account_enabled_nonce'], 'wpam_accounts_account_enabled_nonce' ) ) {
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
	/*if ( ! isset( $_POST['wpam_accounts_account_enabled'] ) ) {
		return;
	}*/

	// Sanitize user input.
	//error_log('wpam_accounts_account_enabled = '.$_POST['wpam_accounts_account_enabled']);
	if(isset($_POST['wpam_accounts_account_enabled'])) {
		update_post_meta( $post_id, '_wpam_accounts_account_enabled', 'yes' );
	} else {
		update_post_meta( $post_id, '_wpam_accounts_account_enabled', 'no' );
	}

}

add_action( 'save_post', 'save_wpam_accounts_account_enabled_meta_box_data' );


/**
 *  =========================== Account Registered Time ==================================
 */

function wpam_accounts_registered_time_meta_box() {
	add_meta_box(
		'wpam_accounts_register_time_container',
		__( 'Account Registered Time', 'wpamaccounts' ),
		'wpam_accounts_registered_time_meta_box_callback',
		'wpam_accounts'
	);
}

function wpam_accounts_registered_time_meta_box_callback( $post ) {

	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'wpam_accounts_registered_time_nonce', 'wpam_accounts_registered_time_nonce' );

	$value = get_post_meta( $post->ID, '_wpam_accounts_registered_time', true );

	echo '<p style="width:100%" id="wpam_accounts_registered_time">' . esc_attr( $value ).'</p>';
}

add_action( 'add_meta_boxes', 'wpam_accounts_registered_time_meta_box' );

function save_wpam_accounts_registered_time_meta_box_data( $post_id, $post, $update ) {

	// Check if our nonce is set.
	if ( ! isset( $_POST['wpam_accounts_email_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['wpam_accounts_email_nonce'], 'wpam_accounts_email_nonce' ) ) {
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

	$is_new = $post->post_date === $post->post_modified;

	// Make sure that it is a new post.
	if ( !$is_new ) {
		return;
	}

	// Sanitize user input.
	$my_data = date(DATE_RFC2822);

	error_log('Date is: '.$my_data);

	// Update the meta field in the database.
	update_post_meta( $post_id, '_wpam_accounts_registered_time', $my_data );


}

add_action( 'save_post', 'save_wpam_accounts_registered_time_meta_box_data', 10, 3 );


/**
 * @param $username
 * @param $exclude
 *
 * @return bool
 *
 * **** Various post related functions
 */

function wpam_unique_username($username,$exclude) {
	$args = array(
		'title' => $username,
		'post_type' => 'wpam_accounts',
		'post_status' => 'any',
		'posts_per_page' => -1
	);
	$this_query = new WP_Query($args);
	if($this_query->have_posts()) {
	    if($exclude != '') {
	        $count = $this_query->found_posts;
	        if($count==1) {
	            $post_title = $this_query->posts[0]->post_title;
	            if($post_title==$exclude) {
                    return true;
                } else {
	                return false;
                }
            } else {
	            return false;
            }
        } else {
            return false;
        }
	} else {
		return true;
	}
}

function wpam_unique_email($email,$exclude) {
	$args = array(
		'meta_key' => '_wpam_accounts_email',
		'meta_value' => $email,
		'post_type' => 'wpam_accounts',
		'post_status' => 'any',
		'posts_per_page' => -1
	);
    $this_query = new WP_Query($args);
    if($this_query->have_posts()) {
        if($exclude != '') {
            $count = $this_query->found_posts;

            if($count==1) {
                $post_ID = $this_query->posts[0]->ID;
                $post_email = get_post_meta($post_ID,'_wpam_accounts_email',true);
                if($post_email==$exclude) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return true;
    }
}

/**
 * Next we need two simple error handling functions:
 * Error handling function for use with save_car_data function below it:
 * /

/**
 * Writes an error message to the screen if error is thrown in save_car_data function
 *
 */
/*function handle_wpam_errors() {
	//If there are no errors, then exit the function
	if(!( $errors = get_transient('settings_errors'))) {
		return;
	}
	//Otherwise, build the list of errors that exist in the settings errors
	$message = '<div id="wpam-message" class="error below-h2"><p><ul>';
  foreach($errors as $error) {
	  $message .= '<li>' . $error['message'] . '</li>';
  }
  $message .= '</ul></p></div><!– #error –>';
  //Write error messages to the screen
  echo $message;
  //Clear and the transient and unhook any other notices so we don’t see duplicate messages
  delete_transient('settings_errors');
  remove_action('admin_notices', 'handle_wpam_errors');
}

function wpam_error($slug,$err){
	add_settings_error(
		$slug,
		$slug,
		$err,
		'error'
	);
	set_transient('settings_errors', get_settings_errors(), 30);
}
add_action('admin_notices', 'handle_wpam_errors');
*/

