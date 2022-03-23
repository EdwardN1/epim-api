<?php
/**
* Template Name: Full Width page
*/
get_header();
get_template_part('templates/breadcrumbs', 'tpl');
$full_width_fields = new CMB2Fields(get_the_ID());
?>

  <section class="full-width">
    <article class="full-width__inner full-width__container wysiwyg">
      <h1 class="title">
        <?php the_title(); ?>
      </h1>

      <?php
      the_content();
      $read_more_content = $full_width_fields->field('read_more_content');
      if($read_more_content):
      ?>

        <a href="#" class="read-more js-read-more">Read more</a>
        <div class="read-more__content js-read-more-content">

          <?php echo $full_width_fields->format_content($read_more_content); ?>

        </div>

      <?php endif; ?>

    </article>
  </section>

<?php get_footer();
