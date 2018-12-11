<?php

namespace Socius\WpOrm\Db;

use Socius\WpOrm\Db\DBConn;

class Schema {

  public static function create(String $tableName, String $sql) {
    $conn = new DBConn();
    $sql = "CREATE TABLE IF NOT EXISTS $tableName ($sql);";
    $conn->db->query($sql);
  }

  public static function tableExists(String $tableName) {
    $conn = new DBConn();
    $sql = "
      SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES
      WHERE TABLE_SCHEMA='fsc_test'
    ;";
    $res = $conn->db->query($sql)->fetch();
    if(!$res) {
      $res = [];
    }
    return in_array($tableName, $res);
  }

  public static function drop(String $tableName) {
    $conn = new DBConn();
    $sql = "DROP TABLE IF EXISTS $tableName;";
    $conn->db->query($sql);
  }

}