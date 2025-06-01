<?php

namespace Paysera\CommissionTask\Service\Currency;

use Paysera\CommissionTask\Model\CurrencyCollection;

interface CurrencyFetcher
{
    public function fetchCurrencies(): CurrencyCollection;
}
