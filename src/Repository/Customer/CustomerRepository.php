<?php

namespace Paysera\CommissionTask\Repository\Customer;

use Paysera\CommissionTask\Model\Customer;

interface CustomerRepository
{
    public function saveCustomer(Customer $customer): void;

    public function getCustomer(int $id): ?Customer;
}
