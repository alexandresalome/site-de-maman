<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AppWebTestCase extends WebTestCase
{
    protected function getEntityManager(Client $client)
    {
        $kernel = $client->getKernel();
        $kernel->boot();

        return $kernel
            ->getContainer()
            ->get('doctrine')
            ->getManager()
        ;
    }

    static protected function createAdminClient()
    {
        return self::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));
    }
}
