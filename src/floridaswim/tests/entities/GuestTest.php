<?php

namespace FloridaSwim\Tests\Entities;

use FloridaSwim\Entities\Guest;
use FloridaSwim\Entities\FormFill;
use FloridaSwim\Tests\BaseTestCase;

class GuestTest extends BaseTestCase
{

  public function testCanCreateGuest() 
  {

    $formFill = new FormFill;
    $this->orm()->persist($formFill);
    $this->orm()->flush();

    $guest = new Guest;
    $guest->addFormFill($formFill);
    $guest->set('first_name', 'Donald');
    $guest->set('last_name', 'Duck');
    $guest->set('email_address', 'donald@duck.com');
    $guest->set('phone_number', '555-555-5555');
    $guest->set('zip_code', '55555');
    $guest->set('pool_access', true);
    $this->orm()->persist($guest);
    $this->orm()->flush();

    $createdGuest = $this->orm()->getRepository('FloridaSwim\Entities\Guest')
          ->find($guest->get('id'));

    $this->assertNotNull($createdGuest);

    $this->orm()->remove($formFill);
    $this->orm()->remove($guest);
    $this->orm()->flush();
  }

  public function testCanAddAndAccessFormFill()
  {

    $formFill = new FormFill;
    $this->orm()->persist($formFill);
    $this->orm()->flush();

    $guest = new Guest;
    $guest->addFormFill($formFill);
    $guest->set('first_name', 'Donald');
    $guest->set('last_name', 'Duck');
    $guest->set('email_address', 'donald@duck.com');
    $guest->set('phone_number', '555-555-5555');
    $guest->set('zip_code', '55555');
    $guest->set('pool_access', true);

    $this->orm()->persist($guest);
    $this->orm()->flush();

    $this->assertNotNull($guest->get('form_fill'));

    $this->orm()->remove($formFill);
    $this->orm()->remove($guest);
    $this->orm()->flush();

  }

}