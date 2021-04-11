<?php

namespace App\Entity;

use Doctrine\ORM\EntityRepository;

class OrderRepository extends EntityRepository
{
    public function findLatest($count = 30)
    {
        return $this
            ->createQueryBuilder('o')
            ->orderBy('o.createdAt', 'DESC')
            ->setMaxResults($count)
            ->getQuery()
            ->getResult()
        ;
    }
}
