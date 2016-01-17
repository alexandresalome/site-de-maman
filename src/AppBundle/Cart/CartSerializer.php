<?php

namespace AppBundle\Cart;

use Doctrine\Common\Persistence\ObjectManager;

class CartSerializer
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function serialize(Cart $cart)
    {
        $data = array(
            'rows' => array()
        );

        foreach ($cart->getRows() as $row) {
            $data['rows'][$row->getMeal()->getId()] = array(
                'quantity' => $row->getQuantity()
            );
        }

        return $data;
    }

    public function deserialize(array $data)
    {
        $cart = new Cart();
        foreach ($data['rows'] as $id => $row) {
            $meal = $this->manager->getRepository('AppBundle:Meal')->find($id);
            if (!$meal) {
                continue;
            }

            $row = new CartRow($meal, $row['quantity']);
            $cart->addRow($row);
        }

        return $cart;
    }
}
