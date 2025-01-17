<?php

namespace App\Http\Controllers;

use App\Filters\ScanFilter;
use App\Http\Resources\ScanResource;
use App\Models\Scan;

class ScanController extends Controller
{
    public function index(ScanFilter $filter)
    {
        $filterOptions = [
            'symbol' => Scan::select('symbol')->distinct()->orderBy('symbol')->pluck('symbol')
                ->map(fn ($symbol) => ['name' => $symbol, 'value' => $symbol]),
        ];

        $scans = Scan::filter($filter)->orderBy('id', 'desc')
            ->paginate(25)->onEachSide(2)
            ->withQueryString();

        return inertia('Scans/Index', [
            'scans' => ScanResource::collection($scans),
            'filterOptions' => $filterOptions,
        ]);
    }

    public function show(Scan $scan, ScanFilter $filter)
    {
        $scans = Scan::filter($filter)->orderBy('id', 'desc')->get();

        $activeIndex = $scans->search(fn ($s) => $s->id === $scan->id);

        $meta = [
            'previousUrl' => $activeIndex > 0 ? $scans->get($activeIndex - 1)->path : '',
            'nextUrl' => $activeIndex < $scans->count() - 1 ? $scans->get($activeIndex + 1)->path : '',
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
