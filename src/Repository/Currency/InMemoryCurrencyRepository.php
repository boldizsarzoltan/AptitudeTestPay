<?php

namespace Paysera\CommissionTask\Repository\Currency;

use Paysera\CommissionTask\Exception\InvalidCurrencyTypeException;
use Paysera\CommissionTask\Model\Currency;
use Paysera\CommissionTask\Repositories\Currency\CurrencyRepository;

class InMemoryCurrencyRepository implements CurrencyRepository
{
    public function getCurrency(string $currency): Currency
    {
        // TODO: Implement getCurrency() method.
    }

    public function getCurrencyConversionRate(Currency $startCurrency, Currency $targetCurrency): float
    {
        // TODO: Implement getCurrencyConversionRate() method.
    }
}