<?php

namespace FloridaSwim\Controllers;

use Valitron\Validator;
use FloridaSwim\Entities\Guest;

class GuestController extends BaseController {

  protected $namespace = '/fscr/v1';
  protected $resource_name = 'guests';

  public function registerRoutes() {
    register_rest_route($this->namespace, '/guests', array(
      'methods'             => \WP_REST_Server::READABLE,
      'callback'            => array( $this, 'index' ),
      //'permission_callback' => array( $this, 'index_permissions_check' ),
      //'args'                => $this->get_endpoint_args_for_item_schema( true ),
    ));
    register_rest_route($this->namespace, '/guests/create', array(
      'methods'             => \WP_REST_Server::CREATABLE,
      'callback'            => array( $this, 'create' ),
      //'permission_callback' => array( $this, 'create_permissions_check' ),
      //'args'                => $this->get_endpoint_args_for_item_schema( true ),
    ));
    register_rest_route($this->namespace, '/guests/(?P<id>\d+)', array(
      'methods'             => \WP_REST_Server::READABLE,
      'callback'            => array( $this, 'read' ),
      //'permission_callback' => array( $this, 'read_permissions_check' ),
      //'args'                => $this->get_endpoint_args_for_item_schema( true ),
    ));
    register_rest_route($this->namespace, '/guests/update/(?P<id>\d+)', array(
      'methods'             => \WP_REST_Server::EDITABLE,
      'callback'            => array( $this, 'update' ),
      //'permission_callback' => array( $this, 'update_permissions_check' ),
      //'args'                => $this->get_endpoint_args_for_item_schema( true ),
    ));
    register_rest_route($this->namespace, '/guests/delete/(?P<id>\d+)', array(
      'methods'             => \WP_REST_Server::DELETABLE,
      'callback'            => array( $this, 'delete' ),
      //'permission_callback' => array( $this, 'delete_permissions_check' ),
      //'args'                => $this->get_endpoint_args_for_item_schema( true ),
    ));
  }


  /**
   * Display a listing of all stored Guest objects.
   *
   */
  public function index(\WP_REST_Request $request) {
    $guests = $this->orm()->getRepository('FloridaSwim\Entities\Guest')->findAll();
    $arr = [];
    foreach ($guests as $guest) {
      $arr[] = $guest->toArray();
    }
    return new \WP_REST_Response(['guests' => $arr], 200);
  }


  /**
   * Store a Guest Object in storage.
   *
   */
  public function create(\WP_REST_Request $request) {

    // validate params
    $v = new Validator($request->get_json_params());
    $v->rules([
      'required' => [
        'first_name', 'last_name', 'email_address', 'phone_number', 'zip_code', 'pool_access', 'form_entry_id' 
      ],
      'email' => [
        'email_address'
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

    // create guest
    $guest = new Guest();
    $guest->addFormEntry($formEntry);
    $guest->set('first_name', $request->get_param('first_name'));
    $guest->set('last_name', $request->get_param('last_name'));
    $guest->set('phone_number', $request->get_param('phone_number'));
    $guest->set('email_address', $request->get_param('email_address'));
    $guest->set('zip_code', $request->get_param('zip_code'));
    $guest->set('pool_access', $request->get_param('pool_access'));
    $this->orm()->persist($guest);
    $this->orm()->flush();
    if(!$guest->get('id')) {
      return new \WP_REST_Response(['message' => 'Sorry, something went wrong.'], 500);
    }

    // return response with created guest
    $arr = $guest->toArray();
    return new \WP_REST_Response([
      "guest" => $arr
    ], 201);

  }


  /**
   * Get a Guest Object from storage.
   *
   */
  public function read(\WP_REST_Request $request) {

    // find guest
    $id = $request->get_param('id');
    $guest = $this->orm()->getRepository('FloridaSwim\Entities\Guest')->find($id);
    if(!$guest) {
      return new \WP_REST_Response([
        "code" => "rest_no_route",
        "message" => "No route was found matching the URL and request method",
        "data" => [
          "status" => 404
        ]
      ], 404);
    }

    // display guest
    $arr = $guest->toArray();
    return new \WP_REST_Response([
      "guest" => $arr
    ], 200);

  }


  /**
   * Update a Guest Object in storage.
   *
   */
  public function update(\WP_REST_Request $request) {

    // find guest
    $id = $request->get_param('id');
    $guest = $this->orm()->getRepository('FloridaSwim\Entities\Guest')->find($id);
    if(!$guest) {
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
        'email_address', 'pool_access'
      ],
      'email' => [
        'email_address'
      ],
      'boolean' => [
        'pool_access'
      ]
    ]);
    if (!$v->validate()) {
      $response = new \WP_REST_Response(['errors' => $v->errors()], 400);
    }

    // update guest
    $incomingJson = $request->get_json_params();
    foreach($incomingJson as $key => $value) {
      // okay because set() will only set a value if its key already exists
      $guest->set($key, $value);
    }
    $arr = $guest->toArray();
    return new \WP_REST_Response([
      "guest" => $arr
    ], 200);

  }


  /**
   * Delete a Guest Object from the database.
   *
   */
  public function delete(\WP_REST_Request $request) {

    // find guest
    $id = $request->get_param('id');
    $guest = $this->orm()->getRepository('FloridaSwim\Entities\Guest')->find($id);
    if(!$guest) {
      return new \WP_REST_Response([
        "code" => "rest_no_route",
        "message" => "No route was found matching the URL and request method",
        "data" => [
          "status" => 404
        ]
      ], 404);
    }
  
    $this->orm()->remove($guest);
    $this->orm()->flush();
    return new \WP_REST_Response([
      "message" => "guest deleted"
    ], 200);

  }

}