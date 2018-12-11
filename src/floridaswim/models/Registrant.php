<?php

namespace FloridaSwim\Models;

use Socius\WpOrm\Models\Model;
use FloridaSwim\Models\Person;

class Registrant extends Model {

  protected $table = 'fscr_registrants';

  public function person() {
    return $this->belongsTo(new Person(), 'person_id');
  }

}