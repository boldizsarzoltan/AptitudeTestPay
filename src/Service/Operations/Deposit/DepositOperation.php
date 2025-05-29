<?php

namespace Paysera\CommissionTask\Service\Operations\Deposit;

use Paysera\CommissionTask\Model\OperationModel;
use Paysera\CommissionTask\Model\OperationResult;
use Paysera\CommissionTask\Model\OperationType;
use Paysera\CommissionTask\Service\Math;
use Paysera\CommissionTask\Service\Operations\Deposit\Rule\DepositRuleInterface;
use Paysera\CommissionTask\Service\Operations\Operation;

class DepositOperation implements Operation
{
    /**
     * @var array<DepositRuleInterface>
     */
    private array $operationRules;
    private Math $math;

    /**
     * @param array<DepositRuleInterface> $operationRules
     */
    public function __construct(
        array $operationRules,
        Math $math
    ) {
        $this->operationRules = $operationRules;
        $this->math = $math;
    }

    public function getType(): OperationType
    {
        return new OperationType(OperationType::DEPOSIT);
    }

    public function doOperation(OperationModel $operation): OperationResult
    {
        $commission  = 0;
        $currentlyProcessed  = 0;
        foreach ($this->operationRules as $operationRule) {
            if (!$operationRule->isMatch($operation)) {
                continue;
            }
            $matchedAmount = $operationRule->getMatchedAmount($operation);
            $currentlyProcessed = $this->math->add($currentlyProcessed, $matchedAmount);
            $commission = $this->math->add(
                $commission,
                $operationRule->getCommission($operation->getCopyWithNewAmount($matchedAmount))
            );
            if ($currentlyProcessed == $operation->getAmount()) {
                return new OperationResult($commission);
            }
        }
        throw new \RuntimeException('Commisioned rule did not match all the amount');
    }
}
