<?php if(get_field('masthead')) : ?>
<!-- TOP IMAGE -->
<div class="top-image-push"></div>

<div class="top-image" style="background: url('<?php the_field('masthead'); ?>'); background-size: cover; background-position: center;"></div>
<?php endif; ?>
