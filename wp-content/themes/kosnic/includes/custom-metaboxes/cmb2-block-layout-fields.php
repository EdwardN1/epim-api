<?php

function get_post_type_taxonomies() {
  return array_map(
    'ucwords',
    get_taxonomies([
      'object_type'  => ['post'],
      'hierarchical' => true,
      'public'       => true
    ])
  );
}

$block_layout_fields = new_cmb2_box([
  'id'           => 'block_layout_terms',
  'title'        => __('Block Layout Taxonomy Terms', 'kos'),
  'object_types' => ['page'],
  'show_on'      => [
                      'key' => 'page-template',
                      'value' => 'page-block-layout.php'
                    ],
  'context'    => 'side',
  'priority'   => 'high',
  'show_names' => true
]);

$block_layout_fields->add_field([
  'desc'    => __('Select taxonomy to list term items on template', 'cmb2'),
  'id'      => $prefix . 'taxonomy_type',
  'type'    => 'select',
  'options' => get_post_type_taxonomies()
]);
