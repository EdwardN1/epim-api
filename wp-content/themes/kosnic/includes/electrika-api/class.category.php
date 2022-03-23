<?php

namespace ElectrikaAPI;

class Category {
  public $attributes,
         $name,
         $ID;

  private $category_data;

  public function __construct($category_data) {
    $this->category_data = $category_data;
    $this->set_category_values();
  }

  public function css_classes() {
    $default_css_classes = 'product-category__item js-product-category-item';

    return $this->attributes->hasChildren === true ?
      $default_css_classes .= ' has-children js-has-children' :
      $default_css_classes;
  }

  private function set_category_values() {
    $this->ID = $this->category_data['ID'];
    $this->name = $this->category_data['Name'];
    $this->attributes = (object) [
      'hasChildren' => false,
      'slug' => $this->url_slug()
    ];

    if(!empty($this->category_data['HasChildren'])) {
      $this->attributes->hasChildren = filter_var(
        $this->category_data['HasChildren'],
        FILTER_VALIDATE_BOOLEAN
      );
    }
  }

  private function url_slug() {
    $slug_from_name = sanitize_title($this->name);

    return "$slug_from_name-{$this->ID}";
  }
}
