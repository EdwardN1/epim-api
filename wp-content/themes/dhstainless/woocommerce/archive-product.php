<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
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
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header( 'shop' ); ?>

    <!-- TOP IMAGE -->
    <div class="top-image-push"></div>

    <!-- <div class="top-image" style="background: url('<?php bloginfo('template_directory'); ?>/img/products-3.jpg'); background-size: cover; background-position: center; background-color: rgba(50,50,50,0.4); background-blend-mode: multiply;"></div> -->

    <?php
     /**
     * Hook: woocommerce_before_main_content.
     *
     * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
     * @hooked woocommerce_breadcrumb - 20
     * @hooked WC_Structured_Data::generate_website_data() - 30
     */
     do_action( 'woocommerce_before_main_content' );
    ?>

    <div class="container">

      <div class="row">

        <div class="product-wrapper">

          <?php get_template_part('partials/sidebar', 'products'); ?>

          <!-- MAIN -->
          <div class="col-md-9">

            <div class="introduction">

              <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
            		<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
                <div class="line-break"></div>
            	<?php endif; ?>

            	<?php
            	/**
            	 * Hook: woocommerce_archive_description.
            	 *
            	 * @hooked woocommerce_taxonomy_archive_description - 10
            	 * @hooked woocommerce_product_archive_description - 10
            	 */
            	do_action( 'woocommerce_archive_description' );
            	?>

            </div>



              <?php get_template_part('partials/content', 'sub-categories'); ?>




          </div>

        </div>

      </div>

    </div>


<?php get_footer( 'shop' );?>
