<?php

namespace FloridaSwim\Controllers;

use Valitron\Validator;
use FloridaSwim\Entities\Student;

class StudentController extends \WP_REST_Controller {

  protected $namespace = '/fscr/v1';
  protected $resource_name = 'students';

  public function registerRoutes() {
    register_rest_route($this->namespace, '/students', array(
      'methods'             => \WP_REST_Server::READABLE,
      'callback'            => array( $this, 'index' ),
      //'permission_callback' => array( $this, 'index_permissions_check' ),
      //'args'                => $this->get_endpoint_args_for_item_schema( true ),
    ));
    register_rest_route($this->namespace, '/students/create', array(
      'methods'             => \WP_REST_Server::CREATABLE,
      'callback'            => array( $this, 'create' ),
      //'permission_callback' => array( $this, 'create_permissions_check' ),
      //'args'                => $this->get_endpoint_args_for_item_schema( true ),
    ));
    register_rest_route($this->namespace, '/students/(?P<id>\d+)', array(
      'methods'             => \WP_REST_Server::READABLE,
      'callback'            => array( $this, 'read' ),
      //'permission_callback' => array( $this, 'read_permissions_check' ),
      //'args'                => $this->get_endpoint_args_for_item_schema( true ),
    ));
    register_rest_route($this->namespace, '/students/update/(?P<id>\d+)', array(
      'methods'             => \WP_REST_Server::EDITABLE,
      'callback'            => array( $this, 'update' ),
      //'permission_callback' => array( $this, 'update_permissions_check' ),
      //'args'                => $this->get_endpoint_args_for_item_schema( true ),
    ));
    register_rest_route($this->namespace, '/students/delete/(?P<id>\d+)', array(
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
    $students = $this->orm()->getRepository('FloridaSwim\Entities\Student')->findAll();
    $arr = [];
    foreach ($students as $student) {
      $arr[] = $student->toArray();
    }
    return new \WP_REST_Response(['students' => $arr], 200);
  }


  /**
   * Store a new instance of this resource.
   *
   */
  public function create(\WP_REST_Request $request) {

    // validate params
    $v = new Validator($request->get_params());
    $v->rules([
      'required' => [
        'student_name', 'student_date_of_birth', 'guardian_id'
      ]
    ]);
    if (!$v->validate()) {
      return new \WP_REST_Response(['errors' => $v->errors()], 400);
    }

    // find associated guardian
    $guardian = $this->orm()->getRepository('FloridaSwim\Entities\Guardian')->find($request->get_param('guardian_id'));
    if(!$guardian) {
      return new \WP_REST_Response(['errors' => ['guardian' => 'Please enter a valid guardian ID.']], 400);
    }

    // create a new student
    $student = new Student;
    $student->set('name', $request->get_param('name'));
    $student->set('date_of_birth', $request->get_param('date_of_birth'));
    $student->addGuardian($guardian);
    $this->orm()->persist($student);
    $this->orm()->flush();
    if(!$student->get('id')) {
      return new \WP_REST_Response(['message' => 'Sorry, something went wrong.'], 500);
    }

    // return response with created student
    $arr = $student->toArray();
    return new \WP_REST_Response([
      "student" => $arr
    ], 201);

  }


  /**
   * Get a specific instance of this resource.
   *
   */
  public function read(\WP_REST_Request $request) {

    // find student
    $id = $request->get_param('id');
    $student = $this->orm()->getRepository('FloridaSwim\Entities\Student')->find($id);
    if(!$student) {
      return new \WP_REST_Response([
        "code" => "rest_no_route",
        "message" => "No route was found matching the URL and request method",
        "data" => [
          "status" => 404
        ]
      ], 404);
    }

    // display student
    $arr = $student->toArray();
    return new \WP_REST_Response([
      "student" => $arr
    ], 200);

  }


  /**
   * Update a specific instance of this resource.
   *
   */
  public function update(\WP_REST_Request $request) {

    // find student
    $id = $request->get_param('id');
    $student = $this->orm()->getRepository('FloridaSwim\Entities\Student')->find($id);
    if(!$student) {
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
        'date_of_birth'
      ],
      'date' => [
        'date_of_birth'
      ]
    ]);
    if (!$v->validate()) {
      $response = new \WP_REST_Response(['errors' => $v->errors()], 400);
    }

    // update student
    $incomingJson = $request->get_json_params();
    foreach($incomingJson as $key => $value) {
      // okay because set() will only set a value if its key already exists
      $student->set($key, $value);
    }
    $arr = $student->toArray();
    return new \WP_REST_Response([
      "student" => $arr
    ], 200);

  }


  /**
   * Delete an instance of this resource from storage.
   *
   */
  public function delete(\WP_REST_Request $request) {

    // find student
    $id = $request->get_param('id');
    $student = $this->orm()->getRepository('FloridaSwim\Entities\Student')->find($id);
    if(!$student) {
      return new \WP_REST_Response([
        "code" => "rest_no_route",
        "message" => "No route was found matching the URL and request method",
        "data" => [
          "status" => 404
        ]
      ], 404);
    }
  
    $this->orm()->remove($student);
    $this->orm()->flush();
    return new \WP_REST_Response([
      "message" => "student deleted"
    ], 200);

  }

}