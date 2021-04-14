<?php

namespace App\Twig;

use App\Price\Price;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class PriceExtension extends AbstractExtension
{
    private \NumberFormatter $numberFormatter;

    public function __construct($locale = 'fr_FR', $style = \NumberFormatter::CURRENCY)
    {
        $this->numberFormatter = new \NumberFormatter($locale, $style);
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('format_price', [$this, 'formatCurrency']),
        ];
    }

    public function formatCurrency($price): string
    {
        if (is_array($price)) {
            $price = new Price($price[0], $price[1]);
        }

        $amount = $price->getAmount();
        $currency = $price->getCurrency();

        return $this->numberFormatter->formatCurrency($amount, $currency);
    }
}
