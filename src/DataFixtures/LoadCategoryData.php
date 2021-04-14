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
        $categories = [
            ['name' => 'Apéritif', 'group' => 'fr'],
            ['name' => 'Entrée', 'group' => 'fr'],
            ['name' => 'Sur le pouce', 'group' => 'fr'],
            ['name' => 'Pâtes', 'group' => 'fr'],
            ['name' => 'Plats', 'group' => 'fr'],
            ['name' => 'Desserts', 'group' => 'fr'],
            ['name' => 'Apéritif', 'group' => 'pt'],
            ['name' => 'Entrée', 'group' => 'pt'],
            ['name' => 'Sur le pouce', 'group' => 'pt'],
            ['name' => 'Pâtes', 'group' => 'pt'],
            ['name' => 'Plats', 'group' => 'pt'],
            ['name' => 'Desserts', 'group' => 'pt'],
        ];

        foreach ($categories as $pos => $data) {
            $name = $data['name'];
            $group = $data['group'];
            $category = new Category();
            $category
                ->setPosition($pos + 1)
                ->setName($name)
                ->setGroup($group)
            ;

            $manager->persist($category);
        }

        $manager->flush();
    }
}
