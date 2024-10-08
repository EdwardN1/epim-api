<?php

if ( ! defined( 'ABSPATH' ) )
	exit;

//Product Cat Create page
function epim_taxonomy_add_new_meta_field() {
    ?>

    <div class="form-field">

        <label for="epim_api_id"><?php _e('API ID', 'epim'); ?></label>
        <input type="text" name="epim_api_id" id="epim_api_id">
        <p class="description"><?php _e('Enter an API ID', 'epim'); ?></p>

        <label for="epim_api_id"><?php _e('API Alias', 'epim'); ?></label>
        <input type="text" name="epim_api_alias" id="epim_api_alias">
        <p class="description"><?php _e('Enter an API Alias', 'epim'); ?></p>

        <label for="epim_api_parent_id"><?php _e('API PARENT ID', 'epim'); ?></label>
        <input type="text" name="epim_api_parent_id" id="epim_api_parent_id">
        <p class="description"><?php _e('Enter an API PARENT ID', 'epim'); ?></p>

        <label for="epim_api_picture_ids"><?php _e('API PICTURE IDS', 'epim'); ?></label>
        <input type="text" name="epim_api_picture_IDS" id="epim_api_picture_ids">
        <p class="description"><?php _e('Enter API PICTURE IDS', 'epim'); ?></p>

        <label for="epim_api_picture_link"><?php _e('API PICTURE LINK', 'epim'); ?></label>
        <input type="text" name="epim_api_picture_link" id="epim_api_picture_link">
        <p class="description"><?php _e('Enter API PICTURE LINK', 'epim'); ?></p>

        <label for="epim_api_exclude_from_category_menu"><?php _e('Exclude From Category Menu', 'epim'); ?></label>
        <input type="checkbox" name="epim_api_exclude_from_category_menu" id="epim_api_exclude_from_category_menu">
        <p class="description"><?php _e('Choose if this category shows in the category menu', 'epim'); ?></p>

        <!--<label for="epim_api_Qty_Break_1"><?php /*_e('Qty_Break_1', 'epim'); */?></label>
        <input type="text" name="epim_api_Qty_Break_1" id="epim_api_Qty_Break_1">
        <p class="description"><?php /*_e('Enter API Qty_Break_1', 'epim'); */?></p>

        <label for="epim_api_Qty_Break_2"><?php /*_e('Qty_Break_2', 'epim'); */?></label>
        <input type="text" name="epim_api_Qty_Break_2" id="epim_api_Qty_Break_2">
        <p class="description"><?php /*_e('Enter API Qty_Break_2', 'epim'); */?></p>

        <label for="epim_api_Qty_Break_3"><?php /*_e('Qty_Break_3', 'epim'); */?></label>
        <input type="text" name="epim_api_Qty_Break_3" id="epim_api_Qty_Break_3">
        <p class="description"><?php /*_e('Enter API Qty_Break_3', 'epim'); */?></p>

        <label for="epim_api_Qty_Price_1"><?php /*_e('Qty_Price_1', 'epim'); */?></label>
        <input type="text" name="epim_api_Qty_Price_1" id="epim_api_Qty_Price_1">
        <p class="description"><?php /*_e('Enter API Qty_Price_1', 'epim'); */?></p>

        <label for="epim_api_Qty_Price_2"><?php /*_e('Qty_Price_2', 'epim'); */?></label>
        <input type="text" name="epim_api_Qty_Price_2" id="epim_api_Qty_Price_2">
        <p class="description"><?php /*_e('Enter API Qty_Price_2', 'epim'); */?></p>

        <label for="epim_api_Qty_Price_3"><?php /*_e('Qty_Price_3', 'epim'); */?></label>
        <input type="text" name="epim_api_Qty_Price_3" id="epim_api_Qty_Price_3">
        <p class="description"><?php /*_e('Enter API Qty_Price_3', 'epim'); */?></p>-->

    </div>
    <?php
}

