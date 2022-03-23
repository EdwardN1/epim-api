<?php

function posts_taxonomy_filter($cmb) {
  $tax_terms = $cmb->prop('show_on_terms', []);

  if(empty($tax_terms) || !$cmb->object_id()) return false;

  $post_id = $cmb->object_id();
  $post = get_post($post_id);

  foreach($tax_terms as $taxonomy => $slugs) {
    $terms = get_the_terms($post, $taxonomy);

    if(!empty($terms)) {
      foreach($terms as $term) {
        if(in_array($term->slug, $slugs, true)) return true;
      }
    }
  }
}

$case_study_fields = new_cmb2_box([
  'id' => 'case_study_fields',
  'title' => __('Related Products', 'kos'),
  'object_types' => ['post'],
  'show_on_cb' => 'posts_taxonomy_filter',
  'show_on_terms' => ['category' => ['case-studies']],
  'context' => 'normal',
  'priority' => 'high',
  'show_names' => true
]);

$case_study_product_fields_id = $case_study_fields->add_field([
  'id'      => $prefix . 'case_study_product_group',
  'type'    => 'group',
  'options' => [
    'group_title'   => __('Product ID {#}', 'kos'),
    'add_button'    => __('Add Another Product ID', 'kos'),
    'remove_button' => __('Remove Product ID', 'kos')
  ]
]);

$case_study_fields->add_group_field($case_study_product_fields_id, [
  'name' => __('Product ID', 'kos'),
  'desc' => '<p class="cmb2-metabox-description">Enter a Product ID (from ' .
            'Electrika API data) to display this case study on the ' .
            'corresponding product page.</p>',
  'id'   => 'product_id',
  'type' => 'text_small'
]);
