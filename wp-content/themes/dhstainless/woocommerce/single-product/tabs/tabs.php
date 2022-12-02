<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $tabs ) ) : $link = 0; $panel = 0; ?>
<div class="container-full" style="background: url('<?php bloginfo('template_directory'); ?>/img/sectors.jpg'); background-size: cover; background-position: center;">

  <div class="container">

    <div class="row tabbed-panels tabbed-panels-2">

    	<div class="woocommerce-tabs wc-tabs-wrapper">

        <ul class="nav nav-tabs" role="tablist">

          <?php if(get_field('technical_information')) : ?>
            <li class="active" role="presentation"><a href="#tab-technical" aria-controls="profile" role="tab" data-toggle="tab">Technical Information</a></li>
          <?php endif; ?>
    		</ul>
        <!-- Tab panes -->
        <div class="tab-content">

        <?php if(get_field('technical_information')) : ?>
          <div role="tabpanel" class="tab-pane fade in active" id="tab-technical">
						<div class="product-data">
            <?php the_field('technical_information'); ?>
						</div>
          </div>
        <?php endif; ?>
        </div>

    	</div>

    </div>

  </div>

</div>

<?php endif; ?>


<!-- HOME BOXES -->
<div class="container-full">
  <div class="row home-boxes">
    <div class="col-md-4 home-box home-box-1" style="background: url('<?php bloginfo('template_directory'); ?>/img/home-box-1.jpg'); background-size: cover; background-position: center;">
      <div class="home-box-overlay">
        <div class="home-box-inner">
          <h3>Expert technical <br>knowledge</h3>
          <p>Contact us for expert technical advice with applications, grade selection and product working pressures.</p>
        </div>
      </div>
    </div>
    <div class="col-md-4 home-box home-box-2" style="background: url('<?php bloginfo('template_directory'); ?>/img/home-box-2.jpg'); background-size: cover; background-position: center;">
      <div class="home-box-overlay">
        <div class="home-box-inner">
          <h3>Wide range of <br>products</h3>
          <p>DH Stainless stock the widest range of stainless steel piping and tubular products.</p>
        </div>
      </div>
    </div>
    <div class="col-md-4 home-box home-box-3" style="background: url('<?php bloginfo('template_directory'); ?>/img/home-box-3.jpg'); background-size: cover; background-position: center;">
      <div class="home-box-overlay">
        <div class="home-box-inner">
          <h3>Three stock <br>centres</h3>
          <p>DH Stainless have our main distribution stock hub at our head office in North West England as well as stock branches in Scotland and South East England.</p>
        </div>
      </div>
    </div>
  </div>
</div>
