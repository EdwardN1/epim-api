<?php

// Register Custom Post Type
function custom_post_type() {

    $labels = array(
        'name'                  => _x( 'Cached Orders', 'Post Type General Name', 'wpiai_domain' ),
        'singular_name'         => _x( 'Cached Order', 'Post Type Singular Name', 'wpiai_domain' ),
        'menu_name'             => __( 'Cached Orders', 'wpiai_domain' ),
        'name_admin_bar'        => __( 'Cached Orders', 'wpiai_domain' ),
        'archives'              => __( 'Order Archives', 'wpiai_domain' ),
        'attributes'            => __( 'Order Attributes', 'wpiai_domain' ),
        'parent_item_colon'     => __( 'Parent Order:', 'wpiai_domain' ),
        'all_items'             => __( 'All Orders', 'wpiai_domain' ),
        'add_new_item'          => __( 'Add New Order', 'wpiai_domain' ),
        'add_new'               => __( 'Add New', 'wpiai_domain' ),
        'new_item'              => __( 'New Order', 'wpiai_domain' ),
        'edit_item'             => __( 'Edit Order', 'wpiai_domain' ),
        'update_item'           => __( 'Update Order', 'wpiai_domain' ),
        'view_item'             => __( 'View Order', 'wpiai_domain' ),
        'view_items'            => __( 'View Order', 'wpiai_domain' ),
        'search_items'          => __( 'Search Order', 'wpiai_domain' ),
        'not_found'             => __( 'Not found', 'wpiai_domain' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'wpiai_domain' ),
        'featured_image'        => __( 'Featured Image', 'wpiai_domain' ),
        'set_featured_image'    => __( 'Set featured image', 'wpiai_domain' ),
        'remove_featured_image' => __( 'Remove featured image', 'wpiai_domain' ),
        'use_featured_image'    => __( 'Use as featured image', 'wpiai_domain' ),
        'insert_into_item'      => __( 'Insert into Order', 'wpiai_domain' ),
        'uploaded_to_this_item' => __( 'Uploaded to this Order', 'wpiai_domain' ),
        'items_list'            => __( 'Items list', 'wpiai_domain' ),
        'items_list_navigation' => __( 'Items list navigation', 'wpiai_domain' ),
        'filter_items_list'     => __( 'Filter Orders list', 'wpiai_domain' ),
    );
    $args = array(
        'label'                 => __( 'Cached Order', 'wpiai_domain' ),
        'description'           => __( 'Cached Orders coming in from INFOR', 'wpiai_domain' ),
        'labels'                => $labels,
        //'supports'              => array( 'title', 'editor' ),
        'supports'              => array( 'title' ),
        'taxonomies'            => array(),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
    );
    register_post_type( 'wpiai_cached_orders', $args );

}
add_action( 'init', 'custom_post_type', 0 );


function wpiai_cached_order_add_meta_box() {
//this will add the metabox for the member post type
$screens = array( 'wpiai_cached_orders' );

foreach ( $screens as $screen ) {

    add_meta_box(
        'wpiai_cached_order_timestamp',
        __( 'Order Timestamp', 'wpiai_domain' ),
        'wpiai_cached_order_timestamp_meta_box_callback',
        $screen
    );

    add_meta_box(
        'wpiai_cached_order_request',
        __( 'Order request', 'wpiai_domain' ),
        'wpiai_cached_order_request_meta_box_callback',
        $screen
    );
}
}
add_action( 'add_meta_boxes', 'wpiai_cached_order_add_meta_box' );

/**
 * Prints the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
function wpiai_cached_order_timestamp_meta_box_callback( $post ) {

// Add a nonce field so we can check for it later.
    wp_nonce_field( 'wpiai_cached_order_timestamp', 'wpiai_cached_order_timestamp_nonce' );

    /*
     * Use get_post_meta() to retrieve an existing value
     * from the database and use the value for the form.
     */
    $value = get_post_meta( $post->ID, '_wpiai_cached_order_timestamp', true );

    echo '<label for="wpiai_cached_order_timestamp">';
    _e( 'Order Time Stamp', 'wpiai_domain' );
    echo '</label> ';
    echo '<input type="text" id="wpiai_cached_order_timestamp" name="wpiai_cached_order_timestamp" value="' . esc_attr( $value ) . '" size="25" />';
}

