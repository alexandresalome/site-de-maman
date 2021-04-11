<?php

namespace App\Entity;

use Doctrine\ORM\EntityRepository;

class MealRepository extends EntityRepository
{
    public function findOrderedByPosition()
    {
        return $this
            ->createQueryBuilder('m')
            ->orderBy('m.position', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function countAll()
    {
        return $this
            ->createQueryBuilder('m')
            ->select('COUNT(m.id)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
