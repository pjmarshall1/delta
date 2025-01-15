<?php

use App\Http\Resources\ScanResource;
use App\Models\Scan;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('requires authentication', function () {
    get(route('scans.index'))->assertRedirectToRoute('login');
});

it('should return the correct component', function () {
    actingAs(User::factory()->create())
        ->get(route('scans.index'))
        ->assertComponent('Scans/Index');
});

it('passes scans to the view', function () {
    $scans = Scan::factory(2)->create();

    actingAs(User::factory()->create())
        ->get(route('scans.index'))
        ->assertHasPaginatedResource('scans', ScanResource::collection($scans->sortByDesc('date')));
});

it('passes filtered scans to the view', function ($filter, $passedData, $failedData) {
    $scan = Scan::factory()->create($passedData);
    Scan::factory()->create($failedData);

    actingAs(User::factory()->create())
        ->get(route('scans.index', $filter))
        ->assertHasPaginatedResource('scans', ScanResource::collection([$scan]));
})->with('scan filters');

it('passes sorted scans to the view', function ($sortBy, $direction, $filter = []) {
    $scans = [
        Scan::factory()->create(['reviewed' => true]),
        Scan::factory()->create(['reviewed' => false]),
    ];

    $sessionMap = [
        'pre_market' => 'p_count',
        'open_market' => 'm_count',
        'after_market' => 'a_count',
    ];

    $sortedScans = collect($scans)->sortBy(function ($scan) use ($sortBy, $sessionMap, $filter) {
        if ($sortBy === 'alerts_count') {
            return $filter ? $scan->{$sessionMap[$filter['session']]} : $scan->p_count + $scan->m_count + $scan->a_count;
        }

        return $scan->{$sortBy};
    }, SORT_REGULAR, $direction === 'desc')->values();

    actingAs(User::factory()->create())
        ->get(route('scans.index', ['direction' => $direction, 'sortBy' => $sortBy] + $filter))
        ->assertHasPaginatedResource('scans', ScanResource::collection($sortedScans));
})->with('scan sorts');
