<?php

namespace FloridaSwim\Entities;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

class Person
{

    protected $id;
    protected $first_name;
    protected $last_name;
    protected $email_address;
    protected $phone_number;
    protected $zip_code;
    protected $pool_access;
    protected $created_at;

    public function __construct() {
        $this->created_at = new \DateTime();
    }

    public static function loadMetadata(ClassMetadata $metadata)
    {
        global $wpdb;
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable($wpdb->prefix . "fwcr_persons");
        $builder->createField('id', 'integer')->isPrimaryKey()->generatedValue()->build();
        $builder->addField('first_name', 'string');
        $builder->addField('last_name', 'string');
        $builder->addField('email_address', 'string');
        $builder->addField('phone_number', 'string');
        $builder->addField('zip_code', 'string');
        $builder->addField('pool_access', 'string');
        $builder->addField('created_at', 'datetime');
    }
}