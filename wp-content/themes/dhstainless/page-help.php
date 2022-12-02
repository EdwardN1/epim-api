<?php
/*
  Template Name: Help & Advice
*/
?>
<?php get_header(); ?>


  <?php get_template_part('partials/content', 'masthead'); ?>


  <?php get_template_part('partials/content', 'breadcrumb'); ?>


  <div class="container">

    <div class="row">

      <!-- MAIN -->
      <div class="col-md-12">


        <!-- introduction -->
        <div class="introduction">

          <h1><?php the_title(); ?></h1>

          <div class="line-break"></div>

          <?php the_content(); ?>

        </div>
        <!-- /.introduction -->


        <div class="clearfix products-inner">

          <div class="col-md-4 col-sm-4 product-box" style="background-image: url('<?php the_field('about_section_image'); ?>'); background-size: cover; background-position: center; background-color: rgba(50,50,50,0.8); background-blend-mode: multiply;">
            <div class="product-overlay"></div>
            <div class="product-name">About Us</div>
            <a href="<?php bloginfo('url'); ?>/about-us/" class="product-button">Read More</a>
          </div>

          <div class="col-md-4 col-sm-4 product-box" style="background-image: url('<?php the_field('faq_section_image'); ?>'); background-size: cover; background-position: center; background-color: rgba(50,50,50,0.8); background-blend-mode: multiply;">
            <div class="product-overlay"></div>
            <div class="product-name">FAQs</div>
            <a href="<?php bloginfo('url'); ?>/faqs/" class="product-button">View FAQs</a>
          </div>

          <div class="col-md-4 col-sm-4 product-box" style="background-image: url('<?php the_field('downloads_section_image'); ?>'); background-size: cover; background-position: center; background-color: rgba(50,50,50,0.8); background-blend-mode: multiply;">
            <div class="product-overlay"></div>
            <div class="product-name">Downloads</div>
            <a href="<?php bloginfo('url'); ?>/downloads/" class="product-button">View Downloads</a>
          </div>


        </div>

      </div>

    </div>

  </div>


<?php get_footer(); ?>
