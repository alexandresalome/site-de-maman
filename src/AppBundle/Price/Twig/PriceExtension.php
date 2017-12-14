<?php

namespace AppBundle\Price\Twig;

use AppBundle\Price\Price;

class PriceExtension extends \Twig_Extension
{
    private $numberFormatter;

    public function __construct($locale = 'fr_FR', $style = \NumberFormatter::CURRENCY)
    {
        $this->numberFormatter = new \NumberFormatter($locale, $style);
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('format_currency', array($this, 'formatCurrency'))
        );
    }

    public function formatCurrency($price)
    {
        if (is_array($price)) {
            $price = new Price($price[0], $price[1]);
        }

        $amount = $price->getAmount();
        $currency = $price->getCurrency();

        return $this->numberFormatter->formatCurrency($amount, $currency);
    }
}
