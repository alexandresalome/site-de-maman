<?php

namespace App\Tests\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AppWebTestCase extends WebTestCase
{
    protected function getEntityManager(): EntityManagerInterface
    {
        self::bootKernel();

        return self::$container->get(EntityManagerInterface::class);
    }

    protected static function createAdminClient(): KernelBrowser
    {
        return self::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));
    }
}
