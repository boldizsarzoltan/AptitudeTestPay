<?php

namespace Paysera\CommissionTask;

use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Exception;
use Paysera\CommissionTask\Service\CsvReader;

class Container
{
    protected ContainerBuilder $containerBuilder;
    protected \DI\Container $container;

    public function __construct()
    {
        $this->init();
        $this->container = $this->containerBuilder->build();
    }

    protected function getPaymentCoordinator(): PaymentCoordinator
    {
        return $this->container->get(PaymentCoordinator::class);
    }

    public function runOperations(string $inputFilePath)
    {
        $operations = (new CsvReader($inputFilePath, false))->readAll();
        $paymentCoordinator = $this->getPaymentCoordinator();
        foreach ($operations as $operation) {
            $this->handleOperation($paymentCoordinator, $operation);
        }
    }

    private function detectParameters()
    {
        $this->containerBuilder->addDefinitions(__DIR__ . '/../config/configurations.php');
    }

    /**
     * @param PaymentCoordinator $paymentCoordinator
     * @param array<mixed> $operation
     * @return void
     */
    public function handleOperation(PaymentCoordinator $paymentCoordinator, array $operation): void
    {
        try {
            $result = $paymentCoordinator->runOperation($operation);
            echo($result->getFee() . PHP_EOL);
        } catch (Exception $e) {
            echo($e->getMessage() . PHP_EOL);
        }
    }

    /**
     * @return string
     */
    protected function getEnvParameters(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . '..';
    }

    /**
     * @return void
     * @throws Exception
     */
    public function init(): void
    {
        $dotenv = Dotenv::createImmutable($this->getEnvParameters());
        $dotenv->load();
        $this->containerBuilder = new ContainerBuilder();
        $this->containerBuilder->useAutowiring(true);
        $this->detectParameters();
    }
}
