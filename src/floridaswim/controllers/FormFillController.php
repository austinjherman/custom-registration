<?php

namespace FloridaSwim\Controllers;

use Valitron\Validator;
use Doctrine\ORM\EntityManager;
use FloridaSwim\Entities\Guest;
use FloridaSwim\Entities\FormFill;
use FloridaSwim\Controllers\BaseController;

class FormFillController extends BaseController {

  protected $namespace = '/fscr/v1';
  protected $resource_name = 'forms';

  public function registerRoutes() {
    register_rest_route($this->namespace, '/forms', array(
      'methods'             => \WP_REST_Server::READABLE,
      'callback'            => array( $this, 'index' ),
      //'permission_callback' => array( $this, 'index_permissions_check' ),
      //'args'                => $this->get_endpoint_args_for_item_schema( true ),
    ));
    register_rest_route($this->namespace, '/forms/create', array(
      'methods'             => \WP_REST_Server::CREATABLE,
      'callback'            => array( $this, 'create' ),
      //'permission_callback' => array( $this, 'create_permissions_check' ),
      //'args'                => $this->get_endpoint_args_for_item_schema( true ),
    ));
    register_rest_route($this->namespace, '/forms/(?P<id>\d+)', array(
      'methods'             => \WP_REST_Server::READABLE,
      'callback'            => array( $this, 'read' ),
      //'permission_callback' => array( $this, 'read_permissions_check' ),
      //'args'                => $this->get_endpoint_args_for_item_schema( true ),
    ));
    /*register_rest_route($this->namespace, '/forms/update/(?P<id>\d+)', array(
      'methods'             => \WP_REST_Server::EDITABLE,
      'callback'            => array( $this, 'update' ),
      //'permission_callback' => array( $this, 'update_permissions_check' ),
      //'args'                => $this->get_endpoint_args_for_item_schema( true ),
    ));*/
    register_rest_route($this->namespace, '/forms/delete/(?P<id>\d+)', array(
      'methods'             => \WP_REST_Server::DELETABLE,
      'callback'            => array( $this, 'delete' ),
      //'permission_callback' => array( $this, 'delete_permissions_check' ),
      //'args'                => $this->get_endpoint_args_for_item_schema( true ),
    ));
  }

  /**
   * Display a listing of all FormFill objects.
   *
   */
  public function index(\WP_REST_Request $request) {
    $forms = $this->orm()->getRepository('FloridaSwim\Entities\FormFill')->findAll();
    $arr = [];
    foreach ($forms as $form) {
      $arr[] = $form->toArray();
    }
    return new \WP_REST_Response(['forms' => $arr], 200);
  }

  /**
   * Store a FormFill object in the database.
   *
   */
  public function create(\WP_REST_Request $request) {

    $formFill = new FormFill;
    $this->orm()->persist($formFill);

    $this->orm()->flush();

    if(!$formFill->get('id')) {
      return new \WP_REST_Response(['message' => 'Sorry, something went wrong.'], 500);
    }

    $arr = $formFill->toArray();
    $response = new \WP_REST_Response([
      "form" => $arr
    ], 201);

    return $response;

  }

  /**
   * Get a single FormFill object from the database.
   *
   */
  public function read(\WP_REST_Request $request) {
    $id = $request->get_param('id');
    $form = $this->orm()->getRepository('FloridaSwim\Entities\FormFill')->find($id);
    if(!$form) {
      $response = new \WP_REST_Response([
        "code" => "rest_no_route",
        "message" => "No route was found matching the URL and request method",
        "data" => [
          "status" => 404
        ]
      ], 404);
    }
    else {
      $arr = $form->toArray();
      $response = new \WP_REST_Response([
        "form" => $arr
      ], 200);
    }
    return $response;
  }


  /**
   * Update a single FormFill object in storage.
   *
   */
  public function update(\WP_REST_Request $request) {
    // Not Implemented
  }


  /**
   * Delete a single FormFill object in storage.
   *
   */
  public function delete(\WP_REST_Request $request) {
    $id = $request->get_param('id');
    $form = $this->orm()->getRepository('FloridaSwim\Entities\FormFill')->find($id);
    if(!$form) {
      $response = new \WP_REST_Response([
        "code" => "rest_no_route",
        "message" => "No route was found matching the URL and request method",
        "data" => [
          "status" => 404
        ]
      ], 404);
    }
    else {
      $this->orm()->remove($form);
      $this->orm()->flush();
    }
    return $response;
  }


}