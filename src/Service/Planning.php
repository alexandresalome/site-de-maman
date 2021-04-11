<?php

namespace App\Service;

use App\Service\Loader\HolidayLoaderInterface;

class Planning
{
    private HolidayLoaderInterface $holidayLoader;
    private array $workDayHours = ['18:30', '19:00', '19:30', '20:00', '20:30'];
    private array $saturdayHours = ['10:00', '10:30', '11:00', '11:30', '12:00', '17:00', '17:30', '18:00', '18:30', '19:00'];
    private array $sundayHours = ['10:00', '10:30', '11:00', '11:30', '12:00'];

    public function __construct(HolidayLoaderInterface $holidayLoader)
    {
        $this->holidayLoader = $holidayLoader;
    }

    public function getFirstAvailableTime()
    {
        $times = $this->getAvailableTimes();
        $day = array_shift($times);

        return array_shift($day);
    }

    public function getDateFormatter(): \IntlDateFormatter
    {
        return \IntlDateFormatter::create('fr_FR', \IntlDateFormatter::FULL, \IntlDateFormatter::NONE);
    }

    public function getAvailableTimes(): array
    {
        $holidays = $this->holidayLoader->getHolidays();

        $result = array();
        $formatter = $this->getDateFormatter();

        $current = new \DateTime('+2 days');
        $dayCount = 21;

        $iterations = 50000;
        while (count($result) < $dayCount) {

            // security
            $iterations--;
            if ($iterations === 0) {
                throw new \LogicException('Too much iterations looking for an available time.');
            }

            if ($this->isClosed($current, $holidays)) {
                $current->modify('+1 day');

                continue;
            }

            $day = (int) $current->format('N');
            $date = $formatter->format($current);

            if ($day === 6) {
                $hours = $this->saturdayHours;
            } elseif ($day === 7) {
                $hours = $this->sundayHours;
            } else {
                $hours = $this->workDayHours;
            }

            $values = array_map(function ($val) use ($date) {
                return $date.' // '.$val;
            }, $hours);
            $result[$date] = array_combine($values, $values);

            $current->modify('+1 day');
        }

        return $result;
    }

    private function isClosed(\DateTime $day, array $holidays): bool
    {
        foreach ($holidays as $holiday) {
            if ($holiday->match($day)) {
                return true;
            }
        }

        return false;
    }
}
