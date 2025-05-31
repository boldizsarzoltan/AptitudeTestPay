<?php

namespace Paysera\CommissionTask\Service\HIstory;

use Paysera\CommissionTask\Model\History;
use Paysera\CommissionTask\Model\OperationModel;
use Paysera\CommissionTask\Model\OperationResult;
use Paysera\CommissionTask\Model\OperationType;
use Paysera\CommissionTask\Repository\Balance\HistoryRepository;
use Paysera\CommissionTask\Service\Currency\CurrencyService;
use Paysera\CommissionTask\Service\Math;

class HistoryService
{
    private CurrencyService $currencyService;
    private HistoryRepository $repository;
    private Math $math;

    public function __construct(
        HistoryRepository $repository,
        CurrencyService $currencyService,
        Math $math
    ) {
        $this->repository = $repository;
        $this->currencyService = $currencyService;
        $this->math = $math;
    }

    public function addHistory(OperationModel $operationModel, OperationResult $result): void
    {
        $currentBalance = $this->repository->getHistory(
            $operationModel->getCustomer(),
            $operationModel->getDateTime()
        );
        $newBalance = $this->modifyHistory($currentBalance, $result, $operationModel);
        $this->repository->saveHistory(
            $operationModel->getCustomer(),
            $newBalance,
            $operationModel->getDateTime()
        );
    }

    private function modifyHistory(
        History $currentBalance,
        OperationResult $result,
        OperationModel $operationModel
    ): History {
        $amount = $this->currencyService->convertToDefault(
            $operationModel->getCurrency(),
            $operationModel->getAmount()
        );
        switch ($operationModel->getOperationType()->getOperationType()) {
            case OperationType::WITHDRAW:
                return new History(
                    $currentBalance->getDepositedAmount(),
                    $this->math->add($amount, $currentBalance->getWithdrawalAmount()),
                    $currentBalance->getDepositCount() + 1,
                    $currentBalance->getWithdrawalCount()
                );
            case OperationType::DEPOSIT:
                return new History(
                    $this->math->add($amount, $currentBalance->getWithdrawalAmount()),
                    $currentBalance->getWithdrawalAmount(),
                    $currentBalance->getDepositCount(),
                    $currentBalance->getWithdrawalCount() + 1
                );
        }
        throw new \RuntimeException('Unknown operation type in history');
    }
}
