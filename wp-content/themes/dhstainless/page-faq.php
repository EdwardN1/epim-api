<?php
/*
  Template Name: FAQ
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

          <?php if(have_rows('faqs')) : $faq = 0; ?>
          <!-- FAQS BLOCK -->
          <div class="clearfix faqs-inner">

            <div class="faqs-filters clearfix">

              <div class="faq-filter filter-all active">All</div>

              <div class="faq-filter filter-products">Products</div>

              <div class="faq-filter filter-sectors">Sectors</div>

              <div class="faq-filter filter-troubleshooting">Troubleshooting</div>

            </div>

            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

              <?php while(have_rows('faqs')) : the_row(); $faq++; ?>
              <div class="panel filter-tag-all <?php the_sub_field('type'); ?>">
                <div class="panel-heading collapsed" role="tab" id="heading<?php echo $faq; ?>" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $faq; ?>" aria-expanded="false" aria-controls="collapse<?php echo $faq; ?>">
                      <?php the_sub_field('heading'); ?><div class="panel-close icon-down-arrow"></div>
                </div>
                <div id="collapse<?php echo $faq; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo $faq; ?>">
                  <div class="panel-body">
                    <?php the_sub_field('content'); ?>
                  </div>
                </div>
              </div>
              <?php endwhile; ?>

            </div>

          </div>
          <!-- /.FAQS BLOCK -->
          <?php endif; ?>

        </div>

      </div>

    </div>

  </div>

<?php get_footer(); ?>
