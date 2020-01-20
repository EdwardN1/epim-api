<?php

//Product Cat Create page
function epim_taxonomy_add_new_meta_field() {
    ?>

    <div class="form-field">

        <label for="epim_api_id"><?php _e('API ID', 'epim'); ?></label>
        <input type="text" name="epim_api_id" id="epim_api_id">
        <p class="description"><?php _e('Enter an API ID', 'epim'); ?></p>

        <label for="epim_api_parent_id"><?php _e('API PARENT ID', 'epim'); ?></label>
        <input type="text" name="epim_api_parent_id" id="epim_api_parent_id">
        <p class="description"><?php _e('Enter an API PARENT ID', 'epim'); ?></p>

        <label for="epim_api_picture_ids"><?php _e('API PICTURE IDS', 'epim'); ?></label>
        <input type="text" name="epim_api_picture_IDS" id="epim_api_picture_ids">
        <p class="description"><?php _e('Enter API PICTURE IDS', 'epim'); ?></p>

        <label for="epim_api_picture_link"><?php _e('API PICTURE LINK', 'epim'); ?></label>
        <input type="text" name="epim_api_picture_link" id="epim_api_picture_link">
        <p class="description"><?php _e('Enter API PICTURE LINK', 'epim'); ?></p>
    </div>
    <?php
}

//Product Cat Edit page
function epim_taxonomy_edit_meta_field($term) {

    //getting term ID
    $term_id = $term->term_id;

    // retrieve the existing value(s) for this meta field.
    $epim_api_id = get_term_meta($term_id, 'epim_api_id', true);
    $epim_api_parent_id = get_term_meta($term_id, 'epim_api_parent_id', true);
    $epim_api_picture_ids = get_term_meta($term_id, 'epim_api_picture_ids', true);
    $epim_api_picture_link = get_term_meta($term_id, 'epim_api_picture_link', true);

    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="epim_api_id"><?php _e('API ID', 'epim'); ?></label></th>
        <td>
            <input type="text" name="epim_api_id" id="epim_api_id" value="<?php echo esc_attr($epim_api_id) ? esc_attr($epim_api_id) : ''; ?>">
            <p class="description"><?php _e('Enter an API ID', 'epim'); ?></p>
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
    <?php
}

add_action('product_cat_add_form_fields', 'epim_taxonomy_add_new_meta_field', 10, 1);
add_action('product_cat_edit_form_fields', 'epim_taxonomy_edit_meta_field', 10, 1);

// Save extra taxonomy fields callback function.
function epim_save_taxonomy_custom_meta($term_id) {

    $epim_api_id = filter_input(INPUT_POST, 'epim_api_id');
    $epim_api_parent_id = filter_input(INPUT_POST, 'epim_api_parent_id');
    $epim_api_picture_ids = filter_input(INPUT_POST, 'epim_api_picture_ids');
    $epim_api_picture_link = filter_input(INPUT_POST, 'epim_api_picture_link');

    update_term_meta($term_id, 'epim_api_id', $epim_api_id);
    update_term_meta($term_id, 'epim_api_parent_id', $epim_api_parent_id);
    update_term_meta($term_id, 'epim_api_picture_ids', $epim_api_picture_ids);
    update_term_meta($term_id, 'epim_api_picture_link', $epim_api_picture_link);
}

add_action('edited_product_cat', 'epim_save_taxonomy_custom_meta', 10, 1);
add_action('create_product_cat', 'epim_save_taxonomy_custom_meta', 10, 1);