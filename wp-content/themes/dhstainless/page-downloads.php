<?php
/*
  Template Name: Downloads
*/
?>
<?php get_header(); ?>


  <?php get_template_part('partials/content', 'masthead'); ?>


  <?php get_template_part('partials/content', 'breadcrumb'); ?>


  <div class="container">

    <div class="row">

      <div class="article-wrapper">

        <?php get_template_part('partials/sidebar', 'help'); ?>


        <!-- MAIN -->
        <div class="col-md-9">


          <!-- introduction block -->
          <div class="introduction">

            <h1><?php the_title(); ?></h1>
            <div class="line-break"></div>
            <?php the_content(); ?>

          </div>
          <!-- /.introduction block -->


          <?php if(have_rows('downloads')) : ?>
          <!-- downloads -->
          <div class="downloads clearfix">

            <div class="downloads-filters clearfix">

              <div class="download-filter filter-all active">All</div>
              <div class="download-filter filter-metric">Metric</div>
              <div class="download-filter filter-iso">ISO</div>
              <div class="download-filter filter-ansi">ANSI</div>
              <div class="download-filter filter-hygienic">Hygienic</div>
              <div class="download-filter filter-press">Press</div>
              <div class="download-filter filter-valves">Valves</div>
              <div class="download-filter filter-extruded">Extruded Headers</div>

            </div>

            <div class="row download-wrapper">

              <?php while(have_rows('downloads')) : the_row(); ?>
              <div class="col-md-4 filter-tag-all <?php the_sub_field('type'); ?>">
                <a href="<?php the_sub_field('file'); ?>" target="_blank"><div class="download-box">
                  <img src="<?php bloginfo('template_directory'); ?>/img/download.png"><?php the_sub_field('file_name'); ?>
                </div></a>
              </div>
              <?php endwhile; ?>

            </div>

          </div>
          <!-- /.downloads -->
          <?php endif; ?>

        </div>

      </div>

    </div>

  </div>

<?php get_footer(); ?>
