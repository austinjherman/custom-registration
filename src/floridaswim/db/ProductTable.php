<?php

namespace FloridaSwim\Db;

use Socius\WpOrm\Db\Schema;
use Socius\WpOrm\Db\Table;

class ProductTable extends Table {

  public static function create() {
    Schema::create('fscr_products', '
      id INT(10) UNSIGNED AUTO_INCREMENT,
      PRIMARY KEY (id),
      product_name VARCHAR(50) NOT NULL,
      created_at TIMESTAMP
    ');
  }

}