<?php
/*add_filter('acf/format_value/name=test', 'test_acf_format_value', 10, 3);

function test_acf_format_value($value, $post_id, $field) {
    $current_language = apply_filters('wpml_current_language', NULL);
    $applicationhybrispk = get_field('applicationhybrispk',$post_id);
    return $value.' - '.$current_language.' - '.$applicationhybrispk;
}*/

add_filter('the_title', 'change_title', 10, 2);

function change_title($title, $id)
{
    $current_language = apply_filters('wpml_current_language', NULL);
    if (get_post_type($id) == "products") $title = $title;
    if (have_rows('product_data')) {
        while (have_rows('product_data')) : the_row();
            $language = get_sub_field('language');
            if($language==$current_language) {
                $productpath = get_sub_field('productpath');
                $path_found = true;
            }
        endwhile;
    }
    return $title;
}

add_filter('post_type_link', 'change_products_url',10,4);

function change_products_url($post_link, $post, $leavename, $sample)
{
    $res = $post_link;
    if ('products' == get_post_type($post)) {
        $api_id = get_field('productid', $post->ID);
        if ($api_id) {
            $api_post_id = post_exists($api_id, '', '', 'api_product');
            if ($api_post_id) {
                $current_language = apply_filters('wpml_current_language', NULL);
                $root_found = false;
                $product_link_root = '';
                if (have_rows('available_languages', 'option')) {
                    while (have_rows('available_languages', 'option')) : the_row();
                        $wpml_extension = get_sub_field('wpml_extension');
                        if ($wpml_extension == $current_language) {
                            $product_link_root = get_sub_field('product_link_root');
                            $root_found = true;
                        }
                    endwhile;
                }
                if ($root_found) {
                    $path_found = false;
                    $productpath = '';
                    if (have_rows('product_data')) {
                        while (have_rows('product_data')) : the_row();
                            $language = get_sub_field('language');
                            if($language==$current_language) {
                                $productpath = get_sub_field('productpath');
                                $path_found = true;
                            }
                        endwhile;
                    }
                    if($path_found) {
                        $res = $product_link_root.$productpath;
                    }
                }
            }
        }

    }
    return $res;
}

/* Add a paragraph only to Pages. */
function change_the_content ( $content ) {
    error_log('change_the_content page template = '.get_page_template());
    return $content . '<p>'.'Page template = '.get_page_template().'</p>';

}

//add_filter( 'the_content', 'change_the_content');