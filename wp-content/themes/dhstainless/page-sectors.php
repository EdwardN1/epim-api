<?php
/*
  Template Name: Sectors - Parent
*/
?>
<?php get_header(); ?>


  <?php get_template_part('partials/content', 'masthead'); ?>


  <?php get_template_part('partials/content', 'breadcrumb'); ?>


  <div class="container">

    <div class="row">

      <!-- MAIN -->
      <div class="col-md-12">


      <div class="introduction intro-full">

          <h1><?php the_title(); ?></h1>

          <div class="line-break"></div>

          <?php the_content(); ?>

        </div>

        <?php
          $args = array(
            'post_type' => 'page',
            'post_parent' => $post->ID,
            'orderby' => 'menu_order',
            'order' => 'ASC'
          );
          $sectors = new WP_Query($args);
        ?>



        <!-- sectors -->
        <div class="clearfix products-inner">
          <?php if($sectors->have_posts()) : ?>

            <?php while($sectors->have_posts()) : $sectors->the_post(); ?>
            <?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' ); ?>

              <div class="col-md-4 col-sm-4 product-list-box">

                <a href="<?php the_permalink(); ?>">

                  <img src="<?php echo $thumb[0]; ?>" class="img-responsive">
                </a>

                  <div class="product-list-name text-center"><?php the_title(); ?></div>

                  <a href="<?php the_permalink(); ?>"><div class="product-list-button">View Sector</div></a>


              </div>

            <?php endwhile;  ?>

          <?php else : ?>
            <p>Currently, no sectors available.</p>
          <?php endif; ?>
        </div>
        <!-- /.sectors -->

      </div>

    </div>

  </div>

<?php get_footer(); ?>
