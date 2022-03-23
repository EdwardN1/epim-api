<div class="newsletter-signup col-8">
  <?php
  $site_options = new SiteOptions();
  $newsletter_shortcode = $site_options->field('mailchimp_newsletter');

  if(!empty($newsletter_shortcode)):
  ?>

    <!-- <p class="newsletter-signup__text">Sign up to our newsletter</p>
    <div class="newsletter-form">
      <?php // echo do_shortcode($newsletter_shortcode); ?>
    </div> -->

  <?php endif; ?>
</div>
