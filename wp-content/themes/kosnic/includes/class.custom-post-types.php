<?php

Class CustomPostTypes {
  public function __construct() {
    add_action('init', [$this, 'initialize_cpts']);
    add_action('init', [$this, 'initialize_custom_taxonomies']);
  }

  public function initialize_cpts() {
    $custom_post_types['application'] = [
      'labels' => [
        'name' => 'Applications',
        'singular_name' => 'Application'
      ],
      'public' => true,
      'menu_position' => 27,
      'menu_icon' => 'dashicons-portfolio',
      'capability_type' => 'post',
      'has_archive' => true,
      'supports' => [
        'title',
        'editor',
        'thumbnail',
        'page-attributes'
      ],
      'rewrite' => [
        'slug' => 'applications',
        'with_front' => false
      ],
      'taxonomies' => ['type']
    ];

    $custom_post_types['specification'] = [
      'labels' => [
        'name' => 'Specification',
        'singular_name' => 'Specification'
      ],
      'public' => true,
      'menu_position' => 28,
      'menu_icon' => 'dashicons-tag',
      'capability_type' => 'post',
      'has_archive' => true,
      'supports' => [
        'title',
        'editor',
        'thumbnail',
        'page-attributes'
      ],
      'rewrite' => [
        'slug' => 'specification',
        'with_front' => false
      ],
        'taxonomies' => ['spectypes']
    ];

    $this->register_custom_post_types($custom_post_types);
  }

  public function initialize_custom_taxonomies() {
    $custom_taxonomies['type'] = [
      'post_type' => 'application',
      'taxonomy_args' => [
        'hierarchical' => true,
        'labels' => $this->generate_taxonomy_labels_for('Type', 'Types'),
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'rewrite' => [
          'slug' => 'application-type',
        ]
      ]
    ];

  $custom_taxonomies['spectypes'] = [
      'post_type' => 'specification',
      'taxonomy_args' => [
          'hierarchical' => true,
          'labels' => $this->generate_taxonomy_labels_for('Spec Type', 'Spec Types'),
          'public' => true,
          'show_ui' => true,
          'show_admin_column' => true,
          'rewrite' => [
              'slug' => 'specification-type',
          ]
      ]
  ];

    $this->register_custom_taxonomies($custom_taxonomies);
  }

  private function register_custom_post_types($cpts) {
    foreach($cpts as $cpt => $cpt_args) {
      register_post_type($cpt, $cpt_args);
    }
  }

  private function register_custom_taxonomies($custom_taxonomies) {
    foreach($custom_taxonomies as $taxonomy => $taxonomy_options) {
      register_taxonomy(
        $taxonomy,
        $taxonomy_options['post_type'],
        $taxonomy_options['taxonomy_args']
      );
    }
  }

  private function generate_taxonomy_labels_for($singular, $plural) {
    return [
      'name' => $plural,
      'singular_name' => $singular,
      'all_items' => "All $plural",
      'parent_item' => "Parent $singular",
      'parent_item_colon' => "Parent $singular:",
      'edit_item' => "Edit $singular",
      'update_item' => "Update $singular",
      'add_new_item' => "Add New $singular",
      'new_item_name' => "New $singular Name",
      'menu_name' => $plural
    ];
  }
}
