<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OrderController extends Controller
{
    /**
     * @Route(path="/order/{id}", methods="GET", name="order_show")
     * @ParamConverter
     */
    public function showAction(Order $order)
    {
        return $this->render('order/show.html.twig', array(
            'order' => $order
        ));
    }
}
