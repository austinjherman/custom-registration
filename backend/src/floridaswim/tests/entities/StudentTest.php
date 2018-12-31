<?php

namespace FloridaSwim\Tests\Entities;

use FloridaSwim\Entities\Guest;
use FloridaSwim\Entities\Student;
use FloridaSwim\Tests\BaseTestCase;

class StudentTest extends BaseTestCase
{
    public function testCanCreateStudent() {
      $student = new Student;
      $student->set('name', 'testCanCreateStudent');
      $student->set('date_of_birth', new \DateTime());
      $student->set('description', 'This student is awesome.');
      $this->orm()->persist($student);
      $this->orm()->flush();

      $id = $student->get('id');

      $createdStudent = $this->orm()->getRepository('FloridaSwim\Entities\Student')->find($id);

      $this->assertNotNull($createdStudent);

      $this->orm()->remove($createdStudent);
      $this->orm()->flush();
    }
}