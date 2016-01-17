<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Meal;
use Doctrine\Common\Persistence\ObjectManager;

class LoadMealData extends Fixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $data = array(
            'Entrées' => array(
                'Coquille Saint-Jacques' => array(
                    'description' => 'Coquille Saint-Jacques, Persil, Oignon, Vin blanc, Sel, Poivre, Chapelure, Beurre',
                    'price' => '2.35',
                    'delay' => 2
                ),
                'Soufflé au fromage' => array(
                    'description' => 'Lait, beurre, farine, sel, poivre, noix de muscade, oeuf, comté',
                    'price' => '1.85',
                    'delay' => 1
                ),
                'Soufflé au jambon' => array(
                    'description' => 'Lait, beurre, farine, sel, poivre, noix de muscade, oeuf, jambon',
                    'price' => '1.85',
                    'delay' => 1
                ),
            ),
            'Plats' => array(
                'Lasagnes' => array(
                    'description' => 'Pâtes, sauce tomate, lait, farine, beurre, céleri, carotte',
                    'price' => '4.85',
                    'delay' => 2
                ),
                'Steack Pates' => array(
                    'description' => 'Steack, Pâtes',
                    'price' => '2.00',
                    'delay' => 1
                )
            ),
            'Desserts' => array(
                'Cake au chocolat' => array(
                    'description' => 'Beurre, chocolat, farine, sucre',
                    'price' => '1.45',
                    'delay' => 1
                )
            ),
            'Sur le pouce' => array(
            )
        );

        foreach ($data as $categoryName => $meals) {
            $category = $this->get('doctrine')->getRepository('AppBundle:Category')->findOneByName($categoryName);
            if (!$category) {
                throw new \InvalidArgumentException(sprintf('Found no category with name "%s".', $categoryName));
            }

            $position = 0;
            foreach ($meals as $name => $mealData) {
                $position++;

                $meal = new Meal();
                $meal
                    ->setName($name)
                    ->setCategory($category)
                    ->setDescription($mealData['description'])
                    ->setPrice($mealData['price'])
                    ->setDelay($mealData['delay'])
                    ->setPosition($position)
                ;

                $manager->persist($meal);
            }
        }

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 2;
    }
}
