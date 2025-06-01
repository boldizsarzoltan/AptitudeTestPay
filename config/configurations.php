<?php

use Paysera\CommissionTask\Repository\Balance\HistoryRepository;
use Paysera\CommissionTask\Repository\Balance\InMemoryHistoryRepository;
use Paysera\CommissionTask\Repository\Currency\CurrencyRepository;
use Paysera\CommissionTask\Repository\Currency\InMemoryCurrencyRepository;
use Paysera\CommissionTask\Repository\Customer\CustomerRepository;
use Paysera\CommissionTask\Repository\Customer\InMemoryCustomerRepository;
use Paysera\CommissionTask\Service\Currency\CurrencyFetcher;
use Paysera\CommissionTask\Service\Currency\CurrencyFetcherService;
use Paysera\CommissionTask\Service\Math;
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

return [
    // Define 'api.key' using a factory
    'api.key' => factory(function () {
        // This closure will be executed by PHP-DI when 'api.key' is requested
        if (!isset($_ENV['API_KEY'])) {
            throw new Exception("API_KEY environment variable is not set.");
        }
        return $_ENV['API_KEY'];
    }),
    'settings.scale' => factory(function () {
        // This closure will be executed by PHP-DI when 'api.key' is requested
        if (!isset($_ENV['SCALE'])) {
            throw new Exception("SCALE environment variable is not set.");
        }
        return (int) $_ENV['SCALE'];
    }),
    'api.url' => factory(function () {
        if (!isset($_ENV['API_URL'])) {
            throw new Exception("API_URL environment variable is not set.");
        }
        return $_ENV['API_URL'];
    }),
    InMemoryHistoryRepository::class => autowire(InMemoryHistoryRepository::class)
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
        ),
    HistoryRepository::class => autowire(InMemoryHistoryRepository::class),
    CurrencyRepository::class => autowire(InMemoryCurrencyRepository::class),
    CustomerRepository::class => autowire(InMemoryCustomerRepository::class),
    CurrencyFetcher::class => autowire(CurrencyFetcherService::class)
    ->constructorParameter('apiKey', get('api.key'))
    ->constructorParameter('baseUrl', get('api.url')),
    Math::class => autowire(Math::class)
    ->constructorParameter("scale", get('settings.scale')),
];