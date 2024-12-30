<?php

namespace App\Services\MarketData;

use App\Services\MarketData\Helpers\DataValidator;
use Exception;

class MarketDataService
{
    /**
     * Constructor to inject dependencies.
     */
    public function __construct(protected DataValidator $validator, protected string $providerName, protected string $apiKey)
    {
        //
    }

    /**
     * Get aggregate data for a specific symbol, using multiplier, timespan, start and end dates.
     *
     * @throws Exception
     */
    public function getAggregateData(string $symbol, string $multiplier, string $timespan, string $startDate, string $endDate): array
    {
        $providerClass = $this->providerClass('Aggregate');

        $provider = new $providerClass($this->apiKey);

        $response = $provider->fetch($symbol, $multiplier, $timespan, $startDate, $endDate);

        if ($response['status'] === 'error') {
            throw new Exception($response['message']);
        }

        $data = $provider->normalize($response['results']);

        // Validate keys
        $requiredKeys = ['open', 'high', 'low', 'close', 'volume', 'time'];
        $this->validator->validateKeys($data, $requiredKeys, 'Aggregate Data');

        return $data;
    }

    /**
     * Get the class for the provider.
     *
     * @throws Exception
     */
    private function providerClass(string $className): string
    {
        $providerClass = 'App\\Services\\MarketData\\Providers\\'.ucfirst($this->providerName).'\\'.$className;

        if (! class_exists($providerClass)) {
            throw new Exception("Provider class for {$this->providerName} not found.");
        }

        return $providerClass;
    }

    /**
     * @throws Exception
     */
    public function getDetailsData(string $symbol, string $date): array
    {
        $providerClass = $this->providerClass('Details');

        $provider = new $providerClass($this->apiKey);

        $response = $provider->fetch($symbol, $date);

        if ($response['status'] === 'error') {
            throw new Exception($response['message']);
        }

        if (! $response['results']) {
            throw new Exception('No data found for the given parameters');
        }

        $data = $provider->normalize($response['results']);

        // Validate keys
        $requiredKeys = ['name', 'exchange', 'market_cap', 'list_date'];
        $this->validator->validateKeys($data, $requiredKeys, 'Quote Data');

        return $data;
    }
}
