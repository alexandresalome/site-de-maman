<?php

namespace App\Entity;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
    public function findOrderedByPosition()
    {
        return $this
            ->createQueryBuilder('c')
            ->select('c, m')
            ->leftJoin('c.meals', 'm')
            ->orderBy('c.position', 'ASC')
            ->addOrderBy('m.position', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function countAll()
    {
        return $this
            ->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
