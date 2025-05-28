<?php

namespace Paysera\CommissionTask\Repositories\Currency;

use Paysera\CommissionTask\Exception\InvalidCurrencyTypeException;
use Paysera\CommissionTask\Model\Currency;

interface CurrencyRepository
{
    /**
     * @param string $currency
     * @return Currency
     * @throws InvalidCurrencyTypeException
     */
    public function getCurrency(string $currency): Currency;

    public function getCurrencyConversionRate(Currency $startCurrency, Currency $targetCurrency): float;
}
