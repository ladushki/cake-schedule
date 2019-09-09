<?php

namespace Tests\Feature;

use App\Handlers\CakeHandler;
use App\Services\CakeDayService;
use Tests\TestCase;

class CakeTest extends TestCase
{

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCakeCommand()
    {

        $this->artisan('cake')
             ->assertExitCode(0);
    }

    public function testCakesCount()
    {
        $birthdays = [
            'Dave, 1986-06-26',
            'Rob, 1950-07-07',
            'Sam, 1950-07-15',
            'Kate, 1950-07-16',
            'Alex, 1950-07-22',
            'Jen, 1950-07-23',
            'Pete, 1950-07-24',
        ];
        $cakes    = [
            '2019-06-27, 1, 0, 1',
            '2019-07-09, 1, 0, 1',
            '2019-07-17, 0, 1, 2',
            '2019-07-24, 0, 1, 2',
            '2019-07-26, 1, 0, 1',
        ];
        $service = new CakeDayService();
        $handler  = new CakeHandler($service);
        $actual   = $handler->run($birthdays);
        $this->assertEquals($cakes, $actual->toArray());
    }

    public function testGetBirthday()
    {
        $service = new CakeDayService();
        $expected = '2019-01-01';
        $actual = $service->getBirthday('1973-01-01');
        $this->assertEquals($expected, $actual);
    }

    public function testNextWorkingDay()
    {
        $service = new CakeDayService();
        $holidays = [
            '01-01',
            '01-02',
            '09-09',
            '09-20',
        ];
        $service->setHolidays($holidays);

        $expected = '2019-09-23';
        $actual = $service->getNextWorkingDay('2019-09-19');
        $this->assertEquals($expected, $actual);

        $expected = '2019-09-10';
        $actual = $service->getNextWorkingDay('2019-09-06');
        $this->assertEquals($expected, $actual);

        $expected = '2019-01-03';
        $actual = $service->getNextWorkingDay('2019-01-01');
        $this->assertEquals($expected, $actual);

        $expected = '2019-09-16';
        $actual = $service->getNextWorkingDay('2019-09-13');
        $this->assertEquals($expected, $actual);

        $expected = '2019-12-26';
        $actual = $service->getNextWorkingDay('2019-12-25');
        $this->assertEquals($expected, $actual);
    }
}
