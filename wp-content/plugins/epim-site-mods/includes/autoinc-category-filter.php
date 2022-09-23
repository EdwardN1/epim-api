<?php
add_shortcode( 'epimCategoryFilter', 'epsm_category_filter' );

function epsm_category_filter($atts) {
$settings = shortcode_atts( array(
    'background' => '#ffffff',
    'width'      => '240px',
    'float'      => 'right',
), $atts );
ob_start(); ?>
<?php echo epsm_vertical_filter();?>
<?php
    $content = ob_get_contents();
    ob_end_clean();

    //return $content;
    return $content;
}

function epsm_get_categories($term_id) {
    $taxonomy = 'product_cat';

    $args = array(
        'show_option_all'    => '',
        'orderby'            => 'name',
        'order'              => 'ASC',
        'style'              => '',
        'show_count'         => 0,
        'hide_empty'         => 1,
        'use_desc_for_title' => 1,
        'parent'           => $term_id,
        'feed'               => '',
        'feed_type'          => '',
        'feed_image'         => '',
        'exclude'            => '',
        'exclude_tree'       => '',
        'include'            => '',
        'hierarchical'       => 1,
        'title_li'           => __( '' ),
        'show_option_none'   => __( '' ),
        'number'             => null,
        'echo'               => 0,
        'depth'              => 0,
        'current_category'   => 0,
        'pad_counts'         => 0,
        'taxonomy'           => $taxonomy,
        'walker'             => null
    );

    return get_categories($args);
}

function epsm_vertical_filter()
{
    $term_id = get_queried_object_id();

    $parents = epsm_get_categories($term_id);

    if(count($parents) > 0) {
        $output = '<ul class="epsm accordion">';
        foreach ($parents as $parent) {
            $children1 = epsm_get_categories($parent->term_id);
            if(count($children1) > 0) {
                $output .= '<li class="has-children"><a href="' . get_term_link($parent, 'product_cat') . '" >' . $parent->name . '</a>';
                $output .= '<a class="toggle-menu" href="javascript:void(0);"><span class="toggle-menu-text">Toggle menu</span></a>';
                $output .= '<ul class="inner">';
                foreach ($children1 as $child1) {
                    $children2 = epsm_get_categories($child1->term_id);
                    $output .= '<li><a href="' . get_term_link($child1, 'product_cat') . '" >' . $child1->name . '</a></li>';
                }

                $output .= '</ul>';
                $output .= '</li>';
            } else {
                $output .= '<li><a href="' . get_term_link($parent, 'product_cat') . '" >' . $parent->name . '</a></li>';
            }
        }
        $output .= '</ul>';
    }

    return $output;


}