<?php

namespace Paysera\CommissionTask\Model;

class History
{
    private float $depositedAmount;
    private float $withdrawalAmount;
    private int $depositCount;
    private int $withdrawalCount;

    public function __construct(
        float $depositedAmount,
        float $withdrawalAmount,
        int $depositCount,
        int $withdrawalCount
    ) {
        $this->depositedAmount = $depositedAmount;
        $this->withdrawalAmount = $withdrawalAmount;
        $this->depositCount = $depositCount;
        $this->withdrawalCount = $withdrawalCount;
    }

    public function getDepositedAmount(): float
    {
        return $this->depositedAmount;
    }

    public function getWithdrawalAmount(): float
    {
        return $this->withdrawalAmount;
    }

    public function getDepositCount(): int
    {
        return $this->depositCount;
    }

    public function getWithdrawalCount(): int
    {
        return $this->withdrawalCount;
    }
}
