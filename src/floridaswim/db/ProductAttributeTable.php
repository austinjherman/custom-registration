<?php

namespace FloridaSwim\Db;

use Socius\WpOrm\Db\Schema;
use Socius\WpOrm\Db\Table;

class ProductAttributeTable extends Table {

  public static function create() {
    Schema::create('fscr_product_attributes', '
      id INT(10) UNSIGNED AUTO_INCREMENT,
      PRIMARY KEY (id),
      attribute_name VARCHAR(255),
      attribute_value VARCHAR(255),
      product_id INT(10) UNSIGNED NOT NULL,
      FOREIGN KEY (product_id)
        REFERENCES fscr_products(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
      created_at TIMESTAMP
    ');
  }

}