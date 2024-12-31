<?php

namespace App\Http\Controllers;

use App\Events\UpdateStatus;
use App\Http\Requests\ScanImportRequest;
use App\Jobs\CreateScan;
use App\Services\ScanParseService;
use Exception;
use Illuminate\Support\Facades\Bus;
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

        $date = Date::parse(Str::remove('_Momo.csv', $file->getClientOriginalName()))
            ->format('Y-m-d');

        try {
            $scan_alerts = $parser->parseFile($file->getRealPath(), $date);
        } catch (Exception $e) {
            broadcast(new UpdateStatus('error', $e->getMessage(), $request->user()->id));

            return back();
        }

        $userId = auth()->user()->id;
        $batch = $scan_alerts->sortBy('timestamp')->groupBy('symbol')->map(fn ($group) => new CreateScan($group))->toArray();

        Bus::batch($batch)
            ->before(fn () => broadcast(new UpdateStatus('processing', '0%', $userId)))
            ->progress(fn ($batch) => broadcast(new UpdateStatus('processing', $batch->progress().'%', $userId)))
            ->catch(fn (Exception $e) => broadcast(new UpdateStatus('error', $e['message'], $userId)))
            ->finally(fn () => broadcast(new UpdateStatus('complete', 'File imported successfully', $userId)))
            ->dispatchAfterResponse();

        return back();
    }
}
