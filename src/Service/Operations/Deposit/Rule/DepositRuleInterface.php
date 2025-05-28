<?php

namespace Paysera\CommissionTask\Service\Operations\Deposit\Rule;

use Paysera\CommissionTask\Model\OperationModel;

interface DepositRuleInterface
{
    public function isMatch(OperationModel $operation): bool;
    public function getMatchedAmount(OperationModel $operation): float;
    public function getCommission(OperationModel $operation): float;
}