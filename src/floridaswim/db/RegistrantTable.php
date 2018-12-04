<?php

namespace FloridaSwim\Db;

use Socius\WpOrm\Db\Schema;
use Socius\WpOrm\Db\Table;

class RegistrantTable extends Table {

  public static function create() {
    Schema::create('fscr_registrants', '
      id INT(10) UNSIGNED AUTO_INCREMENT,
      PRIMARY KEY (id),
      person_id INT(10) UNSIGNED NOT NULL,
      FOREIGN KEY (person_id)
        REFERENCES fscr_people(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
      email_address VARCHAR(50) NOT NULL,
      phone_number VARCHAR(25) NOT NULL,
      zip_code VARCHAR(25) NOT NULL,
      pool_access BOOLEAN NOT NULL,
      created_at TIMESTAMP
    ');
  }

}
