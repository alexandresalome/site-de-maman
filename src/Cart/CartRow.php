<?php

namespace App\Cart;

use App\Entity\Meal;

class CartRow
{
    private $meal;

    private $quantity;

    public function __construct(Meal $meal, $quantity = 1)
    {
        $this->meal = $meal;
        $this->quantity = (int) $quantity;
    }

    public function getPrice()
    {
        return $this->meal->getPrice()->mul($this->quantity);
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
        $this->quantity = (int) $quantity;

        return $this;
    }

    public function toArray()
    {
        return array(
            'meal' => $this->meal->getName(),
            'quantity' => $this->quantity,
            'unit_price' => $this->meal->getPrice()->toArray(),
            'price' => $this->getPrice()->toArray()
        );
    }
}
