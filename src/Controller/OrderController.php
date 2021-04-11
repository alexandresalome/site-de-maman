<?php

namespace App\Controller;

use App\Entity\Order;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route(path="/order/{id}", methods="GET", name="order_show")
     * @ParamConverter("id")
     */
    public function showAction(Order $order): Response
    {
        return $this->render('order/show.html.twig', array(
            'order' => $order
        ));
    }
}
