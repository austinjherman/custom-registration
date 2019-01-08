<?php

namespace FloridaSwim\Entities;

use FloridaSwim\Entities\BaseModel;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

class LessonDuration extends BaseModel {

    protected $id;
    protected $duration_in_minutes;
    protected $created_at;
    protected $updated_at;
    protected $completed_at;

    protected $expose = [
        'id', 'created_at', 'updated_at', 'completed_at'
    ];

    public function __construct() {
        $this->created_at = new \DateTime();
    }

    public static function loadMetadata(ClassMetadata $metadata) {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable(parent::tablePrefix() . "fwcr_lesson_durations");
        $builder->createField('id', 'integer')->isPrimaryKey()->generatedValue()->build();
        $builder->addField('duration_in_minutes', 'integer');
        $builder->addField('created_at', 'datetime');
        $builder->addField('updated_at', 'datetime', ['nullable' => true]);
        $builder->addField('completed_at', 'datetime', ['nullable' => true]);
    }

}