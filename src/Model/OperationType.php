<?php

namespace Paysera\CommissionTask\Model;

use Paysera\CommissionTask\Exception\InvalidOperationTypeException;

class OperationType
{
    public const DEPOSIT = 'deposit';
    public const WITHDRAW = 'withdraw';
    private const ALLOWED_OPERATIONS = [
        self::DEPOSIT,
        self::WITHDRAW,
    ];
    private string $operationType;

    /**
     * @throws InvalidOperationTypeException
     */
    public function __construct(string $operationType)
    {
        $this->validateOperationType($operationType);
        $this->operationType = $operationType;
    }

    private function validateOperationType(string $operationType): void
    {
        if (!in_array($operationType, self::ALLOWED_OPERATIONS)) {
            throw new InvalidOperationTypeException("Invalid operation type '{$operationType}'.");
        }
    }

    public function getOperationType(): string
    {
        return $this->operationType;
    }
}