<?php

namespace App\Controller;

use App\Entity\Order;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminOrderController extends AbstractController
{
    /**
     * @Route(path="/admin/commandes", name="admin_order_index")
     */
    public function indexAction(): Response
    {
        return $this->render('admin_order/index.html.twig', array(
            'orders' => $this->getDoctrine()->getRepository(Order::class)->findLatest()
        ));
    }

    /**
     * @Route(path="/admin/commandes/{id}", name="admin_order_show")
     * @ParamConverter("id")
     */
    public function showAction(Order $order): Response
    {
        return $this->render('admin_order/show.html.twig', array(
            'order' => $order
        ));
    }

    /**
     * @Route(path="/admin/commandes/{id}/supprimer", name="admin_order_delete")
     * @ParamConverter("id")
     */
    public function deleteAction(Request $request, Order $order): Response
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
