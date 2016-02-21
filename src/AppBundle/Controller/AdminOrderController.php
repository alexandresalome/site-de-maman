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

class AdminOrderController extends Controller
{
    /**
     * @Route(path="/admin/commandes", name="admin_order_index")
     */
    public function indexAction()
    {
        return $this->render('admin_order/index.html.twig', array(
            'orders' => $this->getDoctrine()->getRepository(Order::class)->findLatest()
        ));
    }

    /**
     * @Route(path="/admin/commandes/{id}", name="admin_order_show")
     * @ParamConverter
     */
    public function showAction(Order $order)
    {
        return $this->render('admin_order/show.html.twig', array(
            'order' => $order
        ));
    }

    /**
     * @Route(path="/admin/commandes/{id}/supprimer", name="admin_order_delete")
     * @ParamConverter
     */
    public function deleteAction(Request $request, Order $order)
    {
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($order);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'La commande de '.$order->getFullname().' a bien été supprimée.');

            return $this->redirectToRoute('admin_order_index');
        }

        return $this->render('admin_order/delete.html.twig', array(
            'order' => $order
        ));
    }
}
