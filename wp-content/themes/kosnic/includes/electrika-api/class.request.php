<?php

namespace ElectrikaAPI;

class Request {
  public $node_id;

  private $endpoints = [
    'node' => NODE_ITEM,
    'attributes' => NODE_ATTRIBUTES,
    'children' => NODE_CHILDREN,
    'datasheets' => NODE_DATASHEETS,
    'datasheets_pdf' => NODE_DATASHEETS_PDF,
    'breadcrumb' => NODE_BREADCRUMB
  ];

  public function __construct($node_id) {
    $this->node_id = $node_id;
  }

  public function for($endpoint) {
    if(!array_key_exists($endpoint, $this->endpoints)) {
      return (object) [
        'status' => 404,
        'msg' => 'Endpoint not found'
      ];
    }

    return $this->fetch(
      trailingslashit($this->endpoints[$endpoint]) . $this->node_id
    );
  }

  private function fetch($request_url) {
    $response = wp_remote_get($request_url);

    return (object) [
      'status' => wp_remote_retrieve_response_code($response),
      'headers' => wp_remote_retrieve_headers($response),
      'body' => json_decode(
        stripslashes(wp_remote_retrieve_body($response)),
        true
      )
    ];
  }
}
