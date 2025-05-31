<?php

namespace Paysera\CommissionTask\Service\Operations\Withdraw\Rule;

use Paysera\CommissionTask\Model\Currency;
use Paysera\CommissionTask\Model\CustomerType;
use Paysera\CommissionTask\Model\OperationModel;
use Paysera\CommissionTask\Repository\Balance\HistoryRepository;
use Paysera\CommissionTask\Service\Currency\CurrencyService;

class PrivateClientFirstThousandEuroFreeRule implements WithdrawRuleInterface
{
    private HistoryRepository $repository;
    private CurrencyService $currencyService;

    public function __construct(
        HistoryRepository $repository,
        CurrencyService   $currencyService
    ) {
        $this->repository = $repository;
        $this->currencyService = $currencyService;
    }

    public function isMatch(OperationModel $operation): bool
    {
        if (!$operation->getCustomer()->getCustomerType()->isPrivateClient()) {
            return false;
        }
        $currentHistory = $this->repository->getHistory(
            $operation->getCustomer(),
            $operation->getDateTime()
        );
        if ($currentHistory->getWithdrawalCount() > 3) {
            return false;
        }
        return $currentHistory->getWithdrawalAmount() < 1000;
    }

    public function getMatchedAmount(OperationModel $operation): float
    {
        $currentHistory = $this->repository->getHistory(
            $operation->getCustomer(),
            $operation->getDateTime()
        );
        $currentOperationInDefault = $this->currencyService->convertToDefault(
            $operation->getCurrency(),
            $operation->getAmount()
        );
        $matchedAmount = (float) min(
            max(1000 - $currentHistory->getWithdrawalAmount(), 0),
            $currentOperationInDefault
        );
        if ($matchedAmount == $currentOperationInDefault) {
            return $operation->getAmount();
        }
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