//Product Cat Edit page
function epim_taxonomy_edit_meta_field($term) {

    //getting term ID
    $term_id = $term->term_id;

    // retrieve the existing value(s) for this meta field.
    $epim_api_id = get_term_meta($term_id, 'epim_api_id', true);
    $epim_api_alias = get_term_meta($term_id, 'epim_api_alias', true);
    $epim_api_parent_id = get_term_meta($term_id, 'epim_api_parent_id', true);
    $epim_api_picture_ids = get_term_meta($term_id, 'epim_api_picture_ids', true);
    $epim_api_picture_link = get_term_meta($term_id, 'epim_api_picture_link', true);
	$epim_api_exclude_from_category_menu = get_term_meta($term_id, 'epim_api_exclude_from_category_menu', true);
	$epim_api_exclude_from_category_menu_checked = '';
    if($epim_api_exclude_from_category_menu == 'on') {
	    $epim_api_exclude_from_category_menu_checked = ' checked';
    }
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="epim_api_id"><?php _e('API ID', 'epim'); ?></label></th>
        <td>
            <input type="text" name="epim_api_id" id="epim_api_id" value="<?php echo esc_attr($epim_api_id) ? esc_attr($epim_api_id) : ''; ?>">
            <p class="description"><?php _e('Enter an API ID', 'epim'); ?></p>
        </td>
    </tr>

    <tr class="form-field">
        <th scope="row" valign="top"><label for="epim_api_alias"><?php _e('API Alias', 'epim'); ?></label></th>
        <td>
            <input type="text" name="epim_api_alias" id="epim_api_alias" value="<?php echo esc_attr($epim_api_alias) ? esc_attr($epim_api_alias) : ''; ?>">
            <p class="description"><?php _e('Enter an API Alias', 'epim'); ?></p>
        </td>
    </tr>

    <tr class="form-field">
        <th scope="row" valign="top"><label for="epim_api_parent_id"><?php _e('API PARENT ID', 'epim'); ?></label></th>
        <td>
            <input type="text" name="epim_api_parent_id" id="epim_api_parent_id" value="<?php echo esc_attr($epim_api_parent_id) ? esc_attr($epim_api_parent_id) : ''; ?>">
            <p class="description"><?php _e('Enter an API PARENT ID', 'epim'); ?></p>
        </td>
    </tr>

    <tr class="form-field">
        <th scope="row" valign="top"><label for="epim_api_picture_ids"><?php _e('API PICTURE IDS', 'epim'); ?></label></th>
        <td>
            <input type="text" name="epim_api_picture_ids" id="epim_api_picture_ids" value="<?php echo esc_attr($epim_api_picture_ids) ? esc_attr($epim_api_picture_ids) : ''; ?>">
            <p class="description"><?php _e('Enter API PICTURE IDS', 'epim'); ?></p>
        </td>
    </tr>

    <tr class="form-field">
        <th scope="row" valign="top"><label for="epim_api_picture_link"><?php _e('API PICTURE LINK', 'epim'); ?></label></th>
        <td>
            <input type="text" name="epim_api_picture_link" id="epim_api_picture_link" value="<?php echo esc_attr($epim_api_picture_link) ? esc_attr($epim_api_picture_link) : ''; ?>">
            <p class="description"><?php _e('Enter API PICTURE IDS', 'epim'); ?></p>
        </td>
    </tr>

    <tr class="form-field">
        <th scope="row" valign="top"><label for="epim_api_exclude_from_category_menu"><?php _e('Exclude From Category Menu', 'epim'); ?></label></th>
        <td>
            <input type="checkbox" name="epim_api_exclude_from_category_menu" id="epim_api_exclude_from_category_menu" <?php _e($epim_api_exclude_from_category_menu_checked,'epim');?>/>
            <p class="description"><?php _e('Choose if this category shows in the category menu', 'epim'); ?></p>
        </td>
    </tr>

    <tr class="form-field">
        <th scope="row" valign="top"><label><?php _e('Category Hierarchy:', 'epim'); ?></label></th>
        <td>
    <!--        <?php
/*            $parent_categories = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => true, 'child_of' => $term_id ]);
            foreach ($parent_categories as $parent_category) {
                $parent_name = $parent_category->name;
                $parent_URL = get_category_link($parent_category->term_id);
                */?>
                <p class="description"><a href="/wp-admin/term.php?taxonomy=product_cat&tag_ID=<?php /*_e($parent_category->term_id,'epim');*/?>&post_type=product"><?php /*_e($parent_name,'epim');*/?></a> <a href="<?php /*_e($parent_URL,'epim');*/?>" target="_blank">(view)</a> </p>
                --><?php
/*            }
            */?>
            <?php _e(get_term_parents_list($term_id,'product_cat'))?>
        </td>
    </tr>

    <tr class="form-field">
        <th scope="row" valign="top"><label><?php _e('Child Categories:', 'epim'); ?></label></th>
        <td>
            <?php
            $child_categories = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => false, 'parent' => $term_id ]);
            foreach ($child_categories as $child_category) {
                $child_name = $child_category->name;
                $child_URL = get_category_link($child_category->term_id);
                ?>
                <p class="description"><a href="/wp-admin/term.php?taxonomy=product_cat&tag_ID=<?php _e($child_category->term_id,'epim');?>&post_type=product"><?php _e($child_name,'epim');?></a> <a href="<?php _e($child_URL,'epim');?>" target="_blank">(view)</a> </p>
                <?php
            }
            ?>
        </td>
    </tr>
    <?php
}

