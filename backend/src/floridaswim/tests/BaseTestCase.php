<?php

namespace FloridaSwim\Tests;

use Doctrine\ORM\Tools\Setup;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManager;
use FloridaSwim\FloridaSwimCustomRegistration;
use Doctrine\ORM\Mapping\Driver\StaticPHPDriver;

class BaseTestCase extends TestCase
{

  protected $doctineEm;
  protected $pathToEntities;

  public function __construct() 
  {
    parent::__construct();
    $this->pathToEntities = "../entities";
    $this->bootDoctrine();
  }

  public function bootDoctrine() {
    // a couple configuration variables  
    $paths = array($this->pathToEntities);
    $isDevMode = true;

    // the connection configuration
    $dbParams = array(
      'driver'   => 'pdo_mysql',
      'user'     => "root",
      'password' => "root",
      'dbname'   => "fsc_test"
    );

    $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
    $entityManager = EntityManager::create($dbParams, $config);

    $driver = new StaticPHPDriver($this->pathToEntities);
    $entityManager->getConfiguration()->setMetadataDriverImpl($driver);

    $this->doctrineEm = $entityManager;
  }

  public function orm() {
    return $this->doctrineEm;
  }

}