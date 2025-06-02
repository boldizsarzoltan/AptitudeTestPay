<?php

namespace Paysera\CommissionTask\Repository\History;

use Paysera\CommissionTask\Model\History;
use Paysera\CommissionTask\Model\Customer;

interface HistoryRepository
{
    public function getHistory(Customer $customer, \DateTime $dateTime): History;
    public function saveHistory(Customer $customer, History $balance, \DateTime $dateTime): void;
}
