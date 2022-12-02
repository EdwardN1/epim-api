<?php if(is_single()) : ?>


  <?php get_template_part('partials/content', 'masthead'); ?>


  <?php get_template_part('partials/content', 'breadcrumb'); ?>


  <div class="container">

    <div class="row">

      <div class="article-wrapper">

        <?php get_template_part('partials/sidebar', 'blog'); ?>

        <!-- MAIN -->
        <div class="col-md-9 blog-body">

          <div class="introduction">

            <h1><?php the_title(); ?></h1>
            <div class="line-break"></div>
            <p class="large-paragraph"><?php the_field('intro'); ?></p>

          </div>

          <div class="blog-content">

            <?php the_content(); ?>

            <a href="<?php bloginfo('url'); ?>/blog/" class="btn-outline">Read All News</a>

          </div>


          <?php
          $posts = get_field('related_posts');
          if( $posts ) : ?>
          <div class="related">

            <h4>Related Posts</h4>
            <div class="line-break"></div>

          </div>

          <div class="row news-links">

            <?php foreach( $posts as $post): ?>
            <?php setup_postdata($post); ?>
            <div class="col-sm-6 news-link">
              <div class="news-link-image" style="background: url('<?php bloginfo('template_directory'); ?>/img/products-2.jpg'); background-size: cover; background-position: center;"></div>
              <h4><?php the_title(); ?></h4>
              <span><?php the_date(); ?></span>
              <?php custom_excerpt(30, ' ...'); ?>
              <a href="<?php the_permalink(); ?>" class="btn-outline">Read More</a>
            </div>
            <?php endforeach; ?>

          </div>
          <?php wp_reset_postdata(); ?>
          <?php endif; ?>

        </div>

      </div>

    </div>

  </div>


<?php else : ?>

  <div class="col-sm-6 news-link">
    <div class="news-link-image" style="background: url('<?php bloginfo('template_directory'); ?>/img/products-2.jpg'); background-size: cover; background-position: center;"></div>
    <h4><?php the_title(); ?></h4>
    <span><?php the_date(); ?></span>
    <?php custom_excerpt(30, ' ...'); ?>
    <a href="<?php the_permalink(); ?>" class="btn-outline">Read More</a>
  </div>

<?php endif; ?>
