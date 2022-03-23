<?php

get_header();
get_template_part('templates/breadcrumbs', 'tpl');
?>

<section class="page-intro">
  <h1 class="page-intro__title">404</h1>
</section>
<section class="page-404-container">
  <div class="wysiwyg">
    <h2>Sorry, we couldn't find what you were looking for...</h2>
    <p>
      <a href="<?php echo home_url(); ?>">Click here</a> to return to the homepage,
      use the main navigation bar above or the product search field to navigate
      elsewhere on our site.
    </p>
    <p>
      Still can't find what you're looking for?
      <a href="<?php echo home_url('/contact-us'); ?>">Click here</a>
      to go to our contact page, get in touch and we'll help you find out more.
    </p>
  </div>
</section>

<?php get_footer();
