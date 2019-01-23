<?php

namespace FloridaSwim\Entities;

use FloridaSwim\Entities\Student;
use FloridaSwim\Entities\BaseModel;
use FloridaSwim\Entities\FormEntry;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

class Guardian extends BaseModel
{

    protected $id;
    protected $form_entry;
    protected $form_entry_id;
    protected $name;
    protected $email_address;
    protected $phone_number;
    protected $created_at;
    protected $updated_at;
    protected $students;

    protected $expose = [
        "id", 
        "name",
        "email_address",
        "phone_number",
        "form_entry",
        "created_at",
        "updated_at"
    ];

    public function __construct() {
        $this->created_at = new \DateTime();
        $this->updated_at = new \DateTime();
        $this->students = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function addFormEntry(FormEntry $formEntry) {
        $formEntry->addGuardian($this);
        $this->form_entry = $formEntry;
    }

    public function addStudent(Student $student) {
        $student->addGuardian($this);
        $this->students[] = $student;
    }
    
    public function getStudents() {
        return $this->students;
    }

    public static function loadMetadata(ClassMetadata $metadata) {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable(parent::tablePrefix() . "fwcr_guardians");
        $builder->createField('id', 'integer')->isPrimaryKey()->generatedValue()->build();
        $builder->createManyToOne('form_entry', 'FloridaSwim\Entities\FormEntry')->addJoinColumn('form_entry_id', 'id', true, false, 'cascade')->build();
        $builder->createOneToMany('students', 'FloridaSwim\Entities\Student')->mappedBy('guardian')->build();
        $builder->addField('name', 'string');
        $builder->addField('email_address', 'string');
        $builder->addField('phone_number', 'string');
        $builder->addField('created_at', 'datetime');
        $builder->addField('updated_at', 'datetime', ['nullable' => true]);
    }

}