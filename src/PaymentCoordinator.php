<?php

namespace Paysera\CommissionTask;
use Paysera\CommissionTask\Model\OperationResult;
use Paysera\CommissionTask\Service\CsvReader;
use Paysera\CommissionTask\Service\Mappers\OperationMapper;
use Paysera\CommissionTask\Service\Operations\Operation;
use Paysera\CommissionTask\Service\Operations\OperationRunner;
use Paysera\CommissionTask\Service\Validators\CustomerValidator;

class PaymentCoordinator
{


    private OperationMapper $operationMapper;
    private OperationRunner $operationRunner;
    private CustomerValidator $customerValidator;

    public function __construct(
        OperationMapper $operationMapper,
        OperationRunner $operationRunner,
        CustomerValidator $customerValidator,
    )
    {
        $this->operationMapper = $operationMapper;
        $this->operationRunner = $operationRunner;
        $this->customerValidator = $customerValidator;
    }

    /**
     * @param array<mixed> $operation
     */
    public function runOperation(array $operation): OperationResult
    {
            $operationModel = $this->operationMapper->mapOperation($operation);
            $this->customerValidator->validateCustomer($operationModel->getCustomer());
            return $this->operationRunner->runOperation($operationModel);
    }
}