<?php

namespace App\Services\MarketData;

use App\Services\MarketData\Exceptions\InvalidResponseException;
use App\Services\MarketData\Exceptions\ProviderNotFoundException;
use App\Services\MarketData\Helpers\DataValidator;
use Exception;
use Illuminate\Support\Facades\Log;

class MarketDataService
{
    private const AGGREGATE_REQUIRED_KEYS = ['open', 'high', 'low', 'close', 'volume', 'time'];

    private const DETAILS_REQUIRED_KEYS = ['name', 'exchange', 'market_cap', 'list_date'];

    /**
     * Constructor to inject dependencies.
     *
     * @throws Exception
     */
    public function __construct(protected DataValidator $validator, protected string $providerName, protected string $apiKey)
    {
        if (empty($providerName)) {
            throw new Exception('Invalid provider name.');
        }

        if (empty($apiKey)) {
            throw new Exception('API key is required.');
        }
    }

    /**
     * Get aggregate data for a specific symbol, using multiplier, timespan, start and end dates.
     *
     * @throws ProviderNotFoundException|InvalidResponseException|Exception
     */
    public function getAggregateData(string $symbol, string $multiplier, string $timespan, string $startDate, string $endDate): array
    {
        $providerClass = $this->providerClass('Aggregate');

        $provider = new $providerClass($this->apiKey);

        $response = $provider->fetch($symbol, $multiplier, $timespan, $startDate, $endDate);

        if ($response['status'] === 'error') {
            Log::error('Provider Error: '.$response['message']);
            throw new InvalidResponseException($response['message']);
        }

        $data = $provider->normalize($response['results']);

        // Validate keys
        $this->validator->validateKeys($data, self::AGGREGATE_REQUIRED_KEYS, 'Aggregate Data');

        return $data;
    }

    /**
     * Get the class for the provider.
     *
     * @throws ProviderNotFoundException
     */
    private function providerClass(string $className): string
    {
        $providerClass = 'App\\Services\\MarketData\\Providers\\'.ucfirst($this->providerName).'\\'.$className;

        if (! class_exists($providerClass)) {
            Log::error("Provider class for {$this->providerName} not found.");
            throw new ProviderNotFoundException("Provider class for {$this->providerName} not found.");
        }

        return $providerClass;
    }

    /**
     * Get detailed data for a specific symbol and date.
     *
     * @throws ProviderNotFoundException|InvalidResponseException|Exception
     */
    public function getDetailsData(string $symbol, string $date): array
    {
        $providerClass = $this->providerClass('Details');

        $provider = new $providerClass($this->apiKey);

        $response = $provider->fetch($symbol, $date);

        if ($response['status'] === 'error') {
            Log::error('Provider Error: '.$response['message']);
            throw new InvalidResponseException($response['message']);
        }

        if (! $response['results']) {
            throw new InvalidResponseException('No data found for the given parameters');
        }

        $data = $provider->normalize($response['results']);

        // Validate keys
        $this->validator->validateKeys($data, self::DETAILS_REQUIRED_KEYS, 'Quote Data');

        return $data;
    }
}
