<?php

$taxonomy_fields = new_cmb2_box([
  'id' => 'tax_fields',
  'title' => __('Page Intro', 'rd'),
  'object_types' => ['term'],
  'taxonomies' =>['category', 'type', 'spectypes']
]);

$taxonomy_fields->add_field([
  'name' => __('Add Category Listing Image'),
  'desc' => __('Image for categories on block listing template'),
  'id' => $prefix . 'listing_image',
  'type' => 'file',
  'allow' => ['attachment']
]);

$taxonomy_fields->add_field([
  'name' => __('Add Category Listing Detail Page Image'),
  'desc' => __('Image for specification type detail page'),
  'id' => $prefix . 'listing_image_detail_page',
  'type' => 'file',
  'allow' => ['attachment']
]);