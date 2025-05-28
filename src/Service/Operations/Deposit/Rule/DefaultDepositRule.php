<?php

namespace Paysera\CommissionTask\Service\Operations\Deposit\Rule;

use Paysera\CommissionTask\Model\OperationModel;

class DefaultDepositRule implements DepositRuleInterface
{
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
        return round(
            $operation->getAmount() / 1000 * 3,
            2
        );
    }
}