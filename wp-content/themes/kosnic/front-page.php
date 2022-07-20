<?php

get_header();

if(have_posts()): while(have_posts()): the_post();
  $homepage_fields = new CMB2Fields(get_the_ID());
  $homepage_fields->field_prefix = 'homepage_';
  $homepage_fields->render('templates/hero-banner-tpl.php');
	?>
	<div id="the_content">
		<?php
		the_content();
		?>
	</div>
<?php
endwhile; endif;


get_footer();
