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
        ->assertHasPaginatedResource('scans', ScanResource::collection($scans));
});

it('filters scans by date range', function () {
    $this->withoutExceptionHandling();

    $scan = Scan::factory()->create(['timestamp' => '2021-01-01 09:00:00']);
    Scan::factory()->create(['timestamp' => '2021-01-02 09:00:00']);

    actingAs(User::factory()->create())
        ->get(route('scans.index', ['startDate' => '2021-01-01', 'endDate' => '2021-01-01']))
        ->assertHasPaginatedResource('scans', ScanResource::collection([$scan]));
});
