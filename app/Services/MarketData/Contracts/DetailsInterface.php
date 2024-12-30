<?php

namespace App\Services\MarketData\Contracts;

interface DetailsInterface
{
    public function fetch(string $symbol, string $date): array;

    public function normalize(array $data): array;
}
