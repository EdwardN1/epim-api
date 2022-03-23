<?php

$read_more_fields = new_cmb2_box([
  'id' => 'read_more_fields',
  'title' => __('Read More', 'kos'),
  'object_types' => ['application', 'specification', 'page', 'post'],
  'show_on_cb' => 'display_read_more_fields_callback',
  'context' => 'normal',
  'priority' => 'high',
  'show_names' => true
]);

$read_more_fields->add_field([
  'name' =>__('Read More Hidden Content', 'kos'),
  'desc' => __('Add the hidden content here', 'kos'),
  'id' => $prefix . 'read_more_content',
  'type' => 'wysiwyg',
]);

function display_read_more_fields_callback($cmb) {
  if(get_post_type() === 'page') {
    $page_template = get_post_meta(get_the_ID(), '_wp_page_template', true);

    if(!empty($page_template)) {
      return $page_template === 'full-width.php';
    }

    return false;
  }

  return true;
}
