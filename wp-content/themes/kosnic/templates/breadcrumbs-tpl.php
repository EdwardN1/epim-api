<?php

if(is_page_template('product-listing.php') ||
  get_query_var('product') === 'true'):
?>

  <nav class="breadcrumbs js-breadcrumbs"></nav>

<?php
elseif(function_exists('yoast_breadcrumb')):
  yoast_breadcrumb('<nav class="breadcrumbs"><ul class="container">','</ul></nav>');
endif;

