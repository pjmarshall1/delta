<?php

namespace App\Http\Controllers;

use App\Http\Resources\ScanResource;
use App\Models\Scan;

class ScanController extends Controller
{
    public function index()
    {
        return inertia('Scans/Index', [
            'scans' => ScanResource::collection(Scan::orderBy('timestamp', 'desc')->paginate(25)->onEachSide(2)),
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
