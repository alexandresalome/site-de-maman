<?php

namespace AppBundle\Price;

/**
 * Price value object
 */
class Price
{
    const AMOUNT_FORMAT = '/^\d+(\.\d+)?$/';
    /**
     * @var string
     */
    private $amount;

    /**
     * @var string
     */
    private $currency;

    public function __construct($amount, $currency = 'EUR')
    {
        if (!is_string($amount) && !preg_match(self::AMOUNT_FORMAT, $amount)) {
            throw new \InvalidArgumentException(sprintf(
                'Malformed amount: "%s".',
                $amount
            ));
        }
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function add(Price $price)
    {
        $scale = max($this->getScale(), $price->getScale());

        return new Price(bcadd($this->amount, $price->getAmount(), $scale));
    }

    public function mul($factor)
    {
        return new Price(bcmul($this->amount, $factor, $this->getScale()));
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function getScale()
    {
        $pos = strrpos($this->amount, '.');

        if (false === $pos) {
            return 0;
        }

        return strlen($this->amount) - $pos - 1;
    }

    public function toArray()
    {
        return array($this->amount, $this->currency);
    }
}
