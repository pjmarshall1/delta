<?php

namespace App\Http\Controllers;

use App\Events\SendToast;
use App\Http\Requests\ScanImportRequest;
use App\Models\Scan;
use App\Services\ScanParseService;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;

class ScanImportController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ScanImportRequest $request, ScanParseService $parser)
    {
        $file = $request->file('file');

        try {
            $scan_alerts = $parser->parseFile($file->getRealPath());
        } catch (Exception $e) {
            broadcast(new SendToast($e->getMessage(), 'error', $request->user()->id));

            return back();
        }

        if ($scan_alerts->isEmpty()) {
            broadcast(new SendToast('There were no scan alerts found. Please verify the file and try again.', 'info', $request->user()->id));

            return back();
        }

        $date = Date::parse(Str::remove('_Momo.csv', $file->getClientOriginalName()))
            ->format('Y-m-d');

        $scan_alerts->sortBy('scanned_at')
            ->map(function ($alert) use ($date) {
                return array_merge(Arr::only($alert, ['symbol', 'volume', 'float', 'short_interest', 'strategy_name']), [
                    'timestamp' => Date::parse($date)
                        ->setTimeFromTimeString($alert['time'])
                        ->shiftTimezone('America/New_York')
                        ->utc()->toDateTimeString(),
                    'price' => $alert['price'] * 10000,
                    'gap_percent' => round($alert['gap_percent'], 2) * 100,
                    'change_percent' => round($alert['change_percent'], 2) * 100,
                    'relative_volume_daily' => round($alert['relative_volume_daily'], 2) * 100,
                    'relative_volume_five' => round($alert['relative_volume_five'], 2) * 100,
                ]);
            })
            ->groupBy('symbol')
            ->each(function ($alerts) use ($date) {
                $scan = Arr::only($alerts->first(), [
                    'symbol', 'price', 'relative_volume', 'gap_percent', 'float', 'short_interest',
                ]);

                $scan['timestamp'] = Date::parse($date)
                    ->shiftTimezone('America/New_York')
                    ->utc()->toDateTimeString();

                $marketOpen = Date::parse($scan['timestamp'])
                    ->setTime(9, 30, 00)
                    ->shiftTimezone('America/New_York')
                    ->utc()->toDateTimeString();
                $marketClose = Date::parse($scan['timestamp'])
                    ->setTime(16, 00, 00)
                    ->shiftTimezone('America/New_York')
                    ->utc()->toDateTimeString();

                $scan['p_count'] = $alerts->where('timestamp', '<', $marketOpen)->count();
                $scan['m_count'] = $alerts->whereBetween('timestamp', [$marketOpen, $marketClose])->count();
                $scan['a_count'] = $alerts->where('timestamp', '>', $marketClose)->count();

                if ($scan = Scan::updateOrCreate($scan)) {
                    $scan->alerts()->upsert($alerts->toArray(), ['symbol', 'timestamp']);
                }
            });

        broadcast(new SendToast('Import completed successfully', 'success', $request->user()->id));

        return back();
    }
}
