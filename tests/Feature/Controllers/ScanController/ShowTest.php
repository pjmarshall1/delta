<?php

use App\Http\Resources\ScanResource;
use App\Models\Scan;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('requires authentication', function () {
    get(route('scans.show', 1))->assertRedirectToRoute('login');
});
it('should return the correct component', function () {
    $scan = Scan::factory()->create();

    actingAs(User::factory()->create())
        ->get(route('scans.show', $scan->id))
        ->assertComponent('Scans/Show');
});

it('passes a scan to the view', function () {
    $scan = Scan::factory()->create();

    actingAs(User::factory()->create())
        ->get(route('scans.show', $scan->id))
        ->assertHasResource('scan', ScanResource::make($scan));
});
