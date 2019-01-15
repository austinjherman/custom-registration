<?php

namespace FloridaSwim\Entities;

use FloridaSwim\Entities\FormEntry;
use FloridaSwim\Entities\BaseModel;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

class Guest extends BaseModel
{

    protected $id;
    protected $form_entry;
    protected $form_entry_id;
    protected $first_name;
    protected $last_name;
    protected $email_address;
    protected $phone_number;
    protected $zip_code;
    protected $pool_access;
    protected $stripe_token;
    protected $created_at;
    protected $updated_at;

    protected $expose = [
        "id", 
        "first_name", 
        "last_name",
        "email_address",
        "phone_number",
        "created_at",
        "updated_at"
    ];

    public function __construct() {
        $this->created_at = new \DateTime();
        $this->updated_at = new \DateTime();
    }

    public function addFormEntry(FormEntry $formEntry) 
    {
        $formEntry->addGuest($this);
        $this->form_entry = $formEntry;
    }

    public static function loadMetadata(ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable(parent::tablePrefix() . "fwcr_guests");
        $builder->createField('id', 'integer')->isPrimaryKey()->generatedValue()->build();
        $builder->createOneToOne('form_entry', 'FloridaSwim\Entities\FormEntry')->inversedBy('guest')->addJoinColumn('form_entry_id', 'id', false, false, 'cascade')->build();
        $builder->addField('first_name', 'string');
        $builder->addField('last_name', 'string');
        $builder->addField('email_address', 'string');
        $builder->addField('phone_number', 'string');
        $builder->addField('zip_code', 'string');
        $builder->addField('pool_access', 'string');
        $builder->addField('stripe_token', 'string', ['nullable' => true]);
        $builder->addField('created_at', 'datetime');
        $builder->addField('updated_at', 'datetime', ['nullable' => true]);
    }

}