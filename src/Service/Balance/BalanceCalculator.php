<?php

namespace Paysera\CommissionTask\Service\Balance;

use Paysera\CommissionTask\Model\Balance;
use Paysera\CommissionTask\Model\OperationModel;
use Paysera\CommissionTask\Model\OperationResult;
use Paysera\CommissionTask\Model\OperationType;
use Paysera\CommissionTask\Repositories\Balance\BalanceRepository;
use Paysera\CommissionTask\Service\Currency\CurrencyService;

class BalanceCalculator
{
    private CurrencyService $currencyService;
    private BalanceRepository $repository;

    public function __construct(
        BalanceRepository $repository,
        CurrencyService $currencyService
    ) {
        $this->repository = $repository;
        $this->currencyService = $currencyService;
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
        $amount = $this->currencyService->convertToDefault($operationModel->getCurrency(), $result->fee);
        switch ($operationModel->getOperationType()->getOperationType()) {
            case OperationType::WITHDRAW:
                return new Balance(
                    $currentBalance->getDepositedAmount(),
                    $amount + $currentBalance->getWithdrawalAmount()
                );
            case OperationType::DEPOSIT:
                return new Balance(
                    $amount + $currentBalance->getDepositedAmount(),
                    $currentBalance->getWithdrawalAmount()
                );
        }
    }
}
