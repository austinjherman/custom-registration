<?php

namespace Socius\WpOrm\Models;

use Socius\WpOrm\Db\DBConn;

class Model {

  protected $table = '';

  public static function find($id) {
    $model = new static();
    $table = $model->table;
    $conn = new DBConn();
    $id = htmlspecialchars($id);
    $sql = "SELECT * FROM $table WHERE id=$id;";
    $res = $conn->db->get_results($sql);
    $conn->db->close();
    if(!empty($res)) {
      return $res;
    }
    return null;
  }

  public static function where($colName, $value) {
    $model = new static();
    $table = $model->table;
    $conn = new DBConn();
    $colName = htmlspecialchars($colName);
    $value = htmlspecialchars($value);
    $sql = "SELECT * FROM $table WHERE $colName='$value';";
    $res = $conn->db->get_results($sql);
    $conn->db->close();
    if(!empty($res)) {
      return $res;
    }
    return null;
  }

  public static function all() {
    $model = new static();
    $table = $model->table;
    $sql = "SELECT * FROM $table";
    $conn = new DBConn();
    $res = $conn->db->get_results($sql);
    $conn->db->close();
    if(!empty($res)) {
      return $res;
    }
    return null;
  }

  public function save() {
    $columns = '';
    $values = '';
    foreach ($this as $key => $value) {
      if($key != 'table') {
        $columns .= "$key,";
        $value = htmlspecialchars($value);
        $values .= "'$value',";
      }
    }
    $columns = rtrim($columns, ',');
    $values = rtrim($values, ',');
    $conn = new DBConn();
    $sql = "
      INSERT INTO $this->table ($columns) VALUES ($values);
    ";
    var_dump($sql);
    $conn->db->query($sql);
    $this->id = (int)$conn->db->insert_id;
    $conn->db->close();
  }

  public function tableName() {
    return $this->table;
  }

}