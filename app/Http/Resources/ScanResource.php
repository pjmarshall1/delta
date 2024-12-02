<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Date;

/**
 * @property int $id
 * @property string $timestamp
 * @property string $symbol
 * @property int $previous_close
 * @property int $gap_percent
 * @property int $float
 * @property int $short_interest
 */
class ScanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date' => Date::parse($this->timestamp)->toDateString(),
            'symbol' => $this->symbol,
            'previous_close' => $this->previous_close,
            'gap_percent' => $this->gap_percent,
            'float' => $this->float,
            'short_interest' => $this->short_interest,
        ];
    }
}
