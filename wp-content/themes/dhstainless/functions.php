<?php

// Load the Theme CSS files
function theme_styles() {

    global $wp_styles;

    wp_enqueue_style( 'bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' );
    wp_enqueue_style( 'slick', get_template_directory_uri() . '/css/slick.css' );
    wp_enqueue_style( 'slick-theme', get_template_directory_uri() . '/css/slick-theme.css' );
    wp_enqueue_style( 'styles', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style(
        'wpa-print-style',
        get_stylesheet_directory_uri() . '/css/print.css',
        array(),
        '20190305', 
        'print' // print styles only
    );

}

add_action( 'wp_enqueue_scripts', 'theme_styles' );

// Load the Theme JS
function theme_js() {

    global $wp_scripts;

    wp_enqueue_script( 'bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', array('jquery'), '', true );

    wp_enqueue_script( 'html5shiv', 'https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js', array('jquery'), '', false );
    $wp_scripts->add_data( 'html5shiv', 'conditional', 'lt IE 9' );

    wp_enqueue_script( 'respondjs', 'https://oss.maxcdn.com/respond/1.4.2/respond.min.js', array('jquery'), '', false );
    $wp_scripts->add_data( 'respondjs', 'conditional', 'lt IE 9' );

    wp_enqueue_script( 'slick', get_template_directory_uri() . '/js/slick.min.js', array('jquery'), '', true );

    wp_enqueue_script( 'main', get_template_directory_uri() . '/js/main.js', array('jquery'), '', true );

    wp_enqueue_script( 'lity', get_template_directory_uri() . '/js/lity.js', array('jquery'), '', true );

    wp_enqueue_script( 'theme', get_template_directory_uri() . '/js/theme.js', array('jquery'), '', true );

}

add_action( 'wp_enqueue_scripts', 'theme_js' );

// Enable custom menus
add_theme_support( 'menus' );

// Create custom menus
function register_my_menus() {
  register_nav_menus(
      array(
          'main' => __('Main Menu'),
          'products' => __('Products Menu'),
          'mobile' => __('Mobile Menu'),
          'top' => __('Top Menu'),
          'footer' => __('Footer Menu'),
          'sectors' => __('Sectors Menu'),
          'Help' => __('Help Menu')
        )
    );
}
add_action('init', 'register_my_menus');

// Enable post thumbnails
add_theme_support( 'post-thumbnails' );

// activate widgets
add_theme_support('widgets');

register_sidebar( array(
    'name'          => __( 'Sidebar' ),
    'id'            => 'blog',
    'description'   => 'The left side-bar for the blog pages',
    'class'         => '',
    'before_widget' => '<div class="sidebar-section %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h5>',
    'after_title'   => '</h5><div class="line-break"></div>',
) );

//require_once('wp_bootstrap_navwalker.php');

function custom_excerpt($new_length = 20, $new_more = '...') {
  add_filter('excerpt_length', function () use ($new_length) {
    return $new_length;
  }, 999);
  add_filter('excerpt_more', function () use ($new_more) {
    return $new_more;
  });
  $output = get_the_excerpt();
  $output = apply_filters('wptexturize', $output);
  $output = apply_filters('convert_chars', $output);
  $output = '<p>' . $output . '</p>';
  echo $output;
}

//Add the page slug to the Body Class
function add_slug_body_class( $classes ) {
global $post;
if ( isset( $post ) ) {
$classes[] = $post->post_type . '-' . $post->post_name;
}
return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );

remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );


add_filter( 'body_class', 'subpage' );
// Add specific CSS class by filter
function subpage( $classes ) {
    if ( is_page() && ! is_front_page() || is_search() )
    	$classes[] = 'subpage';
    return $classes;
}


// woocommerce stuff in here //

add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}

add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_cart_button_text' );    // 2.1 +

function woo_custom_cart_button_text() {

        return __( 'Add To Quote', 'woocommerce' );

}

// show empty categories across the site
add_filter( 'woocommerce_product_subcategories_hide_empty', 'hide_empty_categories', 10, 1 );
function hide_empty_categories ( $hide_empty ) {
    $hide_empty  =  FALSE;
    // You can add other logic here too
    return $hide_empty;
}

// remove result count and product ordering
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);

// enable woocommerce gallery features
add_action( 'after_setup_theme', 'dhstainless_setup' );

function dhstainless_setup() {
add_theme_support( 'wc-product-gallery-zoom' );
add_theme_support( 'wc-product-gallery-lightbox' );
add_theme_support( 'wc-product-gallery-slider' );
}

// remove product meta hook
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);

/**
 * Rename product data tabs
 */
add_filter( 'woocommerce_product_tabs', 'woo_rename_tabs', 98 );
function woo_rename_tabs( $tabs ) {

	$tabs['description']['title'] = __( 'Features' );

	return $tabs;
}

// unhook price
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);

// unhook automatically generated related Products
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

?>
