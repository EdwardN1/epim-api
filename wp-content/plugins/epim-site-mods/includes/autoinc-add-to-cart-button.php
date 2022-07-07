<?php
add_action('woocommerce_after_shop_loop_item', 'epsm_add_to_cart_button', 10);

function epsm_add_to_cart_button()
{
    ?>
    <div class="add-to-basket-loop">
        <?php
        echo do_shortcode('[add_to_cart id="' . get_the_ID() . '" show_price="FALSE" style="border: none; padding:0;"]');
        ?>
    </div>
    <?php
}

add_action('woocommerce_after_shop_loop_item_title', 'epsm_display_sku', 10);

function epsm_display_sku()
{
    global $product;
    ?>
    <div class="product-sku">
        SKU: <?php echo $product->get_sku();?>
    </div>
    <?php
}