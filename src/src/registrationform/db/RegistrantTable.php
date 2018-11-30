<?php

namespace Socius\RegistrationForm\Db;

use Socius\WpOrm\Db\Schema;
use Socius\WpOrm\Db\Table;

class RegistrantTable extends Table {

  public static function create() {
    Schema::create('registrants', [
      'id'            => 'INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
      'first_name'    => 'VARCHAR(25) NOT NULL',
      'last_name'     => 'VARCHAR(25) NOT NULL',
      'email_address' => 'VARCHAR(50) NOT NULL',
      'phone_number'  => 'VARCHAR(25) NOT NULL',
      'zip_code'      => 'VARCHAR(25) NOT NULL',
      'created_at'    => 'TIMESTAMP'
    ]);
  }

}

