<?php

namespace Paysera\CommissionTask\Service\Operations\Withdraw\Rule;

use Paysera\CommissionTask\Model\CustomerType;
use Paysera\CommissionTask\Model\OperationModel;

class DefaultPrivateClientRule implements WithdrawRuleInterface
{
    public function isMatch(OperationModel $operation): bool
    {
        return $operation->getCustomer()->getCustomerType() === CustomerType::PRIVATE_CLIENT;
    }

    public function getMatchedAmount(OperationModel $operation): float
    {
        return $operation->getAmount();
    }

    public function getCommission(OperationModel $operation): float
    {
        return $operation->getAmount() / 1000 * 3;
    }
}