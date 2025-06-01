<?php

namespace Paysera\CommissionTask\Tests\Functional;

use Paysera\CommissionTask\Service\Currency\CurrencyFetcher;
use Paysera\CommissionTask\Service\Currency\CurrencyFetcherService;
use Paysera\CommissionTask\Tests\Functional\Services\FunctionalTestContainer;
use Paysera\CommissionTask\Tests\Functional\Services\FunctionalTestPaymentFetcher;
use PHPUnit\Framework\TestCase;

class FunctionalBehaviorTest extends TestCase
{
    private static FunctionalTestContainer $container;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::$container = new FunctionalTestContainer(
            [
                CurrencyFetcher::class => \DI\autowire(FunctionalTestPaymentFetcher::class)
            ]
        );
    }

    /**
     * @dataProvider functionalDataProvider
     */
    public function testWorksCorrectly(array $data, float $expectedResult): void
    {
        $paymentCoordinator = self::$container->getPaymentCoordinator();
        self::$container->handleOperation($paymentCoordinator, $data);
        $this->assertEquals(
            $expectedResult,
            self::$container->result
        );
    }

    public function functionalDataProvider()
    {
        return [
            "input 1" => [
                [
                    "2014-12-31",
                    4,
                    "private",
                    "withdraw",
                    1200.00,
                    "EUR",
                ],
                0.6
            ],
            "input 2" => [
                [
                    "2015-01-01",
                    4,
                    "private",
                    "withdraw",
                    1000.00,
                    "EUR",
                ],
                3
            ],
            "input 3" => [
                [
                    "2016-01-05",
                    4,
                    "private",
                    "withdraw",
                    1000.00,
                    "EUR",
                ],
                0
            ],
            "input 4" => [
                [
                    "2016-01-05",
                    1,
                    "private",
                    "deposit",
                    200.00,
                    "EUR",
                ],
                0.06
            ],
            "input 5" => [
                [
                    "2016-01-06",
                    2,
                    "business",
                    "withdraw",
                    300.00,
                    "EUR",
                ],
                1.5
            ],
            "input 6" => [
                [
                    "2016-01-06",
                    1,
                    "private",
                    "withdraw",
                    30000,
                    "JPY",
                ],
                0
            ],
            "input 7" => [
                [
                    "2016-01-07",
                    1,
                    "private",
                    "withdraw",
                    1000.00,
                    "EUR",
                ],
                0.693
            ],
            "input 8" => [
                [
                    "2016-01-07",
                    1,
                    "private",
                    "withdraw",
                    100.00,
                    "USD",
                ],
                0.3
            ],
            "input 9" => [
                [
                    "2016-01-10",
                    1,
                    "private",
                    "withdraw",
                    100.00,
                    "EUR",
                ],
                0.3
            ],
            "input 10" => [
                [
                    "2016-01-10",
                    2,
                    "business",
                    "deposit",
                    10000.00,
                    "EUR",
                ],
                3
            ],
            "input 11" => [
                [
                    "2016-01-10",
                    3,
                    "private",
                    "withdraw",
                    1000.00,
                    "EUR",
                ],
                0
            ],
            "input 12" => [
                [
                    "2016-02-15",
                    1,
                    "private",
                    "withdraw",
                    300.00,
                    "EUR",
                ],
                0
            ],
            "input 13" => [
                [
                    "2016-02-19",
                    5,
                    "private",
                    "withdraw",
                    3000000,
                    "JPY",
                ],
                8611.41
            ],
        ];
    }
}