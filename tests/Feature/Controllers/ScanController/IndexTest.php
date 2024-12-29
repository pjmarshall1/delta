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
    $scans = Scan::factory(10)->create();

    actingAs(User::factory()->create())
        ->get(route('scans.index'))
        ->assertHasPaginatedResource('scans', ScanResource::collection($scans));
});
