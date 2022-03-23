<?php
/**
* Template Name: Block page
*/
get_header();

if(have_posts()): while(have_posts()): the_post();
  $page_fields = new CMB2Fields(get_the_ID());
  $selected_taxonomy = $page_fields->field('taxonomy_type');
  $taxonomy_terms = new TaxonomyTerms($selected_taxonomy);

  get_template_part('templates/breadcrumbs', 'tpl');
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
endwhile; endif;
get_footer();
