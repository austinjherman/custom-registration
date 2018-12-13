<?php

namespace FloridaSwim\Tests\Entities;

use FloridaSwim\Entities\Lesson;
use FloridaSwim\Tests\BaseTestCase;

class LessonTest extends BaseTestCase
{
    public function testCanCreateLesson() {
      $lesson = new Lesson;
      $lesson->set('name', 'Swimming');
      $lesson->set('duration_to_price', [
        30 => 45.00,
        60 => 70.00
      ]);
      $this->orm()->persist($lesson);
      $this->orm()->flush();
      $id = $lesson->get('id');
      $createdLesson = $this->orm()->getRepository('FloridaSwim\Entities\Lesson')->find($id);
      $this->assertNotNull($createdLesson);
      $this->orm()->remove($createdLesson);
      $this->orm()->flush();
    }
}