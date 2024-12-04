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
        return inertia('Scans/Show', [
            'scan' => ScanResource::make($scan->load('alerts')),
        ]);
    }
}
