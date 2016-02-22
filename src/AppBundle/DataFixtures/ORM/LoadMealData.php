<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Meal;
use AppBundle\Price\Price;
use Doctrine\Common\Persistence\ObjectManager;

class LoadMealData extends Fixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $data = array(
            'Apéritif' => array(
                '10 Petits croissants au saumon' => '4.00',
                '6 Beignets de morue' => '3.00',
                '6 Rissoes à la viande ou aux crevettes' => '3.00',
                '20 Roulés saucisses ou divers petits fours' => '3.00',
                'Cake jambon/olives ou saumon ou thon' => '4.00',
                '10 Navettes garnies ( jambon, fromage, paté etc...)' => '3.00',
            ),
            'Entrée' => array(
                'Coquille Saint Jacques' => '3.50',
                'Coquille aux fruits de mer' => '2.40',
                'Quiche lorraine (6 parts)' => '3.50',
                'Quiche saumon-brocolis ou saumon-poireaux' => '4.50',
            ),
            'Sur le pouce' => array(
                'Ficelle Picarde' => '2.50',
                'Croque Madame' => '1.50',
                'Couronne de jambon, fromage (6 parts)' => '7.50',
                'Crêpe au sarazin garnie' => '2.50',
                'Hamburger' => '2.50',
                'Pizza' => '5.00',
            ),
            'Pâtes' => array(
                '6 Cannellonis chêvre, épinards' => '4.50',
                'Spaguettis bolognaise' => '4.50',
                'Lasagnes viande tomate ou saumon épinards' => '4.50',
                'Tagliatelles carbonara' => '4.50',
                'Pâtes à la Rosa' => '4.50',
                'Pennes aux 4 fromages' => '4.50',
                'Tagliatelles au saumon' => '4.50',
                'Pâtes aux fruits de mer' => '4.50',
            ),
            'Plats' => array(
                'Hachis parmentier' => '5.00',
                'Endives au gratin (jambon, pommes de terre)' => '5.00',
                'Tartiflette' => '5.00',
                'Morue à la crème, pomme de terre, épinards ou pas' => '5.00',
                'Rata aux poireaux, pomme de terre, sauté de porc' => '5.00',
                'Escalope de porc panée avec riz et légumes' => '5.00',
                'Rouelle avec pomme de terre au four' => '5.00',
                'Brandade de morue parmentière' => '5.00',
                'Fricassée de volaille à l\'ancienne, riz pilaf' => '5.00',
                'Pomme de terre macaire (jambon-fromage)' => '5.00',
                'Gratin dauphinois au jambon' => '5.00',
                'Risotto aux fruits de mer' => '5.00',
                'Risotto poulet, champignons' => '5.00',
                'Chou rouge cuit avec pomme et saucisse' => '5.00',
                'Tomate farçie en sauce, riz ou pâtes au choix' => '6.00',
                'Courgette farcie garniture au choix' => '6.00',
                'Paêlla' => '7.00',
                'Bœuf bourguignon avec pâtes ou purée' => '7.00',
                'Bœuf strogonof (tomate crème moutarde), garniture au choix' => '7.00',
                'Carbonnade flamande avec garniture' => '7.00',
                'Langue de bœuf, pomme de terre, légumes, sauce' => '7.00',
                'Roti de porc Orloff, garniture au choix' => '7.00',
                'Filet mignon, pomme de terre, endive, sauce maroille' => '7.00',
                'Choucroute viande ou poisson, pommes vapeur' => '7.00',
                'Parmentier de canard' => '8.00',
                'Veau marengo, garniture au choix' => '8.00',
                'Couscous 3 viandes(mouton ou bœuf, poulet, merguez)' => '8.00',
                'Cassoulet au canard' => '8.00',
                'Tripes à la portugaise avec du riz' => '8.00',
                'Blanquette de veau à l\'ancienne, garniture au choix' => '8.00',
                'Poulpe mijoté aux légumes, pomme de terre' => '8.00',
            ),
            'Desserts' => array(
                'Pudding portugais (8 parts)' => '6.00',
                '6 pasteis de natas' => '4.00',
                'Tarte aux fruits (6 personnes)' => '4.00',
            ),
        );

        foreach ($data as $categoryName => $meals) {
            $category = $this->get('doctrine')->getRepository('AppBundle:Category')->findOneByName($categoryName);
            if (!$category) {
                throw new \InvalidArgumentException(sprintf('Found no category with name "%s".', $categoryName));
            }

            $position = 0;
            foreach ($meals as $name => $mealData) {
                $position++;

                if (is_string($mealData)) {
                    $mealData = array('price' => $mealData);
                }

                $mealData = array_merge(array(
                    'description' => null,
                ), $mealData);

                $meal = new Meal();
                $meal
                    ->setName($name)
                    ->setCategory($category)
                    ->setDescription($mealData['description'])
                    ->setPrice(new Price($mealData['price']))
                    ->setPosition($position)
                ;

                if (isset($mealData['active'])) {
                    $meal->setActive($mealData['active']);
                }

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
