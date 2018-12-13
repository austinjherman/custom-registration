<?php

namespace FloridaSwim\Entities;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

class Lesson
{

    protected $id;
    protected $name;
    protected $duration_to_price;
    protected $lesson_packages;
    protected $created_at;

    public function __construct() 
    {
        $this->created_at = new \DateTime();
    }

    public static function loadMetadata(ClassMetadata $metadata)
    {
        global $wpdb;
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable($wpdb->prefix . "fwcr_lessons");
        $builder->createField('id', 'integer')->isPrimaryKey()->generatedValue()->build();
        $builder->createField('name', 'string');
        $builder->createField('duration_to_price', 'array');
        $builder->createOneToMany('lesson_packages', 'FloridaSwim\Entities\LessonPackage')->build();
        $builder->addField('created_at', 'datetime');
    }

}