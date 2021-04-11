<?php

namespace App\Tests\Controller;

use App\Service\Sms\InMemorySms;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Mailer\DataCollector\MessageDataCollector;
use Symfony\Component\Mailer\Event\MessageEvent;
use Symfony\Component\Mime\Email;
use Symfony\Component\VarDumper\Cloner\Data;

class CartControllerTest extends AppWebTestCase
{
    public function testAddToCart()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/menu/plats');

        $this->addToCart('Tartiflette', 3, $crawler, $client);

        $crawler = $client->request('GET', '/cart');

        self::assertStringContainsString('Votre panier', $crawler->text());
        self::assertStringContainsString('Tartiflette', $crawler->text());
        self::assertStringContainsString('15,00 €', $crawler->text());
    }

    public function testOrder(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/menu/plats');
        $this->addToCart('Tartiflette', 3, $crawler, $client);

        $crawler = $client->request('GET', '/order');

        $form = $crawler->selectButton('Commander')->form();
        $crawler = $client->submit($form);
        self::assertStringContainsString('Cette valeur ne doit pas être vide.', $crawler->text());

        $form = $crawler->selectButton('Commander')->form(array(
            'order[fullname]' => 'Alice Bob',
            'order[phone]'    => '0123456789',
            'order[email]'    => 'alice@example.org',
        ));

        $client->enableProfiler();

        $client->submit($form);
        self::assertTrue($client->getResponse()->isRedirect(), $client->getCrawler()->text());

        /** @var MessageDataCollector $collector */
        $collector = $client->getProfile()->getCollector('mailer');
        $events = $collector->getEvents()->getEvents();
        self::assertCount(2, $events);

        // Owner email

        /** @var TemplatedEmail $message */
        $message = $events[0]->getMessage();
        self::assertEquals('demo@example.org', $message->getSender()->toString());
        self::assertEquals('"Demo" <demo@example.org>', $message->getTo()[0]->toString());
        self::assertStringContainsString('Nouvelle commande de Alice Bob pour le', $message->getSubject());

        // Customer email

        /** @var TemplatedEmail $message */
        $message = $events[1]->getMessage();
        self::assertCount(1, $message->getTo());
        self::assertEquals('"Alice Bob" <alice@example.org>', $message->getTo()[0]->toString());
        self::assertStringContainsString('Réception de votre commande', $message->getSubject());

        $sms = self::$container->get(InMemorySms::class);
        $messages = $sms->getMessages();
        self::assertCount(1, $messages);
        self::assertStringContainsString('Nouvelle commande de Alice Bob', $messages[0]);

        $crawler = $client->followRedirect();

        self::assertStringContainsString('Commande du', $crawler->text());
        self::assertStringContainsString('Alice Bob', $crawler->text());
    }

    private function addToCart(string $mealName, int $quantity, Crawler $crawler, KernelBrowser $client)
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

        self::assertEquals(200, $client->getResponse()->getStatusCode());
    }

    private function assertHasLog($expected, Data $logs)
    {
        $logs = $logs->getValue();
        foreach ($logs as $log) {
            if (false !== strpos($log['message'], $expected)) {
                return;
            }
        }

        throw new \RuntimeException('Message not found in logs.');
    }
}
