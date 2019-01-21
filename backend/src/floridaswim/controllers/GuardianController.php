<?php

namespace FloridaSwim\Controllers;

use Valitron\Validator;
use FloridaSwim\Entities\Guardian;
use FloridaSwim\Controllers\BaseController;

class GuardianController extends BaseController {

  protected $namespace = '/fscr/v1';
  protected $resource_name = 'guardians';

  public function registerRoutes() {
    register_rest_route($this->namespace, '/guardians', array(
      'methods'             => \WP_REST_Server::READABLE,
      'callback'            => array( $this, 'index' ),
      //'permission_callback' => array( $this, 'index_permissions_check' ),
      //'args'                => $this->get_endpoint_args_for_item_schema( true ),
    ));
    register_rest_route($this->namespace, '/guardians/create', array(
      'methods'             => \WP_REST_Server::CREATABLE,
      'callback'            => array( $this, 'create' ),
      //'permission_callback' => array( $this, 'create_permissions_check' ),
      //'args'                => $this->get_endpoint_args_for_item_schema( true ),
    ));
    register_rest_route($this->namespace, '/guardians/(?P<id>\d+)', array(
      'methods'             => \WP_REST_Server::READABLE,
      'callback'            => array( $this, 'read' ),
      //'permission_callback' => array( $this, 'read_permissions_check' ),
      //'args'                => $this->get_endpoint_args_for_item_schema( true ),
    ));
    register_rest_route($this->namespace, '/guardians/update/(?P<id>\d+)', array(
      'methods'             => \WP_REST_Server::EDITABLE,
      'callback'            => array( $this, 'update' ),
      //'permission_callback' => array( $this, 'update_permissions_check' ),
      //'args'                => $this->get_endpoint_args_for_item_schema( true ),
    ));
    register_rest_route($this->namespace, '/guardians/delete/(?P<id>\d+)', array(
      'methods'             => \WP_REST_Server::DELETABLE,
      'callback'            => array( $this, 'delete' ),
      //'permission_callback' => array( $this, 'delete_permissions_check' ),
      //'args'                => $this->get_endpoint_args_for_item_schema( true ),
    ));
  }


  /**
   * Get all instances of this resource from storage.
   *
   */
  public function index(\WP_REST_Request $request) {
    $guardians = $this->orm()->getRepository('FloridaSwim\Entities\Guardian')->findAll();
    $arr = [];
    foreach ($guardians as $guardian) {
      $arr[] = $guardian->toArray();
    }
    return new \WP_REST_Response(['guardians' => $arr], 200);
  }


  /**
   * Store a new instance of this resource.
   *
   */
  public function create(\WP_REST_Request $request) {

    // validate these params
    $v = new Validator($request->get_json_params());
    $v->rules([
      'required' => [
        'name', 'email', 'phone_number', 'form_entry_id'
      ],
      'email' => [
        'email'
      ]
    ]);
    if (!$v->validate()) {
      return new \WP_REST_Response(['errors' => $v->errors()], 400);
    }

    // find associated form entry
    $formEntryId = $request->get_param('form_entry_id');
    $formEntry = $this->orm()->getRepository('FloridaSwim\Entities\FormEntry')->find($formEntryId);
    if(!$formEntry) {
      return new \WP_REST_Response(['errors' => ['form entry' => 'Please enter a valid form entry ID.']], 400);
    }

    // create new guardian
    $guardian = new Guardian;
    $guardian->addFormEntry($formEntry);
    $guardian->set('name', $request->get_param('name'));
    $guardian->set('email_address', $request->get_param('email'));
    $guardian->set('phone_number', $request->get_param('phone_number'));
    $guardian->set('form_entry_id', $request->get_param('form_entry_id'));
    $guardian->set('updated_at', new \DateTime());
    $this->orm()->persist($guardian);
    $this->orm()->flush();
    if(!$guardian->get('id')) {
      return new \WP_REST_Response(['message' => 'Sorry, something went wrong.'], 500);
    }

    // return response with created guardian
    $arr = $guardian->toArray();
    return new \WP_REST_Response([
      "guardian" => $arr
    ], 201);

  }


  /**
   * Get a specific instance of this resource.
   *
   */
  public function read(\WP_REST_Request $request) {

    // find guardian
    $id = $request->get_param('id');
    $guardian = $this->orm()->getRepository('FloridaSwim\Entities\Student')->find($id);
    if(!$guardian) {
      return new \WP_REST_Response([
        "code" => "rest_no_route",
        "message" => "No route was found matching the URL and request method",
        "data" => [
          "status" => 404
        ]
      ], 404);
    }

    // display student
    $arr = $guardian->toArray();
    return new \WP_REST_Response([
      "guardian" => $arr
    ], 200);

  }


  /**
   * Update a specific instance of this resource.
   *
   */
  public function update(\WP_REST_Request $request) {

    // find guardian
    $id = $request->get_param('id');
    $guardian = $this->orm()->getRepository('FloridaSwim\Entities\Guardian')->find($id);
    if(!$guardian) {
      return new \WP_REST_Response([
        "code" => "rest_no_route",
        "message" => "No route was found matching the URL and request method",
        "data" => [
          "status" => 404
        ]
      ], 404);
    }

    // validate request
    $v = new Validator($request->get_json_params());
    $v->rules([
      'optional' => [
        'email'
      ],
      'email' => [
        'email'
      ]
    ]);
    if (!$v->validate()) {
      $response = new \WP_REST_Response(['errors' => $v->errors()], 400);
    }

    // update guardian
    $incomingJson = $request->get_json_params();
    foreach($incomingJson as $key => $value) {
      // okay because set() will only set a value if its key already exists
      $guardian->set($key, $value);
    }

    $this->orm()->persist($guardian);
    $this->orm()->flush();

    $arr = $guardian->toArray();
    return new \WP_REST_Response([
      "guardian" => $arr
    ], 200);

  }


  /**
   * Delete an instance of this resource from storage.
   *
   */
  public function delete(\WP_REST_Request $request) {

    // find guardian
    $id = $request->get_param('id');
    $guardian = $this->orm()->getRepository('FloridaSwim\Entities\Guardian')->find($id);
    if(!$guardian) {
      return new \WP_REST_Response([
        "code" => "rest_no_route",
        "message" => "No route was found matching the URL and request method",
        "data" => [
          "status" => 404
        ]
      ], 404);
    }
    // remove guardian from storage
    $this->orm()->remove($guardian);
    $this->orm()->flush();
    return new \WP_REST_Response([
      "message" => "guardian deleted"
    ], 200);

  }

}