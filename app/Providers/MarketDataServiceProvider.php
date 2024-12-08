<?php

namespace App\Providers;

use App\Services\MarketData\Helpers\DataValidator;
use App\Services\MarketData\MarketDataService;
use Illuminate\Support\ServiceProvider;

class MarketDataServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(DataValidator::class, function () {
            return new DataValidator;
        });

        $this->app->bind(MarketDataService::class, function () {
            $providerName = config('market.default');
            $apiKey = config("market.providers.{$providerName}.api_key");

            return new MarketDataService(
                $this->app->make(DataValidator::class),
                $providerName,
                $apiKey
            );
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Additional boot logic if needed
    }
}
