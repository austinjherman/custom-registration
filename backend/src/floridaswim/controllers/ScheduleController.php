<?php

namespace FloridaSwim\Controllers;

use Valitron\Validator;
use FloridaSwim\Entities\Schedule;
use FloridaSwim\Controllers\BaseController;

class ScheduleController extends BaseController {

  protected $namespace = '/fscr/v1';
  protected $resource_name = 'schedules';

  public function registerRoutes() {
    register_rest_route($this->namespace, '/schedules', array(
      'methods'             => \WP_REST_Server::READABLE,
      'callback'            => array( $this, 'index' ),
      //'permission_callback' => array( $this, 'index_permissions_check' ),
      //'args'                => $this->get_endpoint_args_for_item_schema( true ),
    ));
    register_rest_route($this->namespace, '/schedules/create', array(
      'methods'             => \WP_REST_Server::CREATABLE,
      'callback'            => array( $this, 'create' ),
      //'permission_callback' => array( $this, 'create_permissions_check' ),
      //'args'                => $this->get_endpoint_args_for_item_schema( true ),
    ));
    register_rest_route($this->namespace, '/schedules/(?P<id>\d+)', array(
      'methods'             => \WP_REST_Server::READABLE,
      'callback'            => array( $this, 'read' ),
      //'permission_callback' => array( $this, 'read_permissions_check' ),
      //'args'                => $this->get_endpoint_args_for_item_schema( true ),
    ));
    register_rest_route($this->namespace, '/schedules/update/(?P<id>\d+)', array(
      'methods'             => \WP_REST_Server::EDITABLE,
      'callback'            => array( $this, 'update' ),
      //'permission_callback' => array( $this, 'update_permissions_check' ),
      //'args'                => $this->get_endpoint_args_for_item_schema( true ),
    ));
    register_rest_route($this->namespace, '/schedules/delete/(?P<id>\d+)', array(
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
    $schedules = $this->orm()->getRepository('FloridaSwim\Entities\Schedule')->findAll();
    $arr = [];
    foreach ($schedules as $schedule) {
      $arr[] = $schedule->toArray();
    }
    return new \WP_REST_Response(['schedules' => $arr], 200);
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
        'days_available', 'time_availability_weekdays', 'student_id'
      ],
      'integer' => ['student_id']
    ]);
    if (!$v->validate()) {
      return new \WP_REST_Response(['errors' => $v->errors()], 400);
    }

    $student = $this->orm()->getRepository('FloridaSwim\Entities\Student')->find($request->get_param('student_id'));
    if(!$student) {
      return new \WP_REST_Response(['errors' => ['student' => 'Please enter a valid student ID.']], 400);
    }

    // create new schedule
    $schedule = new Schedule;
    $schedule->set('days_available', implode(",", $request->get_param('days_available')));
    $schedule->set('time_availability_weekdays', implode(",", $request->get_param('time_availability_weekdays')));
    $schedule->set('description', $request->get_param('description'));
    $schedule->addStudent($student);
    $this->orm()->persist($schedule);
    
    try {
      $this->orm()->flush();
    }
    catch (\Exception $e) {
      return new \WP_REST_Response(['errors' => ['schdule' => $e->getMessage()]], 400);
    }

    if(!$schedule->get('id')) {
      return new \WP_REST_Response(['message' => 'Sorry, something went wrong.'], 500);
    }

    // return response with created guardian
    $arr = $schedule->toArray();
    return new \WP_REST_Response([
      "schedule" => $arr
    ], 201);

  }


  /**
   * Get a specific instance of this resource.
   *
   */
  public function read(\WP_REST_Request $request) {

    // find schedule
    $id = $request->get_param('id');
    $schedule = $this->orm()->getRepository('FloridaSwim\Entities\Schedule')->find($id);
    if(!$schedule) {
      return new \WP_REST_Response([
        "code" => "rest_no_route",
        "message" => "No route was found matching the URL and request method",
        "data" => [
          "status" => 404
        ]
      ], 404);
    }

    // display student
    $arr = $schedule->toArray();
    return new \WP_REST_Response([
      "schedule" => $arr
    ], 200);

  }


  /**
   * Update a specific instance of this resource.
   *
   */
  public function update(\WP_REST_Request $request) {

    // find student
    $id = $request->get_param('id');
    $schedule = $this->orm()->getRepository('FloridaSwim\Entities\Schedule')->find($id);
    if(!$schedule) {
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
      'optional' => ['lesson_frequency_per_week', 'student_id'],
      'integer' => ['lesson_frequency_per_week', 'student_id']
    ]);
    if (!$v->validate()) {
      $response = new \WP_REST_Response(['errors' => $v->errors()], 400);
    }

    // update student
    $incomingJson = $request->get_json_params();
    foreach($incomingJson as $key => $value) {
      // okay because set() will only set a value if its key already exists
      $schedule->set($key, $value);
    }

    $this->orm()->persist($schedule);
    $this->orm()->flush();

    $arr = $schedule->toArray();
    return new \WP_REST_Response([
      "schedule" => $arr
    ], 200);

  }


  /**
   * Delete an instance of this resource from storage.
   *
   */
  public function delete(\WP_REST_Request $request) {

    // find schedule
    $id = $request->get_param('id');
    $schedule = $this->orm()->getRepository('FloridaSwim\Entities\Schedule')->find($id);
    if(!$schedule) {
      return new \WP_REST_Response([
        "code" => "rest_no_route",
        "message" => "No route was found matching the URL and request method",
        "data" => [
          "status" => 404
        ]
      ], 404);
    }
  
    // remove guardian from storage
    $this->orm()->remove($schedule);
    $this->orm()->flush();
    return new \WP_REST_Response([
      "message" => "schedule deleted"
    ], 200);

  }

}