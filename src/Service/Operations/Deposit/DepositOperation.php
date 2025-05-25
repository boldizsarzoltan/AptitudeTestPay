<?php

namespace Paysera\CommissionTask\Service\Operations\Deposit;

use Paysera\CommissionTask\Model\OperationModel;
use Paysera\CommissionTask\Model\OperationResult;
use Paysera\CommissionTask\Model\OperationType;
use Paysera\CommissionTask\Service\Operations\Operation;

class DepositOperation implements Operation
{
    public function getType(): OperationType
    {
        return new OperationType(OperationType::DEPOSIT);
    }

    public function doOperation(OperationModel $operation): OperationResult
    {
        // TODO: Implement doOperation() method.
    }
}