<?php

namespace App\Models;

use Filterable\Traits\Filterable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Scan extends Model
{
    use Filterable, HasFactory;

    public function casts(): array
    {
        return [
            'reviewed' => 'boolean',
        ];
    }

    public function alerts(): HasMany
    {
        return $this->hasMany(ScanAlert::class);
    }

    public function path(): Attribute
    {
        return Attribute::make(
            get: fn () => route('scans.show', $this)
        );
    }
}
