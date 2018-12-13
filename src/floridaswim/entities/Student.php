<?php

namespace FloridaSwim\Entities;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

class Student
{

    protected $id;
    protected $registrant;
    protected $registrant_id;
    protected $guardian;
    protected $guardian_id;
    protected $first_name;
    protected $last_name;
    protected $date_of_birth;
    protected $created_at;

    public function __construct() 
    {
        $this->created_at = new \DateTime();
    }

    public static function loadMetadata(ClassMetadata $metadata)
    {
        global $wpdb;
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable($wpdb->prefix . "fwcr_students");
        $builder->createField('id', 'integer')->isPrimaryKey()->generatedValue()->build();

        // One registrant can have many students
        $builder->createManyToOne('registrant_id', 'FloridaSwim\Entities\Registrant')->addJoinColumn('registrant_id', 'id', false, false)->build();

        // One guardian have have many students
        $builder->createManyToOne('guardian_id', 'FloridaSwim\Entities\Guardians')->addJoinColumn('guardian_id', 'id', false, false)->build();

        $builder->addField('first_name', 'string');
        $builder->addField('last_name', 'string');
        $builder->addField('date_of_birth', 'datetime');
        $builder->addField('created_at', 'datetime');
    }

}