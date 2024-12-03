<?php

namespace App\Http\Resources;

use App\Models\ScanAlert;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin ScanAlert */
class ScanAlertResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'timestamp' => $this->timestamp,
            'symbol' => $this->symbol,
            'price' => $this->price,
            'volume' => $this->volume,
            'float' => $this->float,
            'relative_volume_daily' => $this->relative_volume_daily,
            'relative_volume_five_minute' => $this->relative_volume_five,
            'gap_percent' => $this->gap_percent,
            'change_percent' => $this->change_percent,
            'short_interest' => $this->short_interest,
            'strategy_name' => $this->strategy_name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
