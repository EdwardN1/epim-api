<?php
// remove the default action for product title on shop page
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title' );

// add the custom action to show the custom product title
add_action( 'woocommerce_shop_loop_item_title', 'kosnic_woocommerce_template_loop_product_title', 99 );

function kosnic_woocommerce_template_loop_product_title() {
	global $post;

	$product = wc_get_product( $post->ID );

	if ( $product ) {
		$product_attributes = $product->get_attributes();
		$web_description_id = $product_attributes['pa_web-description']['options']['0'];
		if ( $web_description_id ) {
			$web_description = get_term( $web_description_id )->name;
			if ( $web_description ) {
				echo '<h2 class="woocommerce-loop-product__title sku-title">SKU: ' . $product->get_sku(). '<br>' . esc_html( $web_description ) . '</h2>';
			} else {
				echo '<h2 class="woocommerce-loop-product__title sku-title">SKU: ' . $product->get_sku(). '<br>' . esc_html( $product->get_title() ) . '</h2>';
			}
		} else {
			echo '<h2 class="woocommerce-loop-product__title sku-title">SKU: ' . $product->get_sku(). '<br>' . esc_html( $product->get_title() ) . '</h2>';
		}
	}
}