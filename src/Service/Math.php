<?php

declare(strict_types=1);

namespace Paysera\CommissionTask\Service;

class Math
{
    private $scale;

    public function __construct(int $scale)
    {
        $this->scale = $scale;
    }

    public function add(string $leftOperand, string $rightOperand): string
    {
        return bcadd($leftOperand, $rightOperand, $this->scale);
    }

    public function multiply(float $amount, float $scale): float
    {
        return (float) bcmul((string)$amount, (string)$scale, $this->scale);
    }

    public function divide(float $num1, float $num2): float
    {
        return (float) bcdiv((string)$num1, (string)$num2, $this->scale);
    }
}
