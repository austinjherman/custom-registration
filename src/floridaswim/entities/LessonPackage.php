<?php

namespace FloridaSwim\Entities;

use FloridaSwim\Entities\Lesson;
use FloridaSwim\Entities\BaseModel;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

class LessonPackage extends BaseModel
{

    protected $id;
    protected $lesson;
    protected $lesson_id;
    protected $lesson_quantity;
    protected $name;
    protected $heading;
    protected $description;
    protected $created_at;
    protected $updated_at;

    public function __construct() 
    {
        $this->created_at = new \DateTime();
    }

    public function addLesson(Lesson $lesson) {
        $lesson->addLessonPackage($this);
        $this->lesson = $lesson;
    }

    public static function loadMetadata(ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable(parent::tablePrefix() . "fwcr_lesson_packages");
        $builder->createField('id', 'integer')->isPrimaryKey()->generatedValue()->build();
        // one Lesson can have many LessonPackages
        $builder->createManyToOne('lesson', 'FloridaSwim\Entities\Lesson')->addJoinColumn('lesson_id', 'id', false, false, 'cascade')->inversedBy('lessons')->build();
        $builder->addField('lesson_quantity', 'smallint');
        $builder->addField('name', 'string');
        $builder->addField('heading', 'string');
        $builder->addField('description', 'text', ['nullable' => true]);
        $builder->addField('created_at', 'datetime');
        $builder->addField('updated_at', 'datetime', ['nullable' => true]);
    }

}