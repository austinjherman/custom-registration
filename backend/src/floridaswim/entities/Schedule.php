<?php

namespace FloridaSwim\Entities;

use FloridaSwim\Entities\BaseModel;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

class Schedule extends BaseModel {

    protected $id;
    protected $student;
    protected $student_id;
    protected $days_available;
    protected $time_availability_weekdays;
    protected $description;
    protected $created_at;
    protected $updated_at;

    protected $expose = [
        'id',
        'student_id',
        'days_available',
        'time_availability_weekdays',
        'description',
        'created_at',
        'updated_at'
    ];

    public function __construct() {
        $this->created_at = new \DateTime();
        $this->updated_at = new \DateTime();
    }

    public function addStudent(Student $student) {
        $student->addSchedule($this);
        $this->student = $student;
    }

    public static function loadMetadata(ClassMetadata $metadata) {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable(parent::tablePrefix() . "fwcr_schedules");
        $builder->createField('id', 'integer')->isPrimaryKey()->generatedValue()->build();
        $builder->createOneToOne('student', 'FloridaSwim\Entities\Student')->inversedBy('schedule')->addJoinColumn('student_id', 'id', false, false, 'cascade')->build();
        $builder->addField('days_available', 'array');
        $builder->addField('time_availability_weekdays', 'array');
        $builder->addField('description', 'text', ['nullable' => true]);
        $builder->addField('created_at', 'datetime');
        $builder->addField('updated_at', 'datetime', ['nullable' => true]);
    }

}