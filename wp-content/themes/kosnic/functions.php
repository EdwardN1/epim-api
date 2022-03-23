<?php

require_once(get_template_directory() . '/includes/constants.php');
require_once(THEME_FOLDER . '/includes/class.wp-brunch.php');
require_once(THEME_FOLDER . '/includes/electrika-api/bootstrap.php');

class WPTheme extends WPBrunch {
  public static function init() {
    parent::init();

    spl_autoload_register([__CLASS__, 'autoload_classes']);
    spl_autoload_register([__CLASS__, 'autoload_lib_classes']);

    add_action('wp_enqueue_scripts', [__CLASS__, 'style_script_includes']);

    add_action('after_setup_theme', [__CLASS__, 'theme_support']);
    add_action('after_setup_theme', [__CLASS__, 'custom_image_sizes']);
    add_action('after_setup_theme', [__CLASS__, 'register_nav_menus']);

    add_action('init', [__CLASS__, 'include_additional_files'], LOAD_ON_INIT);
    add_action('init', [__CLASS__, 'additional_rewrite_urls'], LOAD_ON_INIT);

    add_action('template_redirect', [__CLASS__, 'product_template_redirect']);
    add_action('template_redirect', [__CLASS__, 'product_search_redirect']);

    add_filter('query_vars', [__CLASS__, 'add_custom_query_vars']);
    add_filter(
      'excerpt_length',
      [__CLASS__, 'reduce_excerpt_length'],
      LOAD_AFTER_THEME
    );
    add_filter('body_class', [__CLASS__, 'product_single_body_classes']);
  }

  public static function style_script_includes() {
    wp_enqueue_script('jquery');
    wp_register_script(
      'respond',
      '//cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.js',
      '',
      '1.4.2',
      true
    );
    wp_enqueue_script('respond');
    wp_register_script(
      'modernizr',
      '//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js',
      '',
      '2.8.3',
      true
    );
    wp_enqueue_script('modernizr');
    wp_register_script(
      'selectivizr',
      '//cdnjs.cloudflare.com/ajax/libs/selectivizr/1.0.2/selectivizr-min.js',
      '',
      '1.0.2',
      true
    );
    wp_enqueue_script('selectivizr');
    wp_register_style(
      'font-awesome-lib',
      '//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css',
      '',
      '4.5.0'
    );
    wp_enqueue_style('font-awesome-lib');
    wp_localize_script(
      'theme_js',
      'wpAjax',
      ['ajaxurl' => admin_url('admin-ajax.php')]
    );

  }

  public static function theme_support() {
    add_theme_support('html5', ['search-form']);
    add_theme_support('automatic-feed-links');
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
  }

  public static function custom_image_sizes() {
    add_image_size('article-listing-image', 340, 340, true);
    add_image_size('showcase-hero-image', 2560, 1440, true);
    add_image_size('home-page-banner-image', 2560, 1440, true);
    add_image_size('product-image', 390, 345, true);
  }

  public static function register_nav_menus() {
    register_nav_menus([
      'main_menu' => 'Main navigation menu',
      'top_right_nav' =>  'Top right navigation menu',
      'footer_menu' =>  'Footer navigation menu'
    ]);
  }

  public static function reduce_excerpt_length($length) {
    return 25;
  }

  public static function include_additional_files() {
    new CustomPostTypes();
    require_once THEME_FOLDER . '/includes/frontend-helpers.php';
    require_once THEME_FOLDER . '/includes/custom-shortcodes.php';

    if(is_admin()) {
      require_once THEME_FOLDER . '/includes/custom-metaboxes.php';
      require_once THEME_FOLDER . '/includes/class.kosnic-admin.php';
    }
  }

  public static function additional_rewrite_urls() {
    add_rewrite_rule(
      '^products/categories/([^/]*)-([0-9]{6})/page/([0-9]+)?$',
      'index.php?pagename=products&node_id=$matches[2]&paged=$matches[3]',
      'top'
    );

    add_rewrite_rule(
      '^products/categories/([^/]*)-([0-9]{6})/?',
      'index.php?pagename=products&node_id=$matches[2]',
      'top'
    );

    add_rewrite_rule(
      '^products/([^/]*)-([0-9]{6,7})/?',
      'index.php?node_id=$matches[2]&product=true',
      'top'
    );

    add_rewrite_rule(
      '^products/page/([0-9]+)?$',
      'index.php?pagename=products&paged=$matches[1]',
      'top'
    );
  }

  public static function add_custom_query_vars($query_vars) {
    $query_vars[] = 'node_id';
    $query_vars[] = 'product';

    return $query_vars;
  }

  public static function product_template_redirect() {
    if(self::is_single_product()) {
      add_filter('template_include', function() {
        return THEME_FOLDER . '/product-single.php';
      });
    }
  }

  public static function product_search_redirect() {
    if(is_page('product-search') && empty($_POST['product_search'])) {
      wp_redirect(site_url('/products'));
      exit();
    }
  }

  public static function product_single_body_classes($classes) {
    if(self::is_single_product()) {
      $amended_classes = explode(
        ',',
        str_replace('home,blog,', '', implode($classes, ','))
      );

      $amended_classes[] = 'product-single-template';
      $classes = $amended_classes;
    }

    return $classes;
  }

  public static function autoload_classes($name) {
    $class_name = self::format_class_filename($name);
    $class_path = THEME_FOLDER . "/includes/class.$class_name.php";

    if(file_exists($class_path)) require_once $class_path;
  }

  public static function autoload_lib_classes($name) {
    $lib_class_name = THEME_FOLDER . '/includes/class.'
      . strtolower($name) . '.php';

    if(file_exists($lib_class_name)) require_once($lib_class_name);
  }

  private static function format_class_filename($filename) {
    return strtolower(
      implode(
        '-',
        preg_split('/(?=[A-Z])/', $filename, -1, PREG_SPLIT_NO_EMPTY)
      )
    );
  }

  private static function is_single_product() {
    return !empty(get_query_var('node_id')) &&
      get_query_var('product') === 'true';
  }
}

add_filter("wpseo_breadcrumb_links", "kosnic_override_yoast_breadcrumb_trail");

function kosnic_override_yoast_breadcrumb_trail($links) {
    if(is_woocommerce()) {
        if(is_array($links)) {
            if(!is_shop()){
                $count = 0;
                $shopLinks = array();
                $shopLink = array();
                $shopLink['url'] = '/shop/';
                $shopLink['text'] = 'Shop';
                $shopLinks[] = $shopLink;
                foreach ($links as $link) {
                    if($count>0) {
                        $shopLinks[] = $link;
                    }
                    $count++;
                }
                return $shopLinks;
            } else {
                return $links;
            }

        } else {
            return $links;
        }
    } else {
        return $links;
    }
}


WPTheme::init();
