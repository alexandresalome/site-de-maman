<?php

namespace App\Entity;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
    public function findOneByGroupAndSlug(string $group, string $slug): Category
    {
        $result = $this
            ->createQueryBuilder('c')
            ->select('c')
            ->where('c.group = :group AND c.slug = :slug')
            ->setParameter('group', $group)
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        if (!$result) {
            throw new \InvalidArgumentException(sprintf('No category with slug "%s" in group "%s".', $slug, $group));
        }

        return $result;
    }

    public function findOrderedByPosition(string $group)
    {
        return $this
            ->createQueryBuilder('c')
            ->select('c, m')
            ->where('c.group = :group')
            ->setParameter('group', $group)
            ->leftJoin('c.meals', 'm')
            ->orderBy('c.position', 'ASC')
            ->addOrderBy('m.position', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function countAll(string $group)
    {
        return $this
            ->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.group = :group')
            ->setParameter('group', $group)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
