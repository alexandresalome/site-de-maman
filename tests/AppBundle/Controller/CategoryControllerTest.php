<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Meal;
use AppBundle\Price\Price;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class CategoryControllerTest extends WebTestCase
{
    private $meal;
    private $client;

    public function setUp()
    {
        $this->client = self::createClient();
        $em = $this->getEntityManager($this->client);

        $this->meal = new Meal();
        $this->meal
            ->setName('Inactive')
            ->setDescription('Inactive')
            ->setPrice(new Price('12.00'))
            ->setActive(false)
        ;

        $category = $em->getRepository(Category::class)->findOneByName('Plats');
        $this->meal->setCategory($category);

        $em->persist($this->meal);
        $em->flush();
    }

    public function tearDown()
    {
        $em = $this->getEntityManager($this->client);

        $this->meal = $em->merge($this->meal);
        $em->remove($this->meal);
        $em->flush();
    }

    public function testInactive()
    {
        $client = $this->client;

        $crawler = $client->request('GET', '/menu/plats');

        $this->assertNotContains('Inactive', $crawler->text());
    }

    private function getEntityManager(Client $client)
    {
        $kernel = $client->getKernel();
        $kernel->boot();

        return $kernel
            ->getContainer()
            ->get('doctrine')
            ->getManager()
        ;
    }
}
