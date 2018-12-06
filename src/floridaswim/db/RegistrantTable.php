<?php

namespace FloridaSwim\Db;

use Socius\WpOrm\Db\Schema;
use Socius\WpOrm\Db\Table;
use FloridaSwim\Models\Registrant;

class RegistrantTable extends Table {

  public static function create() {
    $registrant = new Registrant();
    Schema::create($registrant->tableName(), '
      id INT(10) UNSIGNED AUTO_INCREMENT,
      PRIMARY KEY (id),
      person_id INT(10) UNSIGNED NOT NULL,
      FOREIGN KEY (person_id)
        REFERENCES fscr_people(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
      created_at TIMESTAMP
    ');
  }

}
