<?php
/**
 * Simple checkout field addition example.
 *
 * @param  array $fields List of existing billing fields.
 * @return array         List of modified billing fields.
 */
function jeroensormani_add_checkout_fields( $fields ) {

    $args = array(
        'post_type' => 'branches',
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
        $fieldOptions[$branchName] = $branchName;
    endwhile;

    wp_reset_postdata();

    $fields['shipping_COLLECTION_BRANCH'] = array(
        'label'        => __( 'Collection Branch' ),
        'type'        => 'select',
        'options' => $fieldOptions,
        'class'        => array( 'form-row-wide' ),
        'priority'     => 155,
        'required'     => true,
    );

    return $fields;
}
add_filter( 'woocommerce_billing_fields', 'jeroensormani_add_checkout_fields' );


function js_woocommerce_admin_shipping_fields( $fields ) {

    $fields['COLLECTION_BRANCH'] = array(
        'label' => __( 'Collection Branch' ),
        'show' => true,
    );

    return $fields;
}
add_filter( 'woocommerce_admin_shipping_fields', 'js_woocommerce_admin_shipping_fields' );

/* To use:
1. Add this snippet to your theme's functions.php file
2. Change the meta key names in the snippet
3. Create a custom field in the order post - e.g. key = "Tracking Code" value = abcdefg
4. When next updating the status, or during any other event which emails the user, they will see this field in their email
*/
add_filter('woocommerce_email_order_meta_keys', 'my_custom_order_meta_keys');

function my_custom_order_meta_keys( $keys ) {
    $keys[] = 'shipping_COLLECTION_BRANCH'; // This will look for a custom field called 'Tracking Code' and add it to emails
    return $keys;
}


