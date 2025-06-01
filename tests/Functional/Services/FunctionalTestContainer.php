<?php

namespace Paysera\CommissionTask\Tests\Functional\Services;

use Paysera\CommissionTask\Container;
use Paysera\CommissionTask\PaymentCoordinator;

class FunctionalTestContainer extends Container
{
    public float $result = 0;
    /**
     * @var array<string, \stdClass>
     */
    private array $overWrittenDefinitions;

    public function __construct(
            array $overWrittenDefinitions
    ) {
        $this->overWrittenDefinitions = $overWrittenDefinitions;
        parent::__construct();
    }

    public function getPaymentCoordinator(): PaymentCoordinator
    {
        return parent::getPaymentCoordinator();
    }

    public function handleOperation(PaymentCoordinator $paymentCoordinator, array $operation): void
    {
        try {
            $result = $paymentCoordinator->runOperation($operation);
            $this->result = $result->getFee();
        } catch (\Exception $e) {
            echo($e->getMessage() . PHP_EOL);
            die();
        }
    }

    protected function getEnvParameters(): string
    {
        return __DIR__;
    }

    public function init(): void
    {
        parent::init();
        $this->containerBuilder->addDefinitions($this->overWrittenDefinitions);
    }
}