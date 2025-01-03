<?php

namespace App\Http\Controllers;

use App\Http\Resources\ScanResource;
use App\Models\Scan;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class ScanController extends Controller
{
    public function index()
    {
        $scans = QueryBuilder::for(Scan::class)
            ->defaultSort('-timestamp')
            ->allowedSorts([
                AllowedSort::field('date', 'timestamp'),
                AllowedSort::field('premarket', 'p_count'),
                AllowedSort::field('market', 'm_count'),
                AllowedSort::field('aftermarket', 'a_count'),
                'symbol', 'price', 'gap_percent', 'float', 'reviewed', 'short_interest', 'exchange', 'market_cap', 'list_date',
            ])
            ->paginate(25)->onEachSide(2)
            ->appends(request()->query());

        return inertia('Scans/Index', [
            'scans' => ScanResource::collection($scans),
        ]);
    }

    public function show(Scan $scan)
    {
        $meta = [
            'previousUrl' => Scan::where('timestamp', '>', $scan->timestamp)->orderBy('timestamp', 'asc')->first()?->path,
            'nextUrl' => Scan::where('timestamp', '<', $scan->timestamp)->orderBy('timestamp', 'desc')->first()?->path,
        ];

        return inertia('Scans/Show', [
            'meta' => $meta,
            'scan' => ScanResource::make($scan->load('alerts')),
        ]);
    }

    public function update(Scan $scan)
    {
        $scan->update(request()->validate(['reviewed' => 'required|boolean']));

        return back();
    }
}
