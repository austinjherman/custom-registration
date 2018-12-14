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
    register_rest_route($this->namespace, '/forms/update/(?P<id>\d+)', array(
      'methods'             => \WP_REST_Server::EDITABLE,
      'callback'            => array( $this, 'update' ),
      //'permission_callback' => array( $this, 'update_permissions_check' ),
      //'args'                => $this->get_endpoint_args_for_item_schema( true ),
    ));
    register_rest_route($this->namespace, '/forms/delete/(?P<id>\d+)', array(
      'methods'             => \WP_REST_Server::DELETABLE,
      'callback'            => array( $this, 'delete' ),
      //'permission_callback' => array( $this, 'delete_permissions_check' ),
      //'args'                => $this->get_endpoint_args_for_item_schema( true ),
    ));
  }

  public function index(\WP_REST_Request $request) {
    $forms = $this->orm()->getRepository('FloridaSwim\Entities\FormFill')->findAll();
    $arr = [];
    foreach ($forms as $form) {
      $arr[] = $form->toArray();
    }
    return new \WP_REST_Response(['forms' => $arr], 200);
  }

  public function create(\WP_REST_Request $request) {

    $formFill = new FormFill;
    $this->orm()->persist($formFill);

    $this->orm()->flush();

    if(!$formFill->get('id')) {
      return new \WP_REST_Response(['message' => 'Sorry, something went wrong.'], 500);
    }

    $json = json_encode($formFill);

    $response = new \WP_REST_Response([
      "form" => $json
    ], 201);

    return $response;

  }

}