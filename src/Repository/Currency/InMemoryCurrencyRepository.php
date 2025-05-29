<?php

namespace Paysera\CommissionTask\Repository\Currency;

use Paysera\CommissionTask\Exception\InvalidCurrencyTypeException;
use Paysera\CommissionTask\Model\Currency;
use Paysera\CommissionTask\Model\CurrencyCollection;
use Paysera\CommissionTask\Service\Currency\CurrencyFetcherService;
use Paysera\CommissionTask\Service\Math;

class InMemoryCurrencyRepository implements CurrencyRepository
{
    private CurrencyCollection $currencies;
    private CurrencyFetcherService $service;
    private Math $math;

    public function __construct(
        CurrencyFetcherService $service,
        Math $math
    ) {
        $this->service = $service;
        $this->math = $math;
        $this->initCurrencies();
    }

    public function getCurrency(string $currency): Currency
    {
        if (!isset($this->currencies->getRates()[$currency]) && $currency !== Currency::DEFAULT_CURRENCY) {
            throw new InvalidCurrencyTypeException($currency);
        }
        return new Currency($currency);
    }

    /**
     * @throws InvalidCurrencyTypeException
     */
    public function getCurrencyConversionRate(Currency $startCurrency, Currency $targetCurrency): float
    {
        if (
            !isset($this->currencies->getRates()[$targetCurrency->getCurrency()]) &&
            !$targetCurrency->isDefault()
        ) {
            throw new InvalidCurrencyTypeException($targetCurrency->getCurrency());
        }
        if (
            !isset($this->currencies->getRates()[$startCurrency->getCurrency()]) &&
            !$startCurrency->isDefault()
        ) {
            throw new InvalidCurrencyTypeException($startCurrency->getCurrency());
        }
        if ($targetCurrency->isDefault() && $startCurrency->isDefault()) {
            return 1;
        }
        if ($targetCurrency->isDefault()) {
            return $this->currencies->getRates()[$startCurrency->getCurrency()];
        }
        if ($startCurrency->isDefault()) {
            return $this->math->divide(1, $this->currencies->getRates()[$targetCurrency->getCurrency()]);
        }
        return $this->math->multiply(
            $this->currencies->getRates()[$startCurrency->getCurrency()],
            $this->math->divide(1, $this->currencies->getRates()[$targetCurrency->getCurrency()])
        );
    }

    private function initCurrencies(): void
    {
        if (!isset($this->currencies)) {
            $this->currencies = $this->service->fetchCurrencies();
        }
    }
}
