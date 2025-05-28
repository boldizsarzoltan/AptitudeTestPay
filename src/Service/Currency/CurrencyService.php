<?php

namespace Paysera\CommissionTask\Service\Currency;

use Paysera\CommissionTask\Model\Currency;
use Paysera\CommissionTask\Repositories\Currency\CurrencyRepository;
use Paysera\CommissionTask\Repositories\Customer\CustomerRepository;

class CurrencyService
{
    private CurrencyRepository $repository;

    public function __construct(
        CurrencyRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function convertTo(Currency $startCurrency, Currency $targetCurrency, float $amount): float
    {
        $conversionRate = $this->repository->getCurrencyConversionRate(
            $startCurrency,
            $targetCurrency
        );
        return $conversionRate * $amount;
    }

    public function convertToDefault(Currency $currency, float $amount): float
    {
        if ($currency->isDefault()) {
            return $amount;
        }
        $conversionRate = $this->repository->getCurrencyConversionRate(
            $currency,
            Currency::getDefaultCurrency()
        );
        return $conversionRate * $amount;
    }
}
