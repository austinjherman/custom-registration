<?php

namespace FloridaSwim\Tests\Entities;

use FloridaSwim\Entities\Student;
use FloridaSwim\Entities\Schedule;
use FloridaSwim\Tests\BaseTestCase;

class ScheduleTest extends BaseTestCase
{
    public function testCanCreateSchedule() {

      $student = new Student;
      $student->set('name', 'Student');
      $student->set('date_of_birth', new \DateTime());
      $this->orm()->persist($student);

      $schedule = new Schedule;
      $schedule->addStudent($student);
      $schedule->set('lesson_frequency_per_week', 3);
      $schedule->set('lessons_begin', 'as soon as possible');
      $schedule->set('days_available', ['Monday', 'Wednesday', 'Friday']);
      $schedule->set('time_availability_weekdays', ['10am - 2pm']);
      $this->orm()->persist($schedule);

      $this->orm()->flush();

      $id = $schedule->get('id');

      $createdSchedule = $this->orm()->getRepository('FloridaSwim\Entities\Schedule')->find($id);

      $this->assertNotNull($createdSchedule);

      $this->orm()->remove($createdSchedule);
      $this->orm()->remove($student);
      $this->orm()->flush();

    }
}