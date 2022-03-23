
<?php
get_header();
get_template_part('templates/breadcrumbs', 'tpl');

$term_id = get_queried_object()->term_id;
$showcase_fields = get_term($term_id, 'type');
$showcase_background_image = get_term_meta(
  $term_id,
  CMB2_PREFIX . 'listing_image',
  true
);
?>

<section class="showcase">
  <div class="showcase__hero"
       style="background-image: url('<?php echo $showcase_background_image; ?>')">
  </div>
  <article class="showcase__inner container wysiwyg">
    <h1 class="title">
      <?php single_term_title(); ?>
    </h1>

    <?php echo apply_filters('the_content', $showcase_fields->description); ?>
  </article>

  <?php
  $related_name = single_term_title('', false);
  $related_link = home_url('/applications');
  $related_args = [
    'post_type' => 'application'
  ];

  if(have_posts()):
  ?>

    <section class="related">
      <div class="related__bar">
        <h3 class="related__title">
          <?php echo $related_name; ?> Articles
        </h3>
        <a href="<?php echo $related_link; ?>">View All Applications</a>
      </div>
      <div class="grid owl-carousel__related owl-carousel">

        <?php
        while(have_posts()): the_post();
          $related_fields = new CMB2Fields(get_the_ID());
          $related_image = wp_get_attachment_image_src($related_fields->field('article_listing_image_id'),
            'article-listing-image'
          )[0];

          $render_args = [
            'title' => get_the_title(),
            'snippet' => get_the_excerpt(),
            'image' => $related_image,
            'link' => get_the_permalink()
          ];

          $related_fields->render('templates/related-carousel-tpl.php', $render_args);
        endwhile;
        ?>

      </div>
    </section>

  <?php endif; ?>

</section>

<?php

get_footer();
