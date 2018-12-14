<?php

namespace FloridaSwim\Entities;

use FloridaSwim\Entities\FormFill;
use FloridaSwim\Entities\BaseModel;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

class Guest extends BaseModel
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
    protected $stripe_token;

    protected $expose = [
        "id", 
        "first_name", 
        "last_name",
        "email_address",
        "phone_number",
        "form_fill"
    ];

    public function __construct() 
    {
        $this->created_at = new \DateTime();
    }

    public function addFormFill(FormFill $formFill) 
    {
        $formFill->addGuest($this);
        $this->form_fill = $formFill;
    }

    public static function loadMetadata(ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable(parent::tablePrefix() . "fwcr_guests");
        $builder->createField('id', 'integer')->isPrimaryKey()->generatedValue()->build();
        $builder->createOneToOne('form_fill', 'FloridaSwim\Entities\FormFill')->inversedBy('guest')->addJoinColumn('form_fill_id', 'id', false, false, 'cascade')->build();
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