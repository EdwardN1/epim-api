<?php get_header(); ?>

  <div class="top-image-push"></div>

  <?php get_template_part('partials/content', 'breadcrumb'); ?>

  <div class="container">

    <div class="row">

      <?php get_template_part('partials/sidebar', 'blog'); ?>

      <!-- MAIN -->
      <div class="col-md-9">

        <div class="introduction">

          <h1>Posted in <?php single_cat_title(); ?></h1>
          <div class="line-break"></div>

        </div>

        <div class="row news-links">
        <?php if(have_posts()) : ?>

          <?php while(have_posts()) : the_post(); ?>

            <?php get_template_part('partials/content', 'post'); ?>

          <?php endwhile; ?>

        <?php else : ?>

          <p>No posts</p>

        <?php endif; ?>
        </div>

      </div>

    </div>

  </div>

<?php get_footer(); ?>
