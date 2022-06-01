<?php
/* Add sku to product search */
function az_pre_get_posts($query)
{
// conditions - change the post type clause if you're not searching woocommerce or 'product' post type
    if (is_admin() || !$query->is_main_query() || !$query->is_search() || !get_query_var('post_type') == 'product') {
        return;
    }
    add_filter('posts_join', 'az_search_join');
    add_filter('posts_where', 'az_search_where');
    add_filter('posts_groupby', 'az_search_groupby');

}

add_action('pre_get_posts', 'az_pre_get_posts');

function az_search_join($join)
{
    global $wpdb;
    $join .= " LEFT JOIN $wpdb->postmeta gm ON (" .
        $wpdb->posts . ".ID = gm.post_id AND gm.meta_key='_sku')"; // change to your meta key if not woo

    return $join;
}

function az_search_where($where)
{
    global $wpdb;
    $where = preg_replace(
        "/\(\s*{$wpdb->posts}.post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
        "({$wpdb->posts}.post_title LIKE $1) OR (gm.meta_value LIKE $1)", $where);
    return $where;
}

/* grouping by id to make sure no dupes */
function az_search_groupby($groupby)
{
    global $wpdb;
    $mygroupby = "{$wpdb->posts}.ID";
    if (preg_match("/$mygroupby/", $groupby)) {
        // grouping we need is already there
        return $groupby;
    }
    if (!strlen(trim($groupby))) {
        // groupby was empty, use ours
        return $mygroupby;
    }
    // wasn't empty, append ours
    return $groupby . ", " . $mygroupby;
}