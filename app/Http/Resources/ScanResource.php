<?php

namespace App\Http\Resources;

use App\Models\Scan;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Date;

/** @mixin Scan */
class ScanResource extends JsonResource
{
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
            'alerts' => ScanAlertResource::collection($this->whenLoaded('alerts')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
