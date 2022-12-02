<?php if(is_single()) : ?>

  <?php get_template_part('partials/content', 'masthead'); ?>

  <?php get_template_part('partials/content', 'breadcrumb'); ?>

  <div class="container">

    <div class="row">

      <?php get_template_part('partials/sidebar', 'help'); ?>

      <!-- MAIN -->
      <div class="col-md-9 blog-body">

        <div class="introduction">

          <h1><?php the_title(); ?></h1>
          <div class="line-break"></div>

          <p class="large-paragraph"><?php the_field('intro'); ?></p>

        </div>

        <div class="blog-content">

          <?php the_content(); ?>

          <a href="<?php bloginfo('url'); ?>/case-studies/" class="btn-outline">Read All Case Studies</a>

        </div>


        <?php
        $posts = get_field('related_case_studies');
        if( $posts ) : ?>
        <div class="related">

          <h4>Related Case Studies</h4>
          <div class="line-break"></div>

        </div>

        <div class="clearfix products-inner">

          <?php foreach( $posts as $post): ?>
          <?php setup_postdata($post); ?>
          <div class="col-md-6 col-sm-6 product-box" style="background-image: url(''); background-size: cover; background-position: center; background-color: rgba(50,50,50,0.8); background-blend-mode: multiply;">
            <div class="product-overlay"></div>
            <div class="product-name"><?php the_title(); ?></div>
            <a href="<?php the_permalink(); ?>" class="product-button">View Case Study</a>
          </div>
          <?php endforeach; ?>

        </div>
        <?php wp_reset_postdata(); ?>
        <?php endif; ?>

      </div>

    </div>

  </div>

<?php else : ?>

  <div class="col-md-6 col-sm-6 product-box" style="background-image: url(''); background-size: cover; background-position: center; background-color: rgba(50,50,50,0.8); background-blend-mode: multiply;">
    <div class="product-overlay"></div>
    <div class="product-name"><?php the_title(); ?></div>
    <a href="<?php the_permalink(); ?>" class="product-button">View Case Study</a>
  </div>

<?php endif; ?>
