<?php

namespace FloridaSwim\Entities;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

class Guardian
{

    protected $id;
    protected $registrant;
    protected $registrant_id;
    protected $first_name;
    protected $last_name;
    protected $email_address;
    protected $created_at;

    public function __construct() 
    {
        $this->created_at = new \DateTime();
    }

    public static function loadMetadata(ClassMetadata $metadata)
    {
        global $wpdb;
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable($wpdb->prefix . "fwcr_guardians");
        $builder->createField('id', 'integer')->isPrimaryKey()->generatedValue()->build();

        // One registrant can have many parent/guardians
        $builder->createManyToOne('registrant_id', 'FloridaSwim\Entities\Registrant')->addJoinColumn('registrant_id', 'id', false, false)->build();

        $builder->addField('first_name', 'string');
        $builder->addField('last_name', 'string');
        $builder->addField('email_address', 'string');
        $builder->addField('created_at', 'datetime');
    }

}