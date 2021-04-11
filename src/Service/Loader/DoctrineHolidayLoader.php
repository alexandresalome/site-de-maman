<?php

namespace App\Service\Loader;

use App\Entity\Holiday;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineHolidayLoader implements HolidayLoaderInterface
{
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function getHolidays()
    {
        return $this->manager->getRepository(Holiday::class)->findAll();
    }
}
