<?php

namespace FloridaSwim\Entities;

use FloridaSwim\Entities\Student;
use FloridaSwim\Entities\BaseModel;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

class Guardian extends BaseModel
{

    protected $id;
    protected $guest;
    protected $guest_id;
    protected $name;
    protected $email_address;
    protected $created_at;
    protected $updated_at;

    protected $students;

    public function __construct() 
    {
        $this->created_at = new \DateTime();
    }

    public function addStudent(Student $student) {
        $this->students[] = $student;
    }

    public static function loadMetadata(ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable(parent::tablePrefix() . "fwcr_guardians");
        $builder->createField('id', 'integer')->isPrimaryKey()->generatedValue()->build();

        // One form_fill can have many parent/guardians
        $builder->createManyToOne('guest', 'FloridaSwim\Entities\Guest')->addJoinColumn('guest_id', 'id', true, false, 'cascade')->build();

        $builder->createOneToMany('students', 'FloridaSwim\Entities\Student')->mappedBy('guardian_id')->build();

        $builder->addField('name', 'string');
        $builder->addField('email_address', 'string');
        $builder->addField('created_at', 'datetime');
        $builder->addField('updated_at', 'datetime', ['nullable' => true]);
    }

}