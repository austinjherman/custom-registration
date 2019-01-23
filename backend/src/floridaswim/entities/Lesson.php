<?php

namespace FloridaSwim\Entities;

use FloridaSwim\Entities\BaseModel;
use FloridaSwim\Entities\PromoCode;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

class Lesson extends BaseModel
{

    protected $id;
    protected $name;
    protected $promo_codes;
    protected $durations;
    protected $students;
    protected $created_at;
    protected $updated_at;

    protected $expose = [
        'id',
        'name',
        'durations',
        'created_at',
        'updated_at'
    ];

    public function __construct() {
        $this->created_at = new \DateTime();
        $this->updated_at = new \DateTime();
        $this->durations = new ArrayCollection();
    }

    public function addPromoCode(PromoCode $promoCode) {
        $this->promo_codes[] = $promoCode;
    }

    public static function loadMetadata(ClassMetadata $metadata) {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable(parent::tablePrefix() . "fwcr_lessons");
        $builder->createField('id', 'integer')->isPrimaryKey()->generatedValue()->build();
        $builder->createOneToMany('students', 'FloridaSwim\Entities\Student')->mappedBy('lesson')->build();
        $builder->createOneToMany('durations', 'FloridaSwim\Entities\Duration')->mappedBy('lesson')->build();
        $builder->createOneToMany('promo_codes', 'FloridaSwim\Entities\PromoCode')->mappedBy('lesson')->build();
        $builder->addField('name', 'string');
        $builder->addField('created_at', 'datetime');
        $builder->addField('updated_at', 'datetime', ['nullable' => true]);
    }

}