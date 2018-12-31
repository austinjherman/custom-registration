<?php

namespace FloridaSwim\Entities;

class BaseModel implements \JsonSerializable {

  public function get($key) {
    if(property_exists($this, $key)) {
      return $this->$key;
    }
    return null;
  }

  public function set($key, $value) {
    if(property_exists($this, $key)) {
      $this->$key = $value;
      return true;
    }
    return null;
  }

  public static function tablePrefix() {
    global $wpdb;
    if(!isset($wpdb)) {
      $wpdb = new \stdClass();
      $wpdb->prefix = "wp_";
    }
    return $wpdb->prefix;
  }

  public function jsonSerialize() {
    $res = [];
    foreach($this->expose as $property) {
      if(property_exists($this, $property)) {
        $res[$property] = $this->$property;
      }
    }
    return $res;
  }

  public function toArray() {
    return json_decode(json_encode($this));
  }

}