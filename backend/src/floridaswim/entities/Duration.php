<?php

namespace FloridaSwim\Entities;

use FloridaSwim\Entities\BaseModel;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

class Duration extends BaseModel {

    protected $id;
    protected $lesson_id;
    protected $lesson;
    protected $duration;
    protected $price;
    protected $created_at;
    protected $updated_at;

    protected $expose = [
        'id', 
        'duration', 
        'price', 
        'created_at', 
        'updated_at'
    ];

    public function __construct() {
        $this->created_at = new \DateTime();
        $this->updated_at = new \DateTime();
    }

    public static function loadMetadata(ClassMetadata $metadata) {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable(parent::tablePrefix() . "fwcr_durations");
        $builder->createField('id', 'integer')->isPrimaryKey()->generatedValue()->build();
        $builder->createManyToOne('lesson', 'FloridaSwim\Entities\Lesson')->addJoinColumn('lesson_id', 'id', true, false)->inversedBy('durations')->build();
        $builder->addField('duration', 'string');
        $builder->addField('price', 'decimal');
        $builder->addField('created_at', 'datetime');
        $builder->addField('updated_at', 'datetime');
    }

}