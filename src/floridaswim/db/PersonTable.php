<?php

namespace FloridaSwim\Db;

use Socius\WpOrm\Db\Schema;
use Socius\WpOrm\Db\Table;

class PersonTable extends Table {

  public static function create() {
    Schema::create('fscr_people', '
      id INT(10) UNSIGNED AUTO_INCREMENT,
      PRIMARY KEY (id),
      first_name VARCHAR(25) NOT NULL,
      last_name VARCHAR(25) NOT NULL,
      created_at TIMESTAMP
    ');
  }

}
