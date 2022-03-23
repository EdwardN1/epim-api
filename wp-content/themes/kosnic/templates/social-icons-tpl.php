<?php
$site_options = new SiteOptions();
$twitter_url = $site_options->field('twitter_url');
$facebook_url = $site_options->field('facebook_url');
$instagram_url = $site_options->field('instagram_url');
$pinterest_url = $site_options->field('pinterest_url');
$linkedin_url = $site_options->field('linkedin_url');
$social_array = [
  'twitter' => $twitter_url,
  'facebook' => $facebook_url,
  'instagram' => $instagram_url,
  'pinterest' => $pinterest_url,
  'linkedin' => $linkedin_url
];
$social_icons = array_filter($social_array);

if(!empty($social_icons)):
?>

<div class="social-links col-4">
  <ul class="social-links__items">
    <li class="social-links__item social-links__title">SOCIAL</li>

    <?php foreach($social_icons as $social => $url): ?>

      <li class="social-links__item">
        <a href="<?php echo $url; ?>" target="_blank">
          <i class="fa fa-<?php echo $social; ?>"></i>
        </a>
      </li>

    <?php endforeach; ?>

  </ul>
</div>

<?php endif; ?>