add_action('product_cat_add_form_fields', 'epim_taxonomy_add_new_meta_field', 10, 1);
add_action('product_cat_edit_form_fields', 'epim_taxonomy_edit_meta_field', 10, 1);

// Save extra taxonomy fields callback function.
function epim_save_taxonomy_custom_meta($term_id) {

    $epim_api_id = filter_input(INPUT_POST, 'epim_api_id');
    $epim_api_alias = filter_input(INPUT_POST, 'epim_api_alias');
    $epim_api_parent_id = filter_input(INPUT_POST, 'epim_api_parent_id');
    $epim_api_picture_ids = filter_input(INPUT_POST, 'epim_api_picture_ids');
    $epim_api_picture_link = filter_input(INPUT_POST, 'epim_api_picture_link');
    $epim_api_exclude_from_category_menu = filter_input(INPUT_POST, 'epim_api_exclude_from_category_menu');

    update_term_meta($term_id, 'epim_api_id', $epim_api_id);
    update_term_meta($term_id, 'epim_api_alias', $epim_api_alias);
    update_term_meta($term_id, 'epim_api_parent_id', $epim_api_parent_id);
    update_term_meta($term_id, 'epim_api_picture_ids', $epim_api_picture_ids);
    update_term_meta($term_id, 'epim_api_picture_link', $epim_api_picture_link);
	update_term_meta($term_id, 'epim_api_exclude_from_category_menu', $epim_api_exclude_from_category_menu);
}

add_action('edited_product_cat', 'epim_save_taxonomy_custom_meta', 10, 1);
add_action('create_product_cat', 'epim_save_taxonomy_custom_meta', 10, 1);

/**
 * add the fields into REST API
 */

add_action( 'rest_api_init', function () {
	register_rest_field( 'product_cat', 'epim_api_id', array(
		'get_callback' => function( $post_arr ) {
			return get_term_meta( $post_arr['id'], 'epim_api_id', true );
		},
	) );
    register_rest_field( 'product_cat', 'epim_api_alias', array(
        'get_callback' => function( $post_arr ) {
            return get_term_meta( $post_arr['id'], 'epim_api_alias', true );
        },
    ) );
	register_rest_field( 'product_cat', 'epim_api_parent_id', array(
		'get_callback' => function( $post_arr ) {
			return get_term_meta( $post_arr['id'], 'epim_api_parent_id', true );
		},
	) );
	register_rest_field( 'product_cat', 'epim_api_picture_ids', array(
		'get_callback' => function( $post_arr ) {
			return get_term_meta( $post_arr['id'], 'epim_api_picture_ids', true );
		},
	) );
	register_rest_field( 'product_cat', 'epim_api_picture_link', array(
		'get_callback' => function( $post_arr ) {
			return get_term_meta( $post_arr['id'], 'epim_api_picture_link', true );
		},
	) );
} );


