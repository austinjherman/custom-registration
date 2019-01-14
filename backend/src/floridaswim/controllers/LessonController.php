<?php

namespace FloridaSwim\Controllers;

use Valitron\Validator;
use FloridaSwim\Entities\Schedule;
use FloridaSwim\Controllers\BaseController;

class LessonController extends BaseController {

  protected $namespace = '/fscr/v1';
  protected $resource_name = 'lessons';

  public function registerRoutes() {
    register_rest_route($this->namespace, '/lessons', array(
      'methods'             => \WP_REST_Server::READABLE,
      'callback'            => array( $this, 'index' ),
      //'permission_callback' => array( $this, 'index_permissions_check' ),
      //'args'                => $this->get_endpoint_args_for_item_schema( true ),
    ));
    register_rest_route($this->namespace, '/lessons/(?P<id>\d+)', array(
      'methods'             => \WP_REST_Server::READABLE,
      'callback'            => array( $this, 'read' ),
      //'permission_callback' => array( $this, 'read_permissions_check' ),
      //'args'                => $this->get_endpoint_args_for_item_schema( true ),
    ));
    register_rest_route($this->namespace, '/lessons/(?P<id>\d+)/durations', array(
      'methods'             => \WP_REST_Server::READABLE,
      'callback'            => array( $this, 'durations' ),
      //'permission_callback' => array( $this, 'read_permissions_check' ),
      //'args'                => $this->get_endpoint_args_for_item_schema( true ),
    ));
  }


  /**
   * Get all instances of this resource from storage.
   *
   */
  public function index(\WP_REST_Request $request) {
    $lessons = $this->orm()->getRepository('FloridaSwim\Entities\Lesson')->findAll();
    $arr = [];
    foreach ($lessons as $lesson) {
      $lesson->set('durations', $lesson->get('durations')->toArray());
      $arr[] = $lesson->toArray();
    }
    return new \WP_REST_Response(['lessons' => $arr], 200);
  }


  /**
   * Get a specific instance of this resource.
   *
   */
  public function read(\WP_REST_Request $request) {

    // find lesson
    $id = $request->get_param('id');
    $lesson = $this->orm()->getRepository('FloridaSwim\Entities\Lesson')->find($id);
    $lesson->set('durations', $lesson->get('durations')->toArray());

    if(!$lesson) {
      return new \WP_REST_Response([
        "code" => "rest_no_route",
        "message" => "No route was found matching the URL and request method",
        "data" => [
          "status" => 404
        ]
      ], 404);
    }

    // display lesson
    $arr = $lesson->toArray();
    return new \WP_REST_Response([
      "lesson" => $arr
    ], 200);

  }

}