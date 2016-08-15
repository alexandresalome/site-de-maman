<?php

namespace AppBundle\Controller;

use Symfony\Component\BrowserKit\Client;
use Symfony\Component\DomCrawler\Crawler;

class CartControllerTest extends AppWebTestCase
{
    public function testAddToCart()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/menu/plats');

        $this->addToCart('Tartiflette', 3, $crawler, $client);

        $crawler = $client->request('GET', '/cart');

        $this->assertContains('Votre panier', $crawler->text());
        $this->assertContains('Tartiflette', $crawler->text());
        $this->assertContains('15,00 €', $crawler->text());
    }

    public function testOrder()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/menu/plats');
        $this->addToCart('Tartiflette', 3, $crawler, $client);

        $crawler = $client->request('GET', '/order');

        $form = $crawler->selectButton('Commander')->form();
        $crawler = $client->submit($form);
        $this->assertContains('Cette valeur ne doit pas être vide.', $crawler->text());

        $form = $crawler->selectButton('Commander')->form(array(
            'order[fullname]' => 'Alice Bob',
            'order[phone]'    => '0123456789',
            'order[email]'    => 'alice@example.org',
        ));

        $client->enableProfiler();

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect(), $client->getCrawler()->text());

        $collector = $client->getProfile()->getCollector('swiftmailer');
        $this->assertEquals(2, $collector->getMessageCount());
        $message = $collector->getMessages()[0];
        $this->assertEquals(array('owner@example.org' => "Owner"), $message->getTo());
        $this->assertContains('Notification de commande', $message->getSubject());

        $message = $collector->getMessages()[1];
        $this->assertEquals(array('alice@example.org' => "Alice Bob"), $message->getTo());
        $this->assertContains('Réception de votre commande', $message->getSubject());

        $collector = $client->getProfile()->getCollector('logger');
        $this->assertHasLog('Notified owner', $collector->getLogs());

        $crawler = $client->followRedirect();

        $this->assertContains('Commande du', $crawler->text());
        $this->assertContains('Alice Bob', $crawler->text());
    }

    private function addToCart($mealName, $quantity, Crawler $crawler, Client $client)
    {
        $titles = $crawler->filter('h4')->reduce(function ($crawler) use ($mealName) {
            return false !== strpos($crawler->text(), $mealName);
        });

        if (count($titles) !== 1) {
            throw new \RuntimeException(sprintf('Expected 1 title containing "%s", found %s.', $mealName, count($titles)));
        }

        $link = $titles->eq(0)->parents()->first()->filter('input[data-meal]');

        $mealId = $link->attr('data-meal');

        $client->request('POST', '/cart', array(
            'meal' => $mealId,
            'mode' => 'add',
            'quantity' => $quantity
        ));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    private function assertHasLog($expected, array $logs)
    {
        foreach ($logs as $log) {
            if (false !== strpos($log['message'], $expected)) {
                return;
            }
        }

        throw new \RuntimeException('Message not found in logs.');
    }
}
