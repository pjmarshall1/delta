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
            'results' => [
                ...$response['results'],
                'name' => $this->cleanStockName($response['results']['name']),
            ],
        ];
    }

    private function cleanStockName($name): string
    {
        $unwantedTerms = [
            'Common Stock', 'Common Stocks', 'Common Shares', 'Ordinary Shares', 'Inc.', 'Corp.', 'Ltd.', 'LLC', 'Corporation', 'PLC',
            'Holdings', 'Holding', 'Group', 'Company', 'Co.', 'S.A.', 'S.A.S.', 'Limited Ordinary Share', 'Limited Ordinary Shares',
        ];

        foreach ($unwantedTerms as $term) {
            $name = preg_replace('/\b'.preg_quote($term, '/').'\b/i', '', $name);
        }

        return trim(preg_replace('/\s+/', ' ', $name));
    }

    public function normalize(array $data): array
    {
        $previousDay = $data['previous_day'] ?? null;

        return [
            'name' => $this->cleanStockName($data['name']),
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
