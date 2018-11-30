<?php

namespace FloridaSwim;

use Socius\WpOrm\Db\Schema;
use FloridaSwim\Db\PersonTable;
use FloridaSwim\Db\StudentTable;
use FloridaSwim\Db\ProductTable;
use FloridaSwim\Db\RegistrantTable;
use FloridaSwim\Db\ParentGuardianTable;
use FloridaSwim\Db\ProductAttributeTable;

class FloridaSwimCustomRegistration {

  public function handleActivation() {
    PersonTable::create();
    RegistrantTable::create();
    ParentGuardianTable::create();
    StudentTable::create();
    ProductTable::create();
    ProductAttributeTable::create();
  }

  public function handleDeactivation() {
    Schema::drop('fscr_students');
    Schema::drop('fscr_parents_guardians');
    Schema::drop('fscr_registrants');
    Schema::drop('fscr_people');
    Schema::drop('fscr_product_attributes');
    Schema::drop('fscr_products');
  }

}