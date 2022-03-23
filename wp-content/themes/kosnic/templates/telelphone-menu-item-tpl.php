<?php
$site_options = new SiteOptions();
$site_telephone_number = $site_options->field('telephone_number');

if($site_telephone_number):
  $formatted_number = preg_replace('/\s+/', '', $site_telephone_number);
?>

<li class="menu-item phone">
  <span class="phone__text">Call Us On : </span>
  <a href="tel:<?php echo $formatted_number; ?>" class="phone__link"><?php echo $site_telephone_number; ?></a>
</li>

<?php endif;
