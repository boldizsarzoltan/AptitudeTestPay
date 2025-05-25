<?php

namespace Paysera\CommissionTask\Model;

class OperationModel
{
    private \DateTime $dateTime;
    private Customer $customer;
    private OperationType $operationType;
    private float $amount;
    private Currency $currency;

    public function __construct(
        \DateTime $dateTime,
        Customer $customer,
        OperationType $operationType,
        float $amount,
        Currency $currency,
    ) {
        $this->dateTime = $dateTime;
        $this->customer = $customer;
        $this->operationType = $operationType;
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function getDateTime(): \DateTime
    {
        return $this->dateTime;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function getOperationType(): OperationType
    {
        return $this->operationType;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }
}