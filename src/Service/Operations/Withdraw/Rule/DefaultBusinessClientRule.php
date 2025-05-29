<?php

namespace Paysera\CommissionTask\Service\Operations\Withdraw\Rule;

use Paysera\CommissionTask\Model\OperationModel;
use Paysera\CommissionTask\Service\Math;

class DefaultBusinessClientRule implements WithdrawRuleInterface
{
    private Math $math;

    public function __construct(
        Math $math
    ) {
        $this->math = $math;
    }

    public function isMatch(OperationModel $operation): bool
    {
        return $operation->getCustomer()->getCustomerType()->isBusinessClient();
    }

    public function getMatchedAmount(OperationModel $operation): float
    {
        return $operation->getAmount();
    }

    public function getCommission(OperationModel $operation): float
    {
        // 0.5%= 0.5 /100 = 0.005
        return $this->math->multiply($operation->getAmount(), 0.005);
    }
}
