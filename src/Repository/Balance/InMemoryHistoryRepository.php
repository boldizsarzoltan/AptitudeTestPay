<?php

namespace Paysera\CommissionTask\Repository\Balance;

use Paysera\CommissionTask\Model\History;
use Paysera\CommissionTask\Model\Customer;
use Paysera\CommissionTask\Utils\DateTimeService;

class InMemoryHistoryRepository implements HistoryRepository
{
    /**
     * @var array<int, History>
     */
    private array $balances;

    public function __construct(
        DateTimeService $service
    )
    {
        $this->balances = [];
    }

    public function getHistory(Customer $customer, \DateTime $dateTime): History
    {
        $currentRelevantTimeFormat = $this->getCurrentRelevantTimeFormat($dateTime);
        if (!isset($this->balances[$customer->getCustomerId()][$currentRelevantTimeFormat])) {
            $this->balances[$customer->getCustomerId()][$currentRelevantTimeFormat] = new History(
                0,
                0,
                0,
                0
            );
        }
        return $this->balances[$customer->getCustomerId()][$currentRelevantTimeFormat];
    }

    public function saveHistory(Customer $customer, History $balance, \DateTime $dateTime): void
    {
        $this->balances[$customer->getCustomerId()][$this->getCurrentRelevantTimeFormat($dateTime)] = $balance;
    }

    private function getCurrentRelevantTimeFormat(\DateTime $dateTime): string
    {
        $currentTime = new DateTimeService();
        return $currentTime->getCurrentDateTime($dateTime)->format("o-W");
    }
}
