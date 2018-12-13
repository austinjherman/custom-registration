<?php

namespace FloridaSwim\Tests\Entities;

use FloridaSwim\Entities\FormFill;
use FloridaSwim\Tests\BaseTestCase;
use FloridaSwim\Entities\Guest;


class FormFillTest extends BaseTestCase
{

  public function testCanCreateFormFill() {
    
    $formFill = new FormFill;
    $this->orm()->persist($formFill);
    $this->orm()->flush();

    $id = $formFill->get('id');
    $createdFormFill = $this->orm()->getRepository('FloridaSwim\Entities\FormFill')
          ->find($id);

    $this->assertNotNull($createdFormFill);
    $this->orm()->remove($formFill);
    $this->orm()->flush();

  }

  public function testCanAddAndAccessGuest() {
    
    $formFill = new FormFill;
    $this->orm()->persist($formFill);

    $guest = new Guest;
    $guest->addFormFill($formFill);
    $guest->set('first_name', 'testCanAddAndAccessGuest');
    $guest->set('last_name', 'Duck');
    $guest->set('email_address', 'donald@duck.com');
    $guest->set('phone_number', '555-555-5555');
    $guest->set('zip_code', '55555');
    $guest->set('pool_access', true);
    $this->orm()->persist($guest);

    $this->orm()->flush();

    $addedGuest = $formFill->get('guest');
    $this->assertEquals($formFill->get('guest')->get('first_name'), 'testCanAddAndAccessGuest');

    $this->orm()->remove($formFill);
    $this->orm()->remove($guest);

    $this->orm()->flush();

  }

  public function testCanDeleteFormFill() {

    $formFill = new FormFill;
    $this->orm()->persist($formFill);

    $this->orm()->flush();

    $id = $formFill->get('id');
    $createdFormFill = $this->orm()->getRepository('FloridaSwim\Entities\FormFill')
          ->find($id);
    $this->assertNotNull($createdFormFill);
    $idToDelete = $createdFormFill->get('id');
    
    $this->orm()->remove($createdFormFill);
    $this->orm()->flush();

    $deletedFormFill = $this->orm()->getRepository('FloridaSwim\Entities\FormFill')
          ->find($idToDelete);

    $this->assertNull($deletedFormFill);

  }

}