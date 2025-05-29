<?php

namespace Paysera\CommissionTask\Service\Operations\Withdraw;

use Paysera\CommissionTask\Model\OperationModel;
use Paysera\CommissionTask\Model\OperationResult;
use Paysera\CommissionTask\Model\OperationType;
use Paysera\CommissionTask\Service\Math;
use Paysera\CommissionTask\Service\Operations\Operation;
use Paysera\CommissionTask\Service\Operations\Withdraw\Rule\WithdrawRuleInterface;

class WithdrawOperation implements Operation
{
    /**
     * @var array<WithdrawRuleInterface>
     */
    private array $operationRules;
    private Math $math;

    /**
     * @param array<WithdrawRuleInterface> $operationRules
     */
    public function __construct(
        array $operationRules,
        Math $math
    )
    {
        $this->operationRules = $operationRules;
        $this->math = $math;
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
    public function getType(): OperationType
    {
        return new OperationType(OperationType::WITHDRAW);
    }
}
