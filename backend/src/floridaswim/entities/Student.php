<?php

namespace FloridaSwim\Entities;

use FloridaSwim\Entities\Guardian;
use FloridaSwim\Entities\BaseModel;
use FloridaSwim\Entities\FormEntry;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

class Student extends BaseModel
{

    protected $id;
    protected $form_entry;
    protected $form_entry_id;
    protected $guardian;
    protected $guardian_id;
    protected $name;
    protected $date_of_birth;
    protected $description;
    protected $pool_address;
    protected $pool_type;
    protected $created_at;
    protected $updated_at;

    protected $schedule;

    protected $expose = [
        "id", 
        "name",
        "date_of_birth",
        "form_entry"
    ];

    public function __construct() 
    {
        $this->created_at = new \DateTime();
    }

    public function addFormEntry(FormEntry $formEntry) 
    {
        $formEntry->addStudent($this);
        $this->form_entry = $formEntry;
    }

    public function addGuardian(Guardian $guardian) 
    {
        $this->guardian = $guardian;
    }

    public function addSchedule(Schedule $schedule) 
    {
        $this->schedule = $schedule;
    }

    public static function loadMetadata(ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable(parent::tablePrefix(). "fwcr_students");
        $builder->createField('id', 'integer')->isPrimaryKey()->generatedValue()->build();

        // One form_entry can have many students
        $builder->createManyToOne('form_entry', 'FloridaSwim\Entities\FormEntry')->addJoinColumn('form_entry_id', 'id', true, false, 'cascade')->build();

        // One guardian can have many students
        $builder->createManyToOne('guardian', 'FloridaSwim\Entities\Guardian')->addJoinColumn('guardian_id', 'id', true, false)->inversedBy('students')->build();

        $builder->addField('name', 'string');
        $builder->addField('date_of_birth', 'datetime');
        $builder->addField('description', 'text', ['nullable' => true]);
        $builder->addField('pool_address', 'string', ['nullable' => true]);
        $builder->addField('pool_type', 'string', ['nullable' => true]);
        $builder->addField('created_at', 'datetime');
        $builder->addField('updated_at', 'datetime', ['nullable' => true]);
    }

}