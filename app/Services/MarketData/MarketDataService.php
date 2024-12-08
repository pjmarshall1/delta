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
        $providerClass = $this->getProviderClass();

        $provider = new $providerClass($this->apiKey);

        $response = $provider->fetch($symbol, $multiplier, $timespan, $startDate, $endDate);

        if ($response['status'] === 'error') {
            throw new Exception($response['message']);
        }

        $data = $provider->normalize($response['results']);

        // Validate keys
        $this->validator->validateKeys($data, ['open', 'high', 'low', 'close', 'volume', 'time'], 'Aggregate Data');

        return $data;
    }

    /**
     * Get the class for the provider.
     *
     * @throws Exception
     */
    private function getProviderClass(): string
    {
        $providerClass = 'App\\Services\\MarketData\\Providers\\'.ucfirst($this->providerName).'\\Aggregate';

        if (! class_exists($providerClass)) {
            throw new Exception("Provider class for {$this->providerName} not found.");
        }

        return $providerClass;
    }
}
