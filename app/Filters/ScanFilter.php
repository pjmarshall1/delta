<?php

namespace App\Filters;

use Filterable\Filter;
use Illuminate\Database\Eloquent\Builder;

class ScanFilter extends Filter
{
    protected array $filters = ['startDate', 'endDate'];

    protected function startDate(string $date): Builder
    {
        return $this->builder->whereDate('date', '>=', $date);
    }

    protected function endDate(string $date): Builder
    {
        return $this->builder->whereDate('date', '<=', $date);
    }
}
