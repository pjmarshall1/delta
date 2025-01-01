<?php

namespace App\Jobs;

use App\Models\Scan;
use App\Services\MarketData\MarketDataService;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;

class CreateScan implements ShouldQueue
{
    use Batchable, Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Collection $alerts) {}

    /**
     * Execute the job.
     */
    public function handle(MarketDataService $provider): void
    {
        $scan = Arr::only($this->alerts->first(), ['timestamp', 'symbol', 'float', 'short_interest', 'price', 'gap_percent']);

        $marketOpen = Date::parse($scan['timestamp'])->setTime(9, 30, 00)->shiftTimezone('America/New_York')
            ->utc()->toDateTimeString();
        $marketClose = Date::parse($scan['timestamp'])->setTime(16, 00, 00)->shiftTimezone('America/New_York')
            ->utc()->toDateTimeString();

        $scan['p_count'] = $this->alerts->where('timestamp', '<', $marketOpen)->count();
        $scan['m_count'] = $this->alerts->whereBetween('timestamp', [$marketOpen, $marketClose])->count();
        $scan['a_count'] = $this->alerts->where('timestamp', '>', $marketClose)->count();

        $scan = array_merge($scan, $provider->getDetailsData($scan['symbol'], Date::parse($scan['timestamp'])->toDateSTring()));

        if ($scan = Scan::updateOrCreate($scan)) {
            $scan->alerts()->upsert($this->alerts->toArray(), ['symbol', 'timestamp']);
        }
    }
}
