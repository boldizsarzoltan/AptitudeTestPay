<?php

namespace Paysera\CommissionTask\Service\Mappers;

use Paysera\CommissionTask\Exception\InvalidOperationException;
use Paysera\CommissionTask\Model\Currency;
use Paysera\CommissionTask\Model\Customer;
use Paysera\CommissionTask\Model\CustomerType;
use Paysera\CommissionTask\Model\OperationModel;
use Paysera\CommissionTask\Model\OperationType;
use Paysera\CommissionTask\Repository\Currency\CurrencyRepository;

class OperationMapper
{
    private CurrencyRepository $currencyRepository;

    public function __construct(
        CurrencyRepository $currencyRepository
    ) {
        $this->currencyRepository = $currencyRepository;
    }


    public function mapOperation(array $operation): OperationModel
    {
        if (!isset($operation[0])) {
            throw new InvalidOperationException('Operation date must not be empty');
        }
        if (!isset($operation[1])) {
            throw new InvalidOperationException('Operation customer id must not be empty');
        }
        if (!isset($operation[2])) {
            throw new InvalidOperationException('Operation customer type must not be empty');
        }
        if (!isset($operation[3])) {
            throw new InvalidOperationException('Operation type must not be empty');
        }
        if (!isset($operation[4])) {
            throw new InvalidOperationException('Operation amount must not be empty');
        }
        if (!isset($operation[5])) {
            throw new InvalidOperationException('Operation currency must not be empty');
        }
        $date = new \DateTime($operation[0]);
        $customerType = new CustomerType($operation[2]);
        $customer = new Customer(
            $operation[1],
            $customerType
        );
        $operationType = new OperationType($operation[3]);
        $amount = $operation[4];
        $currency = $this->currencyRepository->getCurrency($operation[5]);
        return new OperationModel(
            $date,
            $customer,
            $operationType,
            $amount,
            $currency
        );
    }
}
