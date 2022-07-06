<?php
add_action('woocommerce_after_shop_loop_item', 'epsm_add_to_cart_button', 10);

function epsm_add_to_cart_button()
{
    ?>
    <a href="<?php the_permalink(); ?>" class="more">More info</a>
    <?php
    echo do_shortcode('[add_to_cart id="'.get_the_ID().'" show_price="FALSE" style="border: none; padding:0;"]');
}