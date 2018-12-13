<?php

namespace FloridaSwim\Controllers;

use Valitron\Validator;
use FloridaSwim\Models\Guest;
use FloridaSwim\Models\Person;
use Doctrine\ORM\EntityManager;

class BaseController extends \WP_REST_Controller {

  protected $orm;

  public function __construct(EntityManager $doctrineEm) {
    $this->orm = $doctrineEm;
  }

  public function orm() {
    return $this->orm;
  }

}