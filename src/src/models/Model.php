<?php 

namespace Socius\Models;

use Socius\Models\DBConn;

class Model {

  public function __construct($args) {
    $this->tableName = assignTable($args);
  }

  protected function assignTable($args) {
    if(!empty($args['table_name'])) {
      return $args['table_name'];
    }
    else {
      $class = get_class($this);
      throw new Exception("Assign a table for $class on instantation.");
    }
  }

  public static function find($id) {
    $conn = new DBConn();
    return $conn->db->query("SELECT * FROM $this->tableName WHERE id = ?", [$id])->fetch();
  }

}