<?php

namespace FloridaSwim\Entities;

use FloridaSwim\Entities\FormFill;
use FloridaSwim\Entities\BaseModel;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

class Registrant extends BaseModel
{

    protected $id;
    protected $form_fill;
    protected $form_fill_id;
    protected $first_name;
    protected $last_name;
    protected $email_address;
    protected $phone_number;
    protected $zip_code;
    protected $pool_access;
    protected $created_at;
    protected $updated_at;
    protected $completed_at;

    public function __construct() 
    {
        $this->created_at = new \DateTime();
    }

    public function addFormFill(FormFill $formFill) 
    {
        $this->form_fill = $formFill;
    }

    public static function loadMetadata(ClassMetadata $metadata)
    {
        global $wpdb;
        if(!isset($wpdb)) {
            $wpdb = new \stdClass();
            $wpdb->prefix = "wp_";
        }
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable($wpdb->prefix . "fwcr_registrants");
        $builder->createField('id', 'integer')->isPrimaryKey()->generatedValue()->build();
        $builder->addOwningOneToOne('form_fill', 'FloridaSwim\Entities\FormFill');
        $builder->addField('first_name', 'string');
        $builder->addField('last_name', 'string');
        $builder->addField('email_address', 'string');
        $builder->addField('phone_number', 'string');
        $builder->addField('zip_code', 'string');
        $builder->addField('pool_access', 'string');
        $builder->addField('created_at', 'datetime');
        $builder->addField('updated_at', 'datetime', ['nullable' => true]);
        $builder->addField('completed_at', 'datetime', ['nullable' => true]);
    }

}