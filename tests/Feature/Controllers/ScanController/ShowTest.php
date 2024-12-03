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

it('passes a scan with alerts to the view', function () {
    $this->withoutExceptionHandling();

    $scan = Scan::factory()->hasAlerts(3)->create();

    actingAs(User::factory()->create())
        ->get(route('scans.show', $scan->id))
        ->assertHasResource('scan', ScanResource::make($scan->refresh()->load('alerts')));
});
