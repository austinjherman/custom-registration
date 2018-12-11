<?php

namespace Socius\WpOrm\Models;

class Collection {

  protected $items;

  public function __construct(Array $sqlResult, String $className) {
    $this->items = [];
    foreach($sqlResult as $result) {
      $obj = new $className();
      foreach ($result as $key => $value) {
        if($key != 'table') {
          $obj->$key = htmlspecialchars($value);
        }
      }
      $this->items[] = $obj;
    }
  }

  public function get() {
    if(count($this->items > 0)) {
      return $this->items;
    }
    return null;
  }

  public function first() {
    if(isset($this->items[0])) {
      return $this->items[0];
    }
    return null;
  }

}