<?php

namespace App\Tests\Controller;

use App\Entity\Order;

class AdminControllerTest extends AppWebTestCase
{
    public function testIndex_noOrder()
    {
        $client = self::createAdminClient();
        $this->deleteAllOrders();

        $crawler = $client->request('GET', '/admin');

        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertStringContainsString('Aucune commande', $crawler->text());
        self::assertStringNotContainsString('Voir toutes les commandes', $crawler->text());
    }

    public function testIndex_oneOrder()
    {
        $client = self::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));

        $this->deleteAllOrders();
        $this->createOrder('foo');

        $crawler = $client->request('GET', '/admin');

        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertStringContainsString('La dernière commande', $crawler->text());
        self::assertStringContainsString('Voir toutes les commandes', $crawler->text());
    }

    public function testIndex_twoOrders()
    {
        $client = self::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));

        $this->deleteAllOrders();
        $this->createOrder('foo');
        $this->createOrder('bar');

        $crawler = $client->request('GET', '/admin');

        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertStringContainsString('Les 2 dernières commandes', $crawler->text());
        self::assertStringContainsString('Voir toutes les commandes', $crawler->text());
    }

    private function deleteAllOrders(): void
    {
        $em = $this->getEntityManager();

        $orders = $em
            ->getRepository(Order::class)
            ->findAll()
        ;

        foreach ($orders as $order) {
            $em->remove($order);
            $em->flush();
        }
    }

    private function createOrder($identifier): void
    {
        $em = $this->getEntityManager();

        $order = new Order();
        $order
            ->setFullname(ucfirst($identifier))
            ->setEmail($identifier.'@example.org')
            ->setPhone('0123456789')
            ->setDate('Some date')
            ->setOrder(array(
                'rows' => array(
                    array('meal' => 'Meal '.$identifier, 'unit_price' => array('3.00', 'EUR'), 'quantity' => 3, 'price' => array('9.00', 'EUR'))
                ),
                'total_count' => 3,
                'total_price' => array('9.00', 'EUR')
            ))
        ;

        $em->persist($order);
        $em->flush();
    }
}
