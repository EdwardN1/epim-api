<?php get_header(); ?>

  <?php if(have_rows('slides')) : $row = 0; ?>
  <!-- CAROUSEL -->
  <div id="carousel" class="carousel slide carousel-fullscreen carousel-fade" data-ride="carousel">

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
      <?php while(have_rows('slides')) : the_row(); $row++; ?>
      <div class="item <?php if($row == 1) { echo 'active'; }?>" style="background-image: url('<?php the_sub_field('image'); ?>');">
        <div class="carousel-overlay"></div>
        <div class="carousel-caption">

          <div class="container">

          <h1><?php the_sub_field('title'); ?></h1>
          <?php the_sub_field('caption'); ?>
          <a href="<?php the_sub_field('link'); ?>" class="btn-outline">View Products</a>

        </div>
        </div>
      </div>
      <?php endwhile; ?>
    </div>

    <?php if($row > 1) : ?>
    <!-- Controls -->
    <a class="left carousel-control" href="#carousel" role="button" data-slide="prev">
      <div class="icon-left-arrow" aria-hidden="true"></div>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#carousel" role="button" data-slide="next">
      <div class="icon-right-arrow" aria-hidden="true"></div>
      <span class="sr-only">Next</span>
    </a>
    <?php endif; ?>

  </div>
  <?php endif; ?>



  <?php if(have_rows('home_boxes')) : $row = 0; ?>

  <!-- HOME BOXES -->
  <div class="container-full">
    <div class="row home-boxes">
      <?php while(have_rows('home_boxes')) : the_row(); $row++; ?>
      <a href="<?php the_sub_field('link'); ?>">
      <div class="col-md-4 home-box home-box-<?php echo $row; ?>" style="background: url('<?php the_sub_field('background'); ?>'); background-size: cover; background-position: center;">
        <div class="home-box-overlay">
          <div class="home-box-inner">
            <h3><?php the_sub_field('title'); ?></h3>
            <?php the_sub_field('text'); ?>
          </div>
        </div>
      </div>
      </a>
      <?php endwhile; ?>
    </div>
  </div>
  <?php endif; ?>


  <!-- INFORMATION SECTION -->
  <div class="info-block">

    <?php $image = get_field('content_image'); if(!empty($image)) : ?>
    <div class="col-md-4 video-img">
      <a href="https://player.vimeo.com/external/309863088.hd.mp4?s=b4dd89a44ee2c2869948a04ba2c9194a92a04a08&profile_id=174" data-lity>
        <img class="img-responsive" src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
      </a>
    </div>
    <?php endif; ?>

    <div class="container info-section">

      <div class="row">

        <div class="col-md-8">

          <?php the_content(); ?>

        </div>

      </div>

    </div>

  </div>


  <?php wp_reset_postdata(); ?>

<?php get_footer(); ?>
