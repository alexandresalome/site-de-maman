<?php

namespace AppBundle\Service\Loader;

class StaticHolidayLoader implements HolidayLoaderInterface
{
    private $holidays;

    public function __construct(array $holidays)
    {
        $this->holidays = $holidays;
    }

    public function getHolidays()
    {
        return $this->holidays;
    }
}
