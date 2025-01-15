<?php

namespace App\Http\Resources;

use App\Models\Scan;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Scan */
class ScanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'symbol' => $this->symbol,
            'price' => $this->price,
            'gap_percent' => $this->gap_percent,
            'float' => $this->float,
            'short_interest' => $this->short_interest,
            'alerts_count' => [
                'pre_market' => $this->p_count,
                'open_market' => $this->m_count,
                'after_market' => $this->a_count,
            ],
            'reviewed' => $this->reviewed,
            'path' => $this->path,
            'alerts' => ScanAlertResource::collection($this->whenLoaded('alerts')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'name' => $this->name,
            'exchange' => $this->exchange,
            'market_cap' => $this->market_cap,
            'list_date' => $this->list_date,
        ];
    }
}
