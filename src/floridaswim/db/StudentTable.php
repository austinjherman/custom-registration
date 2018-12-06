<?php

namespace FloridaSwim\Db;

use Socius\WpOrm\Db\Schema;
use Socius\WpOrm\Db\Table;

class StudentTable extends Table {

  public static function create() {
    Schema::create('fscr_students', '
      id INT(10) UNSIGNED AUTO_INCREMENT,
      PRIMARY KEY (id),
      person_id INT(10) UNSIGNED NOT NULL,
      FOREIGN KEY (person_id)
        REFERENCES fscr_people(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
      parent_guardian_id INT(10) UNSIGNED,
      FOREIGN KEY (parent_guardian_id)
        REFERENCES fscr_parents_guardians(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
      date_of_birth DATE NOT NULL,
      created_at TIMESTAMP
    ');
  }

}