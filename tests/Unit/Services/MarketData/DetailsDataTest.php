<?php

use App\Services\MarketData\Helpers\DataValidator;
use App\Services\MarketData\MarketDataService;

it('fetches quote data from the provider', function ($providerName) {
    // Set up dependencies
    $validator = new DataValidator;

    // Set up the API key
    $apiKey = config("market.providers.{$providerName}.api_key");

    // Create the MarketDataService instance
    $provider = new MarketDataService($validator, $providerName, $apiKey);

    $response = $provider->getDetailsData('AAPL', '2024-11-07');

    expect($response)->toBeArray()
        ->toHaveKeys(['name', 'exchange', 'market_cap', 'list_date']);
})->with(['polygon']);
