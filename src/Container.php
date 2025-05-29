<?php

namespace Paysera\CommissionTask;

use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Exception;
use Paysera\CommissionTask\Service\CsvReader;

class Container
{
    private ContainerBuilder $containerBuilder;
    private \DI\Container $container;

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . DIRECTORY_SEPARATOR . '..');
        $dotenv->load();
        $this->containerBuilder = new ContainerBuilder();
        $this->containerBuilder->useAutowiring(true);
        $this->detectParameters();
        $this->container = $this->containerBuilder->build();
    }

    private function getPaymentCoordinator(): PaymentCoordinator
    {
        return $this->container->get(PaymentCoordinator::class);
    }

    public function runOperations(string $inputFilePath)
    {
        $operations = (new CsvReader($inputFilePath, false))->readAll();
        $paymentCoordinator = $this->getPaymentCoordinator();
        foreach ($operations as $operation) {
            try {
                $result = $paymentCoordinator->runOperation($operation);
                echo($result->getFee() . PHP_EOL);
            } catch (Exception $e) {
                echo($e->getMessage() . PHP_EOL);
            }
        }
    }

    private function detectParameters()
    {
        $this->containerBuilder->addDefinitions(__DIR__ . '/../config/configurations.php');
    }
}
