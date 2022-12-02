<?php
/*
  Template Name: About
*/
?>
<?php get_header(); ?>


  <?php get_template_part('partials/content', 'masthead'); ?>


  <?php get_template_part('partials/content', 'breadcrumb'); ?>


  <!-- introduction content -->
  <div class="container">

    <div class="introduction intro-full">

      <h1><?php the_title(); ?></h1>
      <div class="line-break"></div>
      <?php the_content(); ?>

    </div>

  </div>
  <!-- /.introduction content -->


  <?php if(have_rows('icons')) : ?>
  <!-- SECTORS -->
  <div class="container-full sectors about-sectors" style="background: #000;">

    <div class="container">

      <h2><?php the_field('block_heading'); ?></h2>

      <?php the_field('block_text'); ?>

      <?php while(have_rows('icons')) : the_row(); ?>
      <div class="sector-icon">
        <div class="<?php the_sub_field('icon'); ?> sector-svg"></div>
        <h5><?php the_sub_field('title'); ?></h5>
      </div>
      <?php endwhile; ?>

    </div>

  </div>
  <!-- /.SECTORS -->
  <?php endif; ?>

  <!-- about video -->
  <?php if( get_field('video') ): ?>
  <div id="playerScroll" class="about-video">
    <div class="embed-container container">
      <iframe src="https://player.vimeo.com/video/<?php echo get_field('video'); ?>" frameborder="0" width="100%" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
    </div>
  </div><!-- end about video -->
  <?php endif; ?>


  <!--
  <?php if(have_rows('team')) : ?>

  <div class="container">

    <div class="introduction intro-full" style="margin-top: 50px;">

      <h1>Meet The Team</h1>
      <div class="line-break"></div>

      <div class="row">

        <?php while(have_rows('team')) : the_row(); ?>
        <div class="col-md-3 col-sm-6 team-member">
          <div class="team-member-image" style="background-image: url('<?php the_sub_field('image'); ?>'); background-size: cover; background-position: center;"></div>
          <h5><?php the_sub_field('name'); ?></h5>
          <span><?php the_sub_field('role'); ?></span>
        </div>
        <?php endwhile; ?>

      </div>

    </div>

  </div>

  <?php endif; ?>
-->

<!--
  <div id="case-studies" class="carousel slide" data-ride="carousel">


    <div class="carousel-inner" role="listbox">

      <?php

        $posts = get_field('case_studies');
        $counter = 0;
        if( $posts ):

      ?>

    <?php foreach( $posts as $post): // variable must be called $post (IMPORTANT)  ?>
        <?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' ); ?>
        <?php setup_postdata($post); ?>

      <div class="item <?php if( $counter == 0 ) { ?> active <?php } ?>" style="background-image: url('<?php echo $thumb['0'];?>'); background-size: cover; background-position: center;">
        <div class="container">
          <div class="row">
            <div class="col-md-5 col-sm-7 case-study-text">
              <h4><?php the_title(); ?></h4>
              <div class="line-break"></div>
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
              <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
              <a href="<?php echo the_permalink(); ?>" class="underlined">Read Full Case Study</a>
            </div>
          </div>
        </div>
      </div>
      <?php $counter++; ?>
      <?php endforeach; ?>
      <?php wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly ?>
      <?php endif; ?>

    </div>


    <a class="case-study-control-prev case-study-control" href="#case-studies" role="button" data-slide="prev">
      <div class="icon-left-arrow" aria-hidden="true"></div>
      <span class="sr-only">Previous</span>
    </a>
    <a class="case-study-control-next case-study-control" href="#case-studies" role="button" data-slide="next">
      <div class="icon-right-arrow" aria-hidden="true"></div>
      <span class="sr-only">Next</span>
    </a>

  </div> -->

  <div class="container">

    <div class="certificates">

      <h3>ISO Accreditation Certificates</h3>

      <div class="line-break"></div>

      <ul>
        <li><a href="<?php bloginfo('template_directory'); ?>/pdfs/ISO-9001-2015.pdf" target="_blank"><img src="<?php bloginfo('template_directory'); ?>/img/certificates/ISO-9001-2015.png" alt="ISO 9001:2015"></a></li>
        <li><a href="<?php bloginfo('template_directory'); ?>/pdfs/ISO-14001-2015.pdf" target="_blank"><img src="<?php bloginfo('template_directory'); ?>/img/certificates/ISO-14001-2015.png" alt="ISO 14001:2015"></a></li>
        <li><a href="<?php bloginfo('template_directory'); ?>/pdfs/ISO-18001-2007.pdf" target="_blank"><img src="<?php bloginfo('template_directory'); ?>/img/certificates/ISO-18001-2007.png" alt="BS OHSAS 18001 2007"></a></li>
      </ul>

    </div>

  </div>


<?php get_footer(); ?>
