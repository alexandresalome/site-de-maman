<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Client;

class AdminOrderControllerTest extends AppWebTestCase
{
    public function testIndex_noOrder()
    {
        $client = self::createAdminClient();

        $this->deleteAllOrders($client);

        $crawler = $client->request('GET', '/admin/commandes');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Patience maman', $crawler->text());
    }

    public function testIndex()
    {
        $client = self::createAdminClient();

        $this->deleteAllOrders($client);
        $this->createOrder($client, 'foo');
        $this->createOrder($client, 'bar');

        $crawler = $client->request('GET', '/admin/commandes');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Foo', $crawler->text());
        $this->assertContains('Bar', $crawler->text());
        $this->assertContains('9,00 €', $crawler->text());
    }

    public function testShow()
    {
        $client = self::createAdminClient();

        $this->deleteAllOrders($client);
        $this->createOrder($client, 'bar');

        $crawler = $client->request('GET', '/admin/commandes');
        $link = $crawler->filter('a:contains("Ouvrir")')->link();

        $crawler = $client->click($link);

        $this->assertContains('Meal bar', $crawler->text());
        $this->assertContains('bar@example.org', $crawler->text());
        $this->assertContains('3,00 €', $crawler->text());
        $this->assertContains('9,00 €', $crawler->text());
    }

    public function testDelete()
    {
        $client = self::createAdminClient();

        $this->deleteAllOrders($client);
        $this->createOrder($client, 'bar');

        $crawler = $client->request('GET', '/admin/commandes');
        $link = $crawler->filter('a:contains("Ouvrir")')->link();
        $crawler = $client->click($link);

        $link = $crawler->filter('a:contains("Supprimer cette commande")')->link();
        $crawler = $client->click($link);

        $this->assertContains('Vous êtes sur le point de supprimer la commande.', $crawler->text());

        $form = $crawler->filter('button:contains("Supprimer")')->form();
        $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertContains('La commande de Bar a bien été supprimée.', $crawler->text());

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

        $crawler = $client->request('GET', '/admin');

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
