<?php

namespace App\Tests\Controller;

use App\Entity\Order;

class AdminOrderControllerTest extends AppWebTestCase
{
    public function testIndex_noOrder(): void
    {
        $client = self::createAdminClient();

        $this->deleteAllOrders();

        $crawler = $client->request('GET', '/admin/commandes');

        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertStringContainsString('Patience maman', $crawler->text());
    }

    public function testIndex(): void
    {
        $client = self::createAdminClient();

        $this->deleteAllOrders();
        $this->createOrder( 'foo');
        $this->createOrder('bar');

        $crawler = $client->request('GET', '/admin/commandes');

        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertStringContainsString('Foo', $crawler->text());
        self::assertStringContainsString('Bar', $crawler->text());
        self::assertStringContainsString('9,00 €', $crawler->text());
    }

    public function testShow(): void
    {
        $client = self::createAdminClient();

        $this->deleteAllOrders();
        $this->createOrder('bar');

        $crawler = $client->request('GET', '/admin/commandes');
        $link = $crawler->filter('a:contains("Ouvrir")')->link();

        $crawler = $client->click($link);

        self::assertStringContainsString('Meal bar', $crawler->text());
        self::assertStringContainsString('bar@example.org', $crawler->text());
        self::assertStringContainsString('3,00 €', $crawler->text());
        self::assertStringContainsString('9,00 €', $crawler->text());
    }

    public function testDelete(): void
    {
        $client = self::createAdminClient();

        $this->deleteAllOrders();
        $this->createOrder('bar');

        $crawler = $client->request('GET', '/admin/commandes');
        $link = $crawler->filter('a:contains("Ouvrir")')->link();
        $crawler = $client->click($link);

        $link = $crawler->filter('a:contains("Supprimer cette commande")')->link();
        $crawler = $client->click($link);

        self::assertStringContainsString('Vous êtes sur le point de supprimer la commande.', $crawler->text());

        $form = $crawler->filter('button:contains("Supprimer")')->form();
        $client->submit($form);
        $crawler = $client->followRedirect();

        self::assertStringContainsString('La commande de Bar a bien été supprimée.', $crawler->text());

    }

    public function testIndex_twoOrders(): void
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

    private function createOrder(string $identifier): void
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
