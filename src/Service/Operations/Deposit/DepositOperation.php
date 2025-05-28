<?php

namespace Paysera\CommissionTask\Service\Operations\Deposit;

use Paysera\CommissionTask\Model\OperationModel;
use Paysera\CommissionTask\Model\OperationResult;
use Paysera\CommissionTask\Model\OperationType;
use Paysera\CommissionTask\Service\Operations\Deposit\Rule\DepositRuleInterface;
use Paysera\CommissionTask\Service\Operations\Operation;

class DepositOperation implements Operation
{
    /**
     * @var array<DepositRuleInterface>
     */
    private array $operationRules;

    /**
     * @param array<DepositRuleInterface> $operationRules
     */
    public function __construct(
        array $operationRules
    )
    {
        $this->operationRules = $operationRules;
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
            if(!$operationRule->isMatch($operation)) {
                continue;
            }
            $matchedAmount = $operationRule->getMatchedAmount($operation);
            $commission += $operationRule->getCommission(
                $operation->getCopyWithNewAmount($matchedAmount)
            );
            if ($matchedAmount == $operation->getAmount()) {
                return new OperationResult($commission);
            }
        }
        throw new \RuntimeException('Commisioned rule did not match all the amount');
    }
}
