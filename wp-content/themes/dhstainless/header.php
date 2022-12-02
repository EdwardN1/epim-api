<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <!--[if IE]>
    <meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php wp_title(); ?></title>
    <link rel="apple-touch-icon" href="apple-touch-icon.png">

    <?php wp_head(); ?>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,700,800" rel="stylesheet">
    <!-- <link href="https://file.myfontastic.com/5EJSRQG2uvUpnjTEZWgsJE/icons.css" rel="stylesheet"> -->

    <link rel='shortcut icon' href='<?php bloginfo('url'); ?>/favicon.png' type='image/x-icon'/>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-6747796-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'UA-6747796-1');
    </script>

    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.css"/>
    <script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.js"></script>
    <script>
        window.addEventListener("load", function () {
            window.cookieconsent.initialise({
                "palette": {
                    "popup": {
                        "background": "#000"
                    },
                    "button": {
                        "background": "#f8be12"
                    }
                },
                "content": {
                    "message": "This website uses cookies. To find out more, view our privacy policy.",
                    "link": "View",
                    "href": "https://www.dhstainless.co.uk/privacy-policy/"
                }
            })
        });
    </script>

</head>

<body <?php body_class(); ?>>

<!-- MOBILE MENU -->

<div class="mobile-menu">
    <nav class="container">
        <div class="row">
            <ul class="mobile-menu-list">
                <li><h4><a href="<?php bloginfo('url'); ?>/">Home</a></h4></li>
                <li>
                    <h4 class="sub-menu"><a href="<?php bloginfo('url'); ?>/products/">Products</a> <i class="icon-chevron-down closed"></i></h4>
                    <?php
                    $args = array(
                        'menu' => 'products',
                        'menu_class' => 'closed',
                        'container' => '',
                        'link_before' => '',
                        'fallback_cb' => false
                    );
                    wp_nav_menu($args);
                    ?>
                </li>
                <li>
                    <h4 class="sub-menu"><a href="<?php bloginfo('url'); ?>/sectors/">Sectors</a> <i class="icon-chevron-down closed"></i></h4>
                    <?php
                    $args = array(
                        'menu' => 'sectors',
                        'menu_class' => 'closed',
                        'container' => '',
                        'link_before' => '',
                        'fallback_cb' => false
                    );
                    wp_nav_menu($args);
                    ?>
                </li>
                <li>
                    <h4 class="sub-menu"><a href="<?php bloginfo('url'); ?>/about-us">About Us</a> <i class="icon-chevron-down closed"></i></h4>
                    <?php
                    $args = array(
                        'menu' => 'help',
                        'menu_class' => 'closed',
                        'container' => '',
                        'link_before' => '',
                        'fallback_cb' => false
                    );
                    wp_nav_menu($args);
                    ?>
                </li>
                <!-- <li><h4><a href="<?php bloginfo('url'); ?>/quote-request">Quote Request</a></h4></li> -->
                <li><h4><a href="<?php bloginfo('url'); ?>/contact/">Contact</a></h4></li>
                <!-- <li><h4><a href="#"><div class="icon-icon2 icon"></div>Brochure Request</a></h4></li> -->
            </ul>
        </div>
    </nav>
</div>

