<?php
/*
  Template Name: Sectors - Child
*/
?>
<?php get_header(); ?>


  <?php get_template_part('partials/content', 'masthead'); ?>


  <?php get_template_part('partials/content', 'breadcrumb'); ?>


  <div class="container">

    <div class="row">

      <div class="article-wrapper">


        <?php get_template_part('partials/sidebar', 'sectors'); ?>


        <!-- MAIN -->
        <div class="col-md-9">


          <!-- introduction -->
          <div class="introduction">

            <h1><?php the_title(); ?></h1>

            <div class="line-break"></div>

            <?php the_content(); ?>

          </div>
          <!-- /.introduction -->


          <!-- 
          <div class="introduction" style="margin-top: 40px;">

            <h2>What We Offer</h2>
            <div class="line-break"></div>

          </div>
          

          <?php if(have_rows('tabs')) : $tab = 0; $panel = 0; ?>
        
          <div class="row tabbed-panels">

            <div>

              <ul class="nav nav-tabs" role="tablist">
                <?php while(have_rows('tabs')) : the_row(); $tab++; ?>
                <li role="presentation" class="<?php if($tab == 1) { echo 'active'; }?>"><a href="#<?php echo $tab; ?>" aria-controls="home" role="tab" data-toggle="tab"><?php the_sub_field('tab_name'); ?></a></li>
                <?php endwhile; ?>
              </ul>

           
              <div class="tab-content">
                <?php while(have_rows('tabs')) : the_row(); $panel++; ?>
                <div role="tabpanel" class="tab-pane fade <?php if($panel == 1) { echo 'in active'; } ?>" id="<?php echo $panel; ?>">
                  <h2><?php the_sub_field('tab_name'); ?></h2>
                  <div class="line-break"></div>
                  <?php the_sub_field('content'); ?>
                </div>
                <?php endwhile; ?>
              </div>
          

            </div>

          </div>
       
          <?php endif; ?>

    -->
          <?php if(get_field('sub_content')) : ?>
          <!-- Sub Content -->
          <div class="introduction">

            <h2><?php the_field('sub_content_heading'); ?></h2>

            <div class="line-break"></div>

            <?php the_field('sub_content'); ?>

            <?php if(get_field('sub_content_link')) : ?><a href="<?php the_field('sub_content_link'); ?>" class="underlined" style="margin-bottom: 50px; display: inline-block;"><?php the_field('sub_content_link_text'); ?></a><?php endif; ?>

          </div>
          <!-- /.Sub Content -->
          <?php endif; ?>



          <!--<div id="case-studies" class="carousel slide" data-ride="carousel">

     
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

            </div>-->



          <?php
          $posts = get_field('related_posts');
          if( $posts ) : $row = 0; ?>
          <!-- RELATED POSTS -->
          <div class="introduction" style="margin-top: 40px;">

            <h2>Related Posts</h2>
            <div class="line-break"></div>

          </div>

          <div class="row news-links" style="margin-bottom: 50px;">

            <?php foreach( $posts as $post) : $row++; ?>
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
          <!-- /.RELATED POSTS -->
          <?php wp_reset_postdata(); ?>
          <?php endif; ?>


        </div>

      </div>

    </div>

  </div>

<?php get_footer(); ?>
