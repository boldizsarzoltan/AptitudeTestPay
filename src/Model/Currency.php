<?php

namespace Paysera\CommissionTask\Model;

class Currency
{
    public const DEFAULT_CURRENCY = 'EUR';
    private string $currency;

    public function __construct(string $currency)
    {
        $this->currency = $currency;
    }

    public static function getDefaultCurrency(): Currency
    {
        return new self(self::DEFAULT_CURRENCY);
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function isDefault(): bool
    {
        return $this->currency === self::DEFAULT_CURRENCY;
    }

}