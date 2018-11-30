<?php

namespace Socius\WpOrm\Db;

class DBConn {

  protected $dbhost;
  protected $dbname;
  protected $dbuser;
  protected $dbpass;

  public function __construct() {
    global $wpdb;
    $this->db = $wpdb;
  }

}