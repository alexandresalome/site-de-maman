<?php

namespace AppBundle\Cart;

use AppBundle\Entity\Meal;

class CartRow
{
    private $meal;

    private $quantity;

    public function __construct(Meal $meal, $quantity = 1)
    {
        $this->meal = $meal;
        $this->quantity = $quantity;
    }

    public function getPrice()
    {
        return bcmul($this->quantity, $this->meal->getPrice());
    }

    public function getMeal()
    {
        return $this->meal;
    }

    public function addQuantity($quantity)
    {
        $this->quantity = (int) $this->quantity + $quantity;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }
}
