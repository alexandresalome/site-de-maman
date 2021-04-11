<?php

namespace App\Cart;

use App\Entity\Meal;
use Doctrine\ORM\EntityManagerInterface;

class CartSerializer
{
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function serialize(Cart $cart): array
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

    public function deserialize(array $data): Cart
    {
        $cart = new Cart();
        foreach ($data['rows'] as $id => $row) {
            $meal = $this->manager->getRepository(Meal::class)->find($id);
            if (!$meal) {
                continue;
            }

            $row = new CartRow($meal, $row['quantity']);
            $cart->addRow($row);
        }

        return $cart;
    }
}
