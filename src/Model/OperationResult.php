<?php

namespace Paysera\CommissionTask\Model;

use Paysera\CommissionTask\Exception\InvalidOperationTypeException;

class OperationResult
{
    private float $fee;

    public function __construct(float $fee)
    {
        $this->fee = $fee;
    }

    public function getFee(): float
    {
        return $this->fee;
    }
}
