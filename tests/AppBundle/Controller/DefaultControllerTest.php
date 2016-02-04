<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $client->enableProfiler();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Liencourt', $crawler->text());

        $qc = $client->getProfile()->getCollector('db')->getQueryCount();
        $this->assertLessThan(5, $qc);

        $this->assertContains('franco-portugais', $crawler->filter('title')->text());

        $filtered = $crawler->filter('p')->reduce(function ($e) {
            return false !== strpos($e->text(), 'morue');
        });

        $this->assertCount(1, $filtered);
    }

    public function testMonkey()
    {
        $client = static::createClient();
        $alreadyChecked = array();
        $this->checkThisPage('/', $client, $alreadyChecked);
    }

    public function testEntrees()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $link = $crawler->selectLink('Entrées')->link();

        $crawler = $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    private function checkThisPage($href, $client, &$alreadyChecked)
    {
        if (in_array($href, $alreadyChecked)) {
            return;
        }

        $crawler = $client->request('GET', $href);
        $this->assertTrue($client->getResponse()->isSuccessful());

        $alreadyChecked[] = $href;

        $links = $crawler->filter('a');
        foreach ($links->extract(array('href')) as $href) {
            $this->checkThisPage($href, $client, $alreadyChecked);
        }
    }
}
