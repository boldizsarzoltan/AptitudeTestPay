<?php

namespace Paysera\CommissionTask\Service\Currency;

use Paysera\CommissionTask\Model\Currency;
use Paysera\CommissionTask\Repository\Currency\CurrencyRepository;
use Paysera\CommissionTask\Service\Math;

class CurrencyService
{
    private CurrencyRepository $repository;
    private Math $math;

    public function __construct(
        CurrencyRepository $repository,
        Math $math
    ) {
        $this->repository = $repository;
        $this->math = $math;
    }

    public function convertTo(Currency $startCurrency, Currency $targetCurrency, float $amount): float
    {
        $conversionRate = $this->repository->getCurrencyConversionRate(
            $startCurrency,
            $targetCurrency
        );
        return $this->math->multiply($amount, $conversionRate);
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
        return $this->math->multiply($amount, $conversionRate);
    }
}
