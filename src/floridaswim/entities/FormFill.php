<?php

namespace FloridaSwim\Entities;

use FloridaSwim\Entities\BaseModel;
use FloridaSwim\Entities\Registrant;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

class FormFill extends BaseModel
{

    protected $id;
    protected $created_at;
    protected $updated_at;
    protected $completed_at;

    protected $registrant;

    public function __construct() 
    {
        $this->created_at = new \DateTime();
    }

    public function addRegistrant(Registrant $registrant) {
        $registrant->addFormFill($this);
        $this->registrant = $registrant;
    }

    public static function loadMetadata(ClassMetadata $metadata)
    {
        global $wpdb;
        if(!isset($wpdb)) {
            $wpdb = new \stdClass();
            $wpdb->prefix = "wp_";
        }
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable($wpdb->prefix . "fwcr_form_fills");
        $builder->createField('id', 'integer')->isPrimaryKey()->generatedValue()->build();
        $builder->addInverseOneToOne('registrant', 'FloridaSwim\Entities\Registrant', 'form_fill_id');
        $builder->addField('created_at', 'datetime');
        $builder->addField('updated_at', 'datetime', ['nullable' => true]);
        $builder->addField('completed_at', 'datetime', ['nullable' => true]);
    }

}