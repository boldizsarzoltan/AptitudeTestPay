<?php

namespace Paysera\CommissionTask\Service\Operations;

use Paysera\CommissionTask\Model\OperationModel;
use Paysera\CommissionTask\Model\OperationResult;
use Paysera\CommissionTask\Model\OperationType;

interface Operation
{
    public function getType(): OperationType;
    public function doOperation(OperationModel $operation): OperationResult;
}
