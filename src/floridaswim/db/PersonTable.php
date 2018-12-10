<?php

namespace FloridaSwim\Db;

use Socius\WpOrm\Db\Schema;
use Socius\WpOrm\Db\Table;
use FloridaSwim\Models\Person;

class PersonTable extends Table {

  public static function create() {
    $person = new Person();
    Schema::create($person->tableName(), '
      id INT(10) UNSIGNED AUTO_INCREMENT,
      PRIMARY KEY (id),
      first_name VARCHAR(25) NOT NULL,
      last_name VARCHAR(25) NOT NULL,
      phone_number VARCHAR(25),
      email_address VARCHAR(128),
      zip_code VARCHAR(25),
      pool_access BOOLEAN,
      created_at TIMESTAMP
    ');
  }

}
