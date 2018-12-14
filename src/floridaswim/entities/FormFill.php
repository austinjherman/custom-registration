<?php

namespace FloridaSwim\Entities;

use FloridaSwim\Entities\BaseModel;
use FloridaSwim\Entities\Guest;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

class FormFill extends BaseModel
{

    protected $id;
    protected $guest;
    protected $created_at;
    protected $updated_at;
    protected $completed_at;

    protected $expose = [
        'id', 'created_at', 'updated_at', 'completed_at'
    ];

    public function __construct() 
    {
        $this->created_at = new \DateTime();
    }

    public function addGuest(Guest $guest) {
        $this->guest = $guest;
    }

    public static function loadMetadata(ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable(parent::tablePrefix() . "fwcr_form_fills");
        $builder->createField('id', 'integer')->isPrimaryKey()->generatedValue()->build();
        $builder->createOneToOne('guest', 'FloridaSwim\Entities\Guest')->mappedBy('form_fill')->build();
        $builder->addField('created_at', 'datetime');
        $builder->addField('updated_at', 'datetime', ['nullable' => true]);
        $builder->addField('completed_at', 'datetime', ['nullable' => true]);
    }

}