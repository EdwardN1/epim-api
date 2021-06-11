<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ERF
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="stylesheet" href="https://use.typekit.net/avl0obs.css">

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-40069567-1"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());
	  gtag('config', 'UA-40069567-1'); 
	</script>

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'erf-co-uk' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="above-header">
			<div class="row">
				<div class="six columns">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'above-header',
						)
					);
					?>
				</div>
				<div class="six columns text-right">
					<div class="end-actions">
						<a href="tel:<?php echo esc_html( str_replace( ' ', '', get_field( 'telephone_number', 'option' ) )); ?>">
							<i class="fa fa-phone"></i> <?php echo esc_html( get_field( 'telephone_number', 'option' ) ); ?>
						</a>

						<div class="enable-vat">
							<span class="inc-vat">inc VAT.</span>

							<?php
							$class = ( $_SESSION['show_vat'] == 'true' ) ? '' : 'active';
							$checked = ( $_SESSION['show_vat'] == 'true' ) ? '' : 'checked';
							?>

							<div class="switch">
								<label>
									<input type="checkbox" <?php echo $checked;?>>
									<div class="slider round <?php echo $class;?>"></div>
								</label>
							</div>

							<span class="ex-vat">ex VAT.</span>
						</div>

						<a class="contact-us" href="<?php echo get_permalink(50457);?>">Contact us</a>
					</div>
				</div>
			</div>
		</div>

		<div class="middle-bar">
			<div class="row">
				<div class="twelve columns">

					<div class="site-branding">
						<?php the_custom_logo(); ?>
					</div>

					<div class="search-wrapper">
						<?php /*
						<form action="<?php echo site_url();?>" method="GET">
							<input type="text" name="s" placeholder="Looking for something?">
							<button><i class="fa fa-search"></i></button>
						</form>
						*/ ?>
						<?php echo do_shortcode( '[aws_search_form]' ); ?>
					</div>

					<div class="user-actions">
						<div class="row">

							<div class="four columns">
								<a href="<?php echo get_permalink( 445 );?>">
									<i class="fa fa-user"></i>
									<?php if( !is_user_logged_in() ): ?>
										<span>Login/Register</span>
									<?php else: ?>
										<span>My Account</span>
									<?php endif; ?>
								</a>
							</div>

							<div class="four columns">
								<a href="<?php echo get_permalink( 170 );?>">
									<i class="fa fa-map-marker"></i>
									<span>Find a Branch</span>
								</a>
							</div>

							<div class="four columns">
								<button class="show_basket">
									<i class="fa fa-shopping-basket"></i>
									<span>Basket (<span class="cart-items"><?php get_cart_item_count(); ?></span>)</span>
								</button>
							</div>

						</div>
					</div>

					<div class="basket-contents">

						<div class="exit-basket"><i class="fa fa-times"></i></div>
						
						<?php

						$cart = WC()->cart->get_cart_contents();

						foreach( $cart as $key => $item ):
							$product = wc_get_product( $item['product_id'] ); ?>
							<div class="row cart-item">
								<div class="two columns">
									<?php echo get_the_post_thumbnail( $item['product_id'] );?>
								</div>
								<div class="six columns">
									<a class="product-title" href="<?php echo get_permalink( $item['product_id'] );?>"><?php echo $product->get_name();?></a>
									<span class="sku"><strong>Item Number: </strong><?php echo $product->get_sku(); ?></span>
									<span class="price-ea"><?php echo wc_price( $product->get_price() ); ?></span>
								</div>
								<div class="two columns">
									<div class="align-center">
										<input type="number" value="<?php echo $item['quantity'];?>">
									</div>
								</div>
								<div class="two columns">
									<div class="align-center">
										<span class="total">£<?php echo $item['line_total']; ?></span>
									</div>
								</div>
							</div>
						<?php endforeach; ?>

						<div class="bottom-totals">
							<div class="row">
								<div class="twelve columns">
									<span class="product-count"><?php get_cart_item_count();?> Total Products</span>
								</div>
								<div class="six columns">
									<h2>Subtotal</h2>
								</div>
								<div class="six columns">
									<span class="cart-total">£<?php echo WC()->cart->get_cart_contents_total(); ?></span>
								
									<ul>
										<li><a class="cart" href="<?php echo WC()->cart->get_cart_url();?>">View Basket</a></li>
										<li><a class="checkout" href="<?php echo WC()->cart->get_checkout_url();?>">Checkout</a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>

		<div class="menu-bar">
			<div class="row">
				<div class="twelve columns">
					<nav id="site-navigation" class="main-navigation">
						<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'erf-co-uk' ); ?></button>
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'menu-1',
								'menu_id'        => 'primary-menu',
							)
						);
						?>
					</nav><!-- #site-navigation -->
				</div>
			</div>
		</div>

	</header><!-- #masthead -->

	<?php get_template_part('template-parts/shared/mobile-header'); ?>
	<?php get_template_part('template-parts/shared/ctas');?>

	<img src="<?php echo get_template_directory_uri();?>/images/ajax-loader.gif" class="erf-loader">