function wpiai_cached_order_request_meta_box_callback( $post ) {

// Add a nonce field so we can check for it later.
    wp_nonce_field( 'wpiai_cached_order_request', 'wpiai_cached_order_request_nonce' );

    /*
     * Use get_post_meta() to retrieve an existing value
     * from the database and use the value for the form.
     */
    $value = get_post_meta( $post->ID, '_wpiai_cached_order_request', true );

    echo '<label for="wpiai_cached_order_request">';
    _e( 'Order Request', 'wpiai_domain' );
    echo '</label> ';
    echo '<input type="text" id="wpiai_cached_order_request" name="wpiai_cached_order_request" value="' . esc_attr( $value ) . '" size="25" />';
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
 function wpiai_cached_order_timestamp_save_meta_box_data( $post_id ) {

     if ( ! isset( $_POST['wpiai_cached_order_timestamp_nonce'] ) ) {
         return;
     }

     if ( ! wp_verify_nonce( $_POST['wpiai_cached_order_timestamp_nonce'], 'wpiai_cached_order_timestamp_nonce' ) ) {
         return;
     }

     if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
         return;
     }

     // Check the user's permissions.
     if ( isset( $_POST['post_type'] ) && 'wpiai_cached_orders' == $_POST['post_type'] ) {

         if ( ! current_user_can( 'edit_page', $post_id ) ) {
             return;
         }

     } else {

         if ( ! current_user_can( 'edit_post', $post_id ) ) {
             return;
         }
     }

     if ( ! isset( $_POST['wpiai_cached_order_timestamp'] ) ) {
         return;
     }

     $my_data = sanitize_text_field( $_POST['wpiai_cached_order_timestamp'] );

     update_post_meta( $post_id, '_wpiai_cached_order_timestamp', $my_data );
 }
add_action( 'save_post', 'wpiai_cached_order_timestamp_save_meta_box_data' );

function wpiai_cached_order_request_save_meta_box_data( $post_id ) {

    if ( ! isset( $_POST['wpiai_cached_order_request_nonce'] ) ) {
        return;
    }

    if ( ! wp_verify_nonce( $_POST['wpiai_cached_order_request_nonce'], 'wpiai_cached_order_request' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions.
    if ( isset( $_POST['post_type'] ) && 'wpiai_cached_orders' == $_POST['post_type'] ) {

        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }

    } else {

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    if ( ! isset( $_POST['wpiai_cached_order_request'] ) ) {
        return;
    }

    $my_data = sanitize_text_field( $_POST['wpiai_cached_order_request'] );

    update_post_meta( $post_id, '_wpiai_cached_order_request', $my_data );
}
add_action( 'save_post', 'wpiai_cached_order_request_save_meta_box_data' );

function set_csdorders( $request ) {
	//return array( 'csdorders' => 'Data' );
	$body = json_decode($request->get_body());
	$meta = $body->meta_data;
	if(is_array($meta)) {
		$CSD_ID = false;
		foreach ($meta as $key_value) {
			if($key_value->key=='CSD_ID') {
				$CSD_ID = $key_value->value;
			}
		}
		if($CSD_ID) {
			return array( 'CSD_ID' => $CSD_ID );
		}
		return array( 'error' => 'could not find CSD_ID in meta_data' );
	} else {
		return array( 'error' => 'No meta could not find CSD_ID' );
	}
}

add_action( 'rest_api_init', function () {
	register_rest_route( 'wc/v3', 'csdorders', array(
		'methods' => 'POST',
		'callback' => 'set_csdorders',
	));
});