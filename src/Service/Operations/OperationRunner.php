<?php

namespace Paysera\CommissionTask\Service\Operations;

use Paysera\CommissionTask\Exception\UnknownOperationException;
use Paysera\CommissionTask\Model\OperationModel;
use Paysera\CommissionTask\Model\OperationResult;
use Paysera\CommissionTask\Repositories\Balance\BalanceRepository;
use Paysera\CommissionTask\Service\Balance\BalanceCalculator;

class OperationRunner
{
    private BalanceCalculator $balanceCalculator;
    /**
     * @var Operation[]
     */
    private array $operations;

    /**
     * @param BalanceCalculator $balanceCalculator
     * @param array<Operation> $operations
     */
    public function __construct(
        BalanceCalculator $balanceCalculator,
        array $operations
    ) {
        $this->balanceCalculator = $balanceCalculator;
        /**
         * @var Operation $operation
         */
        foreach ($operations as $operation) {
            $this->operations[$operation->getType()->getOperationType()] = $operation;
        }
    }

    public function runOperation(OperationModel $operation): OperationResult
    {
        $operationResult = $this->doOperation($operation);
        $this->balanceCalculator->calculateAndSaveBalance($operation, $operationResult);
        return $operationResult;
    }

    private function doOperation(OperationModel $operation): OperationResult
    {
        if (!isset($this->operations[$operation->getOperationType()->getOperationType()])) {
            throw new UnknownOperationException(
                "{$operation->getOperationType()->getOperationType()} is not handled"
            );
        }
        /**
         * @var Operation $currentOperation
         */
        $currentOperation = $this->operations[$operation->getOperationType()->getOperationType()];
        return $currentOperation->doOperation($operation);
    }
}
