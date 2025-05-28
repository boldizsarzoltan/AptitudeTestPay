<?php

namespace Paysera\CommissionTask\Model;

use Paysera\CommissionTask\Exception\InvalidCustomerTypeException;

class CustomerType
{
    public const PRIVATE_CLIENT = 'private';
    public const BUSINESS_CLIENT = 'business';
    private const ALLOWED_TYPES = [
        self::PRIVATE_CLIENT,
        self::BUSINESS_CLIENT,
    ];
    private string $clientType;

    public function __construct(string $clientType)
    {
        $this->validateClientType($clientType);
        $this->clientType = $clientType;
    }

    public function getClientType(): string
    {
        return $this->clientType;
    }

    private function validateClientType(string $clientType): void
    {
        if (!in_array($clientType, self::ALLOWED_TYPES)) {
            throw new InvalidCustomerTypeException("Client type '$clientType' is not valid");
        }
    }
}
