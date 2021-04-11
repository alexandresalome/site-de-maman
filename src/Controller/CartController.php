<?php

namespace App\Controller;

use App\Cart\Cart;
use App\Cart\CartSerializer;
use App\Entity\Meal;
use App\Entity\Order;
use App\Event\OrderCreatedEvent;
use App\Form\Type\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class CartController extends AbstractController
{
    private CartSerializer $serializer;

    public function __construct(CartSerializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @Route(path="/cart", methods="POST", name="cart_setMeal")
     */
    public function setMealAction(Request $request): Response
    {
        $mode = $request->request->get('mode', 'set');

        $mealId = $request->request->get('meal');
        $meal = $this->getDoctrine()->getRepository(Meal::class)->find($mealId);
        if (!$meal) {
            throw $this->createNotFoundException(sprintf(
                'No meal found with id "%s".', $mealId
            ));
        }

        $quantity = (int) $request->request->get('quantity');
        $quantity = max(0, min(1000, $quantity));

        $cart = $this->getCart($request);

        if ($mode === 'add') {
            $cart->addMeal($meal, $quantity);
        } elseif ($mode === 'set') {
            $cart->setMeal($meal, $quantity);
        }

        $this->saveCart($request, $cart);

        return $this->forward(__CLASS__ .'::panelAction');
    }

    /**
     * @Route(path="/cart", methods="GET", name="cart_show")
     */
    public function showAction(Request $request): Response
    {
        return $this->render('cart/show.html.twig', array(
            'cart' => $this->getCart($request)
        ));
    }

    /**
     * @Route(path="/order", methods="GET|POST", name="cart_order")
     */
    public function orderAction(EventDispatcherInterface $eventDispatcher, Request $request): Response
    {
        $form = $this->createForm(OrderType::class);

        $cart = $this->getCart($request);

        if ($cart->isEmpty()) {
            return $this->redirectToRoute('cart_show');
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();
            $order->loadFromCart($this->getCart($request));

            $em = $this->getDoctrine()->getManagerForClass(Order::class);
            $em->persist($order);
            $em->flush();

            $eventDispatcher->dispatch(new OrderCreatedEvent($order));

            $this->cleanCart($request);

            return $this->redirectToRoute('order_show', array('id' => $order->getId()));
        }

        return $this->render('cart/order.html.twig', array(
            'cart' => $cart,
            'form' => $form->createView(),
        ));
    }

    public function panelAction(Request $request): Response
    {
        return $this->render('cart/_panel.html.twig', array(
            'cart' => $this->getCart($request),
        ));
    }

    private function getCart(Request $request): Cart
    {
        $cartData = $request->getSession()->get('cart');
        if (null === $cartData) {
            return new Cart();
        }

        return $this->serializer->deserialize($cartData);
    }

    private function saveCart(Request $request, Cart $cart): void
    {
        $data = $this->serializer->serialize($cart);
        $request->getSession()->set('cart', $data);
    }

    private function cleanCart(Request $request): void
    {
        $request->getSession()->set('cart', null);
    }
}
