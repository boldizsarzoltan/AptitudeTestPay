<?php

namespace Paysera\CommissionTask\Service\Currency;

use GuzzleHttp\Client;
use Paysera\CommissionTask\Model\CurrencyCollection;

class CurrencyFetcherService implements CurrencyFetcher
{
    private string $apiKey;
    private Client $httpClient;
    private string $baseUrl;

    public function __construct(
        string $apiKey,
        Client $httpClient,
        string $baseUrl
    ) {
        $this->apiKey = $apiKey;
        $this->httpClient = $httpClient;
        $this->baseUrl = $baseUrl;
    }

    public function fetchCurrencies(): CurrencyCollection
    {
        $response = $this->httpClient->get(
            $this->baseUrl . '?access_key=' . $this->apiKey,
        );
        if ($response->getStatusCode() !== 200) {
            throw new \Exception("Could not fetch currencies");
        }
        $response = json_decode($response->getBody()->getContents(), true);
        if ($response['success'] !== true) {
            throw new \Exception("Could not fetch currencies");
        }
        $result = new CurrencyCollection(
            $response['rates'],
            $response["date"],
            $response["base"]
        );
        return $result;
    }
}
