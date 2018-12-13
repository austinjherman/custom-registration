<?php

namespace FloridaSwim\Controllers;

use Valitron\Validator;
use FloridaSwim\Models\Person;

class PersonController extends \WP_REST_Controller {

  protected $namespace = '/fscr/v1';
  protected $resource_name = 'test';

  public function registerRoutes() {
    register_rest_route($this->namespace, '/test', array(
      'methods'             => \WP_REST_Server::READABLE,
      'callback'            => array( $this, 'create' ),
      //'permission_callback' => array( $this, 'create_item_permissions_check' ),
      //'args'                => $this->get_endpoint_args_for_item_schema( true ),
    ));
  }

  public function create(\WP_REST_Request $request) {
    
  }

}