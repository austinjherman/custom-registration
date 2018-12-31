<?php

namespace FloridaSwim;

use Valitron\Validator;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use FloridaSwim\Controllers\GuestController;
use FloridaSwim\Controllers\StudentController;
use FloridaSwim\Controllers\GuardianController;
use Doctrine\ORM\Mapping\Driver\StaticPHPDriver;
use FloridaSwim\Controllers\FormEntryController;

class FloridaSwimCustomRegistration {

  private $doctrineEm;
  private $pathToPluginFile;

  public function __construct(string $pathToPluginFile) 
  {
    $this->pathToPluginFile = $pathToPluginFile;
  }

  public function run() 
  {
    $this->bootDoctrine();
    $this->extendValidator();
    register_activation_hook($this->pathToPluginFile, [$this, 'handleActivation']);
    register_deactivation_hook($this->pathToPluginFile, [$this, 'handleDeactivation']);
    add_action( 'rest_api_init', [$this, 'registerApiRoutes'] );
  }

  public function bootDoctrine() 
  {
    // a couple configuration variables  
    $paths = array("$this->pathToPluginFile/src/floridaswim/entities");
    $isDevMode = true;

    // the connection configuration
    $dbParams = array(
      'driver'   => 'pdo_mysql',
      'user'     => DB_USER,
      'password' => DB_PASSWORD,
      'dbname'   => DB_NAME
    );

    $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
    $entityManager = EntityManager::create($dbParams, $config);

    $driver = new StaticPHPDriver("$this->pathToPluginFile/src/floridaswim/entities");
    $entityManager->getConfiguration()->setMetadataDriverImpl($driver);

    $this->doctrineEm = $entityManager;
  }

  public function orm() {
    return $this->doctrineEm;
  }

  public function extendValidator() {
    Validator::addRule('whitelist', function($field, $value, array $params, array $fields) {
      return false;
    }, 'Everything you do is wrong. You fail.');
  }

  public function handleActivation() 
  {
    $this->createTables();
    $this->createPublicFormPage();
  }

  public function handleDeactivation() 
  {
    $this->dropTables();
    $this->deletePublicFormPage();
  }

  public function createTables() 
  {
    $tool = new SchemaTool($this->doctrineEm);
    $classes = array(
      $this->doctrineEm->getClassMetadata('FloridaSwim\Entities\FormEntry'),
      $this->doctrineEm->getClassMetadata('FloridaSwim\Entities\Guest'),
      $this->doctrineEm->getClassMetadata('FloridaSwim\Entities\Guardian'),
      $this->doctrineEm->getClassMetadata('FloridaSwim\Entities\Student'),
      $this->doctrineEm->getClassMetadata('FloridaSwim\Entities\Lesson'),
      $this->doctrineEm->getClassMetadata('FloridaSwim\Entities\LessonPackage'),
      $this->doctrineEm->getClassMetadata('FloridaSwim\Entities\Schedule'),
      $this->doctrineEm->getClassMetadata('FloridaSwim\Entities\PromoCode'),
    );
    $tool->createSchema($classes);
  }

  public function dropTables() 
  {
    $tool = new SchemaTool($this->doctrineEm);
    $classes = array(
      $this->doctrineEm->getClassMetadata('FloridaSwim\Entities\FormEntry'),
      $this->doctrineEm->getClassMetadata('FloridaSwim\Entities\Guest'),
      $this->doctrineEm->getClassMetadata('FloridaSwim\Entities\Guardian'),
      $this->doctrineEm->getClassMetadata('FloridaSwim\Entities\Student'),
      $this->doctrineEm->getClassMetadata('FloridaSwim\Entities\Lesson'),
      $this->doctrineEm->getClassMetadata('FloridaSwim\Entities\LessonPackage'),
      $this->doctrineEm->getClassMetadata('FloridaSwim\Entities\Schedule'),
      $this->doctrineEm->getClassMetadata('FloridaSwim\Entities\PromoCode'),
    );
    $tool->dropSchema($classes);
  }

  public function createPublicFormPage() 
  {
    $post = array(
      'post_title'    => wp_strip_all_tags( 'Florida Swim Custom Registration' ),
      'post_content'  => '[fscr_form]',
      'post_status'   => 'publish',
      'post_author'   => 1,
      'post_type'     => 'page',
    );
    //update_post_meta( $id, '_wp_page_template', 'new_template.php' );
    $pageId = wp_insert_post( $post );
    if($pageId > 0) {
      add_option('fscr_public_form_page_id', $pageId);
    }
  }

  public function deletePublicFormPage() 
  {
    $pageId = get_option('fscr_public_form_page_id');
    if($pageId > 0) {
      wp_delete_post($pageId);
    }
    delete_option('fscr_public_form_page_id');
  }

  public function registerApiRoutes() {
    $formEntryController = new FormEntryController($this->orm());
    $formEntryController->registerRoutes();
    $guestController = new GuestController($this->orm());
    $guestController->registerRoutes();
    $studentController = new StudentController($this->orm());
    $studentController->registerRoutes();
    $guardianController = new GuardianController($this->orm());
    $guardianController->registerRoutes();
  }

}