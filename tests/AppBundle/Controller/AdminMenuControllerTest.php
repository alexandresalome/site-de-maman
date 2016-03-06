<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Meal;
use AppBundle\Entity\Order;
use AppBundle\Price\Price;
use Behat\Transliterator\Transliterator;
use Symfony\Bundle\FrameworkBundle\Client;

class AdminMenuControllerTest extends AppWebTestCase
{
    public function testIndex()
    {
        $client = self::createAdminClient();

        $crawler = $client->request('GET', '/admin/menu');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Sur le pouce', $crawler->text());
    }

    public function testCategoryEdit()
    {
        $client = self::createAdminClient();

        $category = $this->deleteCategory($client, 'Test edited');
        $category = $this->createCategory($client, 'Test');

        $crawler = $client->request('GET', '/admin/menu/category/'.$category->getId());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Enregistrer')->form(array(
            'category[name]' => 'Test edited'
        ));

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect(), $client->getCrawler()->text());

        $crawler = $client->followRedirect();
        $this->assertContains('Catégorie "Test edited" mise à jour.', $crawler->text());
        $crawler = $client->reload();
        $this->assertContains('Test edited', $crawler->text());
    }

    public function testCategoryCreate()
    {
        $client = self::createAdminClient();

        $category = $this->deleteCategory($client, 'Test created');

        $crawler = $client->request('GET', '/admin/menu/create-category');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Enregistrer')->form(array(
            'category[name]' => 'Test created',
            'category[position]' => '42'
        ));

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect(), $client->getCrawler()->text());

        $crawler = $client->followRedirect();
        $this->assertContains('Catégorie "Test created" créée.', $crawler->text());
        $crawler = $client->reload();
        $this->assertContains('Test created', $crawler->text());
    }

    public function testCategoryDelete()
    {
        $client = self::createAdminClient();

        $category = $this->createCategory($client, 'To delete');

        $crawler = $client->request('GET', '/admin/menu/category/'.$category->getId().'/delete');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Supprimer')->form();
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect(), $client->getCrawler()->text());

        $crawler = $client->followRedirect();
        $this->assertContains('La catégorie To delete a bien été supprimée.', $crawler->text());
        $crawler = $client->reload();
        $this->assertNotContains('To delete', $crawler->text());
    }

    public function testMealEdit()
    {
        $client = self::createAdminClient();

        $category = $this->createCategory($client, 'Test');
        $meal = $this->createMeal($client, $category, 'Test');

        $crawler = $client->request('GET', '/admin/menu/meal/'.$meal->getId());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Enregistrer')->form(array(
            'meal[name]' => 'New meal name'
        ));

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect(), $client->getCrawler()->text());

        $crawler = $client->followRedirect();
        $this->assertContains('Plat "New meal name" mis à jour.', $crawler->text());
        $crawler = $client->reload();
        $this->assertContains('New meal name', $crawler->text());
    }

    public function testMealCreate()
    {
        $client = self::createAdminClient();

        $category = $this->createCategory($client, 'Test');

        $crawler = $client->request('GET', '/admin/menu/create-meal/'.$category->getId());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Enregistrer')->form(array(
            'meal[name]' => 'Meal created',
            'meal[position]' => 1,
            'meal[price][amount]' => '2.00',
            'meal[active]' => '1'
        ));

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect(), $client->getCrawler()->text());

        $crawler = $client->followRedirect();
        $this->assertContains('Plat "Meal created" créé.', $crawler->text());
        $crawler = $client->reload();
        $this->assertContains('Meal created', $crawler->text());
    }

    public function testMealDelete()
    {
        $client = self::createAdminClient();

        $category = $this->createCategory($client, 'Test');
        $meal = $this->createMeal($client, $category, 'To delete');

        $crawler = $client->request('GET', '/admin/menu/meal/'.$meal->getId().'/delete');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Supprimer')->form();
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect(), $client->getCrawler()->text());

        $crawler = $client->followRedirect();
        $this->assertContains('Le plat To delete a bien été supprimé.', $crawler->text());
        $crawler = $client->reload();
        $this->assertNotContains('To delete', $crawler->text());
    }

    private function createCategory(Client $client, $name, $mealCount = 1)
    {
        $this->deleteCategory($client, $name);
        $em = $this->getEntityManager($client);

        $category = new Category();
        $category
            ->setName($name)
            ->setPosition(1)
        ;

        $em->persist($category);
        $em->flush();

        while ($mealCount > 0) {
            $this->createMeal($client, $category, 'Meal '.$mealCount);
            $mealCount--;
        }

        return $category;
    }

    private function deleteCategory(Client $client, $name)
    {
        $em = $this->getEntityManager($client);

        $category = $em->getRepository(Category::class)->findOneByName($name);
        if ($category) {
            $em->remove($category);
            $em->flush();
        }
    }

    private function createMeal(Client $client, Category $category, $name)
    {
        $em = $this->getEntityManager($client);
        $em->merge($category);

        $meal = new Meal();
        $meal
            ->setName($name)
            ->setCategory($category)
            ->setPosition(1)
            ->setPrice(new Price('1.23'))
        ;

        $em->persist($meal);
        $em->flush();

        return $meal;
    }
}
