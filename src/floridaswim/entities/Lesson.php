<?php

namespace FloridaSwim\Entities;

use FloridaSwim\Entities\BaseModel;
use FloridaSwim\Entities\PromoCode;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

class Lesson extends BaseModel
{

    protected $id;
    protected $name;
    protected $duration_to_price;
    protected $created_at;
    protected $updated_at;

    protected $lesson_packages;
    protected $promo_codes;

    public function __construct() 
    {
        $this->created_at = new \DateTime();
    }

    public function addLessonPackage(LessonPackage $lessonPackage) {
        $this->lesson_packages[] = $lessonPackage;
    }

    public function addPromoCode(PromoCode $promoCode) {
        $this->promo_codes[] = $promoCode;
    }

    public static function loadMetadata(ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable(parent::tablePrefix() . "fwcr_lessons");
        $builder->createField('id', 'integer')->isPrimaryKey()->generatedValue()->build();
        $builder->createOneToMany('promo_codes', 'FloridaSwim\Entities\PromoCode')->mappedBy('lesson_id')->build();
        $builder->addField('name', 'string');
        $builder->addField('duration_to_price', 'array');
        $builder->createOneToMany('lesson_packages', 'FloridaSwim\Entities\LessonPackage')->mappedBy('lesson_id')->build();
        $builder->addField('created_at', 'datetime');
        $builder->addField('updated_at', 'datetime', ['nullable' => true]);
    }

}