<?php

namespace FloridaSwim\Controllers;

use Valitron\Validator;
use FloridaSwim\Models\Person;
use FloridaSwim\Models\Registrant;

class RegistrantController extends \WP_REST_Controller {

  protected $namespace = '/fscr/v1';
  protected $resource_name = 'registrants';

  public function registerRoutes() {
    register_rest_route($this->namespace, '/registrants', array(
      'methods'             => \WP_REST_Server::READABLE,
      'callback'            => array( $this, 'index' ),
      //'permission_callback' => array( $this, 'create_item_permissions_check' ),
      //'args'                => $this->get_endpoint_args_for_item_schema( true ),
    ));
    register_rest_route($this->namespace, '/registrants/create', array(
      'methods'             => \WP_REST_Server::CREATABLE,
      'callback'            => array( $this, 'create' ),
      //'permission_callback' => array( $this, 'create_item_permissions_check' ),
      //'args'                => $this->get_endpoint_args_for_item_schema( true ),
    ));
  }

  public function index(\WP_REST_Request $request) {
    $registrants = Registrant::all();
    return $registrants;
  }

  public function create(\WP_REST_Request $request) {
    
    // get json params from request
    $request = $request->get_json_params();

    // validate these params
    $v = new Validator($request);
    $v->rules([
      'required' => [
        'first_name', 'last_name', 'phone_number', 'zip_code', 'pool_access', 'email_address'
      ],
      'email' => [
        'email_address'
      ]
    ]);
    if (!$v->validate()) {
      return new \WP_REST_Response(['errors' => $v->errors()], 400);
    }

    // check that email address is unique
    /*$registrant = Registrant::where('email_address', $request['email_address']);
    if($registrant) {
      return new \WP_REST_Response(['errors' => [
        'email_address' => ["Sorry, that email address is already in use."]
      ]], 400);
    }*/

    $person = new Person();
    $person->first_name = $request['first_name'];
    $person->last_name = $request['last_name'];
    $person->phone_number = $request['phone_number'];
    $person->email_address = $request['email_address'];
    $person->zip_code = $request['zip_code'];
    $person->pool_access = $request['pool_access'];
    $person->save();
    if(!$person->id) {
      return new \WP_REST_Response(['message' => 'Sorry, something went wrong.'], 500);
    }

    $registrant = new Registrant();
    $registrant->person_id = $person->id;
    $registrant->save();
    if(!$registrant->id) {
      return new \WP_REST_Response(['message' => 'Sorry, something went wrong.'], 500);
    }
    $response = new \WP_REST_Response([
      'person' => $person,
      'registrant' => $registrant
    ], 201);
    return $response;

  }

}