<?php
add_filter('acf/format_value/name=test', 'test_acf_format_value', 10, 3);

function test_acf_format_value($value, $post_id, $field) {
    $current_language = apply_filters('wpml_current_language', NULL);
    $applicationhybrispk = get_field('applicationhybrispk',$post_id);
    return $value.' - '.$current_language.' - '.$applicationhybrispk;
}

add_filter('the_title', 'change_title', 10, 2);

function change_title($title, $id)
{
    $current_language = apply_filters('wpml_current_language', NULL);
    if (get_post_type($id) == "Products") $title = $title.' - '.$current_language;
    return $title;
}