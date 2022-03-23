<?php

namespace ElectrikaAPI;

class ProductMapper extends Product {
  public function __construct($request, $product_node_body) {
    $product_node_body['Attributes'] = $request->for('attributes')->body;
    $product_node_body['Datasheets'] = $request->for('datasheets')->body;

    parent::__construct($product_node_body);
  }
}
