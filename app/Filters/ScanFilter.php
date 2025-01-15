<?php

namespace App\Filters;

use Filterable\Filter;
use Illuminate\Database\Eloquent\Builder;

class ScanFilter extends Filter
{
    protected array $filters = ['endDate', 'float', 'session', 'reviewed', 'sortBy', 'startDate', 'symbol'];

    protected array $sessionMap = [
        'pre_market' => 'p_count',
        'open_market' => 'm_count',
        'after_market' => 'a_count',
    ];

    protected function endDate(string $date): Builder
    {
        return $this->builder->whereDate('date', '<=', $date);
    }

    protected function float(string $float): Builder
    {
        $floatMap = [
            'low_float' => [0, 10_000_000],
            'medium_float' => [10_000_000, 50_000_000],
            'high_float' => [50_000_000, 1_000_000_000],
        ];

        if (! array_key_exists($float, $floatMap)) {
            return $this->builder;
        }

        return $this->builder->whereBetween('float', $floatMap[$float]);
    }

    protected function session(string $session): Builder
    {
        if (! array_key_exists($session, $this->sessionMap)) {
            return $this->builder;
        }

        return $this->builder->where($this->sessionMap[$session], '>', 0);
    }

    protected function reviewed(string $reviewed): Builder
    {
        return $this->builder->where('reviewed', $reviewed === 'true');
    }

    protected function sortBy(string $column): Builder
    {
        $direction = request('direction', 'asc');

        if ($column === 'alerts_count') {
            if (request('session') === 'pre_market') {
                return $this->builder->orderBy('p_count', $direction);
            }

            if (request('session') === 'open_market') {
                return $this->builder->orderBy('m_count', $direction);
            }

            if (request('session') === 'after_market') {
                return $this->builder->orderBy('a_count', $direction);
            }

            return $this->builder->orderByRaw('p_count + m_count + a_count '.$direction);
        }

        return $this->builder->orderBy($column, $direction);
    }

    protected function startDate(string $date): Builder
    {
        return $this->builder->whereDate('date', '>=', $date);
    }

    protected function symbol(string $symbol): Builder
    {
        return $this->builder->where('symbol', $symbol);
    }
}