add_filter( 'woocommerce_product_data_tabs', 'add_epim_product_data_tab' , 99 , 1 );
function add_epim_product_data_tab( $product_data_tabs ) {

	$product_data_tabs['epim-tab'] = array(
		'label' => __( 'ePim', 'my_text_domain' ),
		'target' => 'epim_product_data',
	);
	return $product_data_tabs;
}

add_action( 'woocommerce_product_data_panels', 'add_epim_product_data_fields' );
function add_epim_product_data_fields($post_id) {
	global $woocommerce, $post;
	?>
    <!-- id below must match target registered in above add_epim_product_data_tab function -->
    <div id="epim_product_data" class="panel woocommerce_options_panel">
		<?php
		woocommerce_wp_text_input( array(
			'id'            => 'epim_API_ID',
			'wrapper_class' => 'show_if_simple',
			'label'         => __( 'API ID', 'my_text_domain' ),
			'description'   => __( 'ePim Product Group ID', 'my_text_domain' ),
			'default'       => '',
			'desc_tip'      => false,
		) );
		woocommerce_wp_text_input( array(
			'id'            => 'epim_product_group_name',
			'wrapper_class' => 'show_if_simple',
			'label'         => __( 'Product Group Name', 'my_text_domain' ),
			'description'   => __( 'ePim Product Group Name', 'my_text_domain' ),
			'default'       => '',
			'desc_tip'      => false,
		) );
		woocommerce_wp_text_input( array(
			'id'            => 'epim_variation_ID',
			'wrapper_class' => 'show_if_simple',
			'label'         => __( 'Variation ID', 'my_text_domain' ),
			'description'   => __( 'ePim variation ID', 'my_text_domain' ),
			'default'       => '',
			'desc_tip'      => false,
		) );
		woocommerce_wp_text_input( array(
			'id'            => 'epim_Qty_Break_1',
			'wrapper_class' => 'show_if_simple',
			'label'         => __( 'Qty_Break_1', 'my_text_domain' ),
			'description'   => __( 'ePim Qty_Break_1', 'my_text_domain' ),
			'default'       => '',
			'desc_tip'      => false,
		) );
		woocommerce_wp_text_input( array(
			'id'            => 'epim_Qty_Price_1',
			'wrapper_class' => 'show_if_simple',
			'label'         => __( 'Qty_Price_1', 'my_text_domain' ),
			'description'   => __( 'ePim Qty_Price_1', 'my_text_domain' ),
			'default'       => '',
			'desc_tip'      => false,
		) );
		woocommerce_wp_text_input( array(
			'id'            => 'epim_Qty_Break_2',
			'wrapper_class' => 'show_if_simple',
			'label'         => __( 'Qty_Break_2', 'my_text_domain' ),
			'description'   => __( 'ePim Qty_Break_2', 'my_text_domain' ),
			'default'       => '',
			'desc_tip'      => false,
		) );
		woocommerce_wp_text_input( array(
			'id'            => 'epim_Qty_Price_2',
			'wrapper_class' => 'show_if_simple',
			'label'         => __( 'Qty_Price_2', 'my_text_domain' ),
			'description'   => __( 'ePim Qty_Price_2', 'my_text_domain' ),
			'default'       => '',
			'desc_tip'      => false,
		) );
		woocommerce_wp_text_input( array(
			'id'            => 'epim_Qty_Break_3',
			'wrapper_class' => 'show_if_simple',
			'label'         => __( 'Qty_Break_3', 'my_text_domain' ),
			'description'   => __( 'ePim Qty_Break_3', 'my_text_domain' ),
			'default'       => '',
			'desc_tip'      => false,
		) );
		woocommerce_wp_text_input( array(
			'id'            => 'epim_Qty_Price_3',
			'wrapper_class' => 'show_if_simple',
			'label'         => __( 'Qty_Price_3', 'my_text_domain' ),
			'description'   => __( 'ePim Qty_Price_3', 'my_text_domain' ),
			'default'       => '',
			'desc_tip'      => false,
		) );
        woocommerce_wp_text_input( array(
            'id'            => 'epim_Part_Code',
            'wrapper_class' => 'show_if_simple',
            'label'         => __( 'Part_Code', 'my_text_domain' ),
            'description'   => __( 'ePim Part_Code', 'my_text_domain' ),
            'default'       => '',
            'desc_tip'      => false,
        ) );

        $epim_api_variation_data = false;
        $wc_metaData = get_post_meta($post->ID,'',true);
        if($wc_metaData) $epim_api_variation_data = $wc_metaData['epim_api_variation_data'][0];
        if($epim_api_variation_data) {
            $struct_json = json_decode($epim_api_variation_data, true);
            echo '<p>ePim API Variation data:<br><pre>'.json_encode($struct_json,JSON_PRETTY_PRINT).'</pre></p>';
        }

		$datasheets = get_post_meta($post->ID,'epim_data_sheets',true);
		if($datasheets) {
		    if(is_array($datasheets)) {
		        echo '<p class="form-field show-if-simple">';
		        foreach ($datasheets as $datasheet) {
		            echo $datasheet['Name'].': '.$datasheet['URL'].'<br>';
                }
		        echo '</p>';
            } else {
                //error_log($post->ID.' datasheets invalid');
            }
        } else {
		    //error_log($post->ID.' no datasheets found');
        }

		?>
    </div>
	<?php
}

