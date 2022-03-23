<?php
get_header();
get_template_part('templates/breadcrumbs', 'tpl');
if(have_posts()):
?>

  <section class="article-list">
    <div class="article-list__grid container">

      <?php
      while(have_posts()): the_post();
        $article_fields = new CMB2Fields(get_the_ID());
        $article_image = wp_get_attachment_image_src(
          $article_fields->field('article_listing_image_id'),
          'article-listing-image'
        )[0];
        ?>

          <article class="grid article-list__item">
            <div class="article-list__image" style="background-image: url('<?php echo $article_image; ?>')"></div>
            <div class="article-list__text">
              <h2 class="article-list__title">
                <a href="<?php the_permalink(); ?>">
                  <?php the_title(); ?>
                </a>
              </h2>
              <time class="article-list__date" datetime="<?php the_time('Y-m-d'); ?>">
                <?php the_time('j/m/Y'); ?>
              </time>
              <p class="article-list__description">
                <?php echo get_the_excerpt(); ?>
              </p>
              <a href="<?php the_permalink(); ?>" class="read-more">
                Read more
              </a>
            </div>
          </article>

        <?php
      endwhile;
      get_template_part('templates/pagination', 'tpl');
      ?>

    </div>
  </section>

<?php
endif;
get_footer();
