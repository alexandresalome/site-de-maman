<?php

namespace AppBundle\Service\Loader;

use AppBundle\Entity\Holiday;
use Doctrine\Common\Persistence\ObjectManager;

class DoctrineHolidayLoader implements HolidayLoaderInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function getHolidays()
    {
        return $this->manager->getRepository(Holiday::class)->findAll();
    }
}
