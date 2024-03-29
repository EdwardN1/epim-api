<?php
/**
 * Simple checkout field addition example.
 *
 * @param array $fields List of existing billing fields.
 * @return array         List of modified billing fields.
 */
function jeroensormani_add_checkout_fields($fields)
{

    $args = array(
        'post_type' => 'cac_branches',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
    );

    $fieldOptions = array();
    $branches = new WP_Query($args);
    while ($branches->have_posts()) : $branches->the_post();
        $id = get_the_ID();
        $branchName = get_the_title();
        $fieldOptions[$id] = $branchName;
    endwhile;

    wp_reset_postdata();

    $fRequired = true;

    $Ioptions = get_option('epim_no_price_or_stocks');

    //error_log('$Ioptions = '.print_r($Ioptions,true));

    if ($Ioptions['checkbox_value'] == 1) $fRequired = false;

    if ($branches->have_posts()) :
        $fields['shipping_COLLECTION_BRANCH'] = array(
            'label' => __('Collection Branch'),
            'type' => 'select',
            'options' => $fieldOptions,
            'class' => array('form-row-wide', 'update_totals_on_change'),
            'priority' => 1,
            'required' => $fRequired,
        );
    endif;

    return $fields;
}

add_filter('woocommerce_billing_fields', 'jeroensormani_add_checkout_fields');


function js_woocommerce_admin_shipping_fields($fields)
{

    $fields['COLLECTION_BRANCH'] = array(
        'label' => __('Collection Branch'),
        'show' => false,
    );

    return $fields;
}

add_filter('woocommerce_admin_shipping_fields', 'js_woocommerce_admin_shipping_fields');

function js_save_custom_field_order_meta($order, $data)
{
    if (isset($_POST['shipping_COLLECTION_BRANCH'])) {
        $branch = get_the_title($_POST['shipping_COLLECTION_BRANCH']);
        $order->update_meta_data('_COLLECTION_BRANCH_NAME', $branch); // Save
    }
}

add_action('woocommerce_checkout_create_order', 'js_save_custom_field_order_meta', 22, 2);

add_action('woocommerce_admin_order_data_after_shipping_address', 'display_custom_meta_data_in_backend_orders', 10, 1);
function display_custom_meta_data_in_backend_orders($order)
{
    $domain = 'clickcollect';

    $collection_branch = $order->get_meta('_COLLECTION_BRANCH_NAME');
    if (!empty($collection_branch))
        echo '<p><strong>' . __('Collection Branch', $domain) . ': </strong> ' . $collection_branch . '</p>';
}


/**
 * Adds 'Profit' column header to 'Orders' page immediately after 'Total' column.
 *
 * @param string[] $columns
 * @return string[] $new_columns
 */
function cac_add_collection_branch_column($columns)
{

    $new_columns = array();

    foreach ($columns as $column_name => $column_info) {

        $new_columns[$column_name] = $column_info;

        if ('order_total' === $column_name) {
            $new_columns['order_collect_branch'] = __('Collection Branch', 'clickcollect');
        }
    }

    return $new_columns;
}

add_filter('manage_edit-shop_order_columns', 'cac_add_collection_branch_column', 20);


function cac_collection_branch_column($column)
{
    global $post;
    if ('order_collect_branch' === $column) {
        $order = wc_get_order($post->ID);
        $ship_array = $order->get_items('shipping');
        $shipping = reset($ship_array)->get_method_id();
        //error_log(get_post_meta($order->get_id(), '_COLLECTION_BRANCH_NAME', true));
        if ($shipping === 'clickcollect') {
            echo get_post_meta($order->get_id(), '_COLLECTION_BRANCH_NAME', true);
        }
    }

}

add_action('manage_shop_order_posts_custom_column', 'cac_collection_branch_column');


