<?php

namespace Paysera\CommissionTask\Service\Operations\Withdraw\Rule;

use Paysera\CommissionTask\Model\Currency;
use Paysera\CommissionTask\Model\CustomerType;
use Paysera\CommissionTask\Model\OperationModel;
use Paysera\CommissionTask\Repository\Balance\BalanceRepository;
use Paysera\CommissionTask\Service\Currency\CurrencyService;

class PrivateClientFirstThousandEuroFreeRule implements WithdrawRuleInterface
{
    private BalanceRepository $repository;
    private CurrencyService $currencyService;

    public function __construct(
        BalanceRepository $repository,
        CurrencyService $currencyService
    ) {
        $this->repository = $repository;
        $this->currencyService = $currencyService;
    }

    public function isMatch(OperationModel $operation): bool
    {
        if (!$operation->getCustomer()->getCustomerType()->isPrivateClient()) {
            return false;
        }
        $currentBalance = $this->repository->getBalance($operation->getCustomer());
        return $currentBalance->getWithdrawalAmount() < 1000;
    }

    public function getMatchedAmount(OperationModel $operation): float
    {
        $currentBalance = $this->repository->getBalance($operation->getCustomer());
        $currentOperationInDefault = $this->currencyService->convertToDefault(
            $operation->getCurrency(),
            $operation->getAmount()
        );
        $matchedAmount = (float) min(
            max(1000 - $currentBalance->getWithdrawalAmount(), 0),
            $currentOperationInDefault
        );
        return $this->currencyService->convertTo(
            Currency::getDefaultCurrency(),
            $operation->getCurrency(),
            $matchedAmount
        );
    }

    public function getCommission(OperationModel $operation): float
    {
        return 0;
    }
}
