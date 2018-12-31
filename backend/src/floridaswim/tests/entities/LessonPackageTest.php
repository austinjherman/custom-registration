<?php

namespace FloridaSwim\Tests\Entities;

use FloridaSwim\Entities\Lesson;
use FloridaSwim\Tests\BaseTestCase;
use FloridaSwim\Entities\LessonPackage;

class LessonPackageTest extends BaseTestCase
{
    public function testCanCreateLessonPackage() {

      $lesson = new Lesson;
      $lesson->set('name', 'Swimming');
      $lesson->set('duration_to_price', [
        30 => 45.00,
        60 => 70.00
      ]);
      $this->orm()->persist($lesson);

      $lessonPackage = new LessonPackage;
      $lessonPackage->addLesson($lesson);
      $lessonPackage->set('lesson_quantity', 8);
      $lessonPackage->set('name', 'Learn to Swim');
      $lessonPackage->set('heading', 'Most Popular');
      $lessonPackage->set('description', 'Learn to swim.');
      $this->orm()->persist($lessonPackage);

      $this->orm()->flush();

      $id = $lessonPackage->get('id');
      $createdLessonPackage = $this->orm()->getRepository('FloridaSwim\Entities\LessonPackage')->find($id);
      $this->assertNotNull($createdLessonPackage);

      $this->orm()->remove($lesson);
      $this->orm()->remove($lessonPackage);

      $this->orm()->flush();
    }
}