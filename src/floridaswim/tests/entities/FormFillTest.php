<?php

namespace FloridaSwim\Tests\Entities;

use FloridaSwim\Entities\FormFill;
use FloridaSwim\Tests\BaseTestCase;
use FloridaSwim\Entities\Registrant;


class FormFillTest extends BaseTestCase
{

  public function testCanCreateFormFill() {
    $formFill = new FormFill;
    $this->orm()->persist($formFill);
    $this->orm()->flush();

    $createdFormFill = $this->orm()->getRepository('FloridaSwim\Entities\FormFill')
          ->find($formFill->get('id'));

    $this->assertNotNull($createdFormFill);
  }

  public function testCanAddAndAccessRegistrant() {
    
    $formFill = new FormFill;

    $registrant = new Registrant;
    $registrant->set('first_name', 'Donald');
    $registrant->set('last_name', 'Duck');
    $registrant->set('email_address', 'donald@duck.com');
    $registrant->set('phone_number', '555-555-5555');
    $registrant->set('zip_code', '55555');
    $registrant->set('pool_access', true);
    $this->orm()->persist($registrant);

    $formFill->addRegistrant($registrant);
    $this->orm()->persist($formFill);

    $this->orm()->flush();

    $addedRegistrant = $formFill->get('registrant');
    $this->assertEquals($formFill->get('registrant')->get('first_name'), 'Donald');
  }

}