<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PortageController extends AbstractController
{
    /**
     * @Route("/portage", name="portage")
     */
    public function portageAction(): Response
    {
        return $this->render('default/portage.html.twig');
    }

}
