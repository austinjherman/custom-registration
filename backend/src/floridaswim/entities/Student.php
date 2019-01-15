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
    protected $form_entry_id;
    protected $form_entry;
    protected $guardian_id;
    protected $guardian;
    protected $lesson_id;
    protected $lesson;
    protected $lesson_qty;
    protected $duration_id;
    protected $duration;
    protected $name;
    protected $date_of_birth;
    protected $description;
    protected $pool_address;
    protected $pool_type;
    protected $schedule;
    protected $created_at;
    protected $updated_at;

    protected $expose = [
        "id", 
        "name",
        "date_of_birth",
        "description",
        "pool_address",
        "pool_type",
        "created_at",
        "updated_at"
    ];

    public function __construct() {
        $this->created_at = new \DateTime();
        $this->updated_at = new \DateTime();
    }

    public function addFormEntry(FormEntry $formEntry) {
        $formEntry->addStudent($this);
        $this->form_entry = $formEntry;
    }

    public function addGuardian(Guardian $guardian) {
        $this->guardian = $guardian;
    }

    public function addSchedule(Schedule $schedule) {
        $this->schedule = $schedule;
    }

    public static function loadMetadata(ClassMetadata $metadata) {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable(parent::tablePrefix() . "fwcr_students");
        $builder->createField('id', 'integer')->isPrimaryKey()->generatedValue()->build();
        $builder->createManyToOne('form_entry', 'FloridaSwim\Entities\FormEntry')->addJoinColumn('form_entry_id', 'id', true, false, 'cascade')->build();
        $builder->createManyToOne('guardian', 'FloridaSwim\Entities\Guardian')->addJoinColumn('guardian_id', 'id', true, false)->inversedBy('students')->build();
        $builder->createManyToOne('lesson', 'FloridaSwim\Entities\Lesson')->addJoinColumn('lesson_id', 'id', true, false)->inversedBy('students')->build();
        $builder->addField('lesson_qty', 'integer');
        $builder->createManyToOne('duration', 'FloridaSwim\Entities\Duration')->addJoinColumn('duration_id', 'id', true, false)->build();
        $builder->addField('name', 'string');
        $builder->addField('date_of_birth', 'datetime');
        $builder->addField('description', 'text', ['nullable' => true]);
        $builder->addField('pool_address', 'string', ['nullable' => true]);
        $builder->addField('pool_type', 'string', ['nullable' => true]);
        $builder->addField('created_at', 'datetime');
        $builder->addField('updated_at', 'datetime', ['nullable' => true]);
    }

}