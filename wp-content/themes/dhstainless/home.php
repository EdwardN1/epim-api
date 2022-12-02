<?php get_header(); ?>


  <!-- TOP IMAGE -->
  <div class="top-image-push"></div>

  <div class="top-image" style="background: url('<?php bloginfo('template_directory'); ?>/img/products-3.jpg'); background-size: cover; background-position: center; background-color: rgba(50,50,50,0.4); background-blend-mode: multiply;"></div>


  <?php get_template_part('partials/content', 'breadcrumb'); ?>


  <div class="container">

    <div class="row">

      <div class="article-wrapper">

        <?php get_template_part('partials/sidebar', 'blog'); ?>

        <!-- MAIN -->
        <div class="col-md-9">

          <div class="introduction">

            <h1>Blog</h1>
            <div class="line-break"></div>

          </div>




            <?php if(have_posts()) : $count = 0; ?>

              <?php while(have_posts()) : the_post(); $count++; ?>

                <?php if($count == 1) : ?>
                <div class="row news-links">
                  <div class="col-md-12 news-link">
                    <div class="news-link-image" style="background: url('<?php bloginfo('template_directory'); ?>/img/products-2.jpg'); background-size: cover; background-position: center;"></div>
                    <h4><?php the_title(); ?></h4>
                    <span><?php the_date(); ?></span>
                    <?php custom_excerpt(30, ' ...'); ?>
                    <a href="<?php the_permalink(); ?>" class="btn-outline">Read More</a>
                  </div>
                </div>

                <div class="row news-links">
                <?php else : ?>
                <?php get_template_part('partials/content', 'post'); ?>
                <?php endif; ?>


              <?php endwhile; ?>
                </div>

            <?php else : ?>

              <p>Please add a new blog post</p>

            <?php endif; ?>

          </div>


        </div>

      </div>

    </div>

  </div>

<?php get_footer(); ?>
