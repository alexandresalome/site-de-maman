<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Holiday;
use Doctrine\Common\Persistence\ObjectManager;

class LoadHolidayData extends Fixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $current = new \DateTime('-4 weeks');
        $current->modify('next monday');
        $max = new \DateTime('+1 years');

        while ($current < $max) {
            $begin = clone $current;
            $begin->modify('next tuesday');
            $end = clone $current;
            $end->modify('next wednesday');
            $current->modify('+1 weeks');

            $holiday = new Holiday();
            $holiday
                ->setBeginAt($begin)
                ->setEndAt($end)
            ;

            $manager->persist($holiday);
        }

        $manager->flush();
    }
}
