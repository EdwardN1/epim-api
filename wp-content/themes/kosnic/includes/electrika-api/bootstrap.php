<?php

define('BASE_API_ENDPOINT', 'http://api-develop.electrika.com/api/');
// define('BASE_API_ENDPOINT', 'https://api.electrika.com/api/’');
define('NODE_ITEM', BASE_API_ENDPOINT . 'Node/');
define('NODE_ATTRIBUTES', BASE_API_ENDPOINT . 'NodeAttributes/');
define('NODE_CHILDREN', BASE_API_ENDPOINT . 'NodeChildren/');
define('NODE_DATASHEETS', BASE_API_ENDPOINT . 'Datasheets/');
define('NODE_DATASHEETS_PDF', BASE_API_ENDPOINT . 'DatasheetsPdf/');
define('NODE_BREADCRUMB', BASE_API_ENDPOINT . 'Breadcrumb/');

require_once(THEME_FOLDER . '/includes/electrika-api/class.template-constructor.php');
require_once(THEME_FOLDER . '/includes/electrika-api/class.category.php');
require_once(THEME_FOLDER . '/includes/electrika-api/class.product.php');
require_once(THEME_FOLDER . '/includes/electrika-api/class.product-mapper.php');
require_once(THEME_FOLDER . '/includes/electrika-api/class.request.php');

ElectrikaAPI\TemplateConstructor::init();

function current_category_node_id() {
  $node_id_query_var = get_query_var('node_id');

  if(!empty($node_id_query_var)) return $node_id_query_var;

  return false;
}

function current_pagination() {
  $pagination = [
    'page' => 0,
    'pageLimit' => 9
  ];

  if(!empty(get_query_var('paged'))) {
    $pagination['page'] = get_query_var('paged') - 1;
  }

  return json_encode($pagination);
}
