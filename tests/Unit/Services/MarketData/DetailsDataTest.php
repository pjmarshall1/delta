<?php

use App\Services\MarketData\Helpers\DataValidator;
use App\Services\MarketData\MarketDataService;

it('fetches quote data from the provider', function ($provider) {
    Config::set('default', $provider);

    $apiKey = config("market.providers.{$provider}.api_key");

    $provider = new MarketDataService(new DataValidator, $provider, $apiKey);

    $response = $provider->getDetailsData('AAPL', '2024-11-07');

    expect($response)->toBeArray()
        ->toHaveKeys(['name', 'exchange', 'market_cap', 'list_date']);
})->with(['polygon']);
