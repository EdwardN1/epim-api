<!doctype html>
<html lang="en" class="no-js">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico">
    <?php wp_head(); ?>
    <?php
/*      if (date('n') == 12) {
          */?><!--<link rel="stylesheet" href="https://kosnic.com/wp-content/themes/kosnic/public/css/xmas.css" type="text/css" media="all" />--><?php
/*      }
    */?>
	  <style>
		  .wp-block-gallery.is-cropped .blocks-gallery-item img {
			flex: initial;
		}
	  </style>
  </head>
  <body <?php body_class(); ?>>

    <section class="page-header grid">
      <div class="col-5">
        <a href="<?php echo get_site_url(); ?>" class="page-header__logo"></a>
      </div>

      <nav class="page-header__mobile-toggle col-7">
        <div class="page-header__mobile-menu-icons">
          <div class="page-header__mobile-menu-icon js-page-header-search"><i class="fa fa-search"></i></div>
          <div class="page-header__mobile-menu-icon page-header__mobile-menu-bars js-page-header-menu">
            <div class="page-header__mobile-menu-bar"></div>
          </div>
        </div>
      </nav>

      <nav class="page-header__nav col-7">
        <ul class="page-header__menu">

          <?php
          wp_nav_menu([
            'theme_location' => 'top_right_nav',
            'container' => false,
            'items_wrap' => '%3$s'
          ]);
          get_template_part('templates/telelphone-menu-item', 'tpl');
          ?>

        </ul>

      </nav>
    </section>
    <div class="ajax-spinner">
        <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
    </div>

    <?php get_template_part('templates/nav-menu', 'tpl'); ?>
