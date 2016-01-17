<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepageAction()
    {
        return $this->render('default/homepage.html.twig');
    }

    public function headerAction($active = null)
    {
        return $this->render('default/_header.html.twig', array(
            'categories' => $this->getDoctrine()->getRepository('AppBundle:Category')->findOrderedByPosition(),
            'active' => $active
        ));
    }
}
