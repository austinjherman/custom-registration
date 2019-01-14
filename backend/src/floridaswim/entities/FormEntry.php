<?php

namespace FloridaSwim\Entities;

use FloridaSwim\Entities\BaseModel;
use FloridaSwim\Entities\Guest;
use FloridaSwim\Entities\Guardian;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

class FormEntry extends BaseModel
{

    protected $id;
    protected $guest;
    protected $guardians;
    protected $students;
    protected $created_at;
    protected $updated_at;
    protected $completed_at;

    protected $expose = [
        'id', 'created_at', 'updated_at', 'completed_at', 'guest'
    ];

    public function __construct() 
    {
        $this->created_at = new \DateTime();
    }

    public function addGuest(Guest $guest) {
        $this->guest = $guest;
    }

    public function addGuardian(Guardian $guardian) {
        $this->guardians[] = $guardian;
    } 

    public function addStudent(Student $student) {
        $this->students[] = $student;
    } 

    public static function loadMetadata(ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable(parent::tablePrefix() . "fwcr_form_entries");
        $builder->createField('id', 'integer')->isPrimaryKey()->generatedValue()->build();
        $builder->createOneToOne('guest', 'FloridaSwim\Entities\Guest')->mappedBy('form_entry')->build();
        // One form_entry can have many parent/guardians
        $builder->createOneToMany('guardians', 'FloridaSwim\Entities\Guardian')->mappedBy('form_entry')->build();
        $builder->createOneToMany('students', 'FloridaSwim\Entities\Student')->mappedBy('form_entry')->build();
        $builder->addField('created_at', 'datetime');
        $builder->addField('updated_at', 'datetime', ['nullable' => true]);
        $builder->addField('completed_at', 'datetime', ['nullable' => true]);
    }

}