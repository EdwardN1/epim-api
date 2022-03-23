<?php

Class Query {
  public $post_type;
  public $query_defaults = [
    'paged'          => 1,
    'post_status'    => 'publish',
    'posts_per_page' => get_option('posts_per_page')
  ];

  public function __construct($post_type) {
    $this->post_type = $post_type;
    $this->query_defaults['post_type'] = $post_type;
  }

  public function query(array $query_args = null) {
    $args = array_merge(
              $this->query_defaults,
              (!empty($query_args) ? $this->set_paged_value($query_args) : [])
            );

    return new WP_Query($args);
  }

  public function no_results($results, $custom_error_msg = null) {
    if(!$results->have_posts()) {
      $post_type_name = get_post_type_object($this->post_type)->labels->name;
      $error_msg = !empty($custom_error_msg) ?
                     $this->error_msg($custom_error_msg, $post_type_name) :
                     'Sorry, no ' . $post_type_name  . ' found';

      return $error_msg;
    }
  }

  private function set_paged_value(array $query_args) {
    $default_paged = !empty($query_args['paged']) ?
                     $query_args['paged'] :
                     $this->query_defaults['paged'];

    $paged_query_var = get_query_var('paged');
    $query_args['paged'] = $paged_query_var ?
                             $paged_query_var :
                             $default_paged;

    return $query_args;
  }

  private function error_msg($error_msg, $post_type_name) {
    return str_replace('#post_type', $post_type_name, $error_msg);
  }
}
