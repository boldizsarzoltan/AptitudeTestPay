<?php

namespace Paysera\CommissionTask\Model;

class CurrencyCollection
{
    /**
     * @param array<string, float> $rates
     */
    public function __construct(
        array $rates,
        string $date,
        string $baseCurrency
    ) {
        $this->rates = $rates;
        $this->date = $date;
        $this->baseCurrency = $baseCurrency;
    }

    public function getBaseCurrency(): string
    {
        return $this->baseCurrency;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getRates(): array
    {
        return $this->rates;
    }
}
