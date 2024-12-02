<?php

namespace App\Http\Controllers;

use App\Http\Resources\ScanResource;
use App\Models\Scan;

class ScanController extends Controller
{
    public function index()
    {
        return inertia('Scans/Index', [
            'scans' => ScanResource::collection(Scan::paginate(25)),
        ]);
    }
}
