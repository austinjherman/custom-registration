<?php

namespace FloridaSwim\Controllers;

use Valitron\Validator;

class StudentController extends \WP_REST_Controller {

  protected $namespace = '/fscr/v1';
  protected $resource_name = 'students';

  public function registerRoutes() {
    register_rest_route($this->namespace, '/students/create', array(
      'methods'             => \WP_REST_Server::CREATABLE,
      'callback'            => array( $this, 'create' ),
      //'permission_callback' => array( $this, 'create_item_permissions_check' ),
      //'args'                => $this->get_endpoint_args_for_item_schema( true ),
    ));
  }

  public function create(\WP_REST_Request $request) {

    // get json params from request
    $request = $request->get_json_params();

    // validate these params
    $v = new Validator($request);
    $v->rules([
      'required' => [
        'name', 'date_of_birth', 'parent_guardian'
      ]
    ]);
    if (!$v->validate()) {
      return new \WP_REST_Response(['errors' => $v->errors()], 400);
    }

  }

}