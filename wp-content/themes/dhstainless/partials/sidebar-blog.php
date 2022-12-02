<!-- SIDEBAR -->
<div class="col-md-3">

  <div class="product-listing">

    <?php
      $args = array(
        'menu' => 'help',
        'menu_class' => '',
        'container' => '',
        'link_before' => '<div class="yellow-box"></div>',
        'fallback_cb' => false
      );
      wp_nav_menu( $args );
    ?>

  </div>

  <?php if ( is_active_sidebar( 'blog' ) ) : ?>

    <?php dynamic_sidebar( 'blog' ); ?>

  <?php endif; ?>

  <a href="https://player.vimeo.com/external/309863088.hd.mp4?s=b4dd89a44ee2c2869948a04ba2c9194a92a04a08&profile_id=174" data-lity><div class="video-box" style="background: url('<?php bloginfo('template_directory'); ?>/img/video-bg.png'); background-size: cover; background-position: center;">

      <div class="video-play"><img src="<?php bloginfo('template_directory'); ?>/img/play.png"></div>

  </div></a>

  <div class="contact-box" style="background: url('<?php bloginfo('template_directory'); ?>/img/products-2.jpg'); background-size: cover; background-position: center; background-color: rgba(50,50,50,0.7); background-blend-mode: multiply;">

    <h3>Contact Us</h3>
    <p>Enquire about our range of products.</p>
    <a href="<?php bloginfo('url'); ?>/contact/" class="btn-dark">Read More</a>

  </div>

</div>
