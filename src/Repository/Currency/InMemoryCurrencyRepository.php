<?php

namespace Paysera\CommissionTask\Repository\Currency;

use GuzzleHttp\Client;
use Paysera\CommissionTask\Exception\InvalidCurrencyTypeException;
use Paysera\CommissionTask\Model\Currency;
use Paysera\CommissionTask\Model\CurrencyCollection;
use Paysera\CommissionTask\Repositories\Currency\CurrencyRepository;
use Paysera\CommissionTask\Service\Currency\CurrencyFetcherService;

class InMemoryCurrencyRepository implements CurrencyRepository
{
    private CurrencyCollection $currencies;
    private CurrencyFetcherService $service;

    public function __construct(
        CurrencyFetcherService $service
    ) {
        $this->service = $service;
        $this->initCurrencies();
    }

    public function getCurrency(string $currency): Currency
    {
        if (!isset($this->currencies->getRates()[$currency]) && $currency !== Currency::DEFAULT_CURRENCY) {
            throw new InvalidCurrencyTypeException($currency);
        }
        return new Currency($currency);
    }

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
            return 1/$this->currencies->getRates()[$targetCurrency->getCurrency()];
        }
        return $this->currencies->getRates()[$startCurrency->getCurrency()] *
            (1/$this->currencies->getRates()[$targetCurrency->getCurrency()]);
    }

    private function initCurrencies(): void
    {
        if (!isset($this->currencies)) {
            $this->currencies = $this->service->fetchCurrencies();
        }
    }
}
