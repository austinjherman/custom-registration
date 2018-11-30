<?php

namespace Socius\WpOrm\Db;

class DBConn {

  protected $dbhost;
  protected $dbname;
  protected $dbuser;
  protected $dbpass;

  public function __construct() {
    $dbhost = 'localhost';
    $dbname = 'fsc_test';
    $dbuser = 'root'; 
    $dbpass = 'root';
    $this->db = new \PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
  }

}