<div class="wrapper">

    <!-- HEADER -->

    <div class="header dark">

        <div class="mobile-menu-button">
            <div class="hamburger" id="hamburger">
                <span class="line"></span>
                <span class="line"></span>
                <span class="line"></span>
            </div>
        </div>

        <div class="header-bottom">
            <div class="container">
                <style>
                    .header.dark .logo img.light {
                        display: block;
                    }
                    .header.dark.scrolled .logo img.light {
                        display: none;
                    }
                    .header.dark .logo img.dark {
                        display: none;
                    }
                    .header.dark.scrolled .logo img.dark {
                        display: block;
                    }
                </style>
                <a href="<?php bloginfo('url'); ?>">
                    <div class="logo" style="background: none; height: 85px; width: 400px; top: 10px;">
                        <img src="/wp-content/themes/dhstainless/img/new-logo.svg" class="light">
                        <img src="/wp-content/themes/dhstainless/img/new-logo-dark.svg" class="dark">
                    </div>
                </a>
                <div class="main-nav">
                    <nav>
                        <ul>
                    <span class="hide-xs hide-sm"><li class="has-dropdown"><a href="<?php bloginfo('url'); ?>/products/">Products</a><div class="icon-chevron-down"></div>
                      <div class="dropdown">
                        <div class="container">
                          <div class="row">
                            <div class="col-md-4 dropdown-col-1">
                              <h4>Products</h4>
                              <div class="line-break"></div>
                              <p>Stainless steel pipe, tube, fittings, flanges and valves.</p>
                              <a href="<?php bloginfo('url'); ?>/products/" class="btn-outline">View All Products</a>
                            </div>
                            <div class="col-md-4 dropdown-col-2">
                              <ul>
                                <li><div class="icon-chevron-right icon"></div><a href="<?php bloginfo('url'); ?>/products/tru-bore-metric-iso/" class="underline">Tru-Bore Metric &amp; ISO</a></li>
                                <li><div class="icon-chevron-right icon"></div><a href="<?php bloginfo('url'); ?>/products/ansi/" class="underline">ANSI</a></li>
                                <li><div class="icon-chevron-right icon"></div><a href="<?php bloginfo('url'); ?>/products/hygienic/" class="underline">Hygenic</a></li>
                                <li><div class="icon-chevron-right icon"></div><a href="<?php bloginfo('url'); ?>/products/press-crimp-fittings/" class="underline">Press/Crimp Fittings</a></li>
                                <li><div class="icon-chevron-right icon"></div><a href="<?php bloginfo('url'); ?>/products/valves/" class="underline">Valves</a></li>
                                <li><div class="icon-chevron-right icon"></div><a href="<?php bloginfo('url'); ?>/products/extruded-branch-pipe-headers/" class="underline">Extruded Branch Pipe Headers</a></li>
                                <li><div class="icon-chevron-right icon"></div><a href="<?php bloginfo('url'); ?>/products/bsp-threaded-fittings/" class="underline">BSP Threaded Fittings</a></li>
                                <li><div class="icon-chevron-right icon"></div><a href="<?php bloginfo('url'); ?>/products/pipe-clips-u-bolts/" class="underline">Pipe Clips and U-Bolts</a></li>
                              </ul>
                            </div>
                            <div class="col-md-4 dropdown-col-3">
                              <div class="interest-box">
                                <div class="interested">Enquire about our range of products.</div>
                                <div class="quote-button"><a href="<?php bloginfo('url'); ?>/contact/" class="btn-outline">Contact Us</a></div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </li>

                    <li class="has-dropdown"><a href="<?php bloginfo('url'); ?>/sectors/">Sectors</a><div class="icon-chevron-down"></div>
                      <div class="dropdown">
                        <div class="container">
                          <div class="row">
                            <div class="col-md-4 dropdown-col-1">
                              <h4>Sectors</h4>
                              <div class="line-break"></div>
                              <p>DH Stainless supply on a regular basis to the following market sectors.</p>
                              <a href="<?php bloginfo('url'); ?>/sectors/" class="btn-outline">View All Sectors</a>
                            </div>
                            <div class="col-md-4 dropdown-col-2">
                              <ul>
                                <li><div class="icon-chevron-right icon"></div><a href="<?php bloginfo('url'); ?>/sectors/renewable-energy/" class="underline">Renewable Energy</a></li>
                                <li><div class="icon-chevron-right icon"></div><a href="<?php bloginfo('url'); ?>/sectors/nuclear/" class="underline">Nuclear</a></li>
                                <li><div class="icon-chevron-right icon"></div><a href="<?php bloginfo('url'); ?>/sectors/building-services/" class="underline">Building Servcies</a></li>
                                <li><div class="icon-chevron-right icon"></div><a href="<?php bloginfo('url'); ?>/sectors/food-drink/" class="underline">Food & Drink</a></li>
                                <li><div class="icon-chevron-right icon"></div><a href="<?php bloginfo('url'); ?>/sectors/chemical-pharmaceutical/" class="underline">Chemcial Pharmaceutical</a></li>
                                <li><div class="icon-chevron-right icon"></div><a href="<?php bloginfo('url'); ?>/sectors/water/" class="underline">Water</a></li>
                                <li><div class="icon-chevron-right icon"></div><a href="<?php bloginfo('url'); ?>/sectors/process-industries/" class="underline">Process Industries</a></li>
                              </ul>
                            </div>
                            <div class="col-md-4 dropdown-col-3">
                              <div class="interest-box">
                                <div class="interested">Enquire about our range of products.</div>
                                <div class="quote-button"><a href="<?php bloginfo('url'); ?>/contact/" class="btn-outline">Contact Us</a></div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </li>

                    <li class="has-dropdown"><a href="<?php bloginfo('url'); ?>/about-us/">About Us</a><div class="icon-chevron-down"></div>
                      <div class="dropdown">
                        <div class="container">
                          <div class="row">
                            <div class="col-md-4 dropdown-col-1">
                              <h4>About Us</h4>
                              <div class="line-break"></div>
                              <p>DH Stainless and our sister company, DH Press Fit, are leading UK stainless steel tube stockists.</p>
                              <a href="<?php bloginfo('url'); ?>/about-us/" class="btn-outline">Find Out More</a>
                            </div>
                            <div class="col-md-4 dropdown-col-2">
                              <ul>
                                <li><div class="icon-chevron-right icon"></div><a href="<?php bloginfo('url'); ?>/about-us/" class="underline">About Us</a></li>
                                <li><div class="icon-chevron-right icon"></div><a href="<?php bloginfo('url'); ?>/faqs/" class="underline">FAQs</a></li>
                                <li><div class="icon-chevron-right icon"></div><a href="<?php bloginfo('url'); ?>/downloads/" class="underline">Downloads</a></li>
                                <li><div class="icon-chevron-right icon"></div><a href="<?php bloginfo('url'); ?>/gallery/" class="underline">Gallery</a></li>
                              </ul>
                            </div>
                            <div class="col-md-4 dropdown-col-3">
                              <div class="interest-box">
                                <div class="interested">Enquire about our range of products.</div>
                                <div class="quote-button"><a href="<?php bloginfo('url'); ?>/contact/" class="btn-outline">Contact Us</a></div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </li>

                        <!-- <li><a href="<?php bloginfo('url'); ?>/quote-request">Quote Request</a></li> -->
                    <li><a href="<?php bloginfo('url'); ?>/contact/">Contact</a></li></span>
                            <!-- <li class="search-toggle"><a href="#" class="search-click"><div class="icon-search-icon search-icon"></div><div class="close-icon icon-cancel"></div></a></li> -->
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <div class="search-box">
            <div class="container">
                <form role="search" method="get" id="header-searchform" action="<?php echo esc_url(home_url('/')); ?>">
                    <div>
                        <input type="search" class="search-field" placeholder="<?php echo esc_attr_x('Search', 'placeholder'); ?>" value="<?php echo get_search_query(); ?>" name="s"/>
                        <input type="submit" value="" class="search-button"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
