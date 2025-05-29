<?php

namespace Paysera\CommissionTask\Repository\Balance;

use Paysera\CommissionTask\Model\Balance;
use Paysera\CommissionTask\Model\Customer;

class InMemoryBalanceRepository implements BalanceRepository
{
    /**
     * @var array<int, Balance>
     */
    private array $balances;

    public function __construct()
    {
        $this->balances = [];
    }

    public function getBalance(Customer $customer): Balance
    {
        if (isset($this->balances[$customer->getCustomerId()])) {
            $this->balances[$customer->getCustomerId()] = new Balance(0, 0);
        }
        return $this->balances[$customer->getCustomerId()];
    }

    public function saveBalance(Customer $customer, Balance $balance): void
    {
        $this->balances[$customer->getCustomerId()] = $balance;
    }
}
