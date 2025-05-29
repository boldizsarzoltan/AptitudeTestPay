<?php

namespace Paysera\CommissionTask\Service\Operations\Withdraw\Rule;

use Paysera\CommissionTask\Model\OperationModel;

interface WithdrawRuleInterface
{
    public function isMatch(OperationModel $operation): bool;
    public function getMatchedAmount(OperationModel $operation): float;
    public function getCommission(OperationModel $operation): float;
}
