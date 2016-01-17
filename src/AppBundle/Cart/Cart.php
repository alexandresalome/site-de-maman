<?php

namespace AppBundle\Cart;

use AppBundle\Entity\Meal;

class Cart
{
    private $rows = array();

    public function getTotal()
    {
        $total = 0;
        foreach ($this->rows as $row) {
            $total += $row->getQuantity();
        }

        return $total;
    }

    public function getTotalPrice()
    {
        $total = '0';
        foreach ($this->rows as $row) {
            $total = bcadd($total, $row->getPrice());
        }

        return $total;
    }

    public function isEmpty()
    {
        return empty($this->rows);
    }

    public function addRow(CartRow $row)
    {
        $this->rows[] = $row;
    }

    public function addMeal(Meal $meal, $quantity = 1)
    {
        $found = null;
        foreach ($this->rows as $row) {
            if ($row->getMeal() === $meal) {
                $found = $row;

                break;
            }
        }

        if ($found) {
            $row->addQuantity($quantity);

            return;
        }

        $row = new CartRow($meal, $quantity);
        $this->addRow($row);
    }

    public function setMeal(Meal $meal, $quantity)
    {
        if ($quantity == 0) {
            foreach ($this->rows as $i => $row) {
                if ($row->getMeal() !== $meal) {
                    continue;
                }
                unset($this->rows[$i]);
            }

            return;
        }

        $found = null;
        foreach ($this->rows as $row) {
            if ($row->getMeal() === $meal) {
                $found = $row;

                break;
            }
        }

        if ($found) {
            $row->setQuantity($quantity);

            return;
        }

        $row = new CartRow($meal, $quantity);
        $this->addRow($row);
    }

    public function getRows()
    {
        return $this->rows;
    }
}
