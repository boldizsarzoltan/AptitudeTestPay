<?php

namespace Paysera\CommissionTask\Service\Operations\Withdraw;

use Paysera\CommissionTask\Model\OperationModel;
use Paysera\CommissionTask\Model\OperationResult;
use Paysera\CommissionTask\Model\OperationType;
use Paysera\CommissionTask\Service\Operations\Operation;

class WithdrawOperation implements Operation
{
    public function getType(): OperationType
    {
        return new OperationType(OperationType::WITHDRAW);
    }

    public function doOperation(OperationModel $operation): OperationResult
    {
        // TODO: Implement doOperation() method.
    }
}