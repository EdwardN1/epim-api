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

function epsm_vertical_filter()
{
    $term_id = get_queried_object_id();
    $taxonomy = 'product_cat';

    $terms = get_terms([
        'taxonomy' => $taxonomy,
        'hide_empty' => true,
        'parent' => get_queried_object_id()
    ]);

    if (count($terms) > 0) {

        $output = '<ul class="epsm accordion">';

        foreach ($terms as $term) {
            $term_link = get_term_link($term, $taxonomy);
            $children = get_term_children($term->term_id, $taxonomy);
            if (count($children) > 0) {
                $output .= '<li class="has-children">';
                $output .= '<a href="' . $term_link . '">' . $term->name . '</a><a class="toggle-menu" href="javascript:void(0);"><span class="toggle-menu-text">Toggle menu</span></a>';
                $output .= '<ul class="inner">';
                foreach ($children as $child_id) {
                    $child = get_term($child_id);
                    $child_term_link = get_term_link($child, $taxonomy);
                    $output .= '<li><a href="' . $child_term_link . '">' . $child->name . '</a></li>';
                }
                $output .= '</ul>';
                $output .= '</li>';
            } else {
                $output .= '<li><a href="' . $term_link . '">' . $term->name . '</a></li>';
            }
            $output .= '';
        }

        return $output . '</ul>';
    }

}