<?php

namespace App\Services\MarketData\Providers\Polygon;

use App\Services\MarketData\Contracts\AggregateInterface;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Http;

class Aggregate implements AggregateInterface
{
    public string $endpoint;

    public function __construct(protected string $apiKey)
    {
        $this->endpoint = 'https://api.polygon.io/v2/aggs';
    }

    public function fetch(string $symbol, int $multiplier, string $timespan, string $startDate, string $endDate): array
    {
        $response = Http::get("{$this->endpoint}/ticker/{$symbol}/range/{$multiplier}/{$timespan}/{$startDate}/{$endDate}", [
            'apiKey' => $this->apiKey,
        ]);

        if (! $response->successful()) {
            return [
                'status' => 'error',
                'message' => 'Failed to fetch aggregate data',
                'details' => $response->body(),
            ];
        }

        return $response->json();
    }

    public function normalize(array $data): array
    {
        return array_filter(array_map(function ($item) {
            if (! isset($item['t'], $item['o'], $item['h'], $item['l'], $item['c'], $item['v'])) {
                return null;
            }

            return [
                'time' => Date::createFromTimestampMs($item['t'])->toDateTimeString(),
                'open' => (float) $item['o'],
                'high' => (float) $item['h'],
                'low' => (float) $item['l'],
                'close' => (float) $item['c'],
                'volume' => (int) $item['v'],
            ];
        }, $data));
    }
}
