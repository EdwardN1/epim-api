<table class="container product-table">
  <?php if(empty($products)): ?>
    <tr>
      <td colspan="5">No Accessories available for this product</td>
    </tr>
  <?php else: ?>

    <thead>
      <tr>
        <th>Product Code</th>
        <th>Useful Lumen</th>
        <th>Total Lumen</th>
        <th>Colour Finish</th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach($products as $product_data):
        $product = new ElectrikaAPI\Product($product_data);
      ?>

        <tr>
          <td class="product-table__cell" data-title="Product Code">
            <a class="accessory-link" href="<?php echo $product->page_url(); ?>">
              <img class="product-table__image"
                   src="<?php echo $product->image_url('small', true); ?>"
                   alt="Image for <?php echo $product->name->code; ?>" />
              <span class="product-table__value">
                <?php echo $product->name->code; ?>
              </span>
            </a>
          </td>
          <td class="product-table__cell" data-title="Useful Lumen">
            <span class="product-table__value">
              <?php echo $product->attributes->useful_lumens; ?>
            </span>
          </td>
          <td class="product-table__cell" data-title="Total Lumen">
            <span class="product-table__value">
              <?php echo $product->attributes->total_lumens; ?>
            </span>
          </td>
          <td class="product-table__cell" data-title="Colour Finish">
            <span class="product-table__value">
              <?php echo $product->attributes->colour_finish; ?>
            </span>
          </td>
        </tr>

      <?php endforeach; ?>
    </tbody>
  <?php endif; ?>
</table>
