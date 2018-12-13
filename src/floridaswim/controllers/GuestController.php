<?php

namespace FloridaSwim\Controllers;

use Valitron\Validator;
use Doctrine\ORM\EntityManager;
use FloridaSwim\Entities\Guest;
use FloridaSwim\Utils\Serializor;
use FloridaSwim\Entities\FormFill;
use FloridaSwim\Controllers\BaseController;

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

  public function index(\WP_REST_Request $request) {
    $guests = $this->orm()->getRepository('FloridaSwim\Entities\Guest')->findAll();
    $guestsArray = [];
    foreach($guests as $guest) {
      $guestArray[] = Serializor::json_encode($guest);
    }
    return new \WP_REST_Response(['guests' => $guestsArray], 200);
  }

  public function create(\WP_REST_Request $request) {
    
    // get json params from request
    $request = $request->get_json_params();

    // validate these params
    $v = new Validator($request);
    $v->rules([
      'required' => [
        'first_name', 'last_name', 'email_address', 'phone_number', 'zip_code', 'pool_access', 
      ],
      'email' => [
        'email_address'
      ]
    ]);
    if (!$v->validate()) {
      return new \WP_REST_Response(['errors' => $v->errors()], 400);
    }

    $formFill = new FormFill;
    $this->orm()->persist($formFill);

    $guest = new Guest();
    $guest->addFormFill($formFill);
    $guest->set('first_name', $request['first_name']);
    $guest->set('last_name', $request['last_name']);
    $guest->set('phone_number', $request['phone_number']);
    $guest->set('email_address', $request['email_address']);
    $guest->set('zip_code', $request['zip_code']);
    $guest->set('pool_access', $request['pool_access']);
    $this->orm()->persist($guest);

    $this->orm()->flush();

    if(!$guest->get('id')) {
      return new \WP_REST_Response(['message' => 'Sorry, something went wrong.'], 500);
    }

    $guestJson = Serializor::json_encode($guest);

    var_dump($guestJson);

    $response = new \WP_REST_Response([
      "guest" => $guestJson
    ], 201);
    return $response;

  }

}