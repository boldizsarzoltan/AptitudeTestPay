<?php

namespace Paysera\CommissionTask\Service\Operations\Deposit\Rule;

use Paysera\CommissionTask\Model\OperationModel;
use Paysera\CommissionTask\Service\Math;

class DefaultDepositRule implements DepositRuleInterface
{
    private Math $math;

    public function __construct(Math $math)
    {
        $this->math = $math;
    }


    public function isMatch(OperationModel $operation): bool
    {
        return true;
    }

    public function getMatchedAmount(OperationModel $operation): float
    {
        return $operation->getAmount();
    }

    public function getCommission(OperationModel $operation): float
    {
        // 0.03% = 0.03 /100 = 0.0003
        return $this->math->multiply($operation->getAmount(), 0.0003);
    }
}
