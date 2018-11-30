<?php

namespace Socius;

use Socius\WpOrm\Db\Schema;
use Socius\RegistrationForm\Db\RegistrantTable;

class Plugin {

  public function handleActivation() {
    if(!Schema::tableExists('registrants')) {
      RegistrantTable::create();
    }
  }

}