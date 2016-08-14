<?php

namespace AppBundle\Service;

use AppBundle\Entity\Holiday;
use AppBundle\Service\Loader\StaticHolidayLoader;
use AppBundle\Service\Planning;

class PlanningTest extends \PHPUnit_Framework_TestCase
{
    public function testNoHolidays()
    {
        $loader = new StaticHolidayLoader(array());
        $planning = new Planning($loader);
        $formatter = $planning->getDateFormatter();

        $nextTime = $planning->getFirstAvailableTime();
        $expected = new \DateTime('+2 days');
        $this->assertContains($formatter->format($expected), $nextTime);
    }
    public function testOneWeekHolidays()
    {
        $holiday = new Holiday();
        $holiday->setBeginAt(new \DateTime());
        $holiday->setEndAt(new \DateTime('+ 7 days'));
        $loader = new StaticHolidayLoader(array($holiday));
        $planning = new Planning($loader);
        $formatter = $planning->getDateFormatter();

        $nextTime = $planning->getFirstAvailableTime();
        $expected = new \DateTime('+8 days');
        $this->assertContains($formatter->format($expected), $nextTime);
    }
}
