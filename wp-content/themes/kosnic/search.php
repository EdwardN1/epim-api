<?php
/**
* Template Name: Product Search page
*/

get_header();

$product_search_value = $_POST['product_search'];

get_template_part('templates/breadcrumbs', 'tpl');
?>

  <section class="product-search product-listing">
    <div class="product-listing__grid container grid">
      <h1 class="product-search__title">
        Product Search for: <?php //echo $product_search_value; ?><?php echo esc_attr(get_search_query()); ?>
        <span class="js-pagination-title"></span>
      </h1>
      <div class="col-12">
        <!--<div class="product-grid js-product-search"
             data-search-term="<?php /*echo $product_search_value; */?>"
             data-num-results="0">

        </div>-->
          <div class="search-results">
              <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

                  <!-- To see additional archive styles, visit the /parts directory -->
                  <?php get_template_part( 'parts/loop', 'archive-grid' ); ?>

              <?php endwhile; ?>

                  <?php //joints_page_navi(); ?>

              <?php else : ?>

                  <?php //get_template_part( 'parts/content', 'missing' ); ?>

              <?php endif; ?>
          </div>
      </div>
      <!--<div class="col-12">
        <nav class="product-pagination js-product-pagination grid">
          <a class="product-pagination__btn btn js-pagination-btn" href="#first">
            First Page
          </a>
          <a class="product-pagination__btn btn js-pagination-btn" href="#prev">
            Prev Page
          </a>
          <a class="product-pagination__btn btn js-pagination-btn" href="#next">
            Next Page
          </a>
          <a class="product-pagination__btn btn js-pagination-btn" href="#last">
            Last Page
          </a>
          <span class="product-pagination__total js-pagination-total"></span>
        </nav>
      </div>-->
    </div>
  </section>

<?php get_footer();
