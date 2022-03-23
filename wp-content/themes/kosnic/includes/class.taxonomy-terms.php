<?php

class TaxonomyTerms {
  public $type,
         $terms_per_row = 4,
         $term_layouts = ['horizontal', 'vertical'];

  public function __construct($type) {
    $this->type = $type;
  }

  public function terms() {
    return $this->spilt_terms(
      get_terms($this->type, ['exclude' => 1, 'hide_empty' => false])
    );
  }

  public function layout_terms($row_terms) {
    $divided_terms = array_chunk($row_terms, ceil($this->terms_per_row / 2));

    return $this->split_terms_into_layout($divided_terms);
  }

  public function render_layout($terms, $row_index) {
    $template_layouts= $this->term_layouts;

    foreach($template_layouts as $layout) {
      include locate_template(
        'templates/block-layout/' . $layout . '-tpl.php'
      );
    }
  }

  public function term_image($term) {
    $image = get_term_meta($term->term_id, '_kos_cmb2_listing_image', true);

    return !empty($image) ?
      'style="background-image: url(' . $image . ');"' :
      false;
  }

  private function spilt_terms($terms) {
    return array_chunk($terms, $this->terms_per_row);
  }

  private function split_terms_into_layout($terms) {
    foreach($this->term_layouts as $layout) {
      $divided_terms = array_values($terms);
      $term_chunk = !empty($divided_terms) ? $divided_terms[0] : [];

      $layout_terms[$layout] = $term_chunk;

      unset($terms[0]);
    }

    return $layout_terms;
  }
}
