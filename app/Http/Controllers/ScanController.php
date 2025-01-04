<?php

namespace App\Http\Controllers;

use App\Filters\ScanFilter;
use App\Http\Resources\ScanResource;
use App\Models\Scan;

class ScanController extends Controller
{
    public function index(ScanFilter $filter)
    {
        $scans = Scan::filter($filter)
            ->orderBy('timestamp', 'desc')
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
