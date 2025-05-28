<?php

namespace Paysera\CommissionTask\Model;

class Balance
{
    private float $depositedAmount;
    private float $withdrawalAmount;

    public function __construct(
        float $depositedAmount,
        float $withdrawalAmount,
    ) {
        $this->depositedAmount = $depositedAmount;
        $this->withdrawalAmount = $withdrawalAmount;
    }

    public function getDepositedAmount(): float
    {
        return $this->depositedAmount;
    }

    public function getWithdrawalAmount(): float
    {
        return $this->withdrawalAmount;
    }
}
