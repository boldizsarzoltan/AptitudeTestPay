<?php

namespace Paysera\CommissionTask\Service\Balance;

use Paysera\CommissionTask\Model\Balance;
use Paysera\CommissionTask\Model\OperationModel;
use Paysera\CommissionTask\Model\OperationResult;
use Paysera\CommissionTask\Model\OperationType;
use Paysera\CommissionTask\Repository\Balance\BalanceRepository;
use Paysera\CommissionTask\Service\Currency\CurrencyService;
use Paysera\CommissionTask\Service\Math;

class BalanceCalculator
{
    private CurrencyService $currencyService;
    private BalanceRepository $repository;
    private Math $math;

    public function __construct(
        BalanceRepository $repository,
        CurrencyService $currencyService,
        Math $math
    ) {
        $this->repository = $repository;
        $this->currencyService = $currencyService;
        $this->math = $math;
    }

    public function calculateAndSaveBalance(OperationModel $operationModel, OperationResult $result): void
    {
        $currentBalance = $this->repository->getBalance($operationModel->getCustomer());
        $newBalance = $this->calculateBalance($currentBalance, $result, $operationModel);
        $this->repository->saveBalance($operationModel->getCustomer(), $newBalance);
    }

    private function calculateBalance(
        Balance $currentBalance,
        OperationResult $result,
        OperationModel $operationModel
    ): Balance {
        $amount = $this->currencyService->convertToDefault($operationModel->getCurrency(), $result->getFee());
        switch ($operationModel->getOperationType()->getOperationType()) {
            case OperationType::WITHDRAW:
                return new Balance(
                    $currentBalance->getDepositedAmount(),
                    $this->math->add($amount, $currentBalance->getWithdrawalAmount())
                );
            case OperationType::DEPOSIT:
                return new Balance(
                    $this->math->add($amount, $currentBalance->getWithdrawalAmount()),
                    $currentBalance->getWithdrawalAmount()
                );
        }
        throw new \RuntimeException('Unknown operation type');
    }
}
