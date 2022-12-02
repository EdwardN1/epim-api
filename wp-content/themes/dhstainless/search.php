<?php get_header(); ?>

  <div class="top-image-push"></div>

  <?php get_template_part('partials/content', 'breadcrumb'); ?>

  <div class="container">

    <div class="row">

      <div class="article-wrapper">

        <?php get_template_part('partials/sidebar', 'blog'); ?>

        <!-- MAIN -->
        <div class="col-md-9">

          <div class="introduction">

            <h1><?php printf( __( 'Search Results for: %s' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
            <div class="line-break"></div>

          </div>

          <div class="row">
            <div class="news-links">
              <?php if(have_posts()) : $count = 0; ?>

                <?php while(have_posts()) : the_post(); $count++; ?>

                  <?php get_template_part('partials/content', 'post'); ?>

                <?php endwhile; ?>

              <?php else : ?>

                <p>No results, please try a different search</p>

              <?php endif; ?>
            </div>
          </div>

        </div>

      </div>

    </div>

  </div>

<?php get_footer(); ?>
