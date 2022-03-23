<?php
get_header();
get_template_part('templates/breadcrumbs', 'tpl');

$taxonomy_terms = new TaxonomyTerms('type');
?>
<section class="block">
  <div class="container">
    <div class="block__grid grid owl-carousel js-block-slider">

      <?php
      $terms = $taxonomy_terms->terms();

      foreach($terms as $row_index => $row_terms):
        $layout_terms = $taxonomy_terms->layout_terms($row_terms);
        $taxonomy_terms->render_layout($layout_terms, $row_index);
      endforeach;
      ?>

    </div>
  </div>
</section>

<?php
get_footer();
