<?php

namespace FloridaSwim;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Mapping\Driver\StaticPHPDriver;


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
    register_activation_hook($this->pathToPluginFile, [$this, 'handleActivation']);
    register_deactivation_hook($this->pathToPluginFile, [$this, 'handleDeactivation']);
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
      $this->doctrineEm->getClassMetadata('FloridaSwim\Entities\FormFill'),
      $this->doctrineEm->getClassMetadata('FloridaSwim\Entities\Registrant'),
      //$this->doctrineEm->getClassMetadata('FloridaSwim\Entities\Guardian'),
      //$this->doctrineEm->getClassMetadata('FloridaSwim\Entities\Student'),
      //$this->doctrineEm->getClassMetadata('FloridaSwim\Entities\Lesson'),
      //$this->doctrineEm->getClassMetadata('FloridaSwim\Entities\LessonPackage'),
    );
    $tool->createSchema($classes);
  }

  public function dropTables() 
  {
    $tool = new SchemaTool($this->doctrineEm);
    $classes = array(
      $this->doctrineEm->getClassMetadata('FloridaSwim\Entities\FormFill'),
      $this->doctrineEm->getClassMetadata('FloridaSwim\Entities\Registrant'),
      //$this->doctrineEm->getClassMetadata('FloridaSwim\Entities\Guardian'),
      //$this->doctrineEm->getClassMetadata('FloridaSwim\Entities\Student'),
      //$this->doctrineEm->getClassMetadata('FloridaSwim\Entities\Lesson'),
      //$this->doctrineEm->getClassMetadata('FloridaSwim\Entities\LessonPackage'),
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

}