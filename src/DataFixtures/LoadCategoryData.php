<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;

class LoadCategoryData extends Fixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $categories = array(
            'Apéritif',
            'Entrée',
            'Sur le pouce',
            'Pâtes',
            'Plats',
            'Desserts'
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
