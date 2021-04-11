<?php

namespace App\Tests\Controller;

use App\Entity\Category;
use App\Entity\Meal;
use App\Price\Price;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class CategoryControllerTest extends AppWebTestCase
{
    private Meal $meal;
    private KernelBrowser $client;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = self::createClient();
        $this->meal = new Meal();
        $this->meal
            ->setName('Inactive')
            ->setDescription('Inactive')
            ->setPrice(new Price('12.00'))
            ->setActive(false)
        ;

        $em = $this->getEntityManager();

        $category = $em->getRepository(Category::class)->findOneByName('Plats');
        $this->meal->setCategory($category);

        $em->persist($this->meal);
        $em->flush();
    }

    public function tearDown(): void
    {
        $em = $this->getEntityManager();

        $meal = $em->getRepository(Meal::class)->find($this->meal->getId());
        $em->remove($meal);
        $em->flush();

        parent::tearDown();
    }

    public function testInactive(): void
    {
        $crawler = $this->client->request('GET', '/menu/plats');

        self::assertStringNotContainsString('Inactive', $crawler->text());
    }
}
