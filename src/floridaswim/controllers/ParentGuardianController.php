<?php

namespace FloridaSwim\Controllers;

use Valitron\Validator;

class ParentGuardianController extends \WP_REST_Controller {

  protected $namespace = '/fscr/v1';
  protected $resource_name = 'guardians';

  public function registerRoutes() {
    register_rest_route($this->namespace, '/guardians/create', array(
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
        'first_name', 'last_name', 'email', 'phone_number'
      ],
      'email' => [
        'email'
      ]
    ]);
    if (!$v->validate()) {
      return new \WP_REST_Response(['errors' => $v->errors()], 400);
    }

  }

}