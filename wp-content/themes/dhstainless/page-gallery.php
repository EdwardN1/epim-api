<?php
/*
  Template Name: Gallery
*/
?>
<?php get_header(); ?>


  <?php get_template_part('partials/content', 'masthead'); ?>

  <div class="top-image-push"></div>

  <?php get_template_part('partials/content', 'breadcrumb'); ?>


  <!-- introduction content -->
  <div class="container">

    <div class="introduction intro-full">

      <h1><?php the_title(); ?></h1>
      <div class="line-break"></div>
     
    </div>

  </div>
  <!-- /.introduction content -->

  <div class="container">


    <?php if(have_rows('gallery')) : ?>
            
      <ul class="col-sm-12 gallery-slides">

        <?php while(have_rows('gallery')) : the_row(); ?>
          <li>
            <?php $image_1 = get_sub_field('image'); if(!empty($image_1)) : ?>
                <img src="<?php echo $image_1; ?>" alt="<?php echo get_sub_field('image_title'); ?>" class="img-responsive">
              <?php endif; ?>

              <span><?php echo get_sub_field('image_title'); ?></span>
          </li>

        <?php endwhile; ?>

      </ul><!-- end gallery slides -->
            
    <?php endif; ?>

    <?php if(have_rows('gallery')) : ?>

      <ul class="col-sm-12 slider-thumbs">
    
        <?php while(have_rows('gallery')) : the_row(); ?>

          <?php $image_1 = get_sub_field('image'); if(!empty($image_1)) : ?>
              <img src="<?php echo $image_1; ?>" alt="<?php echo get_sub_field('image_title'); ?>" class="img-responsive">
            <?php endif; ?>

        <?php endwhile; ?>

      </ul><!-- end slide thumbs -->

    <?php endif; ?>

  </div>



<?php get_footer(); ?>
