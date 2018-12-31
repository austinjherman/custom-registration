<?php

namespace FloridaSwim\Entities;

use FloridaSwim\Entities\Lesson;
use FloridaSwim\Entities\BaseModel;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

class PromoCode extends BaseModel
{

    protected $id;
    protected $lesson;
    protected $lesson_id;
    protected $promo_code;
    protected $created_at;

    protected $schedule;

    public function __construct() 
    {
        $this->created_at = new \DateTime();
    }

    public function addLesson(Lesson $lesson) {
        $lesson->addPromoCode($this);
        $this->lesson = $lesson;
    }

    public static function loadMetadata(ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable(parent::tablePrefix(). "fwcr_promo_codes");
        $builder->createField('id', 'integer')->isPrimaryKey()->generatedValue()->build();
        $builder->createManyToOne('lesson', 'FloridaSwim\Entities\Lesson')->addJoinColumn('lesson_id', 'id', false, false, 'cascade')->inversedBy('students')->build();
        $builder->addField('promo_code', 'string');
        $builder->addField('created_at', 'datetime');
    }

}