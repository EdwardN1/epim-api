<?php
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price_override', 40);

function woocommerce_template_single_price_override()
{
	global $product;
	$price_excl_tax = wc_get_price_excluding_tax($product);
	$currentTaxIDs= $product->get_category_ids();
	$POACats = get_field('poa_categories','option');
	if($POACats) {
		foreach ($POACats as $POACat) {
			foreach ($currentTaxIDs as $currentTaxID) {
				if ($POACat == $currentTaxID) $price_excl_tax = 0;
			}
		}
	}
	if ((!$price_excl_tax) || ($price_excl_tax == 0)) {
		?>
		<p class="price"><span class="only">POA</span><span class="vat">Call for price</span> </p>
		<?php
	} else {
		?>
		<p class="<?php echo esc_attr(apply_filters('woocommerce_product_price_class', 'price')); ?>">
			<span class="only">ONLY</span>
			<span class="display-price"><?php echo $product->get_price_html(); ?></span>
			<span class="vat ex-tax" style="display: none;">Excl.VAT</span><span class="vat inc-tax"
			                                                                     style="display: none;">Incl.VAT</span>
		</p>
		<?php
	}
}