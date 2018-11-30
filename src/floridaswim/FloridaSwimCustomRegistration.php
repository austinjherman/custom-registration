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
    $this->createTables();
    $this->createPublicFormPage();
  }

  public function handleDeactivation() {
    $this->dropTables();
    $this->deletePublicFormPage();
  }

  public function createTables() {
    PersonTable::create();
    RegistrantTable::create();
    ParentGuardianTable::create();
    StudentTable::create();
    ProductTable::create();
    ProductAttributeTable::create();
  }

  public function dropTables() {
    Schema::drop('fscr_students');
    Schema::drop('fscr_parents_guardians');
    Schema::drop('fscr_registrants');
    Schema::drop('fscr_people');
    Schema::drop('fscr_product_attributes');
    Schema::drop('fscr_products');
  }

  public function createPublicFormPage() {
    $post = array(
      'post_title'    => wp_strip_all_tags( 'Florida Swim Custom Registration' ),
      'post_content'  => '',
      'post_status'   => 'publish',
      'post_author'   => 1,
      'post_type'     => 'page',
    );
    $pageId = wp_insert_post( $post );
    if($pageId > 0) {
      add_option('fscr_public_form_page_id', $pageId);
    }
  }

  public function deletePublicFormPage() {
    $pageId = get_option('fscr_public_form_page_id');
    if($pageId > 0) {
      wp_delete_post($pageId);
    }
    delete_option('fscr_public_form_page_id');
  }

}