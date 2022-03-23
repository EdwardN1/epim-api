<?php

$homepage_slider_fields = new_cmb2_box([
  'id'           => 'homepage_slider',
  'title'        => __('Homepage Slider', 'kos'),
  'object_types' => ['page'],
  'show_on'      => [
                      'key' => 'id',
                      'value' => get_option('page_on_front')
                    ],
  'context'    => 'normal',
  'priority'   => 'high',
  'show_names' => true
]);

$homepage_slider_fields_id = $homepage_slider_fields->add_field([
  'id'      => $prefix . 'homepage_slider_group',
  'type'    => 'group',
  'options' => [
                 'group_title'   => __('Slide {#}', 'kos'),
                 'add_button'    => __('Add Another Slide', 'kos'),
                 'remove_button' => __('Remove Slide', 'kos')
               ]
]);

$homepage_slider_fields->add_group_field($homepage_slider_fields_id, [
  'name' => __('Slide Image', 'kos'),
  'desc' => __('Upload/Select image for this slide', 'kos'),
  'id'   => 'image',
  'type' => 'file'
]);

$homepage_slider_fields->add_group_field($homepage_slider_fields_id, [
  'name' => __('Slide Text', 'kos'),
  'desc' => __('Enter the text content for this slide', 'kos'),
  'id'   => 'content',
  'type' => 'text'
]);

$homepage_slider_fields->add_group_field($homepage_slider_fields_id, [
  'name' => __('Slide Subtitle Text', 'kos'),
  'desc' => __('Enter the subtitle text content for this slide', 'kos'),
  'id'   => 'sub_content',
  'type' => 'text'
]);

$homepage_slider_fields->add_group_field($homepage_slider_fields_id, [
  'name' => __('Slide Button Link URL', 'kos'),
  'desc' => __('Enter the button link url for this slide', 'kos'),
  'id'   => 'link_url',
  'type' => 'text_url'
]);

$homepage_slider_fields->add_group_field($homepage_slider_fields_id, [
  'name' => __('Slide Button Link Text', 'kos'),
  'desc' => __('Enter the button link text for this slide', 'kos'),
  'id'   => 'link_text',
  'type' => 'text'
]);
