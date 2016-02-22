<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Client;

class AdminControllerTest extends AppWebTestCase
{
    public function testIndex_noOrder()
    {
        $client = self::createAdminClient();

        $this->deleteAllOrders($client);

        $crawler = $client->request('GET', '/admin/accueil');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Aucune commande', $crawler->text());
        $this->assertNotContains('Voir toutes les commandes', $crawler->text());
    }

    public function testIndex_oneOrder()
    {
        $client = self::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));

        $this->deleteAllOrders($client);
        $this->createOrder($client, 'foo');

        $crawler = $client->request('GET', '/admin/accueil');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('La dernière commande', $crawler->text());
        $this->assertContains('Voir toutes les commandes', $crawler->text());
    }

    public function testIndex_twoOrders()
    {
        $client = self::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));

        $this->deleteAllOrders($client);
        $this->createOrder($client, 'foo');
        $this->createOrder($client, 'bar');

        $crawler = $client->request('GET', '/admin/accueil');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Les 2 dernières commandes', $crawler->text());
        $this->assertContains('Voir toutes les commandes', $crawler->text());
    }

    private function deleteAllOrders(Client $client)
    {
        $em = $this->getEntityManager($client);

        $orders = $em
            ->getRepository(Order::class)
            ->findAll()
        ;

        foreach ($orders as $order) {
            $em->remove($order);
            $em->flush();
        }
    }

    private function createOrder(Client $client, $identifier)
    {
        $em = $this->getEntityManager($client);

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
