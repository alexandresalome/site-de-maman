<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    /**
     * @Route(path="/admin/accueil", name="admin_index")
     */
    public function homepageAction()
    {
        return $this->render('admin/index.html.twig', array(
            'orders' => $this->getDoctrine()->getRepository(Order::class)->findLatest(5)
        ));
    }
}
