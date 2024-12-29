<?php

use App\Models\Scan;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

it('requires authentication', function () {
    post(route('scans.update', 1))->assertRedirectToRoute('login');
});

it('update the reviewed status of a scan', function () {
    $scan = Scan::factory()->create();

    actingAs(User::factory()->create())
        ->post(route('scans.update', $scan), ['reviewed' => true]);

    expect($scan->refresh()->reviewed)->toBeTrue();
});
