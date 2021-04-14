<?php

namespace App\Entity;

use Doctrine\ORM\EntityRepository;

class MealRepository extends EntityRepository
{
    public function findOrderedByPosition(string $group)
    {
        return $this
            ->createQueryBuilder('m')
            ->leftJoin('m.category', 'c')
            ->where('c.group = :group')
            ->setParameter('group', $group)
            ->orderBy('m.position', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function countAll(string $group)
    {
        return $this
            ->createQueryBuilder('m')
            ->leftJoin('m.category', 'c')
            ->where('c.group = :group')
            ->setParameter('group', $group)
            ->select('COUNT(m.id)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
