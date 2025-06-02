<?php

namespace Paysera\CommissionTask\Service\Operations;

use Paysera\CommissionTask\Exception\UnknownOperationException;
use Paysera\CommissionTask\Model\OperationModel;
use Paysera\CommissionTask\Model\OperationResult;
use Paysera\CommissionTask\Repository\History\HistoryRepository;
use Paysera\CommissionTask\Service\HIstory\HistoryService;

class OperationRunner
{
    private HistoryService $balanceCalculator;
    /**
     * @var Operation[]
     */
    private array $operations;

    /**
     * @param HistoryService $balanceCalculator
     * @param array<Operation> $operations
     */
    public function __construct(
        HistoryService $balanceCalculator,
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
        $this->balanceCalculator->addHistory($operation, $operationResult);
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
