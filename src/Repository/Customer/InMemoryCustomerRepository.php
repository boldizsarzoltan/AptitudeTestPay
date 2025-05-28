<?php

namespace Paysera\CommissionTask\Repository\Customer;

use Paysera\CommissionTask\Model\Customer;
use Paysera\CommissionTask\Repositories\Customer\CustomerRepository;

class InMemoryCustomerRepository implements CustomerRepository
{
    /**
     * @var array<int, Customer>
     */
    private array $customers;

    public function __construct()
    {
        $this->customers = [];
    }

    public function saveCustomer(Customer $customer): void
    {
        $this->customers[$customer->getCustomerId()] = $customer;
    }

    public function getCustomer(int $id): ?Customer
    {
        return $this->customers[$id] ?? null;
    }
}
