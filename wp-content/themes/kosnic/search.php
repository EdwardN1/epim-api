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

                  <?php joints_page_navi(); ?>

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

// Borrowed with love from FoundationPress
function joints_page_navi()
{
    global $wp_query;
    $big = 999999999; // This needs to be an unlikely integer
    // For more options and info view the docs for paginate_links()
    // http://codex.wordpress.org/Function_Reference/paginate_links
    $paginate_links = paginate_links(array(
        'base' => str_replace($big, '%#%', html_entity_decode(get_pagenum_link($big))),
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages,
        'mid_size' => 5,
        'prev_next' => true,
        'prev_text' => __('&laquo;', 'jointswp'),
        'next_text' => __('&raquo;', 'jointswp'),
        'type' => 'list',
    ));
    /*$paginate_links = str_replace("<ul class='page-numbers'>", "<ul class='pagination'>", $paginate_links);
    $paginate_links = str_replace('<li><span class="page-numbers dots">', "<li><a href='#'>", $paginate_links);
    $paginate_links = str_replace("<li><span class='page-numbers current'>", "<li class='current'>", $paginate_links);
    $paginate_links = str_replace('</span>', '</a>', $paginate_links);
    $paginate_links = str_replace("<li><a href='#'>&hellip;</a></li>", "<li><span class='dots'>&hellip;</span></li>", $paginate_links);
    $paginate_links = preg_replace('/\s*page-numbers/', '', $paginate_links);*/
    // Display the pagination if more than one page is found.
    if ($paginate_links) {
        echo '<div class="woocommerce">';
        echo '<nav class="woocommerce-pagination">';
        echo $paginate_links;
        echo '</nav><!--// end .pagination -->';
        echo '</div>';
    }
}
?>