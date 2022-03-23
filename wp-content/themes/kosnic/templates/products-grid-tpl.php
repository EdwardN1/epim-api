<?php
	$product_item_css_class = 'product-item__outer';
	$product_css_classes = $search === 'true' ? "$product_item_css_class col-3" : "$product_item_css_class col-4";

	foreach ($products as $product_data)
	{
		$product = new ElectrikaAPI\Product($product_data);
?>
		<a href="<?= $product->page_url(); ?>" class="<?= $product_css_classes; ?>">
			<div class="product-item__inner" style="min-height: 420px;">
				<div class="product-item__image" style="<?= $product->background_image_url('small', true); ?>">
				</div>

				<h2 class="product-item__title"><?= $product->attributes->description->short; ?><br /><br /><?= $product->name->code; ?></h2>
				<div class="product-item__overlay">
					<h2 class="product-item__overlay-title"><?= $product->name->code; ?></h2>
					<p><?= $product->attributes->description->short; ?></p>
				</div>
			</div>
		</a>
<?php
	}
