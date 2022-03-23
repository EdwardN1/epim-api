<?php

function two_column_shortcode($atts, $content = null) {
  $cmb2 = new CMB2Fields(get_the_ID());
  $formatted_content = $cmb2->format_content($content);

  return '<div class="col-6">' . $formatted_content . '</div>';
}
add_shortcode('half_column', 'two_column_shortcode');

function grid_row_shortcode($atts, $content = null) {
  return '<div class="grid">' . do_shortcode($content) . '</div>';
}
add_shortcode('grid_row', 'grid_row_shortcode');
