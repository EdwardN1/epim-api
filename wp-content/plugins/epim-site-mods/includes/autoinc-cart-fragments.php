<?php
/**
 * Show cart contents / total Ajax
 */
add_filter('woocommerce_add_to_cart_fragments', 'epsm_woocommerce_header_add_to_cart_fragment');

function epsm_woocommerce_header_add_to_cart_fragment($fragments)
{
    global $woocommerce;

    ob_start();

    ?>
    <a class="cart-customlocation secondary-colour" href="<?php echo esc_url(wc_get_cart_url()); ?>"
       title="<?php _e('View your shopping cart', 'woothemes'); ?>">
        <span class="cart-total"><?php echo $woocommerce->cart->get_cart_total(); ?></span>
        <span class="cart-items"><?php echo sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count); ?></span>
    </a>
    <?php
    $fragments['a.cart-customlocation'] = ob_get_clean();
    return $fragments;
}

add_shortcode('cartFragments', 'epsm_get_cart_totals');
function epsm_get_cart_totals($atts)
{
    ob_start();
    ?>
    <style>
        .cart-customlocation {

        }
        .cart-customlocation .cart-total {
            font-weight: bold;
            width: 100%;
            display: block;
            text-align: center;
            font-size: 100%;
            line-height: 1;
        }
        .cart-customlocation .cart-items {
            width: 100%;
            display: block;
            text-align: center;
            line-height: 1.2;
            font-size: 85%;
        }
    </style>
    <a class="cart-customlocation secondary-colour" href="<?php echo wc_get_cart_url(); ?>"
       title="<?php _e('View your shopping cart'); ?>">
        <span class="cart-total"><?php echo WC()->cart->get_cart_total(); ?></span>
        <span class="cart-items"><?php echo sprintf(_n('%d item', '%d items', WC()->cart->get_cart_contents_count()), WC()->cart->get_cart_contents_count()); ?></span>
    </a>
    <?php
    $r = ob_get_contents();
    ob_end_clean();
    return $r;
}