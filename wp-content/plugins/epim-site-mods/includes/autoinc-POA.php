<?php
add_action('woocommerce_single_product_summary', 'epsm_woocommerce_template_single_price_override', 40);

function epsm_woocommerce_template_single_price_override()
{
    global $product;
    $price_excl_tax = wc_get_price_excluding_tax($product);
    $currentTaxIDs = $product->get_category_ids();
    $poa = false;
    foreach ($currentTaxIDs as $currentTaxID) {
        $epim_api_exclude_from_category_menu = get_term_meta($currentTaxID, 'epim_api_exclude_from_category_menu', true);
        if ($epim_api_exclude_from_category_menu == 'on') {
            $poa = true;
        }
    }
    if ($poa) {
        ?>
        <script>
            jQuery(document).ready(function ($) {
                $('.woocommerce-page div.product p.price .amount').hide();
                $('.woocommerce-page div.product p.price').html('<span class="poa">POA</span>');
                $('.woocommerce div.product form.cart').hide();
            });
        </script>
        <?php
    }
}

add_action('woocommerce_after_shop_loop_item_title', 'epsm_after_shop_loop_item_title',99);

function epsm_after_shop_loop_item_title()
{
    global $product;
    ?>
    <div>epsm_after_shop_loop_item_title</div>
    <script>
        jQuery(document).ready(function ($) {
            $('.woocommerce-page div.product.post-<?php echo $product->get_id();?> p.price .amount').hide();
            $('.woocommerce-page div.product.post-<?php echo $product->get_id();?> p.price').html('<span class="poa">POA</span>');
            $('.woocommerce div.product.post-<?php echo $product->get_id();?> .add-to-basket-loop').hide();
        });
    </script>
    <?php
}

