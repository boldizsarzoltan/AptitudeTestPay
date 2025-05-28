<?php

namespace Paysera\CommissionTask\Service\Operations\Withdraw\Rule;

use Paysera\CommissionTask\Model\Currency;
use Paysera\CommissionTask\Model\CustomerType;
use Paysera\CommissionTask\Model\OperationModel;
use Paysera\CommissionTask\Repositories\Balance\BalanceRepository;
use Paysera\CommissionTask\Service\Currency\CurrencyService;

class PrivateClientFirstThousandEuroFreeRule implements WithdrawRuleInterface
{
    private BalanceRepository $repository;

    public function __construct(
        BalanceRepository $repository,
        CurrencyService  $currencyService
    ) {
        $this->repository = $repository;
        $this->currencyService = $currencyService;
    }

    public function isMatch(OperationModel $operation): bool
    {
        if ($operation->getCustomer()->getCustomerType() !== CustomerType::PRIVATE_CLIENT) {
            return false;
        }
        $currentBalance = $this->repository->getBalance($operation->getCustomer());
        return $currentBalance->getWithdrawalAmount() < 1000;
    }

    public function getMatchedAmount(OperationModel $operation): float
    {
        $currentBalance = $this->repository->getBalance($operation->getCustomer());
        $currentOperation = $this->currencyService->convertToDefault($operation->getCurrency(), $operation->getAmount());
        $amountInDefault = max(1000 - $currentOperation - $currentBalance->getWithdrawalAmount(), 0);
        return $this->currencyService->convertTo(
            Currency::getDefaultCurrency(),
            $operation->getCurrency(),
            $amountInDefault
        );
    }

    public function getCommission(OperationModel $operation): float
    {
        return 0;
    }
}