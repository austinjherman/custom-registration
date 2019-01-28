<?php

namespace FloridaSwim\Controllers;

use Valitron\Validator;
use FloridaSwim\Entities\FormEntry;
use FloridaSwim\Controllers\BaseController;

class OrderController extends BaseController {

  protected $namespace = '/fscr/v1';
  protected $resource_name = 'orders';

  public function registerRoutes() {
    register_rest_route($this->namespace, '/orders/(?P<id>\d+)', array(
      'methods'             => \WP_REST_Server::READABLE,
      'callback'            => array( $this, 'read' ),
      //'permission_callback' => array( $this, 'read_permissions_check' ),
      //'args'                => $this->get_endpoint_args_for_item_schema( true ),
    ));
  }


  /**
   * Get all instances of this resource from storage.
   *
   */
  public function index(\WP_REST_Request $request) {
    // Not Implemented
  }


  /**
   * Store a new instance of this resource.
   *
   */
  public function create(\WP_REST_Request $request) {
    // Not Implemented
  }


  /**
   * This function calculates the total cost of the swimming lessons. 
   *
   */
  public function read(\WP_REST_Request $request) {

    $id = $request->get_param('id');
    $form = $this->orm()->getRepository('FloridaSwim\Entities\FormEntry')->find($id);
    if(!$form) {
      return new \WP_REST_Response([
        "code" => "rest_no_route",
        "message" => "No route was found matching the URL and request method",
        "data" => [
          "status" => 404
        ]
      ], 404);
    }

    $students = $form->get('students')->toArray();

    /*
    return new \WP_REST_Response([
      "order" => $orderSummary
    ], 200);
    */

    $orderSummary = [];
    $orderSummary['summary']['total'] = 0.00;
    $orderSummary['products'] = [];
    $orderSummary['consolidated'] = [];
    $i = 0;
    foreach($students as $student) {
      // create line items
      $orderSummary['products'][$i]['duration_id'] = $student->get('duration')->get('id');
      $orderSummary['products'][$i]['lesson_name'] = $student->get('duration')->get('lesson')->get('name');
      $orderSummary['products'][$i]['duration_in_minutes'] = $student->get('duration')->get('duration');
      $orderSummary['products'][$i]['price'] = $student->get('duration')->get('price');
      $orderSummary['products'][$i]['quantity'] = $student->get('lesson_qty');
      $orderSummary['summary']['total'] += round($student->get('duration')->get('price') * $student->get('lesson_qty'), 2, PHP_ROUND_HALF_UP);
      $i++;
    }


    $orderSummaryConsolidated = [];
    $orderSummaryCopy = $orderSummary['products'];

    for($i=0; $i < count($orderSummaryCopy); $i++) {

      $orderSummaryConsolidated[$i] = $orderSummaryCopy[$i];
      $duration_id_i = null;
      $duration_id_i = $orderSummaryCopy[$i]['duration_id'];

      for($j=1; $j < count($orderSummaryCopy); $j++) {
        if($duration_id_i == $orderSummaryCopy[$j]['duration_id']) {
          $orderSummaryConsolidated[$i]['quantity'] = $orderSummaryCopy[$i]['quantity'] + $orderSummaryCopy[$j]['quantity'];
          array_splice($orderSummaryCopy, $j, 1);
        }
      }

      $orderSummaryCopy[$i]['total'] = number_format(round($orderSummaryCopy[$i]['price'] * $orderSummaryCopy[$i]['quantity'], 2, PHP_ROUND_HALF_UP), 2);

    }

    $orderSummary['consolidated'] = $orderSummaryConsolidated;

    $orderSummary['summary']['total'] = number_format($orderSummary['summary']['total'], 2);

    return new \WP_REST_Response([
      "order" => $orderSummary
    ], 200);

  }


  /**
   * Update a specific instance of this resource.
   *
   */
  public function update(\WP_REST_Request $request) {
    // Not Implemented
  }


  /**
   * Delete an instance of this resource from storage.
   *
   */
  public function delete(\WP_REST_Request $request) {
    // Not Implemented
  }

  public function countOccurences($needle, $haystack) {

    $occurences = 0;
    for($i=0; $i < count($haystack); $i++) {
      if($needle == $haystack[$i]) {
        $occurences += 1;
      }
    }

    return $occurences;

  }

}