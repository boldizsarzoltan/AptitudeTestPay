<?php

namespace Paysera\CommissionTask;

use DI\ContainerBuilder;
use Paysera\CommissionTask\Service\CsvReader;
use function DI\autowire;

class Container
{
    private ContainerBuilder $containerBuilder;
    private \DI\Container $container;

    public function __construct()
    {
        $this->containerBuilder = new ContainerBuilder();
        $this->containerBuilder->useAutowiring(true);
        $this->detectServices();
        $this->detectRepositories();
        $this->container = $this->containerBuilder->build();
    }

    private function detectServices(): void
    {
        $services = [];
        foreach (scandir(__DIR__ . "/Service") as $service) {
            if (pathinfo($service, PATHINFO_EXTENSION) == "php") {
                $class = "Paysera\\CommissionTask\\Service\\" . $service;
                if (class_exists($class) && !(new ReflectionClass($class))->isAbstract()) {
                    $services[$class] = autowire($class);
                }
            }
        }
        $this->containerBuilder->addDefinitions($services);
    }

    private function getPaymentCoordinator(): \Paysera\CommissionTask\PaymentCoordinator
    {
        return $this->container->get(\Paysera\CommissionTask\PaymentCoordinator::class);
    }

    public function runOperations(string $inputFilePath)
    {
        $operations = new CsvReader($inputFilePath);
        $paymentCoordinator = $this->getPaymentCoordinator();
        foreach ($operations as $operation) {
            try {
                $result = $paymentCoordinator->runOperation($operation);
                echo($result->fee . PHP_EOL);
            }
            catch (\Exception $e) {
                echo($e->getMessage() . PHP_EOL);
            }
        }
    }

    private function detectRepositories()
    {
        $services = [];
        foreach (scandir(__DIR__ . "/Repository") as $repository) {
            if (pathinfo($repository, PATHINFO_EXTENSION) == "php") {
                $class = "Paysera\\CommissionTask\\Repository\\" . $repository;
                if (class_exists($class) && !(new ReflectionClass($class))->isAbstract()) {
                    $services[$class] = autowire($class);
                }
            }
        }
        $this->containerBuilder->addDefinitions($services);
    }
}