<?php
/**
 * Single Product Up-Sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/up-sells.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $upsells ) : ?>

<div class="container related">

  <h3><?php esc_html_e( 'Related Products', 'woocommerce' ) ?></h3>

		<?php woocommerce_product_loop_start(); ?>

			<?php foreach ( $upsells as $upsell ) : ?>

				<?php
				 	$post_object = get_post( $upsell->get_id() );

					setup_postdata( $GLOBALS['post'] =& $post_object );?>

          <?php
          global $product;

          // Ensure visibility
          if ( empty( $product ) || ! $product->is_visible() ) {
          	return;
          }
          $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ) );
          ?>
          <div class="col-md-3">
          	<div class="product-list-box">
          	  <div class="product-list-image" style="background: url('<?php echo $thumbnail[0]; ?>'); background-size: cover; background-position: center;"></div>
          	  <div class="product-list-name"><?php echo $product->get_title(); ?></div>
          	  <a href="<?php echo $product->get_permalink(); ?>"><div class="product-list-button">View Product</div></a>
          	</div>
          </div>

			<?php endforeach; ?>

		<?php woocommerce_product_loop_end(); ?>

</div>

<?php endif;

wp_reset_postdata();
