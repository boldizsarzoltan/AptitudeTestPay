<?php

namespace Paysera\CommissionTask\Service\Validators;

use Paysera\CommissionTask\Exception\CustomerValidationFailedException;
use Paysera\CommissionTask\Model\Customer;
use Paysera\CommissionTask\Repositories\Customer\CustomerRepository;

class CustomerValidator
{
    private CustomerRepository $repository;

    public function __construct(CustomerRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws CustomerValidationFailedException
     */
    public function validateCustomer(Customer $actualCustomer): void
    {
        $savedCustomer = $this->repository->getCustomer($actualCustomer->getCustomerId());
        if (!$savedCustomer) {
            return;
        }
        if (
            $actualCustomer->getCustomerType()->getClientType() !==
            $savedCustomer->getCustomerType()->getClientType()
        ) {
            throw new CustomerValidationFailedException("Customer type changed between operations");
        }
    }
}
