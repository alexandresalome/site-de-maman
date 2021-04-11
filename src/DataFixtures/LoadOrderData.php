<?php

namespace App\DataFixtures;

use App\Entity\Order;
use Doctrine\Persistence\ObjectManager;

class LoadOrderData extends Fixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $data = array(
            'rows' => array(
                array(
                    'meal' => 'Part de pizza',
                    'quantity' => 1,
                    'unit_price' => array('2.00', 'EUR'),
                    'price' => array('2.00', 'EUR'),
                ),
                array(
                    'meal' => 'Muffin',
                    'quantity' => 2,
                    'unit_price' => array('4.00', 'EUR'),
                    'price' => array('8.00', 'EUR'),
                ),
            ),
            'total_count' => 3,
            'total_price' => array('10.00', 'EUR')
        );

        $order = new Order();
        $order
            ->setOrder($data)
            ->setFullname('Firstname Lastname')
            ->setPhone('0123456789')
            ->setEmail('user@example.org')
            ->setDate('Expected date')
        ;

        $manager->persist($order);
        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 3;
    }
}
