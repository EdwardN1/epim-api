<?php

foreach($products as $product_data):
  $product = new ElectrikaAPI\Product($product_data);
  ?>

  <a href="<?php echo $product->page_url(); ?>" class="product-item__outer col-3">
    <div class="product-item__inner">
      <div class="product-item__image"
           style="<?php echo $product->background_image_url('small', true); ?>">
      </div>
      <h2 class="product-item__title"><?php echo $product->name->code; ?></h2>
      <div class="product-item__overlay">
        <h2 class="product-item__overlay-title">
          <?php echo $product->name->code; ?>
        </h2>
        <p><?php echo $product->attributes->description->short; ?></p>
      </div>
    </div>
  </a>

<?php endforeach;
