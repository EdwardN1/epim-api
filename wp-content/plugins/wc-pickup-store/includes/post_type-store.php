<?php
/**
** Register post type store
**/
function wps_store_post_type() {
	$labels = array(
		'name'                  => _x( 'Stores', 'Post Type General Name', WPS_TEXTDOMAIN ),
		'singular_name'         => _x( 'Store', 'Post Type Singular Name', WPS_TEXTDOMAIN ),
		'menu_name'             => __( 'Stores', WPS_TEXTDOMAIN ),
		'name_admin_bar'        => __( 'Store', WPS_TEXTDOMAIN ),
		'archives'              => __( 'Store Archives', WPS_TEXTDOMAIN ),
		'attributes'            => __( 'Store Attributes', WPS_TEXTDOMAIN ),
		'parent_item_colon'     => __( 'Parent Store:', WPS_TEXTDOMAIN ),
		'all_items'             => __( 'All Stores', WPS_TEXTDOMAIN ),
		'add_new_item'          => __( 'Add New Store', WPS_TEXTDOMAIN ),
		'add_new'               => __( 'Add New Store', WPS_TEXTDOMAIN ),
		'new_item'              => __( 'New Store', WPS_TEXTDOMAIN ),
		'edit_item'             => __( 'Edit Store', WPS_TEXTDOMAIN ),
		'update_item'           => __( 'Update Store', WPS_TEXTDOMAIN ),
		'view_item'             => __( 'View Store', WPS_TEXTDOMAIN ),
		'view_items'            => __( 'View Stores', WPS_TEXTDOMAIN ),
		'search_items'          => __( 'Search Store', WPS_TEXTDOMAIN ),
		'not_found'             => __( 'Not found', WPS_TEXTDOMAIN ),
		'not_found_in_trash'    => __( 'Not found in Trash', WPS_TEXTDOMAIN ),
		'featured_image'        => __( 'Featured Image', WPS_TEXTDOMAIN ),
		'set_featured_image'    => __( 'Set featured image', WPS_TEXTDOMAIN ),
		'remove_featured_image' => __( 'Remove featured image', WPS_TEXTDOMAIN ),
		'use_featured_image'    => __( 'Use as featured image', WPS_TEXTDOMAIN ),
		'insert_into_item'      => __( 'Insert into item', WPS_TEXTDOMAIN ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', WPS_TEXTDOMAIN ),
		'items_list'            => __( 'Items list', WPS_TEXTDOMAIN ),
		'items_list_navigation' => __( 'Items list navigation', WPS_TEXTDOMAIN ),
		'filter_items_list'     => __( 'Filter items list', WPS_TEXTDOMAIN ),
	);
	$args = array(
		'label'                 => __( 'Store', WPS_TEXTDOMAIN ),
		'description'           => __( 'Stores', WPS_TEXTDOMAIN ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', ),
		'taxonomies'            => array(),
		'hierarchical'          => true,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-store',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,		
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'rewrite' => array(
			'slug' => 'store',
		)
	);
	register_post_type( 'store', $args );
}
add_action('init', 'wps_store_post_type');

/**
** Show field in column
**/
function wps_store_id_columns($columns) {
	$new = array();
	unset($columns['date']);

	foreach($columns as $key => $value) {
		if($key == 'title') {
			$new['store_id'] = __('ID', WPS_TEXTDOMAIN);
		}
		$new[$key] = $value;
	}
	$new['checkout_visibility'] = __('Exclude in Checkout?', WPS_TEXTDOMAIN);

	return $new;
}
add_filter('manage_edit-store_columns', 'wps_store_id_columns');

function wps_store_id_column_content($name, $post_id) {
	$exclude_store = get_post_meta($post_id, '_exclude_store', true);
	switch ($name) {
		case 'store_id':
			echo '<a href="' . get_edit_post_link($post_id) . '">' . $post_id . '</a>';
		break;
		case 'checkout_visibility':
			echo ($exclude_store == 1) ? __('Yes', WPS_TEXTDOMAIN) : __('No', WPS_TEXTDOMAIN);
		break;
	}
}
add_filter('manage_store_posts_custom_column', 'wps_store_id_column_content', 10, 2);

/**
** Activar stores para dropdown checkout
**/
function wps_store_post_meta_box() {
	add_meta_box('checkout-visibility', __( 'Checkout Visibility', WPS_TEXTDOMAIN ), 'wps_store_metabox_content', 'store', 'side', 'high');
	add_meta_box('store-fields', __( 'Store Fields', WPS_TEXTDOMAIN ), 'wps_store_metabox_details_content', 'store', 'normal', 'high');
}
add_action('add_meta_boxes', 'wps_store_post_meta_box');

function wps_store_metabox_content($post) {
	// Display code/markup goes here. Don't forget to include nonces!
	$pid = $post->ID;	
	$exclude_store = get_post_meta( $pid, '_exclude_store', true );

	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'wps_store_save_content', 'wps_store_metabox_nonce' );
	?>

	<div class="container_data_metabox">
		<div class="sub_data_poker">
			<p><strong><?php _e('Exclude store in checkout.', WPS_TEXTDOMAIN); ?></strong></p>
			<input type="checkbox" name="exclude_store" class="form-control" <?php checked($exclude_store, 1) ?> />
			
			<input type="hidden" name="save_data_form_custom" value="1"/>
		</div>
	</div>

	<?php
}

function wps_store_metabox_details_content($post) {
	// Display code/markup goes here. Don't forget to include nonces!
	$pid = $post->ID;	
	$city = get_post_meta( $pid, 'city', true );
	$phone = get_post_meta( $pid, 'phone', true );
	$map = get_post_meta( $pid, 'map', true );
	$waze = get_post_meta( $pid, 'waze', true );
	$description = get_post_meta( $pid, 'description', true );
	$address = get_post_meta( $pid, 'address', true );
	$store_shipping_cost = get_post_meta( $pid, 'store_shipping_cost', true );

	$store_order_email = get_post_meta( $pid, 'store_order_email', true );
	$enable_order_email = get_post_meta( $pid, 'enable_order_email', true );

	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'wps_store_save_content', 'wps_store_metabox_nonce' );
	?>
	<table class="form-table">
		<?php if(WPS()->costs_per_store == 'yes') : ?>
			<tr>
				<th><?php _e('Store shipping cost', WPS_TEXTDOMAIN) ?></th>
				<td>
					<input type="text" name="store_shipping_cost" class="regular-text" value="<?= $store_shipping_cost ?>">
			</tr>
		<?php endif; ?>
	
		<tr>
			<th><?php _e('City', WPS_TEXTDOMAIN) ?></th>
			<td>
				<input type="text" name="city" class="regular-text" value="<?= $city ?>">
			</td>
		</tr>
		<tr>
			<th><?php _e('Phone', WPS_TEXTDOMAIN) ?></th>
			<td>
				<input type="text" name="phone" class="regular-text" value="<?= $phone ?>">
			</td>
		</tr>	
		<tr>
			<th><?php _e('Order Email Notification', WPS_TEXTDOMAIN) ?></th>
			<td>
				<input type="text" name="store_order_email" class="regular-text" value="<?= $store_order_email ?>"><br>
				<label for="enable-order-email">
					<input type="checkbox" id="enable-order-email" name="enable_order_email" class="form-control" <?php checked($enable_order_email, 1) ?> /> <?php _e('Enable order email notification', WPS_TEXTDOMAIN) ?>
				</label>
			</td>
		</tr>
		<tr>
			<th><?php _e('Waze', WPS_TEXTDOMAIN) ?></th>
			<td>
				<textarea name="waze" class="large-text" rows="3"><?= $waze ?></textarea>
				<p class="description"><?= __('Link de waze', WPS_TEXTDOMAIN) ?></p>
			</td>
		</tr>
		<tr>
			<th><?php _e('Map', WPS_TEXTDOMAIN) ?></th>
			<td>
				<textarea name="map" class="large-text" rows="5"><?= $map ?></textarea>
			</td>
		</tr>
		<tr>
			<th><?php _e('Short description', WPS_TEXTDOMAIN) ?></th>
			<td>
				<?php
					$settings = array('textarea_name' => 'description', 'editor_height' => 75);
					wp_editor($description, 'description', $settings );
				?>
			</td>
		</tr>
		<tr>
			<th><?php _e('Address', WPS_TEXTDOMAIN) ?></th>
			<td>
				<?php
					$settings = array('textarea_name' => 'address', 'editor_height' => 75);
					wp_editor($address, 'address', $settings );
				?>
			</td>
		</tr>
	</table>

	<?php
}

