<?php

namespace FloridaSwim\Entities;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

class Registrant
{

    protected $id;
    protected $person;
    protected $person_id;
    protected $created_at;

    public function __construct() {
        $this->created_at = new \DateTime();
    }

    public static function loadMetadata(ClassMetadata $metadata)
    {
        global $wpdb;
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable($wpdb->prefix . "fwcr_registrants");
        $builder->createField('id', 'integer')->isPrimaryKey()->generatedValue()->build();
        $builder->createManyToOne('person_id', 'FloridaSwim\Entities\Person')->addJoinColumn('person_id', 'id', false, false)->build();
        $builder->addField('created_at', 'datetime');
    }

}