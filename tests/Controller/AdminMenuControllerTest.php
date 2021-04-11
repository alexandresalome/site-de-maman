<?php

namespace App\Tests\Controller;

use App\Entity\Category;
use App\Entity\Meal;
use App\Price\Price;

class AdminMenuControllerTest extends AppWebTestCase
{
    public function testIndex(): void
    {
        $client = self::createAdminClient();

        $crawler = $client->request('GET', '/admin/menu');

        self::assertSame(200, $client->getResponse()->getStatusCode());
        self::assertStringContainsString('Sur le pouce', $crawler->text());
    }

    public function testCategoryEdit(): void
    {
        $client = self::createAdminClient();

        $this->deleteCategory('Test edited');
        $category = $this->createCategory('Test');

        $crawler = $client->request('GET', '/admin/menu/category/'.$category->getId());

        self::assertSame(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Enregistrer')->form(array(
            'category[name]' => 'Test edited'
        ));

        $client->submit($form);
        self::assertTrue($client->getResponse()->isRedirect(), $client->getCrawler()->text());

        $crawler = $client->followRedirect();
        self::assertStringContainsString('Catégorie "Test edited" mise à jour.', $crawler->text());
        $crawler = $client->reload();
        self::assertStringContainsString('Test edited', $crawler->text());
    }

    public function testCategoryCreate(): void
    {
        $client = self::createAdminClient();

        $this->deleteCategory('Test created');

        $crawler = $client->request('GET', '/admin/menu/create-category');

        self::assertSame(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Enregistrer')->form(array(
            'category[name]' => 'Test created',
            'category[position]' => '42'
        ));

        $client->submit($form);
        self::assertTrue($client->getResponse()->isRedirect(), $client->getCrawler()->text());

        $crawler = $client->followRedirect();
        self::assertStringContainsString('Catégorie "Test created" créée.', $crawler->text());
        $crawler = $client->reload();
        self::assertStringContainsString('Test created', $crawler->text());
    }

    public function testCategoryDelete(): void
    {
        $client = self::createAdminClient();

        $category = $this->createCategory('To delete');

        $crawler = $client->request('GET', '/admin/menu/category/'.$category->getId().'/delete');

        self::assertSame(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Supprimer')->form();
        $client->submit($form);
        self::assertTrue($client->getResponse()->isRedirect(), $client->getCrawler()->text());

        $crawler = $client->followRedirect();
        self::assertStringContainsString('La catégorie To delete a bien été supprimée.', $crawler->text());
        $crawler = $client->reload();
        self::assertStringNotContainsString('To delete', $crawler->text());
    }

    public function testMealEdit(): void
    {
        $client = self::createAdminClient();

        $category = $this->createCategory('Test');
        $meal = $this->createMeal($category, 'Test');

        $crawler = $client->request('GET', '/admin/menu/meal/'.$meal->getId());

        self::assertSame(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Enregistrer')->form(array(
            'meal[name]' => 'New meal name'
        ));

        $client->submit($form);
        self::assertTrue($client->getResponse()->isRedirect(), $client->getCrawler()->text());

        $crawler = $client->followRedirect();
        self::assertStringContainsString('Plat "New meal name" mis à jour.', $crawler->text());
        $crawler = $client->reload();
        self::assertStringContainsString('New meal name', $crawler->text());
    }

    public function testMealCreate(): void
    {
        $client = self::createAdminClient();

        $category = $this->createCategory('Test');

        $crawler = $client->request('GET', '/admin/menu/create-meal/'.$category->getId());

        self::assertSame(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Enregistrer')->form(array(
            'meal[name]' => 'Meal created',
            'meal[position]' => 1,
            'meal[price][amount]' => '2.00',
            'meal[active]' => '1'
        ));

        $client->submit($form);
        self::assertTrue($client->getResponse()->isRedirect(), $client->getCrawler()->text());

        $crawler = $client->followRedirect();
        self::assertStringContainsString('Plat "Meal created" créé.', $crawler->text());
        $crawler = $client->reload();
        self::assertStringContainsString('Meal created', $crawler->text());
    }

    public function testMealDelete(): void
    {
        $client = self::createAdminClient();

        $category = $this->createCategory('Test');
        $meal = $this->createMeal($category, 'To delete');

        $crawler = $client->request('GET', '/admin/menu/meal/'.$meal->getId().'/delete');

        self::assertSame(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Supprimer')->form();
        $client->submit($form);
        self::assertTrue($client->getResponse()->isRedirect(), $client->getCrawler()->text());

        $crawler = $client->followRedirect();
        self::assertStringContainsString('Le plat To delete a bien été supprimé.', $crawler->text());
        $crawler = $client->reload();
        self::assertStringNotContainsString('To delete', $crawler->text());
    }

    private function createCategory(string $name): Category
    {
        $this->deleteCategory($name);
        $em = $this->getEntityManager();

        $category = new Category();
        $category
            ->setName($name)
            ->setPosition(1)
        ;

        $em->persist($category);
        $em->flush();

        return $category;
    }

    private function deleteCategory(string $name): void
    {
        $em = $this->getEntityManager();

        $category = $em->getRepository(Category::class)->findOneByName($name);
        if ($category) {
            $em->remove($category);
            $em->flush();
        }
    }

    private function createMeal(Category $category, string $name): Meal
    {
        $em = $this->getEntityManager();
        $category = $em->getRepository(Category::class)->find($category->getId());

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
