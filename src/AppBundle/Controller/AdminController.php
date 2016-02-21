<?php

namespace AppBundle\Controller;

use AppBundle\Cart\Cart;
use AppBundle\Cart\CartSerializer;
use AppBundle\Entity\Order;
use AppBundle\Event\OrderEvent;
use AppBundle\Event\OrderEvents;
use AppBundle\Form\Type\OrderType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    /**
     * @Route(path="/admin/commandes", name="admin_orders")
     */
    public function ordersAction()
    {
        return $this->render('admin/orders.html.twig', array(
            'orders' => $this->getDoctrine()->getRepository(Order::class)->findLatest()
        ));
    }

    /**
     * @Route(path="/admin/commandes/{id}", name="admin_order")
     * @ParamConverter
     */
    public function orderAction(Order $order)
    {
        return $this->render('admin/order.html.twig', array(
            'order' => $order
        ));
    }
}
