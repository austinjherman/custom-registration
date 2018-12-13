<?php

namespace FloridaSwim\Entities;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

class LessonPackage
{

    protected $id;
    protected $lesson;
    protected $lesson_id;
    protected $name;
    protected $heading;
    protected $description;
    protected $description_2;
    protected $created_at;

    public function __construct() 
    {
        $this->created_at = new \DateTime();
    }

    public static function loadMetadata(ClassMetadata $metadata)
    {
        global $wpdb;
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable($wpdb->prefix . "fwcr_lesson_packages");
        $builder->createField('id', 'integer')->isPrimaryKey()->generatedValue()->build();

        // one Lesson can have many LessonPackages
        $builder->createManyToOne('lesson_id', 'FloridaSwim\Entities\Lesson')->addJoinColumn('lesson_id', 'id', false, false)->build();

        $builder->createField('name', 'string');
        $builder->createField('heading', 'string');
        $builder->createField('description', 'text');
        $builder->addField('created_at', 'datetime');
    }

}