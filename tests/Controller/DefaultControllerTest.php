<?php

namespace App\Tests\Controller;

use Symfony\Bridge\Doctrine\DataCollector\DoctrineDataCollector;

class DefaultControllerTest extends AppWebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->enableProfiler();
        $crawler = $client->request('GET', '/');

        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertStringContainsString('Liencourt', $crawler->text());

        /** @var DoctrineDataCollector $collector */
        $collector = $client->getProfile()->getCollector('db');
        $qc = $collector->getQueryCount();
        self::assertLessThan(5, $qc);

        self::assertStringContainsString('franco-portugais', $crawler->filter('title')->text());

        $filtered = $crawler->filter('p')->reduce(function ($e) {
            return false !== strpos($e->text(), 'morue');
        });

        self::assertCount(1, $filtered);
    }

    public function testMonkey(): void
    {
        $client = static::createClient();
        $alreadyChecked = array();
        $this->checkThisPage('/', $client, $alreadyChecked);
    }

    public function testEntrees()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $link = $crawler->selectLink('Entrée')->link();

        $client->click($link);

        self::assertEquals(200, $client->getResponse()->getStatusCode());
    }

    private function checkThisPage($href, $client, &$alreadyChecked): void
    {
        if (in_array($href, $alreadyChecked, true)) {
            return;
        }

        $crawler = $client->request('GET', $href);
        self::assertTrue($client->getResponse()->isSuccessful());

        $alreadyChecked[] = $href;

        $links = $crawler->filter('a');
        foreach ($links->extract(array('href')) as $loopHref) {
            $this->checkThisPage($loopHref, $client, $alreadyChecked);
        }
    }
}
