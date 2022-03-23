<?php

namespace ElectrikaAPI;

class TemplateConstructor {
  public static function init() {
    add_action(
      'wp_ajax_kosnic_build_categories',
      [__CLASS__, 'kosnic_build_categories']
    );
    add_action(
      'wp_ajax_nopriv_kosnic_build_categories',
      [__CLASS__, 'kosnic_build_categories']
    );
    add_action(
      'wp_ajax_kosnic_build_products',
      [__CLASS__, 'kosnic_build_products']
    );
    add_action(
      'wp_ajax_nopriv_kosnic_build_products',
      [__CLASS__, 'kosnic_build_products']
    );
    add_action(
      'wp_ajax_kosnic_build_related_products',
      [__CLASS__, 'kosnic_build_related_products']
    );
    add_action(
      'wp_ajax_nopriv_kosnic_build_related_products',
      [__CLASS__, 'kosnic_build_related_products']
    );
    add_action(
      'wp_ajax_kosnic_build_common_products',
      [__CLASS__, 'kosnic_build_common_products']
    );
    add_action(
      'wp_ajax_nopriv_kosnic_build_common_products',
      [__CLASS__, 'kosnic_build_common_products']
    );
    add_action(
      'wp_ajax_kosnic_build_component_products',
      [__CLASS__, 'kosnic_build_component_products']
    );
    add_action(
      'wp_ajax_nopriv_kosnic_build_component_products',
      [__CLASS__, 'kosnic_build_component_products']
    );
    add_action(
      'wp_ajax_kosnic_build_accessory_products',
      [__CLASS__, 'kosnic_build_accessory_products']
    );
    add_action(
      'wp_ajax_nopriv_kosnic_build_accessory_products',
      [__CLASS__, 'kosnic_build_accessory_products']
    );
    add_action(
      'wp_ajax_kosnic_build_breadcrumbs',
      [__CLASS__, 'kosnic_build_breadcrumbs']
    );
    add_action(
      'wp_ajax_nopriv_kosnic_build_breadcrumbs',
      [__CLASS__, 'kosnic_build_breadcrumbs']
    );
  }

  public static function kosnic_build_categories() {
    $categories = self::format_post_data_for('categories', $_POST);
    $breadcrumbs = self::format_breadcrumbs_data('breadcrumbs', $_POST);
    $categories_template = self::render_template(
      'product-category-tpl.php',
      ['categories' => $categories, 'breadcrumbs' => $breadcrumbs]
    );

    echo $categories_template;
    die();
  }

  public static function kosnic_build_products() {
    self::render_products_template('products-grid-tpl.php', $_POST);
  }

  public static function kosnic_build_related_products() {
    self::render_products_template('related-products-tpl.php', $_POST);
  }

  public static function kosnic_build_common_products() {
    self::render_products_template('common-products-tpl.php', $_POST);
  }

  public static function kosnic_build_component_products() {
    self::render_products_template('component-products-tpl.php', $_POST);
  }

  public static function kosnic_build_accessory_products() {
    $post_data = $_POST;
    $accessories = self::format_post_data_for('accessories', $post_data);
    $fixings = self::format_post_data_for('fixings', $post_data);
    $unique_fixings = array_filter($fixings, function($f) use ($accessories) {
      return !in_array($f['ID'], array_column($accessories, 'ID'));
    });
    $products_template = self::render_template(
      'accessory-products-tpl.php',
      ['products' => array_merge($accessories, $unique_fixings)]
    );

    echo $products_template;
    die();
  }

  public static function kosnic_build_breadcrumbs() {
    $breadcrumbs = self::format_breadcrumbs_data('breadcrumbs', $_POST);
    $breadcrumbs_template = self::render_template(
      'product-breadcrumbs-tpl.php',
      ['breadcrumbs' => $breadcrumbs]
    );

    echo $breadcrumbs_template;
    die();
  }

  private static function render_template($template, $args) {
    extract($args);

    ob_start();
    include(THEME_FOLDER . "/templates/$template");

    return ob_get_clean();
  }

  private static function format_post_data_for($param, $post_data) {
    return json_decode(stripslashes($post_data[$param]), true);
  }

  private static function format_breadcrumbs_data($param, $post_data) {
    $breadcrumbs_data = self::format_post_data_for('breadcrumbs', $post_data);

    if(empty($breadcrumbs_data)) return;

    $breadcrumbs = array_reverse(
      $breadcrumbs_data
    );

    return array_values(array_filter($breadcrumbs, function($k) {
      return $k['NodeType'] === 0;
    }));
  }

  private static function render_products_template($template_file, $post_data) {
    $products = self::format_post_data_for('products', $post_data);
    $products_template = self::render_template(
      $template_file,
      ['products' => $products, 'search' => $post_data['search']]
    );

    echo $products_template;
    die();
  }
}
