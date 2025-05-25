<?php

namespace Paysera\CommissionTask\Model;

use Paysera\CommissionTask\Exception\InvalidOperationTypeException;

class OperationResult
{
    public function __construct(float $fee)
    {
        $this->fee = $fee;
    }
}