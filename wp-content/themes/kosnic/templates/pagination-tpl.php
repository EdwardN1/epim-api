<section class="container pagination">

    <?php
    if(function_exists('wp_pagenavi')):
      wp_pagenavi();
    endif;
    ?>

</section>
