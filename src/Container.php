<?php

namespace Paysera\CommissionTask;

use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Exception;
use Paysera\CommissionTask\Repository\Balance\InMemoryBalanceRepostiry;
use Paysera\CommissionTask\Service\CsvReader;

use Paysera\CommissionTask\Service\Operations\Deposit\DepositOperation;
use Paysera\CommissionTask\Service\Operations\Deposit\Rule\DefaultDepositRule;
use Paysera\CommissionTask\Service\Operations\OperationRunner;
use Paysera\CommissionTask\Service\Operations\Withdraw\Rule\DefaultBusinessClientRule;
use Paysera\CommissionTask\Service\Operations\Withdraw\Rule\DefaultPrivateClientRule;
use Paysera\CommissionTask\Service\Operations\Withdraw\Rule\PrivateClientFirstThousandEuroFreeRule;
use Paysera\CommissionTask\Service\Operations\Withdraw\WithdrawOperation;
use function DI\autowire;
use function DI\factory;
use function DI\get;

class Container
{
    private ContainerBuilder $containerBuilder;
    private \DI\Container $container;

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->load();
        $this->containerBuilder = new ContainerBuilder();
        $this->containerBuilder->useAutowiring(true);
        $this->detectServices();
        $this->detectRepositories();
        $this->detectParameters();
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

    private function getPaymentCoordinator(): PaymentCoordinator
    {
        return $this->container->get(PaymentCoordinator::class);
    }

    public function runOperations(string $inputFilePath)
    {
        $operations = new CsvReader($inputFilePath);
        $paymentCoordinator = $this->getPaymentCoordinator();
        foreach ($operations as $operation) {
            try {
                $result = $paymentCoordinator->runOperation($operation);
                echo($result->fee . PHP_EOL);
            } catch (Exception $e) {
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

    private function detectParameters()
    {
        $this->containerBuilder->addDefinitions([
            // Define 'api.key' using a factory
            'api.key' => factory(function () {
                // This closure will be executed by PHP-DI when 'api.key' is requested
                if (!isset($_ENV['API_KEY'])) {
                    throw new Exception("API_KEY environment variable is not set.");
                }
                return $_ENV['API_KEY'];
            }),
            InMemoryBalanceRepostiry::class => autowire(InMemoryBalanceRepostiry::class)
                ->constructorParameter('apiKey', get('api.key')),
            DepositOperation::class => autowire(DepositOperation::class)
                ->constructorParameter(
                    "operationRules",
                    function (\DI\Container $container) {
                        return [
                            $container->get(DefaultDepositRule::class),
                        ];
                    }
                ),
            WithdrawOperation::class => autowire(WithdrawOperation::class)
                ->constructorParameter(
                    "operationRules",
                    function (\DI\Container $container) {
                        return [
                            $container->get(DefaultBusinessClientRule::class),
                            $container->get(PrivateClientFirstThousandEuroFreeRule::class),
                            $container->get(DefaultPrivateClientRule::class),
                        ];
                    }
                ),
            OperationRunner::class => autowire(OperationRunner::class)
                ->constructorParameter(
                    'operations',
                    function (\DI\Container $container) {
                        return [
                            $container->get(DepositOperation::class),
                            $container->get(WithdrawOperation::class),
                        ];
                    }
                )
        ]);
    }
}
