<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Category;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCategoryData extends Fixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $categories = array(
            'Entrées',
            'Plats',
            'Desserts',
            'Sur le pouce'
        );

        foreach ($categories as $pos => $name) {
            $category = new Category();
            $category
                ->setPosition($pos + 1)
                ->setName($name)
            ;

            $manager->persist($category);
        }

        $manager->flush();
    }
}
