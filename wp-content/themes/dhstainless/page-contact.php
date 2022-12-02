<?php
/*
  Template Name: Contact
*/
?>
<?php get_header(); ?>


  <?php get_template_part('partials/content', 'breadcrumb'); ?>


  <div class="container">

    <div class="introduction intro-full">

      <h1><?php the_title(); ?></h1>

      <div class="line-break"></div>

      <?php the_content(); ?>

    </div>

  </div>



  <?php if(have_rows('addresses')) : ?>
  <!-- addresses -->
  <div class="container">

    <?php while(have_rows('addresses')) : the_row(); ?>
    <div class="row addresses">

      <div class="col-md-6">

        <h2><?php the_sub_field('title'); ?></h2>

        <div class="line-break"></div>

        <h5>Address</h5>

        <?php the_sub_field('address'); ?>

      </div>

      <div class="col-md-6">

        <?php the_sub_field('map'); ?>

      </div>

    </div>
    <?php endwhile; ?>

  </div>
  <!-- /.addresses -->
  <?php endif; ?>


  <div class="container-full contact-form">

    <div class="container">

      <div class="row">

        <div class="clearfix">

          <h3><?php the_field('form_heading'); ?></h3>

          <?php the_field('form_text'); ?>

          <?php the_field('form'); ?>

        </div>

      </div>

    </div>

  </div>


<?php get_footer(); ?>
