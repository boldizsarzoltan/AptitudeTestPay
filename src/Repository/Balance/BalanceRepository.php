<?php

namespace Paysera\CommissionTask\Repositories\Balance;

use Paysera\CommissionTask\Model\Balance;
use Paysera\CommissionTask\Model\Customer;

interface BalanceRepository
{
    public function getBalance(Customer $customer): Balance;
    public function saveBalance(Customer $customer, Balance $balance): void;
}