add_action( 'woocommerce_process_product_meta', 'woocommerce_process_product_meta_fields_save' );
function woocommerce_process_product_meta_fields_save( $post_id ){
	// This is the case to save custom field data of checkbox. You have to do it as per your custom fields
	$epim_API_ID =  sanitize_text_field($_POST['epim_API_ID']);
	update_post_meta( $post_id, 'epim_API_ID', $epim_API_ID );
	$epim_product_group_name =  sanitize_text_field($_POST['epim_product_group_name']);
	update_post_meta( $post_id, 'epim_product_group_name', $epim_product_group_name );
	$epim_variation_ID =  sanitize_text_field($_POST['epim_variation_ID']);
	update_post_meta( $post_id, 'epim_variation_ID', $epim_variation_ID );
	$epim_Qty_Break_1 =  sanitize_text_field($_POST['epim_Qty_Break_1']);
	update_post_meta( $post_id, 'epim_Qty_Break_1', $epim_Qty_Break_1 );
	$epim_Qty_Price_1 =  sanitize_text_field($_POST['epim_Qty_Price_1']);
	update_post_meta( $post_id, 'epim_Qty_Price_1', $epim_Qty_Price_1 );
	$epim_Qty_Break_2 =  sanitize_text_field($_POST['epim_Qty_Break_2']);
	update_post_meta( $post_id, 'epim_Qty_Break_2', $epim_Qty_Break_2 );
	$epim_Qty_Price_2 =  sanitize_text_field($_POST['epim_Qty_Price_2']);
	update_post_meta( $post_id, 'epim_Qty_Price_2', $epim_Qty_Price_2 );
	$epim_Qty_Break_3 =  sanitize_text_field($_POST['epim_Qty_Break_3']);
	update_post_meta( $post_id, 'epim_Qty_Break_3', $epim_Qty_Break_3 );
	$epim_Qty_Price_3 =  sanitize_text_field($_POST['epim_Qty_Price_3']);
	update_post_meta( $post_id, 'epim_Qty_Price_3', $epim_Qty_Price_3 );
    /*$epim_api_variation_data =  sanitize_text_field($_POST['epim_api_variation_data']);
    update_post_meta( $post_id, 'epim_api_variation_data', $epim_api_variation_data );*/
    $epim_Part_Code =  sanitize_text_field($_POST['epim_Part_Code']);
    update_post_meta( $post_id, 'epim_Part_Code', $epim_Part_Code );
}
