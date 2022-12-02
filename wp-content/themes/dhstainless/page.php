<?php get_header(); ?>

  <div class="top-image-push"></div>

  <?php get_template_part('partials/content', 'breadcrumb'); ?>

  <div class="container">

      <div class="introduction">

        <h1><?php the_title(); ?></h1>
        <div class="line-break"></div>

        <?php the_content(); ?>

      </div>

  </div>

<?php get_footer(); ?>
