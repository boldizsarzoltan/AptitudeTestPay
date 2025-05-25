<?php

namespace Paysera\CommissionTask\Model;

class Customer
{
    private int $customerId;
    private CustomerType $customerType;

    public function __construct(
        int $customerId,
        CustomerType $customerType,
    )
    {
        $this->customerId = $customerId;
        $this->customerType = $customerType;
    }

    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    public function setCustomerId(int $customerId): void
    {
        $this->customerId = $customerId;
    }

    public function getCustomerType(): CustomerType
    {
        return $this->customerType;
    }

    public function setCustomerType(CustomerType $customerType): void
    {
        $this->customerType = $customerType;
    }
}