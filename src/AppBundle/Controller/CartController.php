<?php

namespace AppBundle\Controller;

use AppBundle\Cart\Cart;
use AppBundle\Cart\CartSerializer;
use AppBundle\Entity\Order;
use AppBundle\Form\Type\OrderType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CartController extends Controller
{
    /**
     * @Route(path="/cart", methods="POST", name="cart_setMeal")
     */
    public function setMealAction(Request $request)
    {
        $mode = $request->request->get('mode', 'set');

        $mealId = $request->request->get('meal');
        $meal = $this->getDoctrine()->getRepository('AppBundle:Meal')->find($mealId);
        if (!$meal) {
            throw $this->createNotFoundException(sprintf(
                'No meal found with id "%s".', $mealId
            ));
        }

        $quantity = (int) $request->request->get('quantity');
        $quantity = max(0, min(1000, $quantity));

        $cart = $this->getCart($request);

        if ($mode == 'add') {
            $cart->addMeal($meal, $quantity);
        } elseif ($mode == 'set') {
            $cart->setMeal($meal, $quantity);
        }

        $this->saveCart($request, $cart);

        return $this->forward('AppBundle:Cart:panel');
    }

    /**
     * @Route(path="/cart", methods="GET", name="cart_show")
     */
    public function showAction(Request $request)
    {
        return $this->render('cart/show.html.twig', array(
            'cart' => $this->getCart($request)
        ));
    }

    /**
     * @Route(path="/order", methods="GET|POST", name="cart_order")
     */
    public function orderAction(Request $request)
    {
        $form = $this->createForm(OrderType::class);

        $cart = $this->getCart($request);

        if ($cart->isEmpty()) {
            return $this->redirectToRoute('cart_show');
        }

        if ($form->handleRequest($request)->isValid()) {
            $order = $form->getData();
            $order->loadFromCart($this->getCart($request));

            $em = $this->getDoctrine()->getManagerForClass(Order::class);
            $em->persist($order);
            $em->flush();

            return $this->redirectToRoute('order_show', array('id' => $order->getId()));
        }

        return $this->render('cart/order.html.twig', array(
            'cart' => $cart,
            'form' => $form->createView(),
        ));
    }

    public function panelAction(Request $request)
    {
        return $this->render('cart/_panel.html.twig', array(
            'cart' => $this->getCart($request)
        ));
    }

    private function getCart(Request $request)
    {
        $cartData = $request->getSession()->get('cart');
        if (null === $cartData) {
            return new Cart();
        }

        $serializer = new CartSerializer($this->getDoctrine()->getManager());

        return $serializer->deserialize($cartData);
    }

    private function saveCart(Request $request, Cart $cart)
    {
        $serializer = new CartSerializer($this->getDoctrine()->getManager());
        $data = $serializer->serialize($cart);

        $request->getSession()->set('cart', $data);
    }
}
