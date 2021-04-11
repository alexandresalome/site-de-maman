<?php

namespace App\Tests\Service;

use App\Entity\Holiday;
use App\Service\Loader\StaticHolidayLoader;
use App\Service\Planning;
use PHPUnit\Framework\TestCase;

class PlanningTest extends TestCase
{
    public function testNoHolidays(): void
    {
        $loader = new StaticHolidayLoader([]);
        $planning = new Planning($loader);
        $formatter = $planning->getDateFormatter();

        $nextTime = $planning->getFirstAvailableTime();
        $expected = new \DateTime('+2 days');
        self::assertStringContainsString($formatter->format($expected), $nextTime);
    }
    public function testOneWeekHolidays(): void
    {
        $holiday = new Holiday();
        $holiday->setBeginAt(new \DateTime());
        $holiday->setEndAt(new \DateTime('+ 7 days'));
        $loader = new StaticHolidayLoader([$holiday]);
        $planning = new Planning($loader);
        $formatter = $planning->getDateFormatter();

        $nextTime = $planning->getFirstAvailableTime();
        $expected = new \DateTime('+8 days');
        self::assertStringContainsString($formatter->format($expected), $nextTime);
    }
}
