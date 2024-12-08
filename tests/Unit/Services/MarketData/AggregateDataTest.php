<?php

use App\Services\MarketData\Helpers\DataValidator;
use App\Services\MarketData\MarketDataService;

it('fetches aggregate stock data from the provider', function ($provider) {
    Config::set('default', $provider);

    $apiKey = config("market.providers.{$provider}.api_key");

    $provider = new MarketDataService(new DataValidator, $provider, $apiKey);

    $response = $provider->getAggregateData('AAPL', '1', 'day', '2024-11-07', '2024-11-07');

    expect(count($response))->toEqual(1)
        ->and($response[0])->toBeArray()
        ->toHaveKeys(['open', 'high', 'low', 'close', 'volume', 'time']);
})->with(['polygon']);
