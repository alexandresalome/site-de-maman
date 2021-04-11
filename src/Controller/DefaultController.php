<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepageAction(): Response
    {
        return $this->render('default/homepage.html.twig');
    }

    public function headerAction($active = null): Response
    {
        return $this->render('default/_header.html.twig', array(
            'categories' => $this->getDoctrine()->getRepository(Category::class)->findOrderedByPosition(),
            'active' => $active
        ));
    }
}
