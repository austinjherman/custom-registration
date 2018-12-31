<?php

namespace FloridaSwim\Tests\Entities;

use FloridaSwim\Entities\FormEntry;
use FloridaSwim\Tests\BaseTestCase;
use FloridaSwim\Entities\Guest;


class FormEntryTest extends BaseTestCase
{

  public function testCanCreateFormEntry() {
    
    $FormEntry = new FormEntry;
    $this->orm()->persist($formEntry);
    $this->orm()->flush();

    $id = $formEntry->get('id');
    $createdFormEntry = $this->orm()->getRepository('FloridaSwim\Entities\FormEntry')
          ->find($id);

    $this->assertNotNull($createdFormEntry);
    $this->orm()->remove($formEntry);
    $this->orm()->flush();

  }

  public function testCanAddAndAccessGuest() {
    
    $formEntry = new FormEntry;
    $this->orm()->persist($formEntry);

    $guest = new Guest;
    $guest->addFormEntry($formEntry);
    $guest->set('first_name', 'testCanAddAndAccessGuest');
    $guest->set('last_name', 'Duck');
    $guest->set('email_address', 'donald@duck.com');
    $guest->set('phone_number', '555-555-5555');
    $guest->set('zip_code', '55555');
    $guest->set('pool_access', true);
    $this->orm()->persist($guest);

    $this->orm()->flush();

    $addedGuest = $formEntry->get('guest');
    $this->assertEquals($formEntry->get('guest')->get('first_name'), 'testCanAddAndAccessGuest');

    $this->orm()->remove($formEntry);
    $this->orm()->remove($guest);

    $this->orm()->flush();

  }

  public function testCanDeleteFormEntry() {

    $formEntry = new FormEntry;
    $this->orm()->persist($formEntry);

    $this->orm()->flush();

    $id = $formEntry->get('id');
    $createdFormEntry = $this->orm()->getRepository('FloridaSwim\Entities\FormEntry')
          ->find($id);
    $this->assertNotNull($createdFormEntry);
    $idToDelete = $createdFormentry->get('id');
    
    $this->orm()->remove($createdFormEntry);
    $this->orm()->flush();

    $deletedFormEntry = $this->orm()->getRepository('FloridaSwim\Entities\FormEntry')
          ->find($idToDelete);

    $this->assertNull($deletedFormEntry);

  }

}