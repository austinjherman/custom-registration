<?php

namespace FloridaSwim\Tests\Entities;

use FloridaSwim\Entities\Guest;
use FloridaSwim\Entities\Student;
use FloridaSwim\Entities\Guardian;
use FloridaSwim\Tests\BaseTestCase;

class GuardianTest extends BaseTestCase
{

    public function testCanCreateGuardian() {

      $guardian = new Guardian;
      $guardian->set('name', 'testCanCreateGuardian');
      $guardian->set('email_address', 'test@test.com');

      $this->orm()->persist($guardian);
      $this->orm()->flush();

      $id = $guardian->get('id');

      $createdGuardian = $this->orm()->getRepository('FloridaSwim\Entities\Guardian')->find($id);

      $this->assertNotNull($createdGuardian);

      $this->orm()->remove($createdGuardian);
      $this->orm()->flush();

    }

    public function testCanAttachAndAccessStudents() {

      $guardian = new Guardian;
      $guardian->set('name', 'Guardian');
      $guardian->set('email_address', 'test@test.com');
      $this->orm()->persist($guardian);

      $student1 = new Student;
      $student1->set('name', 'Student1');
      $student1->set('date_of_birth', new \DateTime);
      $student1->addGuardian($guardian);
      $this->orm()->persist($student1);

      $student2 = new Student;
      $student2->set('name', 'Student2');
      $student2->set('date_of_birth', new \DateTime);
      $student2->addGuardian($guardian);
      $this->orm()->persist($student2);

      $this->orm()->flush();

      $this->assertEquals($guardian->get('students')->first()->get('name'), 'Student1');

      $this->orm()->remove($guardian);
      $this->orm()->remove($student1);
      $this->orm()->remove($student2);
      $this->orm()->flush();

    }

}