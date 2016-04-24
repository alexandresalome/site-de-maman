<?php

namespace AppBundle\Service;

use AppBundle\Entity\Holiday;
use Doctrine\Common\Persistence\ObjectManager;

class Planning
{
    private $manager;
    private $workDayHours = array('18:30', '19:00', '19:30', '20:00', '20:30');
    private $saturdayHours = array('10:00', '10:30', '11:00', '11:30', '12:00', '17:00', '17:30', '18:00', '18:30', '19:00');
    private $sundayHours = array('10:00', '10:30', '11:00', '11:30', '12:00');

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function getAvailableTimes()
    {
        $holidays = $this->manager->getRepository(Holiday::class)->findAll();

        $result = array();
        $formatter = \IntlDateFormatter::create('fr_FR', \IntlDateFormatter::FULL, \IntlDateFormatter::NONE);

        $current = new \DateTime('+2 days');
        $max     = new \DateTime('+3 weeks');

        while ($current < $max) {
            if ($this->isClosed($current, $holidays)) {
                continue;
            }

            $day = $current->format('N');
            $date = $formatter->format($current);

            if ($day == 6) {
                $hours = $this->saturdayHours;
            } elseif ($day == 7) {
                $hours = $this->sundayHours;
            } else {
                $hours = $this->workDayHours;
            }

            $vals = array_map(function ($val) use ($date) {
                return $date.' // '.$val;
            }, $hours);
            $result[$date] = array_combine($vals, $vals);

            $current->modify('+1 day');
        }

        return $result;
    }

    private function isClosed(\DateTime $day, array $holidays)
    {
        return false;
    }
}
