<?php

namespace FloridaSwim\Tests\Entities;

use FloridaSwim\Entities\FormFill;
use FloridaSwim\Tests\BaseTestCase;
use FloridaSwim\Entities\Registrant;

class RegistrantTest extends BaseTestCase
{

  public function testCanCreateRegistrant() 
  {
    $registrant = new Registrant;
    $registrant->set('first_name', 'Donald');
    $registrant->set('last_name', 'Duck');
    $registrant->set('email_address', 'donald@duck.com');
    $registrant->set('phone_number', '555-555-5555');
    $registrant->set('zip_code', '55555');
    $registrant->set('pool_access', true);
    $this->orm()->persist($registrant);
    $this->orm()->flush();

    $createdRegistrant = $this->orm()->getRepository('FloridaSwim\Entities\Registrant')
          ->find($registrant->get('id'));

    $this->assertNotNull($createdRegistrant);
  }

  public function testCanAddAndAccessFormFill()
  {
    $registrant = new Registrant;
    $registrant->set('first_name', 'Donald');
    $registrant->set('last_name', 'Duck');
    $registrant->set('email_address', 'donald@duck.com');
    $registrant->set('phone_number', '555-555-5555');
    $registrant->set('zip_code', '55555');
    $registrant->set('pool_access', true);

    $formFill = new FormFill;
    $formFill->addRegistrant($registrant);
    $this->orm()->persist($formFill);

    $registrant->addFormFill($formFill);
    $this->orm()->persist($registrant);

    $this->orm()->flush();

    $this->assertNotNull($registrant->get('form_fill'));

  }

}