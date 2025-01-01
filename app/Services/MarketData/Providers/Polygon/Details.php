<?php

namespace App\Services\MarketData\Providers\Polygon;

use App\Services\MarketData\Contracts\DetailsInterface;
use Illuminate\Support\Facades\Http;

class Details implements DetailsInterface
{
    public string $endpoint;

    public function __construct(protected string $apiKey)
    {
        $this->endpoint = 'https://api.polygon.io/v3/reference/tickers';
    }

    public function fetch(string $symbol, string $date): array
    {
        $url = $this->endpoint."/{$symbol}";

        $response = Http::get($url, [
            'date' => $date,
            'apiKey' => $this->apiKey,
        ]);

        if (! $response->successful() || ! isset($response['results'])) {
            return [
                'status' => 'error',
                'message' => 'Failed to fetch quote data',
                'details' => $response->body(),
            ];
        }

        return [
            'status' => 'success',
            'results' => $response['results'],
        ];
    }

    public function normalize(array $data): array
    {
        return [
            'name' => $data['name'] ?? null,
            'exchange' => $this->getExchangeName($data['primary_exchange']) ?? null,
            'market_cap' => $data['market_cap'] ?? null,
            'list_date' => $data['list_date'] ?? null,
        ];
    }

    public function getExchangeName(string $mic): string
    {
        return [
            'XNYS' => 'New York Stock Exchange (NYSE)',
            'ARCX' => 'NYSE Arca',
            'XASE' => 'NYSE American',
            'XNAS' => 'NASDAQ',
            'BATS' => 'Cboe BZX Exchange',
        ][$mic] ?? 'Unknown';
    }
}
