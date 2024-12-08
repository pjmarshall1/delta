<?php

namespace App\Services\MarketData\Contracts;

interface AggregateInterface
{
    public function fetch(string $symbol, int $multiplier, string $timespan, string $startDate, string $endDate): array;

    public function normalize(array $data): array;
}
