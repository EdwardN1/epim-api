<?php

$parentid = get_queried_object_id();

$args = array(
  'parent' => $parentid,
  'hide_empty' => FALSE
);

$terms = get_terms( 'product_cat', $args );

if ( $terms ) : ?>



    <?php foreach ( $terms as $term ) : ?>

      <?php
        $category_thumbnail = get_woocommerce_term_meta($term->term_id, 'thumbnail_id', true);
        $image = wp_get_attachment_url($category_thumbnail);
      ?>

      <div class="col-md-4">
      	<div class="product-list-box">
      	  <a href="<?php echo  esc_url( get_term_link( $term ) ); ?>" >

      			<img src="<?php echo $image; ?>" class="img-responsive">

      		</a>
          <div class="product-list-name text-center"><?php echo $term->name; ?></div>
          <a href="<?php echo  esc_url( get_term_link( $term ) ); ?>" ><div class="product-list-button">View Products</div></a>
        </div>
      </div>


    <?php endforeach; ?>



<?php endif; ?>