function wps_store_save_content($post_id) {
	// Check if our nonce is set.
	if ( ! isset( $_POST['wps_store_metabox_nonce'] ) ) { return; }

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['wps_store_metabox_nonce'], 'wps_store_save_content' ) ) { return; }

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }

	// Make sure that it is set.
	// if ( ! isset( $_POST['exclude_store'] ) ) { return; }

	$checked = isset( $_POST['exclude_store'] ) ? 1 : 0;
	$checked_order_email = isset( $_POST['enable_order_email'] ) ? 1 : 0;
	update_post_meta( $post_id, '_exclude_store', $checked );
	update_post_meta( $post_id, 'city', sanitize_text_field($_POST['city']) );
	update_post_meta( $post_id, 'phone', sanitize_text_field($_POST['phone']) );
	update_post_meta( $post_id, 'waze', esc_url($_POST['waze']) );
	update_post_meta( $post_id, 'map', wp_kses_data($_POST['map']) );
	update_post_meta( $post_id, 'description', wp_kses_data($_POST['description']));
	update_post_meta( $post_id, 'address', wp_kses_data($_POST['address']));

	update_post_meta( $post_id, 'store_order_email', sanitize_text_field($_POST['store_order_email']) );
	update_post_meta( $post_id, 'enable_order_email', $checked_order_email );

	if(isset($_POST['store_shipping_cost'])) {
		update_post_meta( $post_id, 'store_shipping_cost', sanitize_text_field($_POST['store_shipping_cost']));
	}
}
add_action('save_post', 'wps_store_save_content');

/**
** Single store template
**/
function wps_single_store_template($template) {
	if (is_singular('store') && $template !== locate_template(array("single-store.php"))) {
		$template = plugin_dir_path(__DIR__) . 'templates/single-store.php';
	}

	return $template;
}
add_filter('single_template', 'wps_single_store_template');

/**
** Archive Template
**/
function wps_store_archive_template($archive_template) {
	if (is_post_type_archive('store') && $archive_template !== locate_template(array("archive-store.php"))) {
		$archive_template = plugin_dir_path(__DIR__) . 'templates/archive-store.php';
	}

	return $archive_template;
}
add_filter('archive_template', 'wps_store_archive_template');