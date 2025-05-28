<?php

namespace Paysera\CommissionTask\Service\Operations\Withdraw\Rule;

use Paysera\CommissionTask\Model\Customer;
use Paysera\CommissionTask\Model\CustomerType;
use Paysera\CommissionTask\Model\OperationModel;

class DefaultBusinessClientRule implements WithdrawRuleInterface
{
    public function isMatch(OperationModel $operation): bool
    {
        return $operation->getCustomer()->getCustomerType() === CustomerType::BUSINESS_CLIENT;
    }

    public function getMatchedAmount(OperationModel $operation): float
    {
        return $operation->getAmount();
    }

    public function getCommission(OperationModel $operation): float
    {
        return round(
            $operation->getAmount() / 200,
            2
        );
    }
}