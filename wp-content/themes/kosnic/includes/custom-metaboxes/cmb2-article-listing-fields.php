<?php

$article_listing_fields = new_cmb2_box([
  'id' => 'article_listing_fields',
  'title' => __('Article Listing Details', 'kos'),
  'object_types' => ['application', 'specification', 'post'],
  'context' => 'side',
  'priority' => 'low',
  'show_names' => true
]);

$article_listing_fields->add_field([
  'name' =>__('Article Listing image', 'kos'),
  'desc' => __('Add the small image for the article listing page', 'kos'),
  'id' => $prefix . 'article_listing_image',
  'type' => 'file',
]);
